<?php
namespace Falcon\Components\Cache;

use Falcon\Settings;
class Manager {
	private string $cache_dir           = '';
	private string $advanced_cache_file = '';

	public function __construct() {
		$uploads_dir               = wp_upload_dir();
		$this->cache_dir           = $uploads_dir['basedir'] . '/cache';
		$this->advanced_cache_file = WP_CONTENT_DIR . '/advanced-cache.php';

		add_action( 'falcon_settings_save', [ $this, 'setup' ] );
		add_action( 'activate_falcon/falcon.php', [ $this, 'activate' ] );
		add_action( 'deactivate_falcon/falcon.php', [ $this, 'deactivate' ] );
		add_action( 'delete_plugin', [ $this, 'delete' ] );
		$this->hook_to_clear_cache();
	}

	public function setup(): void {
		$active = Settings::is_feature_active( 'cache' );

		if ( $active ) {
			$this->activate();
		} else {
			$this->deactivate();
		}
	}

	public function delete( string $plugin ): void {
		if ( 'falcon/falcon.php' === $plugin ) {
			$this->deactivate();
		}
	}

	public function activate(): void {
		// Extra check when activating the plugin.
		if ( ! Settings::is_feature_active( 'cache' ) ) {
			return;
		}

		file_put_contents( $this->advanced_cache_file, "<?php
require_once __DIR__ . '/plugins/falcon/src/Components/Cache/Serve.php';
new Falcon\Components\Cache\Serve();" );

		wp_mkdir_p( $this->cache_dir );
		$this->update_constant( true );
	}

	public function deactivate(): void {
		wp_delete_file( $this->advanced_cache_file );
		$this->update_constant( false );
		$this->clear_cache();
		$this->remove_cache_dir();
	}

	private function update_constant( bool $enable = true ): void {
		$config_file = ABSPATH . 'wp-config.php';
		if ( ! file_exists( $config_file ) || ! is_writable( $config_file ) ) {
			return;
		}

		$config = file_get_contents( $config_file );
		$config = preg_replace( "/define\s*\(\s*['\"]WP_CACHE['\"].*;\s*\n?/i", '', $config );
		if ( $enable ) {
			$config = preg_replace( '/^<\?php/', "<?php\ndefine( 'WP_CACHE', true );", $config, 1 );
		}

		file_put_contents( $config_file, $config );
	}

	private function hook_to_clear_cache(): void {
		$actions = [
			'save_post',
			'delete_post',
			'trashed_post',
			'edit_term',
			'delete_term',
			'wp_insert_comment',
			'edit_comment',
			'delete_comment',
			'transition_comment_status',
			'switch_theme',
			'activated_plugin',
			'deactivated_plugin',
			'update_option_sidebars_widgets',
			'wp_update_nav_menu',
			'_core_updated_successfully',
			'customize_save_after',
		];

		foreach ( $actions as $action ) {
			add_action( $action, [ $this, 'clear_cache' ] );
		}
	}

	public function clear_cache(): void {
		array_map( 'wp_delete_file', glob( $this->cache_dir . '/*.html' ) );
	}

	private function remove_cache_dir(): void {
		if ( is_dir( $this->cache_dir ) ) {
			rmdir( $this->cache_dir );
		}
	}
}

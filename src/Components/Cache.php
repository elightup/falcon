<?php
namespace Falcon\Components;

use Falcon\Settings;

class Cache {
	private string $cache_dir = '';

	public function __construct() {
		add_action( 'init', [ $this, 'setup' ], 0 );
		$this->hook_to_clear_cache();
	}

	public function setup(): void {
		$uploads_dir = wp_upload_dir();
		$this->cache_dir = $uploads_dir['basedir'] . '/cache';

		if ( Settings::is_feature_active( 'cache' ) ) {
			$this->install();
		} else {
			$this->uninstall();
		}
	}

	public function install(): void {
		wp_mkdir_p( $this->cache_dir );
		$this->add_constant();
		if ( ! file_exists( WP_CONTENT_DIR . '/advanced-cache.php' ) ) {
			copy( FALCON_DIR . '/advanced-cache.php', WP_CONTENT_DIR . '/advanced-cache.php' );
		}
	}

	public function uninstall(): void {
		$this->remove_constant();
		$this->clear_cache();
		unlink( WP_CONTENT_DIR . '/advanced-cache.php' );
	}

	private function add_constant(): void {
		$config_file = ABSPATH . 'wp-config.php';
		if ( ! file_exists( $config_file ) || ! is_writable( $config_file ) ) {
			return;
		}

		$config = file_get_contents( $config_file );
		$config = preg_replace( "/define\s*\(\s*['\"]WP_CACHE['\"].*;\s*\n?/i", '', $config );
		$config = preg_replace( '/^<\?php/', "<?php\ndefine( 'WP_CACHE', true );", $config, 1 );

		file_put_contents( $config_file, $config );
	}

	private function remove_constant(): void {
		$config_file = ABSPATH . 'wp-config.php';
		if ( ! file_exists( $config_file ) || ! is_writable( $config_file ) ) {
			return;
		}

		$config = file_get_contents( $config_file );
		$config = preg_replace( "/define\s*\(\s*['\"]WP_CACHE['\"].*;\s*\n?/i", '', $config );

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
		require_once ABSPATH . 'wp-admin/includes/file.php';
		if ( ! WP_Filesystem() ) {
			return;
		}
		global $wp_filesystem;
		$wp_filesystem->delete( $this->cache_dir, true );
	}
}

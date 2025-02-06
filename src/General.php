<?php
namespace Falcon;

use WP_Query;

class General extends Base {
	protected $features = [
		'no_gutenberg',
		'no_heartbeat',
		'no_embeds',
		'no_revisions',
		'no_self_pings',
		'no_privacy',
		'no_cron',
		'no_auto_updates',
		'no_external_requests',
		'no_comments',
		'search_posts_only',
		'no_comment_url',
		'no_texturize',
		'maintenance_mode',
	];

	public function no_gutenberg() {
		// Disable Gutenberg on the back end.
		add_filter( 'use_block_editor_for_post', '__return_false' );
		add_filter( 'use_block_editor_for_post_type', '__return_false' );

		// Disable Gutenberg for widgets.
		add_filter( 'use_widgets_block_editor', '__return_false' );

		add_action( 'wp_enqueue_scripts', [ $this, 'remove_gutenberg_assets' ], 20 );
	}

	public function remove_gutenberg_assets() {
		// Remove CSS on the front end.
		wp_dequeue_style( 'wp-block-library' );

		// Remove Gutenberg theme.
		wp_dequeue_style( 'wp-block-library-theme' );

		// Remove inline global CSS on the front end.
		wp_dequeue_style( 'global-styles' );

		// Remove classic-themes CSS for backwards compatibility for button blocks.
		wp_dequeue_style( 'classic-theme-styles' );
	}

	public function no_heartbeat() {
		add_action( 'init', [ $this, 'remove_heartbeat_script' ], 1 );
	}

	public function remove_heartbeat_script() {
		wp_deregister_script( 'heartbeat' );
	}

	public function no_embeds() {
		// Remove the REST API endpoint.
		remove_action( 'rest_api_init', 'wp_oembed_register_route' );

		// Turn off oEmbed auto discovery.
		add_filter( 'embed_oembed_discover', '__return_false' );

		// Don't filter oEmbed results.
		remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result' );

		// Remove oEmbed discovery links.
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

		// Remove oEmbed JavaScript from the front-end and back-end.
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}

	public function no_revisions() {
		add_filter( 'wp_revisions_to_keep', '__return_zero' );
	}

	public function no_self_pings() {
		add_action( 'pre_ping', [ $this, 'remove_self_pings' ] );
	}

	public function no_privacy() {
		add_action( 'admin_menu', [ $this, 'remove_menu' ] );
	}

	public function remove_menu() {
		remove_submenu_page( 'options-general.php', 'options-privacy.php' );
		remove_submenu_page( 'tools.php', 'export-personal-data.php' );
		remove_submenu_page( 'tools.php', 'erase-personal-data.php' );
	}

	/**
	 * Remove pings to the website itself.
	 * @link http://wordpress.stackexchange.com/a/1852
	 */
	public function remove_self_pings( &$links ) {
		$home_url = home_url();
		$links    = array_filter( $links, function ( $link ) use ( $home_url ) {
			return false === strpos( $link, $home_url );
		} );
	}

	public function no_cron(): void {
		if ( ! defined( 'DISABLE_WP_CRON' ) ) {
			define( 'DISABLE_WP_CRON', true );
		}
	}

	public function no_auto_updates() {
		add_filter( 'automatic_updater_disabled', '__return_true' );
	}

	public function no_external_requests() {
		if ( ! defined( 'WP_HTTP_BLOCK_EXTERNAL' ) ) {
			define( 'WP_HTTP_BLOCK_EXTERNAL', true );
		}
	}

	public function no_comments() {
		new Components\DisableComments;
	}

	public function search_posts_only(): void {
		add_filter( 'pre_get_posts', function ( WP_Query $query ): void {
			// Bypass Rest API & admin search.
			if ( defined( 'REST_REQUEST' ) || is_admin() ) {
				return;
			}
			if ( $query->is_main_query() && $query->is_search() ) {
				$query->set( 'post_type', 'post' );
			}
		} );
	}

	public function no_comment_url() {
		add_filter( 'comment_form_default_fields', function ( array $fields ): array {
			unset( $fields['url'] );
			return $fields;
		} );
	}

	public function no_texturize() {
		add_filter( 'run_wptexturize', '__return_false' );
	}

	public function maintenance_mode(): void {
		add_action( 'template_redirect', function () {
			if ( ! current_user_can( 'manage_options' ) ) {
				// Translators: %1$s - Header, %2$s - Message.
				$html = sprintf(
					'<h1>%1$s</h1><br>%2$s',
					__( 'Maintenance mode', 'falcon' ),
					__( 'The website is under maintenance, please come back later. Sorry for the inconvenience.', 'falcon' )
				);
				wp_die( wp_kses_post( $html ), esc_html__( 'Maintenance mode', 'falcon' ), 503 );
			}
		} );
	}
}

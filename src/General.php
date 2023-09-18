<?php
namespace Falcon;

use Falcon\Components\DisableComments;
use WP_Error;
use WP_Query;

class General extends Base {
	protected $features = [
		'no_gutenberg',
		'no_rest_api',
		'no_heartbeat',
		'no_xmlrpc',
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
	];

	public function no_gutenberg() {
		// Disable Gutenberg on the back end.
		add_filter( 'use_block_editor_for_post', '__return_false' );

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

	public function no_rest_api() {
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );

		add_filter( 'rest_authentication_errors', [ $this, 'no_public_rest_api' ] );
	}

	public function no_public_rest_api( $access ) {
		return is_user_logged_in()
			? $access
			: new WP_Error( 'rest_login_required', __( 'REST API restricted to authenticated users.', 'falcon' ), [ 'status' => rest_authorization_required_code() ] );
	}

	public function no_heartbeat() {
		add_action( 'init', [ $this, 'remove_heartbeat_script' ], 1 );
	}

	public function remove_heartbeat_script() {
		wp_deregister_script( 'heartbeat' );
	}

	public function no_xmlrpc() {
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'pings_open', '__return_false' );
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
		new DisableComments;
	}

	public function search_posts_only() {
		add_filter( 'pre_get_posts', function ( WP_Query $query ) {
			if ( ! is_admin() && $query->is_search ) {
				$query->set( 'post_type', 'post' );
			}
			return $query;
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
}

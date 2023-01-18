<?php
namespace Falcon;

use WP_Error;

class General extends Base {
	protected $features = [
		'no_gutenberg',
		'no_rest_api',
		'no_heartbeat',
		'no_xmlrpc',
		'no_emojis',
		'no_embeds',
		'no_revisions',
		'no_self_pings',
		'no_privacy',
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

	public function no_emojis() {
		add_action( 'init', [ $this, 'disable_emojis' ] );
	}

	public function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		add_filter( 'tiny_mce_plugins', [ $this, 'disable_emojis_tinymce' ] );
		add_filter( 'wp_resource_hints', [ $this, 'remove_emojis_dns_prefetch' ], 10, 2 );
	}

	public function disable_emojis_tinymce( $plugins ) {
		return is_array( $plugins ) ? array_diff( $plugins, [ 'wpemoji' ] ) : [];
	}

	public function remove_emojis_dns_prefetch( $urls, $relation_type ) {
		if ( 'dns-prefetch' !== $relation_type ) {
			return $urls;
		}
		return array_filter( $urls, function( $url ) {
			return false === strpos( $url, 'https://s.w.org/images/core/emoji/' );
		} );
	}

	public function no_embeds() {
		require FALCON_DIR . '/vendor/disable-embeds/disable-embeds.php';
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
		$links    = array_filter( $links, function( $link ) use ( $home_url ) {
			return false === strpos( $link, $home_url );
		} );
	}
}

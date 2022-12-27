<?php
namespace Falcon;

class General {
	public function __construct() {
		if ( Settings::is_feature_active( 'no_gutenberg' ) ) {
			// Disable Gutenberg on the back end.
			add_filter( 'use_block_editor_for_post', '__return_false' );

			// Disable Gutenberg for widgets.
			add_filter( 'use_widgets_block_editor', '__return_false' );

			add_action( 'wp_enqueue_scripts', [ $this, 'remove_gutenberg_assets' ], 20 );
		}
		if ( Settings::is_feature_active( 'no_revisions' ) ) {
			add_filter( 'wp_revisions_to_keep', '__return_zero' );
		}
		if ( Settings::is_feature_active( 'no_xmlrpc' ) ) {
			add_filter( 'xmlrpc_enabled', '__return_false' );
			add_filter( 'pings_open', '__return_false' );
		}
		if ( Settings::is_feature_active( 'no_heartbeat' ) ) {
			add_action( 'init', [ $this, 'disable_heartbeat' ], 1 );
		}
		if ( Settings::is_feature_active( 'no_emojis' ) ) {
			add_action( 'init', [ $this, 'disable_emojis' ] );
		}
		if ( Settings::is_feature_active( 'no_self_pings' ) ) {
			add_action( 'pre_ping', [ $this, 'stop_self_pings' ] );
		}
	}

	public function remove_gutenberg_assets() {
		// Remove CSS on the front end.
		wp_dequeue_style( 'wp-block-library' );

		// Remove Gutenberg theme.
		wp_dequeue_style( 'wp-block-library-theme' );

		// Remove inline global CSS on the front end.
		wp_dequeue_style( 'global-styles' );
	}

	public function disable_heartbeat() {
		wp_deregister_script( 'heartbeat' );
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

	/**
	 * @link http://wordpress.stackexchange.com/a/1852
	 */
	public function stop_self_pings( &$links ) {
		$home_url = home_url();
		$links    = array_filter( $links, function( $link ) use ( $home_url ) {
			return false === strpos( $link, $home_url );
		} );
	}
}

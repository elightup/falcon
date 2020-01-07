<?php
namespace Falcon;

class General {
	public function __construct() {
		if ( Settings::is_feature_active( 'no_heartbeat' ) ) {
			add_action( 'init', array( $this, 'disable_heartbeat' ), 1 );
		}
		if ( Settings::is_feature_active( 'no_emojis' ) ) {
			add_action( 'init', array( $this, 'disable_emojis' ) );
		}
		if ( Settings::is_feature_active( 'no_self_pings' ) ) {
			add_action( 'pre_ping', array( $this, 'stop_self_pings' ) );
		}
		if ( Settings::is_feature_active( 'no_recent_comments_widget_style' ) ) {
			add_filter( 'show_recent_comments_widget_style', '__return_false' );
		}
		if ( Settings::is_feature_active( 'no_query_string' ) ) {
			add_filter( 'script_loader_src', [ $this, 'remove_query_string' ] );
			add_filter( 'style_loader_src', [ $this, 'remove_query_string' ] );
		}
		if ( Settings::is_feature_active( 'schema_less_urls' ) ) {
			add_filter( 'script_loader_src', [ $this, 'remove_protocol' ] );
			add_filter( 'style_loader_src', [ $this, 'remove_protocol' ] );
		}
		if ( Settings::is_feature_active( 'no_jquery_migrate' ) ) {
			add_action( 'wp_default_scripts', [ $this, 'remove_jquery_migrate' ] );
		}
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
		return is_array( $plugins ) ? array_diff( $plugins, ['wpemoji'] ) : [];
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
		$links = array_filter( $links, function( $link ) {
			return false === strpos( $link, $home_url );
		} );
	}

	public function remove_query_string( $url ) {
		return remove_query_arg( 'ver', $url );
	}

	public function remove_protocol( $url ) {
		return str_replace( [ 'https:', 'http:' ], '', $url );
	}

	public function remove_jquery_migrate( $scripts ) {
		if ( is_admin() || empty( $scripts->registered['jquery'] ) ) {
			return;
		}
		$script = $scripts->registered['jquery'];
		if ( $script->deps ) {
			$script->deps = array_diff( $script->deps, ['jquery-migrate'] );
		}
	}
}

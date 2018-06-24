<?php
/**
 * Optimization for both frontend and backend.
 *
 * @package Falcon
 * @author  GretaThemes <info@gretathemes.com>
 * @link    https://gretathemes.com
 */

namespace Falcon;

/**
 * General optimization class.
 */
class General {
	/**
	 * Add hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'disable_heartbeat' ), 1 );
		add_action( 'init', array( $this, 'disable_emojis' ) );
		add_action( 'pre_ping', array( $this, 'no_self_ping' ) );
	}

	/**
	 * Disable heartbeat.
	 */
	public function disable_heartbeat() {
		wp_deregister_script( 'heartbeat' );
	}

	/**
	 * Disable emojis.
	 */
	public function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
		add_filter( 'wp_resource_hints', array( $this, 'disable_emojis_remove_dns_prefetch' ), 10, 2 );
	}

	/**
	 * Filter function used to remove the tinymce emoji plugin.
	 *
	 * @param  array $plugins
	 *
	 * @return array
	 */
	public function disable_emojis_tinymce( $plugins ) {
		return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
	}
	
	public function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
		if ( 'dns-prefetch' == $relation_type ) {
			// Strip out any URLs referencing the WordPress.org emoji location
			$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
			foreach ( $urls as $key => $url ) {
				if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
					unset( $urls[$key] );
				}
			}
		}
		return $urls;
	}

	/**
	 * Stop self pinging
	 *
	 * @link  http://wordpress.stackexchange.com/a/1852
	 *
	 * @param array $links
	 */
	public function no_self_ping( $links ) {
		$home_url = home_url();
		foreach ( $links as $l => $link ) {
			if ( false !== strpos( $link, $home_url ) ) {
				unset( $links[ $l ] );
			}
		}
	}
}

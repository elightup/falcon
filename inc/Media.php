<?php
/**
 * Optimization for media.
 *
 * @package Falcon
 * @author  GretaThemes <info@gretathemes.com>
 * @link    https://gretathemes.com
 */

namespace Falcon;

/**
 * Media class.
 */
class Media {
	/**
	 * Add hooks.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'dequeue' ], 20 );

		// No styles for recent comments widget.
		add_filter( 'show_recent_comments_widget_style', '__return_false' );

		// Remove query string for resources.
		add_filter( 'script_loader_src', [ $this, 'remove_query_string' ], 10, 2 );
		add_filter( 'style_loader_src', [ $this, 'remove_query_string' ], 10, 2 );

		// Remove protocol for resources.
		add_filter( 'script_loader_src', [ $this, 'remove_protocol' ] );
		add_filter( 'style_loader_src', [ $this, 'remove_protocol' ] );

		// Use jQuery from Google CDN.
		add_action( 'wp_enqueue_scripts', [ $this, 'jquery_cdn' ] );
	}

	/**
	 * Dequeue unnecessary scripts and styles.
	 */
	public function dequeue() {
		// Jetpack
		wp_dequeue_script( 'devicepx' );
	}

	/**
	 * Remove version for resources.
	 *
	 * @param string $url    Resource URL.
	 * @param string $handle Resource handle.
	 *
	 * @return string
	 */
	public function remove_query_string( $url, $handle ) {
		$option  = get_option( 'falcon' );
		$handles = isset( $option['query_string_handles'] ) ? $option['query_string_handles'] : '';
		$handles = array_filter( array_map( 'trim', explode( ',', $handles . ',' ) ) );

		// Make sure $handle does not contain suffix -css added automatically by WordPress.
		array_walk( $handles, function ( &$handle ) {
			$handle = preg_replace( '/-css$/', '', $handle );
		} );

		if ( ! in_array( $handle, $handles ) ) {
			$url = remove_query_arg( 'ver', $url );
		}

		return $url;
	}

	/**
	 * Remove protocol for resources.
	 *
	 * @param string $url Resource URL.
	 *
	 * @return string
	 */
	public function remove_protocol( $url ) {
		return str_replace( [ 'https:', 'http:' ], '', $url );
	}

	/**
	 * Use jQuery from Google CDN.
	 */
	public function jquery_cdn() {
		$option  = get_option( 'falcon' );
		$version = empty( $option['latest_jquery'] ) ? '2.2.4' : '3.1.1';

		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', "https://ajax.googleapis.com/ajax/libs/jquery/$version/jquery.min.js", '', $version, true );
	}
}

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
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue' ), 20 );

		// No styles for recent comments widget.
		add_filter( 'show_recent_comments_widget_style', '__return_false' );

		// Remove query string in JS and CSS file.
		add_filter( 'script_loader_src', array( $this, 'unversion' ) );
		add_filter( 'style_loader_src', array( $this, 'unversion' ) );

		// Use jQuery from Google CDN.
		add_action( 'wp_enqueue_scripts', array( $this, 'jquery_cdn' ) );
	}

	/**
	 * Dequeue unnecessary scripts and styles.
	 */
	public function dequeue() {
		// Jetpack
		wp_dequeue_script( 'devicepx' );
	}

	/**
	 * Remove version for scripts and styles.
	 *
	 * @param  string $src
	 *
	 * @return string
	 */
	public function unversion( $src ) {
		$src = remove_query_arg( 'ver', $src );
		$src = str_replace( array( 'https:', 'http:' ), '', $src );

		return $src;
	}

	/**
	 * Use jQuery from Google CDN.
	 */
	public function jquery_cdn() {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', '', '2.2.4', true );
	}
}

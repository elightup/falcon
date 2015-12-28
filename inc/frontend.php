<?php
/**
 * Optimization for the frontend.
 * @package Falcon
 */

/**
 * Frontend optimization class.
 * @package Falcon
 */
class Falcon_Frontend
{
	/**
	 * Add hooks when class is loaded.
	 */
	public function __construct()
	{
		// Remove query string in JS and CSS file.
		add_filter( 'script_loader_src', array( $this, 'unversion' ) );
		add_filter( 'style_loader_src', array( $this, 'unversion' ) );

		// Cleanup header.
		add_action( 'template_redirect', array( $this, 'cleanup_header' ) );

		// No styles for recent comments widget.
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}

	/**
	 * Remove version for scripts and styles.
	 * @param string $src
	 * @return string
	 */
	public function unversion( $src )
	{
		return remove_query_arg( 'ver', $src );
	}

	/**
	 * Remove junk headers.
	 */
	public function cleanup_header()
	{
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	}
}

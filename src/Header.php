<?php
namespace Falcon;

class Header {
	public function __construct() {
		add_action( 'template_redirect', [ $this, 'cleanup' ] );
	}

	public function cleanup() {
		if ( Settings::is_feature_active( 'no_feed_links' ) ) {
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
		}
		if ( Settings::is_feature_active( 'no_rsd_link' ) ) {
			remove_action( 'wp_head', 'rsd_link' );
		}
		if ( Settings::is_feature_active( 'no_wlwmanifest_link' ) ) {
			remove_action( 'wp_head', 'wlwmanifest_link' );
		}
		if ( Settings::is_feature_active( 'no_adjacent_posts_links' ) ) {
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		}
		if ( Settings::is_feature_active( 'no_wp_generator' ) ) {
			remove_action( 'wp_head', 'wp_generator' );
		}
		if ( Settings::is_feature_active( 'no_shortlink' ) ) {
			remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		}
	}
}

<?php
namespace Falcon;

class Header extends Base {
	protected $features = [
		'no_feed_links',
		'no_rsd_link',
		'no_wlwmanifest_link',
		'no_adjacent_posts_links',
		'no_wp_generator',
		'no_shortlink',
		'no_rest_link',
	];

	public function no_feed_links() {
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}

	public function no_rsd_link() {
		remove_action( 'wp_head', 'rsd_link' );
	}

	public function no_wlwmanifest_link() {
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}

	public function no_adjacent_posts_links() {
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
	}

	public function no_wp_generator() {
		remove_action( 'wp_head', 'wp_generator' );
	}

	public function no_shortlink() {
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
	}

	public function no_rest_link() {
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	}
}

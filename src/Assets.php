<?php
namespace Falcon;

class Assets extends Base {
	protected $features = [
		'no_query_string',
		'no_jquery_migrate',
		'schema_less_urls',
		'no_recent_comments_widget_style',
		'cleanup_menu',
	];

	public function no_query_string() {
		add_filter( 'script_loader_src', [ $this, 'remove_query_string' ] );
		add_filter( 'style_loader_src', [ $this, 'remove_query_string' ] );
	}

	public function remove_query_string( $url ) {
		return remove_query_arg( 'ver', $url );
	}

	public function no_jquery_migrate() {
		add_action( 'wp_default_scripts', [ $this, 'remove_jquery_migrate' ] );
	}

	public function remove_jquery_migrate( $scripts ) {
		if ( empty( $scripts->registered['jquery'] ) ) {
			return;
		}
		$script = $scripts->registered['jquery'];
		if ( $script->deps ) {
			$script->deps = array_diff( $script->deps, [ 'jquery-migrate' ] );
		}
	}

	public function schema_less_urls() {
		add_filter( 'script_loader_src', [ $this, 'remove_protocol' ] );
		add_filter( 'style_loader_src', [ $this, 'remove_protocol' ] );
	}

	public function remove_protocol( $url ) {
		return str_replace( [ 'https:', 'http:' ], '', $url );
	}

	public function no_recent_comments_widget_style() {
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
	}

	public function cleanup_menu() {
		add_filter( 'nav_menu_item_id', '__return_empty_string' );
		add_filter( 'nav_menu_css_class', [ $this, 'remove_menu_class' ] );
		add_filter( 'page_css_class', [ $this, 'remove_page_class' ] );
	}

	public function remove_menu_class( $classes ) {
		return array_intersect( $classes, [
			'menu-item',
			'current-menu-item',
			'menu-item-has-children',
		] );
	}

	public function remove_page_class( $classes ) {
		return array_intersect( $classes, [
			'page_item',
			'page_item_has_children',
			'current_page_item',
		] );
	}
}

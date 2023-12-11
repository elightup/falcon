<?php
namespace Falcon;

class Media extends Base {
	protected $features = [
		'no_query_string',
		'no_jquery_migrate',
		'schema_less_urls',
		'no_recent_comments_widget_style',
		'cleanup_menu',
		'no_emojis',
		'no_image_threshold',
		'no_exif_rotate',
		'no_thumbnails',
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

	public function remove_menu_class( array $classes ): array {
		return array_intersect( $classes, [
			'menu-item',
			'current-menu-item',
			'current-menu-ancestor',
			'menu-item-has-children',
		] );
	}

	public function remove_page_class( array $classes ): array {
		return array_intersect( $classes, [
			'page_item',
			'current_page_item',
			'current_page_ancestor',
			'page_item_has_children',
		] );
	}

	public function no_emojis(): void {
		add_action( 'init', [ $this, 'disable_emojis' ] );
	}

	public function disable_emojis(): void {
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

	public function disable_emojis_tinymce( $plugins ): array {
		return is_array( $plugins ) ? array_diff( $plugins, [ 'wpemoji' ] ) : [];
	}

	public function remove_emojis_dns_prefetch( array $urls, string $type ): array {
		if ( 'dns-prefetch' !== $type ) {
			return $urls;
		}
		return array_filter( $urls, function ( $url ) {
			return false === strpos( $url, 'https://s.w.org/images/core/emoji/' );
		} );
	}

	public function no_image_threshold() {
		add_filter( 'big_image_size_threshold', '__return_zero' );
	}

	public function no_exif_rotate() {
		add_filter( 'wp_image_maybe_exif_rotate', '__return_false' );
	}

	public function no_thumbnails() {
		add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );
	}
}

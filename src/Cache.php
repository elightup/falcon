<?php
namespace Falcon;

defined( 'ABSPATH' ) || die;

class Cache {
	private string $cache_dir;

	public function __construct() {
		if ( ! Settings::is_feature_active( 'cache' ) ) {
			return;
		}

		$upload_dir = wp_upload_dir();
		$this->cache_dir = $upload_dir['basedir'] . '/cache';
		add_action( 'wp_ajax_falcon_clear_cache', [ $this, 'ajax_clear_cache' ] );

		add_action( 'template_redirect', [ $this, 'serve_or_buffer' ], 0 );
		$this->hook_to_clear_cache();
	}

	private function hook_to_clear_cache(): void {
		$actions = [
			'save_post',
			'delete_post',
			'trashed_post',
			'edit_term',
			'delete_term',
			'wp_insert_comment',
			'edit_comment',
			'delete_comment',
			'transition_comment_status',
			'switch_theme',
			'activated_plugin',
			'deactivated_plugin',
			'update_option_sidebars_widgets',
			'wp_update_nav_menu',
			'_core_updated_successfully',
			'customize_save_after',
		];

		foreach ( $actions as $action ) {
			add_action( $action, [ $this, 'clear_cache' ] );
		}
	}

	public function serve_or_buffer(): void {
		if ( ! $this->should_cache() ) {
			return;
		}

		$file = $this->get_file_path();
		if ( file_exists( $file ) ) {
			$this->serve( $file );
			exit;
		}

		ob_start( [ $this, 'save' ] );
	}

	private function should_cache(): bool {
		if ( is_user_logged_in() ) {
			return false;
		}

		if ( $_SERVER['REQUEST_METHOD'] !== 'GET' ) {
			return false;
		}

		if ( wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
			return false;
		}

		if ( session_status() === PHP_SESSION_ACTIVE || ! empty( $_COOKIE ) && ( isset( $_COOKIE[ 'wordpress_logged_in_' . COOKIEHASH ] ) || isset( $_COOKIE[ 'comment_author_' . COOKIEHASH ] ) ) ) {
			return false;
		}

		if ( is_search() || is_404() ) {
			return false;
		}

		if ( is_singular() && post_password_required() ) {
			return false;
		}

		return true;
	}

	private function get_file_path(): string {
		$url = $this->normalize_url();
		$hash = md5( $url );

		return $this->cache_dir . '/' . $hash . '.html';
	}

	private function normalize_url(): string {
		$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
		return trim( $path, '/' );
	}

	private function serve( string $file ): void {
		// public: cache is available to both browser and CDN
		// max-age=31536000: cache for 1 year (considered as infinite)
		// immutable: browser will not revalidate until the cache expires
		header( 'Cache-Control: public, max-age=31536000, immutable' );
		// Last-Modified: the last time the file was modified
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', filemtime( $file ) ) . ' GMT' );
		// Vary: Accept-Encoding: tell CDN to vary the cache by the Accept-Encoding header
		header( 'Vary: Accept-Encoding' );
		// X-Cache: HIT: indicate that the cache was hit
		header( 'X-Cache: HIT' );
		readfile( $file );
	}

	public function save( string $html ): string {
		if ( empty( $html ) ) {
			return $html;
		}

		$this->create_dir();

		$file = $this->get_file_path();
		file_put_contents( $file, $html );

		header( 'X-Cache: MISS' );

		return $html;
	}

	private function create_dir(): void {
		if ( ! is_dir( $this->cache_dir ) ) {
			wp_mkdir_p( $this->cache_dir );
		}
	}

	public function clear_cache(): void {
		if ( ! is_dir( $this->cache_dir ) ) {
			return;
		}

		$files = glob( $this->cache_dir . '/*.html' );
		if ( ! $files ) {
			return;
		}

		foreach ( $files as $file ) {
			unlink( $file );
		}
	}

	public function ajax_clear_cache(): void {
		check_ajax_referer( 'clear-cache' );

		$this->clear_cache();
		wp_send_json_success( __( 'Cache cleared successfully!', 'falcon' ) );
	}
}


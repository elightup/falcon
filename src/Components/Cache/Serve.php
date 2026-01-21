<?php
namespace Falcon\Components\Cache;

defined( 'ABSPATH' ) || die;

class Serve {
	public function __construct() {
		$this->serve();
		ob_start( [ $this, 'save' ] );
	}

	public function serve(): void {
		if ( ! $this->should_cache() ) {
			return;
		}

		$file = $this->get_cache_file();
		if ( ! file_exists( $file ) ) {
			return;
		}

		header( 'Cache-Control: public, max-age=31536000, immutable' );
		header( 'X-Cache: HIT' );
		readfile( $file );
		exit;
	}

	public function save( string $html ): string {
		// If the HTML is empty, don't save it.
		if ( empty( $html ) ) {
			return $html;
		}

		if ( ! $this->should_create_cache() ) {
			return $html;
		}

		$file = $this->get_cache_file();
		wp_mkdir_p( dirname( $file ) );
		file_put_contents( $file, $html );
		header( 'X-Cache: MISS' );
		return $html;
	}

	/**
	 * Check if the request should be cached.
	 *
	 * The function is called in the advanced-cache.php file, which is loaded before the WordPress core,
	 * we should avoid using any template tags or WordPress functions.
	 */
	private function should_cache(): bool {
		// Only cache GET requests.
		if ( $_SERVER['REQUEST_METHOD'] !== 'GET' ) {
			return false;
		}

		// Do not cache search result pages.
		if ( isset( $_GET['s'] ) ) {
			return false;
		}

		// Don't cache AJAX, REST API, XMLRPC requests.
		if ( defined( 'DOING_AJAX' ) || defined( 'REST_REQUEST' ) || defined( 'XMLRPC_REQUEST' ) ) {
			return false;
		}

		// Don't cache logged-in users and comment authors.
		// https://developer.wordpress.org/advanced-administration/wordpress/cookies/
		if ( ! empty( $_COOKIE ) ) {
			foreach ( $_COOKIE as $key => $value ) {
				if ( str_contains( $key, 'wordpress_logged_in_' ) || str_contains( $key, 'comment_author_' ) ) {
					return false;
				}
			}
		}

		// Don't cache if session is active.
		if ( session_status() === PHP_SESSION_ACTIVE ) {
			return false;
		}

		// Don't cache requests to PHP files like wp-login.php
		// These files are still use advanced-cache.php
		$path = $this->get_path();
		if ( str_contains( $path, '.php' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if we should create a cache file.
	 *
	 * This function is called in the save() method, which is called in the output buffering.
	 * So we can use any template tags or WordPress functions.
	 */
	private function should_create_cache(): bool {
		if ( ! $this->should_cache() ) {
			return false;
		}

		// Don't cache search results and 404 pages.
		if ( is_search() || is_404() ) {
			return false;
		}

		// Don't cache password protected posts.
		if ( is_singular() && post_password_required() ) {
			return false;
		}

		// Don't cache for logged-in users.
		if ( is_user_logged_in() ) {
			return false;
		}

		return apply_filters( 'falcon_cache', true );
	}

	private function get_cache_file(): string {
		$hash = md5( $this->get_path() );
		return WP_CONTENT_DIR . '/uploads/cache/' . $hash . '.html';
	}

	private function get_path(): string {
		$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
		$path = trim( $path, '/' );

		return $path === '' ? '/' : $path;
	}
}

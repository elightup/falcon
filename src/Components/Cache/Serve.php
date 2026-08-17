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

		$this->send_cache_headers( 'HIT' );
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
		$this->send_cache_headers( 'MISS' );
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
		// These files still use advanced-cache.php
		$path = parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ) ?? '';
		if ( str_contains( $path, '.php' ) ) {
			return false;
		}

		// Don't cache Markdown requests (e.g. Slim SEO Pro's Markdown for AI).
		// The cache key is based on the URL only, so HTML and Markdown responses for the same URL would collide.
		if ( $this->is_markdown_requested() ) {
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

		// Don't cache if a plugin asked to skip page cache, e.g. Slim SEO Pro's Markdown for AI.
		if ( defined( 'DONOTCACHEPAGE' ) && DONOTCACHEPAGE ) {
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
		$hash = md5( $_SERVER['REQUEST_URI'] ?? '' );
		return WP_CONTENT_DIR . '/uploads/cache/' . $hash . '.html';
	}

	/**
	 * Whether the Accept header prefers Markdown over HTML (RFC 9110).
	 * Mirrors Slim SEO Pro's Markdown for AI so both plugins make the same decision.
	 */
	private function is_markdown_requested(): bool {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- Accept header is only read for content negotiation, never output.
		$accept = $_SERVER['HTTP_ACCEPT'] ?? '';
		if ( $accept === '' ) {
			return false;
		}

		[ $markdown_q, $markdown_specificity ] = $this->accept_quality( $accept, 'text', 'markdown' );
		[ $html_q ]                            = $this->accept_quality( $accept, 'text', 'html' );

		return $markdown_q > 0.0 && ( $markdown_q > $html_q || ( $markdown_q === $html_q && $markdown_specificity === 2 ) );
	}

	/**
	 * Get q-value and specificity for a media type from the Accept header.
	 * Specificity: 2 = exact, 1 = type wildcard, 0 = full wildcard.
	 *
	 * @return array{0: float, 1: int}
	 */
	private function accept_quality( string $accept, string $type, string $subtype ): array {
		$best             = 0.0;
		$best_specificity = -1;

		foreach ( explode( ',', $accept ) as $entry ) {
			$entry = trim( $entry );
			if ( $entry === '' ) {
				continue;
			}

			$params      = explode( ';', $entry );
			$media_type  = strtolower( trim( array_shift( $params ) ) );
			[ $t, $s ]   = array_pad( explode( '/', $media_type, 2 ), 2, '' );
			$specificity = -1;

			if ( $t === $type && $s === $subtype ) {
				$specificity = 2;
			} elseif ( $t === $type && $s === '*' ) {
				$specificity = 1;
			} elseif ( $t === '*' && $s === '*' ) {
				$specificity = 0;
			}

			if ( $specificity < 0 ) {
				continue;
			}

			$q = 1.0;
			foreach ( $params as $param ) {
				$param = trim( $param );
				if ( stripos( $param, 'q=' ) === 0 ) {
					$q = (float) substr( $param, 2 );
				}
			}

			if ( $specificity > $best_specificity || ( $specificity === $best_specificity && $q > $best ) ) {
				$best             = $q;
				$best_specificity = $specificity;
			}
		}

		return [ $best, $best_specificity ];
	}

	private function send_cache_headers( string $status ): void {
		header( 'Cache-Control: public, max-age=31536000, s-maxage=31536000' );
		header( "X-Cache: $status" );
	}
}

<?php
// Falcon Cache

defined( 'ABSPATH' ) || die;

function falcon_should_cache(): bool {
	// Only cache GET requests.
	if ( $_SERVER['REQUEST_METHOD'] !== 'GET' ) {
		return false;
	}

	// Don't cache robots.txt, wp-cron.php, xmlrpc.php, sitemap.xml, sitemap.xsl, etc.
	$uri = $_SERVER['REQUEST_URI'];
	$disallowed_uris = [
		'robots.txt',
		'.php',
		'.xml',
		'.xsl',
	];
	foreach ( $disallowed_uris as $disallowed_uri ) {
		if ( str_contains( $uri, $disallowed_uri ) ) {
			return false;
		}
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

	return true;
}

function falcon_should_create_cache(): bool {
	if ( ! falcon_should_cache() ) {
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

	return true;
}

function falcon_get_cache_file(): string {
	$uri  = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
	$uri  = trim( $uri, '/' );
	$uri  = $uri === '' ? '/' : $uri;
	$hash = md5( $uri );

	return WP_CONTENT_DIR . '/uploads/cache/' . $hash . '.html';
}

function falcon_serve(): void {
	if ( ! falcon_should_cache() ) {
		return;
	}

	$file = falcon_get_cache_file();
	if ( ! file_exists( $file ) ) {
		return;
	}

	header( 'Cache-Control: public, max-age=31536000, immutable' );
	header( 'X-Cache: HIT' );
	readfile( $file );
	exit;
}

function falcon_save( string $html ): string {
	if ( ! falcon_should_create_cache() ) {
		return $html;
	}

	// If the HTML is empty, don't save it.
	if ( empty( $html ) ) {
		return $html;
	}

	$file = falcon_get_cache_file();
	file_put_contents( $file, $html );
	header( 'X-Cache: MISS' );
	return $html;
}

falcon_serve();
ob_start( 'falcon_save' );
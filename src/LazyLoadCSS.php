<?php
namespace Falcon;

use _WP_Dependency;

class LazyLoadCSS {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'process' ], PHP_INT_MAX );
		add_action( 'wp_footer', [ $this, 'process' ], 15 ); // WordPress prints footer scripts at priority 20, so we must run before that.
	}

	public function process() {
		$files = $this->get_files();

		foreach ( $files as $file ) {
			$this->lazy_load( $file );
		}

		echo "\n";
	}

	private function lazy_load( string $file ): void {
		$css = $this->get_css( $file );
		if ( ! $css || ! wp_style_is( $css->handle, 'enqueued' ) ) {
			return;
		}

		wp_dequeue_style( $css->handle );

		$url = add_query_arg( 'ver', $css->ver, $css->src );
		// phpcs:ignore
		echo "\n", '<link rel="stylesheet" href="', esc_url( $url ) . '" media="print" onload="this.media=\'all\'">';
	}

	private function get_css( string $file ): ?_WP_Dependency {
		$wp_styles = wp_styles();

		foreach ( $wp_styles->registered as $handle => $registered ) {
			if ( $handle === $file || str_contains( $registered->src, $file ) ) {
				return $registered;
			}
		}

		return null;
	}

	private function get_files(): array {
		$option = get_option( 'falcon', [] );
		$files  = $option['lazy_load_css'] ?? '';
		$files  = array_map( 'trim', explode( "\n", $files ) );

		return $files;
	}
}

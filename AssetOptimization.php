<?php
namespace Hirosart;

class AssetOptimization {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'handle_assets' ], PHP_INT_MAX );
		add_action( 'wp_footer', [ $this, 'handle_assets' ], 15 ); // WordPress prints footer scripts at priority 20, so we must run before that.

		// Jetpack.
		add_filter( 'jetpack_implode_frontend_css', '__return_false', PHP_INT_MAX );
		add_filter( 'jetpack_sharing_counts', '__return_false', PHP_INT_MAX );
	}

	public function handle_assets(): void {
		$this->disable_assets();
		$this->lazy_load_css();
		$this->remove_polyfill_dependencies( [ 'regenerator-runtime', 'wp-polyfill-inert', 'wp-polyfill' ] );
	}

	private function disable_assets() {
		wp_dequeue_style( 'classic-theme-styles' );
		wp_dequeue_script( 'jetpack-photon' );
		wp_dequeue_script( 'jquery-ui-autocomplete' );

		// WooCommerce.
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-blocks-style' );
		wp_dequeue_script( 'woo-tracks' );
		if ( is_front_page() || is_archive() || is_home() || is_singular( 'post' ) ) {
			wp_dequeue_style( 'woocommerce-layout' );
			wp_dequeue_style( 'woocommerce-smallscreen' );
		}

		if ( is_tax( 'product_cat' ) ) {
			// WC Fields Factory plugin.
			wp_dequeue_style( 'wcff-jquery-ui-style' );
			wp_dequeue_style( 'wcff-timepicker-style' );
			wp_dequeue_style( 'wcff-colorpicker-style' );
			wp_dequeue_style( 'wcff-client-style' );
		}

		if ( is_singular( 'product' ) ) {
			wp_dequeue_script( 'ppcp-smart-button' );
		}

		if ( is_singular( 'post' ) ) {
			// WooCommerce.
			wp_dequeue_style( 'woocommerce' );

			// Woo Quickview plugin.
			wp_dequeue_style( 'sp_wqv-button-icons' );
			wp_dequeue_style( 'wqv-magnific-popup' );
			wp_dequeue_style( 'wqv-perfect-scrollbar' );
			wp_dequeue_style( 'wqv-fontello' );
			wp_dequeue_style( 'wqv-fancy-box' );
			wp_dequeue_style( 'wqv-style' );
			wp_dequeue_style( 'wqv-custom' );

			// WooCommerce.
			wp_dequeue_script( 'woocommerce' );
			wp_dequeue_script( 'wc-add-to-cart' );
			wp_dequeue_script( 'wc-add-to-cart-variation' );
			wp_dequeue_script( 'jquery-blockui' );

			// Woo Quickview plugin.
			wp_dequeue_script( 'wqv-magnific-popup-js' );
			wp_dequeue_script( 'wqv-config-js' );
			wp_dequeue_script( 'wqv-facybox' );
			wp_dequeue_script( 'wqv-perfect-scrollbar-js' );
		}
	}

	private function lazy_load_css() {
		// Theme.
		$this->lazy_load_single_css( 'slick' );

		// WooCommerce.
		$this->lazy_load_single_css( 'woocommerce-general' );

		// Woo Quickview plugin.
		$this->lazy_load_single_css( 'sp_wqv-button-icons' );
		$this->lazy_load_single_css( 'wqv-magnific-popup' );
		$this->lazy_load_single_css( 'wqv-perfect-scrollbar' );
		$this->lazy_load_single_css( 'wqv-fontello' );
		$this->lazy_load_single_css( 'wqv-fancy-box' );
		$this->lazy_load_single_css( 'wqv-style' );
		$this->lazy_load_single_css( 'wqv-custom' );
		include SP_WQV_PATH . '/includes/custom-css.php';
		echo '<style>', $custom_css, '</style>';

		// Flexible shipping plugin.
		$this->lazy_load_single_css( 'flexible-shipping-free-shipping' );
		// Table of contents
		$this->lazy_load_single_css( 'ez-toc' );

		if ( is_tax( 'product_cat' ) ) {
			// Woo Product Filter plugin.
			$this->lazy_load_single_css( 'tooltipster' );
			$this->lazy_load_single_css( 'frontend.filters' );
			$this->lazy_load_single_css( 'jquery-ui' );
			$this->lazy_load_single_css( 'jquery-ui.structure' );
			$this->lazy_load_single_css( 'jquery-ui.theme' );
			$this->lazy_load_single_css( 'font-awesomeWpf' );
			$this->lazy_load_single_css( 'custom.filters' );
		}

		if ( is_singular( 'product' ) ) {
			$this->lazy_load_single_css( 'woocommerce-smallscreen' );
			$this->lazy_load_single_css( 'woocommerce-layout' );
		}
	}

	private function lazy_load_single_css( string $handle ): void {
		if ( ! wp_style_is( $handle, 'enqueued' ) ) {
			return;
		}

		$url = $this->get_css_url( $handle );
		wp_dequeue_style( $handle );
		echo "\n", '<link rel="stylesheet" href="', esc_url( $url ) . '" media="print" onload="this.media=\'all\'">';
	}

	private function get_css_url( string $handle ): string {
		$wp_styles = wp_styles();
		$url       = $wp_styles->registered[ $handle ]->src;
		$ver       = $wp_styles->registered[ $handle ]->ver;
		return add_query_arg( 'ver', $ver, $url );
	}

	private function remove_polyfill_dependencies( array $dependencies ): void {
		global $wp_scripts;
		$scripts = array_keys( $wp_scripts->registered );
		foreach ( $scripts as $script ) {
			$deps                                    = $wp_scripts->registered[ $script ]->deps;
			$deps                                    = array_values( array_diff( $deps, $dependencies ) );
			$wp_scripts->registered[ $script ]->deps = $deps;
		}
	}
}

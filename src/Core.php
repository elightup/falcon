<?php
namespace Falcon;

class Core {
	public function __construct() {
		add_action( 'activated_plugin', [ $this, 'redirect' ], 10, 2 );
		add_filter( 'plugin_action_links_falcon/falcon.php', [ $this, 'add_plugin_action_links' ] );
		add_filter( 'plugin_row_meta', [ $this, 'add_plugin_meta_links' ], 10, 2 );
	}

	public function redirect( $plugin, $network_wide = false ): void {
		$is_cli    = 'cli' === php_sapi_name();
		$is_plugin = 'falcon/falcon.php' === $plugin;

		$action           = isset( $_POST['action'] ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$checked          = isset( $_POST['checked'] ) && is_array( $_POST['checked'] ) ? count( $_POST['checked'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
		$is_bulk_activate = $action === 'activate-selected' && $checked > 1;
		$is_doing_ajax    = defined( 'DOING_AJAX' ) && DOING_AJAX;

		if ( ! $is_plugin || $network_wide || $is_cli || $is_bulk_activate || $this->is_bundled() || $is_doing_ajax ) {
			return;
		}

		wp_safe_redirect( admin_url( 'options-general.php?page=falcon' ) );
		die;
	}

	private function is_bundled(): bool {
		foreach ( $_REQUEST as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification
			if ( str_contains( $key, 'tgmpa' ) || ( is_string( $value ) && str_contains( $value, 'tgmpa' ) ) ) {
				return true;
			}
		}
		return false;
	}

	public function add_plugin_action_links( array $links ): array {
		$links[] = '<a href="' . esc_url( admin_url( 'options-general.php?page=falcon' ) ) . '">' . __( 'Settings', 'falcon' ) . '</a>';
		return $links;
	}

	public function add_plugin_meta_links( array $meta, string $file ) {
		if ( $file !== 'falcon/falcon.php' ) {
			return $meta;
		}

		$meta[] = '<a href="https://wordpress.org/support/plugin/falcon/reviews/?filter=5" target="_blank" title="' . esc_html__( 'Rate Falcon on WordPress.org', 'falcon' ) . '" style="color: #ffb900">'
			. str_repeat( '<span class="dashicons dashicons-star-filled" style="font-size: 16px; width:16px; height: 16px"></span>', 5 )
			. '</a>';

		return $meta;
	}
}

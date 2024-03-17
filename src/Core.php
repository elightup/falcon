<?php
namespace Falcon;

class Core {
	public function __construct() {
		add_filter( 'plugin_action_links_falcon/falcon.php', [ $this, 'add_plugin_action_links' ] );
		add_filter( 'plugin_row_meta', [ $this, 'add_plugin_meta_links' ], 10, 2 );
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

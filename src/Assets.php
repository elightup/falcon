<?php
namespace Falcon;

class Assets {
	public function __construct() {
		if ( Settings::is_feature_active( 'no_query_string' ) ) {
			add_filter( 'script_loader_src', [ $this, 'remove_query_string' ] );
			add_filter( 'style_loader_src', [ $this, 'remove_query_string' ] );
		}
		if ( Settings::is_feature_active( 'no_jquery_migrate' ) ) {
			add_action( 'wp_default_scripts', [ $this, 'remove_jquery_migrate' ] );
		}
		if ( Settings::is_feature_active( 'schema_less_urls' ) ) {
			add_filter( 'script_loader_src', [ $this, 'remove_protocol' ] );
			add_filter( 'style_loader_src', [ $this, 'remove_protocol' ] );
		}
		if ( Settings::is_feature_active( 'no_recent_comments_widget_style' ) ) {
			add_filter( 'show_recent_comments_widget_style', '__return_false' );
		}
	}

	public function remove_query_string( $url ) {
		return remove_query_arg( 'ver', $url );
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

	public function remove_protocol( $url ) {
		return str_replace( [ 'https:', 'http:' ], '', $url );
	}
}

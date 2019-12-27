<?php
namespace Falcon;

class Recommendation {
	public function __construct() {
		add_filter( 'plugins_api_result', [$this, 'recommends'], 10, 3 );
	}

	public function recommends( $res, $action, $args ) {
		global $tab;
		if ( 'featured' !== $tab || 'query_plugins' !== $action || is_wp_error( $res ) ) {
			return $res;
		}

		$res->plugins[] = $this->get_plugin_info( 'slim-seo' );
		return $res;
	}

	private function get_plugin_info( $slug ) {
		$args = [
			'page'     => 1,
			'per_page' => 1,
			'locale'   => get_user_locale(),
			'search'   => $slug,
		];
		$url = add_query_arg( [
			'action'  => 'query_plugins',
			'request' => $args,
		], 'http://api.wordpress.org/plugins/info/1.2/' );

		$http_url = $url;
		$ssl      = wp_http_supports( ['ssl'] );
		if ( $ssl ) {
			$url = set_url_scheme( $url, 'https' );
		}

		$request = wp_remote_get( $url, ['timeout' => 15] );
		$info = wp_remote_retrieve_body( $request );
		if ( ! $info ) {
			return null;
		}
		$info = json_decode( $info, true );
		return $info['plugins'][0];
	}
}
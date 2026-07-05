<?php
namespace Falcon\Components\Cache;

class Cloudflare {
	private const API_URL = 'https://api.cloudflare.com/client/v4';

	public static function is_enabled(): bool {
		$settings = self::get_settings();

		return ! empty( $settings['api_token'] ) && ! empty( $settings['zone_id'] );
	}

	public static function get_settings(): array {
		$option = get_option( 'falcon', [] );

		return $option['cloudflare'] ?? [];
	}

	/**
	 * Purge all cached content on Cloudflare edge.
	 */
	public static function purge(): bool {
		if ( ! self::is_enabled() ) {
			return false;
		}

		$settings = self::get_settings();
		$zone_id  = $settings['zone_id'];

		$response = wp_remote_post(
			self::API_URL . "/zones/$zone_id/purge_cache",
			[
				'timeout' => 15,
				'headers' => [
					'Authorization' => 'Bearer ' . $settings['api_token'],
					'Content-Type'  => 'application/json',
				],
				'body'    => wp_json_encode( [ 'purge_everything' => true ] ),
			]
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $code ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		return ! empty( $body['success'] );
	}
}

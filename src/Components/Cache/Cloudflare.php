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
	 * Resolve and attach zone_id for the current site host when needed.
	 *
	 * @param array $settings Cloudflare settings (api_token, optional zone_id).
	 * @param array $existing Previously saved Cloudflare settings.
	 */
	public static function prepare_settings( array $settings, array $existing = [] ): array {
		$api_token = sanitize_text_field( $settings['api_token'] ?? '' );

		// Keep the saved token when the password field is left blank.
		if ( '' === $api_token && ! empty( $existing['api_token'] ) ) {
			$api_token = $existing['api_token'];
		}

		if ( '' === $api_token ) {
			return [];
		}

		$prepared = [
			'api_token' => $api_token,
			'zone_id'   => sanitize_text_field( $settings['zone_id'] ?? $existing['zone_id'] ?? '' ),
			'zone_name' => sanitize_text_field( $settings['zone_name'] ?? $existing['zone_name'] ?? '' ),
		];

		$token_changed = empty( $existing['api_token'] ) || $existing['api_token'] !== $api_token;
		$host_changed  = ! empty( $prepared['zone_name'] ) && ! self::host_matches_zone( $prepared['zone_name'] );

		// Resolve when the token is new/changed, zone is missing, or the saved zone no longer matches this site.
		if ( $token_changed || $host_changed || '' === $prepared['zone_id'] ) {
			$zone = self::resolve_zone( $api_token );
			if ( $zone ) {
				$prepared['zone_id']   = $zone['id'];
				$prepared['zone_name'] = $zone['name'];
			} elseif ( $token_changed || $host_changed ) {
				$prepared['zone_id']   = '';
				$prepared['zone_name'] = '';
			}
		}

		return array_filter( $prepared );
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

	/**
	 * Find the Cloudflare zone that matches the current site host.
	 *
	 * @return array{id: string, name: string}|null
	 */
	private static function resolve_zone( string $api_token ): ?array {
		foreach ( self::get_host_candidates() as $name ) {
			$zone = self::fetch_zone_by_name( $api_token, $name );
			if ( $zone ) {
				return $zone;
			}
		}

		return null;
	}

	/**
	 * @return array{id: string, name: string}|null
	 */
	private static function fetch_zone_by_name( string $api_token, string $name ): ?array {
		$response = wp_remote_get(
			add_query_arg(
				[
					'name'       => $name,
					'status'     => 'active',
					'per_page'   => 1,
				],
				self::API_URL . '/zones'
			),
			[
				'timeout' => 15,
				'headers' => [
					'Authorization' => 'Bearer ' . $api_token,
					'Content-Type'  => 'application/json',
				],
			]
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return null;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $body['success'] ) || empty( $body['result'][0]['id'] ) ) {
			return null;
		}

		return [
			'id'   => $body['result'][0]['id'],
			'name' => $body['result'][0]['name'] ?? $name,
		];
	}

	/**
	 * Hostnames to try, from most specific to apex.
	 *
	 * @return string[]
	 */
	private static function get_host_candidates(): array {
		$host = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( ! is_string( $host ) || '' === $host ) {
			return [];
		}

		$host       = strtolower( $host );
		$candidates = [ $host ];

		if ( str_starts_with( $host, 'www.' ) ) {
			$candidates[] = substr( $host, 4 );
		}

		$parts = explode( '.', $host );
		while ( count( $parts ) > 2 ) {
			array_shift( $parts );
			$candidates[] = implode( '.', $parts );
		}

		return array_values( array_unique( $candidates ) );
	}

	private static function host_matches_zone( string $zone_name ): bool {
		$zone_name = strtolower( $zone_name );

		foreach ( self::get_host_candidates() as $host ) {
			if ( $host === $zone_name || str_ends_with( $host, '.' . $zone_name ) ) {
				return true;
			}
		}

		return false;
	}
}

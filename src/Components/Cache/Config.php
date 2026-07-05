<?php
namespace Falcon\Components\Cache;

class Config {
	private static string $config_file = '';

	private static function get_config_file(): string {
		if ( '' === self::$config_file ) {
			self::$config_file = WP_CONTENT_DIR . '/uploads/cache/config.json';
		}

		return self::$config_file;
	}

	public static function update(): void {
		$config = [
			'cloudflare' => Cloudflare::is_enabled(),
		];

		$file = self::get_config_file();
		wp_mkdir_p( dirname( $file ) );
		file_put_contents( $file, wp_json_encode( $config ) );
	}

	public static function delete(): void {
		wp_delete_file( self::get_config_file() );
	}

	public static function is_cloudflare_enabled(): bool {
		$file = self::get_config_file();
		if ( ! file_exists( $file ) ) {
			return false;
		}

		$config = json_decode( file_get_contents( $file ), true );

		return ! empty( $config['cloudflare'] );
	}
}

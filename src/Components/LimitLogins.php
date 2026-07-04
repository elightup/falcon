<?php
namespace Falcon\Components;

use WP_Error;

class LimitLogins {
	private int $max_attempts = 3;
	private static bool $was_blocked = false;

	public static function was_blocked(): bool {
		return self::$was_blocked;
	}

	public function __construct() {
		add_filter( 'authenticate', [ $this, 'check_login_attempts' ], 25, 2 );
		add_action( 'wp_login_failed', [ $this, 'track_failed_login' ] );
		add_action( 'wp_login', [ $this, 'clear_login_attempts' ] );
	}

	public function check_login_attempts( $user, string $username ) {
		if ( ! $username ) {
			return $user;
		}

		if ( $this->is_blocked() ) {
			self::$was_blocked = true;
			return new WP_Error( 'too_many_retries', __( '<strong>ERROR:</strong> Too many failed login attempts. Please try again in 1 hour.', 'falcon' ) );
		}

		return $user;
	}

	public function track_failed_login(): void {
		$key      = $this->get_key();
		$attempts = (int) get_transient( $key );

		if ( $attempts < $this->max_attempts ) {
			set_transient( $key, $attempts + 1, HOUR_IN_SECONDS );
		}
	}

	public function clear_login_attempts(): void {
		delete_transient( $this->get_key() );
	}

	private function is_blocked(): bool {
		return (int) get_transient( $this->get_key() ) >= $this->max_attempts;
	}

	private function get_key(): string {
		return 'falcon_login_attempts_' . $this->get_ip_hash();
	}

	private function get_ip_hash(): string {
		$ip = ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] )
			? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
			: sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );

		if ( str_contains( $ip, ',' ) ) {
			$ip = trim( explode( ',', $ip )[0] );
		}

		return wp_hash( $ip );
	}
}

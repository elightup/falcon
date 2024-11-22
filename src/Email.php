<?php
namespace Falcon;

defined( 'ABSPATH' ) || die;

use PHPMailer\PHPMailer\PHPMailer;

class Email extends Base {
	public function __construct() {
		parent::__construct();

		add_action( 'wp_ajax_falcon_test_smtp', [ $this, 'send_test_email' ] );
	}

	protected $features = [
		'no_admin_email_confirm',
		'no_update_emails',
		'no_new_user_emails',
		'no_password_reset_emails',
		'change_default_email',
		'smtp',
	];

	public function no_admin_email_confirm() {
		add_filter( 'admin_email_check_interval', '__return_false' );
	}

	public function no_update_emails() {
		add_filter( 'send_core_update_notification_email', '__return_false' );
		add_filter( 'auto_plugin_update_send_email', '__return_false' );
		add_filter( 'auto_theme_update_send_email', '__return_false' );
	}

	public function no_new_user_emails() {
		add_filter( 'wp_send_new_user_notification_to_admin', '__return_false' );
	}

	public function no_password_reset_emails() {
		remove_action( 'after_password_reset', 'wp_password_change_notification' );
		add_filter( 'send_password_change_email', '__return_false' );
		add_filter( 'woocommerce_disable_password_change_notification', '__return_false' );
	}

	public function smtp(): void {
		add_action( 'phpmailer_init', [ $this, 'setup_smtp' ] );
	}

	public function setup_smtp( PHPMailer $phpmailer ): void {
		$option = get_option( 'falcon', [] );
		$smtp   = $option['smtp'] ?? [];

		if ( empty( $smtp['host'] ) ) {
			return;
		}

		$phpmailer->isSMTP();
		$phpmailer->Host = $smtp['host']; // phpcs:ignore

		if ( ! empty( $smtp['port'] ) ) {
			$phpmailer->Port = (int) $smtp['port']; // phpcs:ignore
		}

		if ( ! empty( $smtp['username'] ) && ! empty( $smtp['password'] ) ) {
			$phpmailer->SMTPAuth = true; // phpcs:ignore
			$phpmailer->Username = $smtp['username']; // phpcs:ignore
			$phpmailer->Password = $smtp['password']; // phpcs:ignore
		}

		if ( ! empty( $smtp['encryption'] ) ) {
			$phpmailer->SMTPSecure = $smtp['encryption'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS; // phpcs:ignore
		}
	}

	public function send_test_email(): void {
		check_ajax_referer( 'send-email' );
		$result = wp_mail( wp_get_current_user()->user_email, __( 'Test email', 'falcon' ), __( 'This is a test email from Falcon.', 'falcon' ) );
		if ( $result ) {
			wp_send_json_success( __( 'The email is sent successfully!', 'falcon' ) );
		}
		wp_send_json_error( __( 'There is something wrong. Please check your configuration again.', 'falcon' ) );
	}

	public function change_default_email(): void {
		$option        = get_option( 'falcon', [] );
		$default_email = $option['default_email'] ?? [];
		$from_name     = $default_email['from_name'] ?? get_bloginfo( 'name' );
		$from_email    = $default_email['from_email'] ?? get_option( 'admin_email' );

		add_filter( 'wp_mail_from', function () use ( $from_email ) {
			return $from_email;
		} );
		add_filter( 'wp_mail_from_name', function () use ( $from_name ) {
			return $from_name;
		} );
	}
}

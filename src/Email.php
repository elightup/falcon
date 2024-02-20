<?php
namespace Falcon;

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

	public function setup_smtp( $phpmailer ) {
		$option = get_option( 'falcon', [] );
		$smtp   = $option[ 'smtp' ] ?? [];

		if ( empty( $smtp[ 'host' ] ) ) {
			return;
		}

		$phpmailer->isSMTP();
		$phpmailer->Host = $smtp[ 'host' ];

		if ( ! empty( $smtp[ 'port' ] ) ) {
			$phpmailer->Port = (int) $smtp[ 'port' ];
		}

		if ( ! empty( $smtp[ 'username' ] ) && ! empty( $smtp[ 'password' ] ) ) {
			$phpmailer->SMTPAuth = true;
			$phpmailer->Username = $smtp[ 'username' ];
			$phpmailer->Password = $smtp[ 'password' ];
		}

		if ( ! empty( $smtp[ 'encryption' ] ) ) {
			$phpmailer->SMTPSecure = $smtp[ 'encryption' ] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
		}
	}

	public function send_test_email(): void {
		$result = wp_mail( wp_get_current_user()->user_email, __( 'Test email', 'falcon' ), __( 'This is a test email from Falcon.', 'falcon' ) );
		if ( $result ) {
			wp_send_json_success( __( 'The email is sent successfully!', 'falcon' ) );
		}
		wp_send_json_error( __( 'There is something wrong. Please check your configuration again.', 'falcon' ) );
	}
}

<?php
namespace Falcon;

class General extends Base {
	protected $features = [
		'no_admin_email_confirm',
		'no_update_emails',
		'no_new_user_emails',
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
	}
}

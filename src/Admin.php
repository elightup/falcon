<?php
namespace Falcon;

class Admin extends Base {
	protected $features = [
		'login_site_icon',
		'no_update_nags',
		'no_footer_text',
		'no_dashboard_widgets',
		'no_wp_logo',
		'no_application_passwords',
	];

	public function login_site_icon() {
		new Components\Login;
	}

	public function no_update_nags() {
		add_action( 'admin_init', [ $this, 'remove_update_nag' ] );
	}

	public function remove_update_nag() {
		remove_action( 'admin_notices', 'update_nag', 3 );
		remove_action( 'network_admin_notices', 'update_nag', 3 );
	}

	public function no_footer_text() {
		add_filter( 'admin_footer_text', '__return_empty_string' );
		add_filter( 'update_footer', '__return_empty_string', 9999 );
	}

	public function no_dashboard_widgets() {
		add_action( 'wp_dashboard_setup', [ $this, 'remove_widgets' ] );
	}
	public function remove_widgets() {
		remove_action( 'welcome_panel', 'wp_welcome_panel' );

		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );

		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );

		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );

		// Gutenberg.
		remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );

		// Popular plugins.
		remove_meta_box( 'wc_admin_dashboard_setup', 'dashboard', 'normal' ); // WooCommerce.
		remove_meta_box( 'themeisle', 'dashboard', 'normal' ); // WP CloudFlare Super Cache.
		remove_meta_box( 'fluentform_stat_widget', 'dashboard', 'normal' ); // Fluent Form.
		remove_meta_box( 'fluentsmtp_reports_widget', 'dashboard', 'normal' ); // Fluent SMTP.
		remove_meta_box( 'jetpack_summary_widget', 'dashboard', 'normal' ); // Jetpack.
	}

	public function no_wp_logo() {
		add_action( 'admin_bar_menu', [ $this, 'remove_wp_logo' ], 20 );
	}

	public function remove_wp_logo( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
	}

	public function no_application_passwords() {
		add_filter( 'wp_is_application_passwords_available', '__return_false' );
	}
}

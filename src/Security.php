<?php
namespace Falcon;

use WP_Error;

class Security extends Base {
	protected $features = [
		'no_rest_api',
		'no_xmlrpc',
		'no_login_errors',
		'restrict_upload',
	];

	public function no_rest_api() {
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
		remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );

		add_filter( 'rest_authentication_errors', [ $this, 'no_public_rest_api' ] );
	}

	public function no_public_rest_api( $access ) {
		return is_user_logged_in()
			? $access
			: new WP_Error( 'rest_login_required', __( 'REST API restricted to authenticated users.', 'falcon' ), [ 'status' => rest_authorization_required_code() ] );
	}

	public function no_xmlrpc() {
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'pings_open', '__return_false' );
	}

	public function no_login_errors(): string {
		return __( 'There is something wrong. Please try again.', 'falcon' );
	}

	public function restrict_upload() {
		add_filter( 'upload_mimes', [ $this, 'restrict_upload_mimes' ] );
	}

	public function restrict_upload_mimes(): array {
		return [
			'jpg|jpeg' => 'image/jpeg',
			'gif'      => 'image/gif',
			'png'      => 'image/png',
			'mp4'      => 'video/mp4',
			'pdf'      => 'application/pdf',
			'docx'     => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'xlsx'     => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'pptx'     => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		];
	}
}
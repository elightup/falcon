<?php
namespace Falcon;

class General extends Base {
	protected $features = [
		'no_update_emails',
	];

	public function no_update_emails() {
		add_filter( 'auto_core_update_send_email', '__return_false' );
		add_filter( 'auto_plugin_update_send_email', '__return_false' );
		add_filter( 'auto_theme_update_send_email', '__return_false' );
	}
}

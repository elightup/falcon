<?php
namespace Falcon;

class Login {
	public function __construct() {
		add_action( 'login_headerurl', [ $this, 'header_url' ] );
		add_action( 'login_headertext', [ $this, 'header_title' ] );
		add_action( 'login_head', [ $this, 'css' ] );
	}

	public function header_url() {
		return home_url( '/' );
	}

	public function header_title() {
		return get_bloginfo( 'name' );
	}

	public function css() {
		$icon = get_site_icon_url();
		if ( ! $icon ) : ?>
			<style>
			.login h1 a {
				background: none;
				width: auto;
				height: auto;
				font-size: inherit;
				font-weight: 700;
				text-indent: 0;
			}
			</style>
		<?php else : ?>
			<style>
			.login h1 a {
				background-image: url(<?= esc_url( $icon ) ?>);
			}
			</style>
			<?php
		endif;
	}
}

<?php
/**
 * Plugin settings.
 *
 * @package Falcon
 * @author  GretaThemes <info@gretathemes.com>
 * @link    https://gretathemes.com
 */

namespace Falcon;

/**
 * Settings class.
 *
 * @package Falcon
 */
class Settings {
	/**
	 * Add hooks to create settings page and register settings.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Add plugin settings menu.
	 */
	public function add_menu() {
		add_options_page( esc_html__( 'Falcon', 'falcon' ), esc_html__( 'Falcon', 'falcon' ), 'manage_options', 'falcon', [ $this, 'render' ] );
	}

	/**
	 * Render settings page.
	 */
	public function render() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Falcon', 'falcon' ); ?></h1>
			<form method="POST" action="options.php">
				<?php
				settings_fields( 'falcon' );
				do_settings_sections( 'falcon' );
				submit_button( esc_html__( 'Save Changes', 'falcon' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting( 'falcon', 'falcon' );
		add_settings_section( 'async_css', esc_html__( 'Load CSS asynchronously', 'falcon' ), [ $this, 'render_async_css_section' ], 'falcon' );
		add_settings_field( 'async_css_handles', esc_html__( 'Stylesheet handles', 'falcon' ), [ $this, 'render_field' ], 'falcon', 'async_css' );
	}

	public function render_async_css_section() {
		?>
		<p><?php esc_html_e( 'The normal stylesheet loading causes most browsers to delay page rendering while the it loads. When the stylesheets are not critical to the initial rendering of a page, load them asynchronously make the page render faster.', 'falcon' ); ?></p>
		<?php
	}

	/**
	 * Render async CSS handle field.
	 */
	public function render_field() {
		$option  = get_option( 'falcon' );
		$handles = isset( $option['async_css_handles'] ) ? $option['async_css_handles'] : '';
		?>
			<input type="text" class="regular-text" name="falcon[async_css_handles]" value="<?= esc_attr( $handles ); ?>">
			<p class="description"><?= wp_kses_post( sprintf( __( 'Separate multiple handles with commas. To get the CSS handle, view the page source and look for <a href="%s" target="_blank">this</a>.', 'falcon' ), 'http://prnt.sc/cw30dr' ) ) ; ?></p>
		<?php
	}
}
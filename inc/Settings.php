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
		add_settings_section( 'general', '', '', 'falcon' );

		add_settings_field( 'latest_jquery', esc_html__( 'Use latest jQuery version', 'falcon' ), [ $this, 'render_checkbox_field' ], 'falcon', 'general', [
			'field' => 'latest_jquery',
		] );

		add_settings_field( 'query_string_handles', esc_html__( 'Keep query string for static resources', 'falcon' ), [ $this, 'render_text_field' ], 'falcon', 'general', [
			'field'       => 'query_string_handles',
			'description' => sprintf( __( 'By default, static resources have the query string removed to make them <a href="%s" target="_blank">cached better</a>.<br>If you want to keep query string for some resources to force users to receive the fresh content, enter their handles here (separated by commas).<br>To get the resource handle, view the page source and look for <a href="%s" target="_blank">this</a>.', 'falcon' ), 'https://gtmetrix.com/remove-query-strings-from-static-resources.html', 'https://i.imgur.com/PerP5U2.png' ),
		] );

		add_settings_field( 'async_css_handles', esc_html__( 'Stylesheet handles', 'falcon' ), [ $this, 'render_text_field' ], 'falcon', 'general', [
			'field'       => 'async_css_handles',
			'description' => sprintf( __( 'The normal stylesheet loading causes most browsers to delay page rendering while the it loads.<br>When the stylesheets are not critical to the initial rendering of a page, load them asynchronously make the page render faster.<br>Separate multiple handles with commas. To get the CSS handle, view the page source and look for <a href="%s" target="_blank">this</a>.', 'falcon' ), 'https://i.imgur.com/PerP5U2.png' ),
		] );
	}

	/**
	 * Render a text field.
	 *
	 * @param array $args Field parameters.
	 */
	public function render_text_field( $args ) {
		$option = get_option( 'falcon' );
		$value  = isset( $option[ $args['field'] ] ) ? $option[ $args['field'] ] : '';
		?>
		<input type="text" class="regular-text" name="falcon[<?= esc_attr( $args['field'] ); ?>]" value="<?= esc_attr( $value ); ?>">
		<p class="description"><?= wp_kses_post( $args['description'] ); ?></p>
		<?php
	}

	/**
	 * Render a checkbox field.
	 *
	 * @param array $args Field parameters.
	 */
	public function render_checkbox_field( $args ) {
		$option = get_option( 'falcon' );
		$value  = isset( $option[ $args['field'] ] ) ? $option[ $args['field'] ] : '';
		?>
		<input type="checkbox" name="falcon[<?= esc_attr( $args['field'] ); ?>]" value="1" <?php checked( $value ); ?>>
		<?php
	}
}

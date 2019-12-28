<?php
namespace Falcon;

class Settings {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
	}

	public function add_menu() {
		$page_hook = add_options_page( esc_html__( 'Falcon', 'falcon' ), esc_html__( 'Falcon', 'falcon' ), 'manage_options', 'falcon', [ $this, 'render' ] );
		add_action( "load-{$page_hook}", [ $this, 'save' ] );
	}

	public function render() {
		?>
		<div class="wrap">
			<h1><?= esc_html( get_admin_page_title() ) ?></h1>
			<form method="POST" action="">
				<?php wp_nonce_field( 'save' ) ?>
				<p><?php esc_html_e( 'Select the features you want the plugin to do to clean up your website and optimize for a better performance.', 'slim-seo' ); ?></p>

				<h3><?php esc_html_e( 'General', 'falcon' ) ?></h3>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_heartbeats"<?php checked( self::is_feature_active( 'no_heartbeats' ) ) ?>>
						<?php esc_html_e( 'Disable heartbeats', 'falcon' ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_emojis"<?php checked( self::is_feature_active( 'no_emojis' ) ) ?>>
						<?php esc_html_e( 'Disable emojis', 'falcon' ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_embeds"<?php checked( self::is_feature_active( 'no_embeds' ) ) ?>>
						<?php esc_html_e( 'Prevent others from embedding your site and vise-versa.', 'falcon' ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_self_pings"<?php checked( self::is_feature_active( 'no_self_pings' ) ) ?>>
						<?php esc_html_e( 'Disable self pings', 'falcon' ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_query_string"<?php checked( self::is_feature_active( 'no_query_string' ) ) ?>>
						<?php esc_html_e( 'Remove query string for JavaScript and CSS files', 'falcon' ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="schema_less_urls"<?php checked( self::is_feature_active( 'schema_less_urls' ) ) ?>>
						<?= wp_kses_post( __( 'Set scheme-less URLs for JavaScript and CSS files, e.g. remove <code>http:</code> and <code>https:</code> from URLs', 'falcon' ) ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_recent_comments_widget_style"<?php checked( self::is_feature_active( 'no_recent_comments_widget_style' ) ) ?>>
						<?= wp_kses_post( __( 'Removes styles for recent comments widget', 'falcon' ) ) ?>
					</label>
				</p>

				<h3><?php esc_html_e( 'Header Cleanup', 'falcon' ) ?></h3>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_feed_links"<?php checked( self::is_feature_active( 'no_feed_links' ) ) ?>>
						<?= wp_kses_post( __( 'Remove feed links', 'falcon' ) ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_rsd_link"<?php checked( self::is_feature_active( 'no_rsd_link' ) ) ?>>
						<?= wp_kses_post( __( 'Remove RSD link', 'falcon' ) ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_wlwmanifest_link"<?php checked( self::is_feature_active( 'no_wlwmanifest_link' ) ) ?>>
						<?= wp_kses_post( __( 'Remove wlwmanifest link', 'falcon' ) ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_adjacent_posts_links"<?php checked( self::is_feature_active( 'no_adjacent_posts_links' ) ) ?>>
						<?= wp_kses_post( __( 'Remove adjacent posts links', 'falcon' ) ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_wp_generator"<?php checked( self::is_feature_active( 'no_wp_generator' ) ) ?>>
						<?= wp_kses_post( __( 'Remove WordPress version number', 'falcon' ) ) ?>
					</label>
				</p>
				<p>
					<label>
						<input type="checkbox" name="falcon[features][]" value="no_shortlink"<?php checked( self::is_feature_active( 'no_shortlink' ) ) ?>>
						<?= wp_kses_post( __( 'Remove shortlink', 'falcon' ) ) ?>
					</label>
				</p>
				<?php submit_button( esc_html__( 'Save Changes', 'falcon' ) ); ?>
			</form>
		</div>
		<?php
	}

	public function save() {
		if ( empty( $_POST['submit'] ) || ! check_ajax_referer( 'save', false, false ) ) {
			return;
		}

		$data = isset( $_POST['falcon'] ) ? $_POST['falcon'] : [];
		update_option( 'falcon', $data );
	}

	public static function is_feature_active( $feature ) {
		$data = get_option( 'falcon', null );
		return null === $data ? true : in_array( $feature, $data['features'], true );
	}
}

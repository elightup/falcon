<?php
namespace Falcon;

class Settings {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
	}

	public function add_menu() {
		$page = add_options_page(
			__( 'Falcon', 'falcon' ),
			__( 'Falcon', 'falcon' ),
			'manage_options',
			'falcon',
			[ $this, 'render' ]
		);
		add_action( "load-$page", [ $this, 'save' ] );
		add_action( "admin_print_styles-$page", [ $this, 'enqueue' ] );
	}

	public function render() {
		?>
		<div class="wrap">
			<h1><?= esc_html( get_admin_page_title() ) ?></h1>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<form method="POST" action="" id="post-body-content">
						<?php wp_nonce_field( 'save' ) ?>

						<nav class="nav-tab-wrapper">
							<a href="#tab-general" class="nav-tab nav-tab-active"><?php esc_html_e( 'General', 'falcon' ) ?></a></li>
							<a href="#tab-header" class="nav-tab"><?php esc_html_e( 'Header', 'falcon' ) ?></a></li>
							<a href="#tab-assets" class="nav-tab"><?php esc_html_e( 'Assets', 'falcon' ) ?></a></li>
							<a href="#tab-admin" class="nav-tab"><?php esc_html_e( 'Admin', 'falcon' ) ?></a></li>
						</nav>
						<div class="tab-pane" id="tab-general">
							<?php
							$this->checkbox( 'no_gutenberg', __( 'Disable Gutenberg (the block editor)', 'falcon' ) );
							$this->checkbox( 'no_rest_api', __( 'Disable REST API for unauthenticated requests', 'falcon' ) );
							$this->checkbox( 'no_heartbeat', __( 'Disable heartbeat', 'falcon' ) );
							$this->checkbox( 'no_xmlrpc', __( 'Disable XML-RPC', 'falcon' ) );
							$this->checkbox( 'no_emojis', __( 'Disable emojis', 'falcon' ) );
							$this->checkbox( 'no_embeds', __( 'Disable embeds, e.g. prevent others from embedding your site and vise-versa', 'falcon' ) );
							$this->checkbox( 'no_revisions', __( 'Disable revisions', 'falcon' ) );
							$this->checkbox( 'no_self_pings', __( 'Disable self pings', 'falcon' ) );
							$this->checkbox( 'no_privacy', __( 'Disable privacy tools', 'falcon' ) );
							?>
						</div>
						<div class="tab-pane hidden" id="tab-header">
							<?php
							$this->checkbox( 'no_feed_links', __( 'Remove feed links', 'falcon' ) );
							$this->checkbox( 'no_rsd_link', __( 'Remove RSD link', 'falcon' ) );
							$this->checkbox( 'no_wlwmanifest_link', __( 'Remove wlwmanifest link', 'falcon' ) );
							$this->checkbox( 'no_adjacent_posts_links', __( 'Remove adjacent posts links', 'falcon' ) );
							$this->checkbox( 'no_wp_generator', __( 'Remove WordPress version number', 'falcon' ) );
							$this->checkbox( 'no_shortlink', __( 'Remove shortlink', 'falcon' ) );
							$this->checkbox( 'no_rest_link', __( 'Remove REST API link', 'falcon' ) );
							?>
						</div>
						<div class="tab-pane hidden" id="tab-assets">
							<?php
							$this->checkbox( 'no_query_string', __( 'Remove query string for JavaScript and CSS files', 'falcon' ) );
							$this->checkbox( 'no_jquery_migrate', __( 'Remove jQuery Migrate', 'falcon' ) );
							$this->checkbox( 'schema_less_urls', __( 'Set scheme-less URLs for JavaScript and CSS files, e.g. remove <code>http:</code> and <code>https:</code> from URLs', 'falcon' ) );
							$this->checkbox( 'no_recent_comments_widget_style', __( 'Remove styles for recent comments widget', 'falcon' ) );
							$this->checkbox( 'cleanup_menu', __( 'Cleanup nav menu item ID & classes', 'falcon' ) );
							?>
						</div>
						<div class="tab-pane hidden" id="tab-admin">
							<?php
							$this->checkbox( 'login_site_icon', __( 'Show site icon on login page', 'falcon' ) );
							$this->checkbox( 'no_update_nags', __( 'Remove update nags', 'falcon' ) );
							$this->checkbox( 'no_footer_text', __( 'Remove footer text', 'falcon' ) );
							$this->checkbox( 'no_dashboard_widgets', __( 'Remove default dashboard widgets', 'falcon' ) );
							$this->checkbox( 'no_wp_logo', __( 'Remove WordPress logo in the admin bar', 'falcon' ) );
							$this->checkbox( 'no_admin_email_confirm', __( 'Remove admin email confirmation', 'falcon' ) );
							$this->checkbox( 'no_application_passwords', __( 'Remove application passwords', 'falcon' ) );
							?>
						</div>

						<?php submit_button( esc_html__( 'Save Changes', 'falcon' ) ); ?>
					</form>
					<div id="postbox-container-1" class="postbox-container">
						<div class="postbox">
							<h3 class="hndle">
								<span><?php esc_html_e( 'Write a review for Falcon', 'falcon' ) ?></span>
							</h3>
							<div class="inside">
								<p><?php esc_html_e( 'If you like Falcon, please write a review on WordPress.org to help us spread the word. We really appreciate that!', 'falcon' ) ?></p>
								<p><a href="https://wordpress.org/support/plugin/falcon/reviews/?filter=5" class="button" target="_blank" rel="noopenner noreferrer"><?php esc_html_e( 'Write a review', 'falcon' ) ?></a></p>
							</div>
						</div>
						<div class="postbox">
							<h3 class="hndle">
								<span><?php esc_html_e( 'Our WordPress Plugins', 'falcon' ) ?></span>
							</h3>
							<div class="inside">
								<p><?php esc_html_e( 'Like this plugin? Check out our other WordPress plugins:', 'falcon' ) ?></p>
								<p><a href="https://wordpress.org/plugins/meta-box/" target="_blank"><strong>Meta Box</strong></a> - <?php esc_html_e( 'The most powerful WordPress plugin for creating custom post types and custom fields.', 'falcon' ) ?></p>
								<p><a href="https://wordpress.org/plugins/slim-seo/" target="_blank"><strong>Slim SEO</strong></a> - <?php esc_html_e( 'A fast, lightweight and full-featured SEO plugin for WordPress with minimal configuration.', 'falcon' ) ?></p>
								<p><a href="https://wpslimseo.com/slim-seo-schema/" target="_blank"><strong>Slim SEO Schema</strong></a> - <?php esc_html_e( 'The best plugin to add schemas (structured data, rich snippets) to WordPress.', 'falcon' ) ?></p>
								<p><a href="https://gretathemes.com" target="_blank"><strong>GretaThemes</strong></a> - <?php esc_html_e( 'Free and premium WordPress themes that clean, simple and just work.', 'falcon' ) ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function enqueue() {
		wp_enqueue_style( 'falcon-settings', FALCON_URL . 'assets/settings.css', [], filemtime( FALCON_DIR . '/assets/settings.css' ) );
		wp_enqueue_script( 'falcon-settings', FALCON_URL . 'assets/settings.js', [], filemtime( FALCON_DIR . '/assets/settings.js' ), true );
	}

	public function save() {
		if ( empty( $_POST['submit'] ) || ! check_ajax_referer( 'save', false, false ) ) {
			return;
		}

		$data = $_POST['falcon'] ?? [];
		update_option( 'falcon', $data );

		add_settings_error( null, 'falcon', __( 'Settings updated.', 'falcon' ), 'success' );
	}

	public static function is_feature_active( string $name ) : bool {
		$data = get_option( 'falcon', null );
		return null === $data ? true : in_array( $name, $data['features'], true );
	}

	private function checkbox( $name, $label ) {
		?>
		<p>
			<label class="switch">
				<input type="checkbox" name="falcon[features][]" value="<?= esc_attr( $name ) ?>"<?php checked( self::is_feature_active( $name ) ) ?>>
				<span class="switch-icon"></span>
				<?= wp_kses_post( $label ) ?>
			</label>
		</p>
		<?php
	}
}

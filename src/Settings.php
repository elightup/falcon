<?php
namespace Falcon;

class Settings {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
		add_action( 'wp_ajax_falcon_save_settings', [ $this, 'save' ] );
		add_action( 'admin_init', [ $this, 'export' ] );
		add_action( 'wp_ajax_falcon_import_settings', [ $this, 'import' ] );
	}

	public function add_menu() {
		$page = add_options_page(
			__( 'Falcon', 'falcon' ),
			__( 'Falcon', 'falcon' ),
			'manage_options',
			'falcon',
			[ $this, 'render' ]
		);
		add_action( "admin_print_styles-$page", [ $this, 'enqueue' ] );
	}

	public function render() {
		?>
		<form method="POST" id="settings-form" class="e-page">
			<div class="e-header">
				<div class="e-branding">
					<svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"><circle cx="32" cy="32" fill="#4f5d73" r="32"/><g fill="#e0e0d1"><path d="M47.7 40.6c.2-.4.6-.6 1-.7s.9-.1 1.4.1l2.5 1.2c.5.2.8.6.9 1.1.2.5.1 1.1-.2 1.6s-.8.9-1.2 1c-.5.1-1 0-1.4-.3L48.4 43c-.4-.3-.7-.7-.8-1.1-.1-.5-.1-.9.1-1.3zM16.4 40.8c.5.7.1 1.8-.7 2.4l-2.3 1.6c-.9.6-2 .3-2.7-.7-.6-1-.2-2.3.7-2.7l2.5-1.2c1.1-.4 2.1-.1 2.5.6zM14 31.8c0 .9-.7 1.6-1.8 1.7l-2.8.3c-1 .1-2-.8-2-2s.9-2.1 1.9-2l2.8.2c1.1.2 1.9.9 1.9 1.8zM16.3 22.7c-.2.4-.6.6-1 .7s-.9.1-1.4-.1l-2.5-1.2c-.5-.2-.8-.6-.9-1.1-.2-.5-.1-1.1.2-1.6s.8-.9 1.2-1c.5-.1 1 0 1.4.3l2.3 1.6c.4.3.7.7.8 1.1.2.5.2 1-.1 1.3zM22.9 16.1c-.7.5-1.8.1-2.4-.7l-1.6-2.3c-.6-.9-.3-2 .7-2.7 1-.6 2.3-.2 2.7.7l1.2 2.5c.4 1 .1 2-.6 2.5zM31.9 13.6c-.9 0-1.6-.7-1.7-1.8L29.9 9c-.1-1 .8-2 2-2s2.1.9 2 1.9l-.2 2.8c-.2 1.1-.9 1.9-1.8 1.9zM41 16c-.4-.2-.6-.6-.7-1s-.1-.9.1-1.4l1.2-2.5c.2-.5.6-.8 1.1-.9.5-.2 1.1-.1 1.6.2s.9.8 1 1.2c.1.5 0 1-.3 1.4l-1.6 2.3c-.3.4-.7.7-1.1.8-.5.1-1 .1-1.3-.1zM47.6 22.5c-.5-.7-.1-1.8.7-2.4l2.3-1.6c.9-.6 2-.3 2.7.7.6 1 .2 2.3-.7 2.7L50 23.1c-.9.5-2 .2-2.4-.6zM50.1 31.5c0-.9.7-1.6 1.8-1.7l2.8-.3c1-.1 2 .8 2 2s-.9 2.1-1.9 2l-2.8-.2c-1.1-.1-1.9-.9-1.9-1.8z"/></g><path d="M41.6 24.4c-.4-.4-1.1-.5-1.5-.2l-4.7 2.9-3.1 1.9H32c-2.8 0-5 2.2-5 5v.3c.2 2.5 2.1 4.5 4.6 4.6h.3c2.8 0 5-2.2 5-5v-.3l1.9-3.1 2.9-4.7c.4-.4.3-1-.1-1.4z" fill="#231f20" opacity=".2"/><path d="M41.6 22.4c-.4-.4-1.1-.5-1.5-.2l-4.7 2.9-3.1 1.9H32c-2.8 0-5 2.2-5 5v.3c.2 2.5 2.1 4.5 4.6 4.6h.3c2.8 0 5-2.2 5-5v-.3l1.9-3.1 2.9-4.7c.4-.4.3-1-.1-1.4z" fill="#c75c5c"/><circle cx="32" cy="32" fill="#fff" r="5"/><path d="M40 49c0 1.7-1.3 3-3 3H27c-1.7 0-3-1.3-3-3v-2c0-1.7 1.3-3 3-3h10c1.7 0 3 1.3 3 3z" fill="#f5cf87"/></svg>
					<h1><?= esc_html( get_admin_page_title() ); ?></h1>
				</div>

				<nav class="e-tabs">
					<?php
					$tabs = apply_filters( 'falcon_settings_tabs', [
						'general'  => __( 'General', 'falcon' ),
						'header'   => __( 'Header', 'falcon' ),
						'media'    => __( 'Media', 'falcon' ),
						'email'    => __( 'Email', 'falcon' ),
						'admin'    => __( 'Admin', 'falcon' ),
						'security' => __( 'Security', 'falcon' ),
					] );

					foreach ( $tabs as $key => $label ) {
						// Translators: %1$s - tab name, %2$s - tab label.
						printf( '<a href="#%1$s" data-tab="%1$s" class="e-tab">%2$s</a>', esc_attr( $key ), esc_html( $label ) );
					}
					?>
				</nav>

				<div class="submit">
					<div class="message hidden"></div>
					<input type="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes', 'falcon' ) ?>">
				</div>
			</div>
			<div class="e-body wrap">
				<div class="wp-header-end"></div>

				<div class="e-wrapper">
					<div class="e-content e-box">
						<?php
						$tab_panes = apply_filters( 'falcon_settings_tab_panes', $this->get_tab_panes() );
						echo implode( '', $tab_panes ); // phpcs:ignore
						?>
					</div>

					<div class="e-sidebar">
						<div class="e-widget e-box">
							<h2 class="e-widget_title"><?php esc_html_e( 'Import & export', 'falcon' ) ?></h2>
							<p><?php esc_html_e( 'Export your current plugin settings and import them on another site to quickly apply the same configuration.', 'falcon' ) ?></p>
							<div class="e-widget_actions">
								<?php $url = wp_nonce_url( add_query_arg( 'action', 'falcon-export' ), 'export' ); ?>
								<a href="<?= esc_url( $url ) ?>" class="button"><?php esc_html_e( 'Export', 'falcon' ) ?></a>
								<label for="import" class="button">
									<?php esc_html_e( 'Import', 'falcon' ) ?>
									<input type="file" id="import" accept=".json">
								</label>
							</div>
						</div>
						<div class="e-widget e-box">
							<h2 class="e-widget_title"><?php esc_html_e( 'Share & feedback', 'falcon' ) ?></h2>
							<?php // Translators: %1$s - URL to the review page, %2$s - URL to the feedback page ?>
							<p><?= wp_kses_post( sprintf( __( 'If you like Falcon, please share it with your friends or <a href="%1$s" target="_blank">write a review</a> to help us spread the word. We also want to hear <a href="%2$s" target="_blank">your feedback</a> to improve the plugin.', 'falcon' ), 'https://wordpress.org/support/plugin/falcon/reviews/?filter=5', 'https://elu.to/fse' ) ); ?></p>
						</div>
						<div class="e-widget e-box">
							<h2 class="e-widget_title"><?php esc_html_e( 'Our WordPress Plugins', 'falcon' ) ?></h2>
							<p><?php esc_html_e( 'Like this plugin? Check out our other WordPress plugins:', 'falcon' ) ?></p>
							<p><a href="https://elu.to/fsm" target="_blank"><strong>Meta Box</strong></a> - <?php esc_html_e( 'A powerful WordPress plugin for creating custom post types and custom fields.', 'falcon' ) ?></p>
							<p><a href="https://elu.to/fss" target="_blank"><strong>Slim SEO</strong></a> - <?php esc_html_e( 'A fast, lightweight and full-featured SEO plugin for WordPress with minimal configuration.', 'falcon' ) ?></p>
						</div>
					</div>
				</div>
			</div>
		</form>
		<?php
	}

	public function enqueue() {
		wp_enqueue_style( 'falcon', FALCON_URL . 'assets/settings.css', [], filemtime( FALCON_DIR . '/assets/settings.css' ) );
		wp_enqueue_script( 'falcon', FALCON_URL . 'assets/settings.js', [], filemtime( FALCON_DIR . '/assets/settings.js' ), true );
		wp_localize_script( 'falcon', 'Falcon', [
			'nonce'        => wp_create_nonce( 'save' ),
			'nonce_email'  => wp_create_nonce( 'send-email' ),
			'nonce_cache'  => wp_create_nonce( 'clear-cache' ),
			'nonce_import' => wp_create_nonce( 'import' ),
			'saving'       => __( 'Saving...', 'falcon' ),
			'save'         => __( 'Save Changes', 'falcon' ),
		] );
	}

	private function get_tab_panes(): array {
		$tab_panes = [];
		$names     = [
			'general',
			'header',
			'media',
			'email',
			'admin',
			'security',
		];
		foreach ( $names as $name ) {
			$tab_panes[ $name ] = $this->get_tab_pane( $name );
		}
		return $tab_panes;
	}

	public function get_tab_pane( string $name ): string {
		ob_start();
		printf( '<div class="e-tabPane" data-tab="%s">', esc_attr( $name ) );
		include FALCON_DIR . "/views/settings/tabs/$name.php";
		do_action( "falcon_settings_tab_$name", $this );
		echo '</div>';
		return ob_get_clean();
	}

	public function save(): void {
		check_ajax_referer( 'save' );

		// phpcs:ignore
		$data = ! empty( $_POST['falcon'] ) ? wp_unslash( $_POST['falcon'] ) : [];
		$data = $this->sanitize_data( $data );

		update_option( 'falcon', $data );

		do_action( 'falcon_settings_save', $data );

		wp_send_json_success( __( 'Settings updated.', 'falcon' ) );
	}

	public static function is_feature_active( string $name ): bool {
		$data = get_option( 'falcon', null );

		$default_disabled = [
			'no_gutenberg',
			'no_cron',
			'no_external_requests',
			'no_comments',
			'no_thumbnails',
			'search_posts_only',
			'block_ai_bots',
			'maintenance_mode',
			'force_login',
			'smtp',
			'cache',
		];

		return null === $data ? ! in_array( $name, $default_disabled, true ) : in_array( $name, $data['features'] ?? [], true );
	}

	public function checkbox( string $name, string $label, string $description = '' ): void {
		?>
		<div class="featureBox">
			<label class="featureBox_switch">
				<input class="featureBox_input" type="checkbox" name="falcon[features][]" value="<?= esc_attr( $name ); ?>"<?php checked( self::is_feature_active( $name ) ) ?>>
				<span class="featureBox_icon"></span>
			</label>
			<div class="featureBox_body">
				<div class="featureBox_title"><?= wp_kses_post( $label ); ?></div>
				<div class="featureBox_description"><?= wp_kses_post( $description ); ?></div>
			</div>
		</div>
		<?php
	}

	public function export(): void {
		if ( ! isset( $_GET['action'] ) || 'falcon-export' !== $_GET['action'] ) {
			return;
		}

		check_ajax_referer( 'export' );

		$data = get_option( 'falcon', [] );
		$data = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename=falcon-settings-' . gmdate( 'Y-m-d' ) . '.json' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );

		echo $data; // phpcs:ignore
		exit;
	}

	public function import(): void {
		check_ajax_referer( 'import' );

		// phpcs:ignore
		if ( empty( $_FILES['file'] ) || $_FILES['file']['error'] !== UPLOAD_ERR_OK ) {
			wp_send_json_error( __( 'Error uploading file.', 'falcon' ) );
		}

		// phpcs:ignore
		$file = $_FILES['file'];
		$file_content = file_get_contents( $file['tmp_name'] ); // phpcs:ignore

		if ( false === $file_content ) {
			wp_send_json_error( __( 'Error reading file.', 'falcon' ) );
		}

		$data = json_decode( $file_content, true );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_send_json_error( __( 'Invalid JSON file.', 'falcon' ) );
		}

		$data = $this->sanitize_data( $data );
		update_option( 'falcon', $data );

		do_action( 'falcon_settings_save', $data );

		wp_send_json_success( __( 'Settings imported successfully.', 'falcon' ) );
	}

	private function sanitize_data( array $data ): array {
		$data['features']      = isset( $data['features'] ) && is_array( $data['features'] ) ? array_map( 'sanitize_text_field', $data['features'] ) : [];
		$data['lazy_load_css'] = isset( $data['lazy_load_css'] ) ? sanitize_textarea_field( $data['lazy_load_css'] ) : '';
		$data['smtp']          = isset( $data['smtp'] ) && is_array( $data['smtp'] ) ? array_map( 'sanitize_text_field', $data['smtp'] ) : [];
		$data['default_email'] = isset( $data['default_email'] ) && is_array( $data['default_email'] ) ? array_map( 'sanitize_text_field', $data['default_email'] ) : [];
		return array_filter( $data );
	}
}

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
		$option        = get_option( 'falcon', [] );
		$smtp          = $option['smtp'] ?? [];
		$default_email = $option['default_email'] ?? [];
		?>
		<form method="POST" action="" class="e-page">
			<div class="e-header">
				<div class="e-branding">
					<svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"><circle cx="32" cy="32" fill="#4f5d73" r="32"/><g fill="#e0e0d1"><path d="M47.7 40.6c.2-.4.6-.6 1-.7s.9-.1 1.4.1l2.5 1.2c.5.2.8.6.9 1.1.2.5.1 1.1-.2 1.6s-.8.9-1.2 1c-.5.1-1 0-1.4-.3L48.4 43c-.4-.3-.7-.7-.8-1.1-.1-.5-.1-.9.1-1.3zM16.4 40.8c.5.7.1 1.8-.7 2.4l-2.3 1.6c-.9.6-2 .3-2.7-.7-.6-1-.2-2.3.7-2.7l2.5-1.2c1.1-.4 2.1-.1 2.5.6zM14 31.8c0 .9-.7 1.6-1.8 1.7l-2.8.3c-1 .1-2-.8-2-2s.9-2.1 1.9-2l2.8.2c1.1.2 1.9.9 1.9 1.8zM16.3 22.7c-.2.4-.6.6-1 .7s-.9.1-1.4-.1l-2.5-1.2c-.5-.2-.8-.6-.9-1.1-.2-.5-.1-1.1.2-1.6s.8-.9 1.2-1c.5-.1 1 0 1.4.3l2.3 1.6c.4.3.7.7.8 1.1.2.5.2 1-.1 1.3zM22.9 16.1c-.7.5-1.8.1-2.4-.7l-1.6-2.3c-.6-.9-.3-2 .7-2.7 1-.6 2.3-.2 2.7.7l1.2 2.5c.4 1 .1 2-.6 2.5zM31.9 13.6c-.9 0-1.6-.7-1.7-1.8L29.9 9c-.1-1 .8-2 2-2s2.1.9 2 1.9l-.2 2.8c-.2 1.1-.9 1.9-1.8 1.9zM41 16c-.4-.2-.6-.6-.7-1s-.1-.9.1-1.4l1.2-2.5c.2-.5.6-.8 1.1-.9.5-.2 1.1-.1 1.6.2s.9.8 1 1.2c.1.5 0 1-.3 1.4l-1.6 2.3c-.3.4-.7.7-1.1.8-.5.1-1 .1-1.3-.1zM47.6 22.5c-.5-.7-.1-1.8.7-2.4l2.3-1.6c.9-.6 2-.3 2.7.7.6 1 .2 2.3-.7 2.7L50 23.1c-.9.5-2 .2-2.4-.6zM50.1 31.5c0-.9.7-1.6 1.8-1.7l2.8-.3c1-.1 2 .8 2 2s-.9 2.1-1.9 2l-2.8-.2c-1.1-.1-1.9-.9-1.9-1.8z"/></g><path d="M41.6 24.4c-.4-.4-1.1-.5-1.5-.2l-4.7 2.9-3.1 1.9H32c-2.8 0-5 2.2-5 5v.3c.2 2.5 2.1 4.5 4.6 4.6h.3c2.8 0 5-2.2 5-5v-.3l1.9-3.1 2.9-4.7c.4-.4.3-1-.1-1.4z" fill="#231f20" opacity=".2"/><path d="M41.6 22.4c-.4-.4-1.1-.5-1.5-.2l-4.7 2.9-3.1 1.9H32c-2.8 0-5 2.2-5 5v.3c.2 2.5 2.1 4.5 4.6 4.6h.3c2.8 0 5-2.2 5-5v-.3l1.9-3.1 2.9-4.7c.4-.4.3-1-.1-1.4z" fill="#c75c5c"/><circle cx="32" cy="32" fill="#fff" r="5"/><path d="M40 49c0 1.7-1.3 3-3 3H27c-1.7 0-3-1.3-3-3v-2c0-1.7 1.3-3 3-3h10c1.7 0 3 1.3 3 3z" fill="#f5cf87"/></svg>
					<h1><?= esc_html( get_admin_page_title() ) ?></h1>
				</div>

				<div class="e-tabs">
					<span data-tab="general" class="e-tab e-tab-active"><?php esc_html_e( 'General', 'falcon' ) ?></span>
					<span data-tab="header" class="e-tab"><?php esc_html_e( 'Header', 'falcon' ) ?></span>
					<span data-tab="media" class="e-tab"><?php esc_html_e( 'Media', 'falcon' ) ?></span>
					<span data-tab="email" class="e-tab"><?php esc_html_e( 'Email', 'falcon' ) ?></span>
					<span data-tab="admin" class="e-tab"><?php esc_html_e( 'Admin', 'falcon' ) ?></span>
					<span data-tab="security" class="e-tab"><?php esc_html_e( 'Security', 'falcon' ) ?></span>
				</div>

				<?php submit_button( esc_html__( 'Save Changes', 'falcon' ) ); ?>
			</div>
			<div class="e-body wrap">
				<div class="wp-header-end"></div>

				<div class="e-wrapper">
					<div class="e-content e-box">
						<?php wp_nonce_field( 'save' ) ?>

						<div class="e-tabPane" data-tab="general">
							<?php
							// Translators: %s - Link to the help docs.
							$this->checkbox( 'no_gutenberg', __( 'Disable Gutenberg (the block editor)', 'falcon' ), sprintf( __( 'Disable the block editor for all post types and use classic editor only. <a href="%s" target="_blank">Learn more</a>.', 'falcon' ), 'https://metabox.io/disable-gutenberg-without-using-plugins/' ) );
							$this->checkbox( 'no_heartbeat', __( 'Disable heartbeat', 'falcon' ), __( 'Reduce the CPU load on the server by disabling the WordPress heartbeat API.', 'falcon' ) );
							$this->checkbox( 'no_embeds', __( 'Disable embeds', 'falcon' ), __( 'Prevent other websites from embedding your site and vise-versa.', 'falcon' ) );
							$this->checkbox( 'no_comments', __( 'Disable comments', 'falcon' ), __( 'Disable comments for all post types. Existing comments will also be hidden on the frontend. And there will be no UI in the admin.', 'falcon' ) );
							$this->checkbox( 'no_comment_url', __( 'Remove website field from comment form', 'falcon' ), __( 'Prevent people from spamming your website with their website URL.', 'falcon' ) );
							$this->checkbox( 'no_revisions', __( 'Disable revisions', 'falcon' ), __( 'Reduce your database bloat by not storing revisions of posts.', 'falcon' ) );
							$this->checkbox( 'no_self_pings', __( 'Disable self pings', 'falcon' ), __( 'Do not send trackbacks and pingbacks to your website when publishing new posts with internal links.', 'falcon' ) );
							$this->checkbox( 'no_privacy', __( 'Disable privacy tools', 'falcon' ), __( 'Remove the privacy tools from the admin.', 'falcon' ) );
							$this->checkbox( 'no_auto_updates', __( 'Disable auto updates', 'falcon' ), __( 'Do not let WordPress auto update. You have to update manually.', 'falcon' ) );
							$this->checkbox( 'no_cron', __( 'Disable cron', 'falcon' ), __( 'Disable scheduled tasks. If you need to run cron jobs, You need to run from the server via a command line, or with an external service.', 'falcon' ) );
							$this->checkbox( 'no_external_requests', __( 'Block external requests', 'falcon' ), __( 'Do not allow to connect to other websites. This will increase the performance, but also prevent the auto updates, license checking, or similar tasks that require remote connections.', 'falcon' ) );
							$this->checkbox( 'search_posts_only', __( 'Search only posts', 'falcon' ), __( 'Do not search other post types when users perform a search.', 'falcon' ) );
							$this->checkbox( 'no_texturize', __( 'Disable texturize', 'falcon' ), __( 'Do not allow WordPress to auto replace some characters with their formatted forms like quotes, dashes, ellipses, etc.', 'falcon' ) );
							$this->checkbox( 'maintenance_mode', __( 'Enable maintenance mode', 'falcon' ), __( 'Put your website under the maintenance mode. This option will display a maintenance message to non-admin users when viewing the website on the front end.', 'falcon' ) );
							?>
						</div>
						<div class="e-tabPane hidden" data-tab="header">
							<?php
							$this->checkbox( 'no_feed_links', __( 'Remove feed links', 'falcon' ), __( 'Remove all RSS and Atom feed URLs from the website\'s head. This includes feeds for posts, categories, tags, comments, authors and search.', 'falcon' ) );
							$this->checkbox( 'no_rsd_link', __( 'Remove RSD link', 'falcon' ), __( 'Remove the RDF URL from the website\'s head.', 'falcon' ) );
							$this->checkbox( 'no_wlwmanifest_link', __( 'Remove wlwmanifest link', 'falcon' ), __( 'Remove the wlwmanifest URL from the website\'s head.', 'falcon' ) );
							$this->checkbox( 'no_adjacent_posts_links', __( 'Remove adjacent posts links', 'falcon' ), __( 'Remove the next and previous post(s) URLs from the website\'s head.', 'falcon' ) );
							$this->checkbox( 'no_wp_generator', __( 'Remove WordPress version number', 'falcon' ), __( 'Remove the WordPress version number from the website\'s head. This helps improving the website security by not exposing the current WordPress version to hackers.', 'falcon' ) );
							$this->checkbox( 'no_shortlink', __( 'Remove shortlink', 'falcon' ), __( 'Remove the short link, e.g. "?p=123" from the website\'s head.', 'falcon' ) );
							$this->checkbox( 'no_rest_link', __( 'Remove REST API link', 'falcon' ), __( 'Remove the REST API URL from the website\'s head.', 'falcon' ) );
							?>
						</div>
						<div class="e-tabPane hidden" data-tab="media">
							<?php
							$this->checkbox( 'no_query_string', __( 'Remove query string for JavaScript and CSS files', 'falcon' ), __( 'Remove "?ver=xxx" and other query string from JavaScript and CSS files. This will make browsers cache these files better.', 'falcon' ) );
							$this->checkbox( 'no_jquery_migrate', __( 'Remove jQuery Migrate', 'falcon' ), __( 'Remove the old jQuery Migrate from both admin and frontend.', 'falcon' ) );
							$this->checkbox( 'schema_less_urls', __( 'Set scheme-less URLs for JavaScript and CSS files', 'falcon' ), __( 'Remove <code>http:</code> and <code>https:</code> from JavaScript and CSS file URLs to make the URL shorter.', 'falcon' ) );
							$this->checkbox( 'no_recent_comments_widget_style', __( 'Remove styles of the recent comments widget', 'falcon' ), __( 'If you are using classic widgets, this feature will remove inline styles of the recent comments widget, outputted by WordPress.', 'falcon' ) );
							$this->checkbox( 'cleanup_menu', __( 'Cleanup nav menu item IDs & classes', 'falcon' ), __( 'Remove IDs and all CSS classes for menu items, except "menu-item", "current-menu-item" and "current-page-item". If your website requires a specific menu item class, turn off this feature.', 'falcon' ) );
							$this->checkbox( 'no_emojis', __( 'Disable emojis', 'falcon' ), __( 'Remove WordPress scripts to auto convert HTML entities to emojis. This won\'t affect the emoji characters that you use in your content.', 'falcon' ) );
							$this->checkbox( 'no_image_threshold', __( 'Disable scaling down big images', 'falcon' ), __( 'Do not let WordPress auto created a scale down version of big images. This feature will save your disk storage.', 'falcon' ) );
							$this->checkbox( 'no_exif_rotate', __( 'Disable automatic image rotation based on EXIF data', 'falcon' ), __( 'Do not let WordPress auto rotate your images based on EXIF data. Your images will always be as they are.', 'falcon' ) );
							$this->checkbox( 'no_thumbnails', __( 'Disable thumbnail generation', 'falcon' ), __( 'Disable generating images with all image sizes.', 'falcon' ) );
							?>
							<fieldset>
								<label for="lazy-load-css"><?php esc_html_e( 'Asynchronous load CSS', 'falcon' ) ?></label>
								<p class="description"><?php esc_html_e( 'Improve your website performance by not letting the CSS files to block your pages rendering.', 'falcon' ) ?></p>
								<textarea id="lazy-load-css" class="large-text code" rows="10" name="falcon[lazy_load_css]"><?= esc_textarea( $option['lazy_load_css'] ?? '' ) ?></textarea>
								<p class="description"><?php esc_html_e( 'Enter CSS handles or keywords of CSS files that you want to load asynchronously, one per line. This feature should be used only for unimportant CSS.', 'falcon' ) ?></p>
							</fieldset>
						</div>
						<div class="e-tabPane hidden" data-tab="admin">
							<?php
							$this->checkbox( 'login_site_icon', __( 'Show site icon on the login page', 'falcon' ), __( 'Use your website site icon as the logo on the login page.', 'falcon' ) );
							$this->checkbox( 'no_update_nags', __( 'Remove update nags', 'falcon' ), __( 'Do not show update messages in the admin.', 'falcon' ) );
							$this->checkbox( 'no_footer_text', __( 'Remove footer text', 'falcon' ), __( 'Remove the footer text in the admin.', 'falcon' ) );
							$this->checkbox( 'no_dashboard_widgets', __( 'Remove default dashboard widgets', 'falcon' ), __( 'Remove all default dashboard widgets to keep your dashboard clean', 'falcon' ) );
							$this->checkbox( 'no_wp_logo', __( 'Remove WordPress logo in the admin bar', 'falcon' ), __( 'Do not show the WordPress logo in the admin bar', 'falcon' ) );
							$this->checkbox( 'no_application_passwords', __( 'Remove application passwords', 'falcon' ), __( 'Disable the application passwords feature if you don\'t integrate your WordPress website with any external service.', 'falcon' ) );
							?>
						</div>
						<div class="e-tabPane hidden" data-tab="email">
							<?php
							$this->checkbox( 'no_admin_email_confirm', __( 'Remove admin email confirmation', 'falcon' ), __( 'Do not ask whether the admin email is correct.', 'falcon' ) );
							$this->checkbox( 'no_update_emails', __( 'Disable auto update email notification', 'falcon' ), __( 'Do not send emails when there are any updates on your website.', 'falcon' ) );
							$this->checkbox( 'no_new_user_emails', __( 'Disable admin email notification when a new user is registered', 'falcon' ), __( 'Do not send emails to admins when there are new users registered on your website.', 'falcon' ) );
							$this->checkbox( 'no_password_reset_emails', __( 'Disable email notification when users reset passwords', 'falcon' ), __( 'Do not send emails when users reset their passwords on your website.', 'falcon' ) );
							?>
							<div class="featureBox">
								<label class="featureBox_switch">
									<input class="featureBox_input" type="checkbox" name="falcon[features][]" value="change_default_email"<?php checked( self::is_feature_active( 'change_default_email' ) ) ?>>
									<span class="featureBox_icon"></span>
								</label>
								<div class="featureBox_body">
									<div class="featureBox_title"><?php esc_html_e( 'Change default email', 'falcon' ) ?></div>
									<?php // Translators: %s - Link to the help docs ?>
									<div class="featureBox_description"><?= wp_kses_post( sprintf( __( 'Change WordPress default email from name and address. <a href="%s">Learn more</a>.', 'falcon' ), 'https://deluxeblogtips.com/change-wordpress-default-email/' ) ) ?></div>
									<div class="featureBox_more">
										<div class="formControls">
											<label for="falcon[default_email][from_name]"><?php esc_html_e( 'From name', 'falcon' ) ?></label>
											<input type="text" class="regular-text" name="falcon[default_email][from_name]" id="falcon[default_email][from_name]" value="<?= esc_attr( $default_email['from_name'] ?? get_bloginfo( 'name' ) ) ?>">
											<label for="falcon[default_email][from_email]"><?php esc_html_e( 'From email', 'falcon' ) ?></label>
											<input type="text" class="regular-text" name="falcon[default_email][from_email]" id="falcon[default_email][from_email]" value="<?= esc_attr( $default_email['from_email'] ?? get_option( 'admin_email' ) ) ?>">
										</div>
									</div>
								</div>
							</div>
							<div class="featureBox">
								<label class="featureBox_switch">
									<input class="featureBox_input" type="checkbox" name="falcon[features][]" value="smtp"<?php checked( self::is_feature_active( 'smtp' ) ) ?>>
									<span class="featureBox_icon"></span>
								</label>
								<div class="featureBox_body">
									<div class="featureBox_title"><?php esc_html_e( 'SMTP', 'falcon' ) ?></div>
									<div class="featureBox_description"><?php esc_html_e( 'Send emails via a SMTP service.', 'falcon' ) ?></div>
									<div class="featureBox_more">
										<div class="formControls">
											<label for="falcon[smtp][host]"><?php esc_html_e( 'Host', 'falcon' ) ?></label>
											<input type="text" class="regular-text" name="falcon[smtp][host]" id="falcon[smtp][host]" value="<?= esc_attr( $smtp['host'] ?? '' ) ?>">
											<label for="falcon[smtp][port]"><?php esc_html_e( 'Port', 'falcon' ) ?></label>
											<input type="text" class="regular-text" name="falcon[smtp][port]" id="falcon[smtp][port]" value="<?= esc_attr( $smtp['port'] ?? '' ) ?>">
											<label for="falcon[smtp][username]"><?php esc_html_e( 'Username', 'falcon' ) ?></label>
											<input type="text" class="regular-text" name="falcon[smtp][username]" id="falcon[smtp][username]" value="<?= esc_attr( $smtp['username'] ?? '' ) ?>">
											<label for="falcon[smtp][password]"><?php esc_html_e( 'Password', 'falcon' ) ?></label>
											<input type="password" name="falcon[smtp][password]" id="falcon[smtp][password]" value="<?= esc_attr( $smtp['password'] ?? '' ) ?>">
											<label><?php esc_html_e( 'Encryption', 'falcon' ) ?></label>
											<div>
												<label><input type="radio" name="falcon[smtp][encryption]" value=""<?php checked( $smtp['encryption'] ?? '', '' ) ?>> <?php esc_html_e( 'None', 'falcon' ) ?></label>
												<label><input type="radio" name="falcon[smtp][encryption]" value="ssl"<?php checked( $smtp['encryption'] ?? '', 'ssl' ) ?>> <?php esc_html_e( 'SSL', 'falcon' ) ?></label>
												<label><input type="radio" name="falcon[smtp][encryption]" value="tls"<?php checked( $smtp['encryption'] ?? '', 'tls' ) ?>> <?php esc_html_e( 'TLS', 'falcon' ) ?></label>
											</div>
										</div>
										<br>
										<button class="button" type="button" id="smtp-test"><?php esc_html_e( 'Send test email', 'falcon' ) ?></button>
										<p class="description"><?php esc_html_e( 'This action will send a test email to your account email.', 'falcon' ) ?>
									</div>
								</div>
							</div>
						</div>
						<div class="e-tabPane hidden" data-tab="security">
							<?php
							$this->checkbox( 'no_rest_api', __( 'Disable REST API for unauthenticated requests', 'falcon' ), __( 'Improve your website security by disabling REST API access for non-authenticated users.', 'falcon' ) );
							// Translators: %s - Link to the help docs.
							$this->checkbox( 'no_xmlrpc', __( 'Disable XML-RPC', 'falcon' ), sprintf( __( 'Protect your site from brute force, DOS and DDOS attacks via XML-RPC. Also disables trackbacks, pingbacks, and brakes the mobile apps. <a href="%s" target="_blank">Learn more</a>.', 'falcon' ), 'https://deluxeblogtips.com/disable-xml-rpc-wordpress/' ) );
							$this->checkbox( 'restrict_upload', __( 'Restrict upload file types', 'falcon' ), __( 'Allow users to upload only common file types, including: images (jpg, jpeg, png, gif), office files (docx, xlsx, pptx), PDF, and videos (mp4).', 'falcon' ) );
							$this->checkbox( 'no_login_errors', __( 'Disable detailed login errors', 'falcon' ), __( 'Show a general error message when the login is incorrect, not specifically whether the username or password is incorrect.', 'falcon' ) );
							$this->checkbox( 'block_ai_bots', __( 'Block AI bots', 'falcon' ), __( 'Stop AI bots from crawling/stealing your website content, which also affects the website performance.', 'falcon' ) );
							$this->checkbox( 'force_login', __( 'Force login', 'falcon' ), __( 'Force users to login to view the website.', 'falcon' ) );
							?>
						</div>
					</div>

					<div class="e-sidebar">
						<div class="e-widget e-box">
							<h2 class="e-widget_title"><?php esc_html_e( 'Write a review for Falcon', 'falcon' ) ?></h2>
							<p><?php esc_html_e( 'If you like Falcon, please write a review on WordPress.org to help us spread the word. We really appreciate that!', 'falcon' ) ?></p>
							<p><a href="https://wordpress.org/support/plugin/falcon/reviews/?filter=5" class="button" target="_blank" rel="noopenner noreferrer"><?php esc_html_e( 'Write a review', 'falcon' ) ?></a></p>
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
	}

	public function save() {
		if ( empty( $_POST['submit'] ) || ! check_ajax_referer( 'save', false, false ) ) {
			return;
		}

		// phpcs:ignore
		$data = ! empty( $_POST['falcon'] ) ? wp_unslash( $_POST['falcon'] ) : [];

		$data['features']      = isset( $data['features'] ) && is_array( $data['features'] ) ? array_map( 'sanitize_text_field', $data['features'] ) : [];
		$data['lazy_load_css'] = isset( $data['lazy_load_css'] ) ? sanitize_textarea_field( $data['lazy_load_css'] ) : '';
		$data['smtp']          = isset( $data['smtp'] ) && is_array( $data['smtp'] ) ? array_map( 'sanitize_text_field', $data['smtp'] ) : [];
		$data['default_email'] = isset( $data['default_email'] ) && is_array( $data['default_email'] ) ? array_map( 'sanitize_text_field', $data['default_email'] ) : [];
		$data                  = array_filter( $data );

		update_option( 'falcon', $data );

		add_settings_error( null, 'falcon', __( 'Settings updated.', 'falcon' ), 'success' );
	}

	public static function is_feature_active( string $name ): bool {
		$data = get_option( 'falcon', null );

		$default_disabled = [
			'no_cron',
			'no_external_requests',
			'no_comments',
			'no_thumbnails',
			'block_ai_bots',
			'maintenance_mode',
			'force_login',
		];

		return null === $data ? ! in_array( $name, $default_disabled, true ) : in_array( $name, $data['features'] ?? [], true );
	}

	private function checkbox( string $name, string $label, string $description = '' ): void {
		?>
		<div class="featureBox">
			<label class="featureBox_switch">
				<input class="featureBox_input" type="checkbox" name="falcon[features][]" value="<?= esc_attr( $name ) ?>"<?php checked( self::is_feature_active( $name ) ) ?>>
				<span class="featureBox_icon"></span>
			</label>
			<div class="featureBox_body">
				<div class="featureBox_title"><?= wp_kses_post( $label ) ?></div>
				<div class="featureBox_description"><?= wp_kses_post( $description ) ?></div>
			</div>
		</div>
		<?php
	}
}

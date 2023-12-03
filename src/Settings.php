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
		$option = get_option( 'falcon', [] );
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
							<a href="#tab-email" class="nav-tab"><?php esc_html_e( 'Email', 'falcon' ) ?></a></li>
						</nav>
						<div class="tab-pane" id="tab-general">
							<?php
							$this->checkbox( 'no_gutenberg', __( 'Disable Gutenberg (the block editor)', 'falcon' ), __( 'Disable the block editor for all post types and use classic editor only.', 'falcon' ) );
							$this->checkbox( 'no_rest_api', __( 'Disable REST API for unauthenticated requests', 'falcon' ), __( 'Improve your website security by disabling REST API access for non-authenticated users.', 'falcon' ) );
							$this->checkbox( 'no_heartbeat', __( 'Disable heartbeat', 'falcon' ), __( 'Reduce the CPU load on the server by disabling the WordPress heartbeat API.', 'falcon' ) );
							$this->checkbox( 'no_xmlrpc', __( 'Disable XML-RPC', 'falcon' ), sprintf( __( 'Protect your site from brute force, DOS and DDOS attacks via XML-RPC. Also disables trackbacks and pingbacks. <a href="%s">Learn more</a>.', 'falcon' ), 'https://deluxeblogtips.com/disable-xml-rpc-wordpress/' ) );
							$this->checkbox( 'no_embeds', __( 'Disable embeds', 'falcon' ), __( 'Prevent other websites from embedding your site and vise-versa', 'falcon' ) );
							$this->checkbox( 'no_comments', __( 'Disable comments', 'falcon' ), __( 'Disable comments for all post types. Existing comments will also be hidden on the frontend. And there will be no UI in the admin.', 'falcon' ) );
							$this->checkbox( 'no_comment_url', __( 'Remove website field from comment form', 'falcon' ), __( 'Prevent people from spamming your website with their website URL.', 'falcon' ) );
							$this->checkbox( 'no_revisions', __( 'Disable revisions', 'falcon' ), __( 'Reduce your database bloat by not storing revisions of posts.', 'falcon' ) );
							$this->checkbox( 'no_self_pings', __( 'Disable self pings', 'falcon' ), __( 'Do not send trackbacks and pingbacks to your website when publishing new posts with internal links.', 'falcon' ) );
							$this->checkbox( 'no_privacy', __( 'Disable privacy tools', 'falcon' ), __( 'Remove the privacy tools from the admin.', 'falcon' ) );
							$this->checkbox( 'no_auto_updates', __( 'Disable auto updates', 'falcon' ), __( 'Do not let WordPress auto update. You have to update manually.', 'falcon' ) );
							$this->checkbox( 'no_cron', __( 'Disable cron', 'falcon' ), __( 'Disable scheduled tasks. If you need to run cron jobs, You need to run from the server via a command line, or with an external service.', 'falcon' ) );
							$this->checkbox( 'no_external_requests', __( 'Block external requests', 'falcon' ), __( 'Do not allow to connect to other websites. This will increase the performance, but also prevent the auto updates, license checking, or similar tasks that require remote connections.', 'falcon' ) );
							$this->checkbox( 'search_posts_only', __( 'Search only posts', 'falcon' ), __( 'Do not search other post types when user perform a search.', 'falcon' ) );
							$this->checkbox( 'no_texturize', __( 'Disable texturize', 'falcon' ), __( 'Do not allow WordPress to auto replace some characters with their formatted forms like quotes, dashes, ellipses, etc.', 'falcon' ) );
							?>
						</div>
						<div class="tab-pane hidden" id="tab-header">
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
						<div class="tab-pane hidden" id="tab-assets">
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
						<div class="tab-pane hidden" id="tab-admin">
							<?php
							$this->checkbox( 'login_site_icon', __( 'Show site icon on the login page', 'falcon' ), __( 'Use your website site icon as the logo on the login page.', 'falcon' ) );
							$this->checkbox( 'no_update_nags', __( 'Remove update nags', 'falcon' ), __( 'Do not show update messages in the admin.', 'falcon' ) );
							$this->checkbox( 'no_footer_text', __( 'Remove footer text', 'falcon' ), __( 'Remove the footer text in the admin.', 'falcon' ) );
							$this->checkbox( 'no_dashboard_widgets', __( 'Remove default dashboard widgets', 'falcon' ), __( 'Remove all default dashboard widgets to keep your dashboard clean', 'falcon' ) );
							$this->checkbox( 'no_wp_logo', __( 'Remove WordPress logo in the admin bar', 'falcon' ), __( 'Do not show the WordPress logo in the admin bar', 'falcon' ) );
							$this->checkbox( 'no_application_passwords', __( 'Remove application passwords', 'falcon' ), __( 'Disable the application passwords feature if you don\'t integrate your WordPress website with any external service.', 'falcon' ) );
							?>
						</div>
						<div class="tab-pane hidden" id="tab-email">
							<?php
							$this->checkbox( 'no_admin_email_confirm', __( 'Remove admin email confirmation', 'falcon' ), __( 'Do not ask whether the admin email is correct.', 'falcon' ) );
							$this->checkbox( 'no_update_emails', __( 'Disable auto update email notification', 'falcon' ), __( 'Do not send emails when there are any updates on your website.', 'falcon' ) );
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
								<p><a href="https://elu.to/fsm" target="_blank"><strong>Meta Box</strong></a> - <?php esc_html_e( 'The most powerful WordPress plugin for creating custom post types and custom fields.', 'falcon' ) ?></p>
								<p><a href="https://elu.to/fsss" target="_blank"><strong>Slim SEO Schema</strong></a> - <?php esc_html_e( 'The best plugin to add schemas (structured data, rich snippets) to WordPress.', 'falcon' ) ?></p>
								<p><a href="https://elu.to/fssl" target="_blank"><strong>Slim SEO Link Manager</strong></a> - <?php esc_html_e( 'Build internal link easier in WordPress with real-time reports.', 'falcon' ) ?></p>
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

	public static function is_feature_active( string $name ): bool {
		$data = get_option( 'falcon', null );

		$default_disabled = [
			'no_cron',
			'no_external_requests',
			'no_comments',
			'no_thumbnails',
		];

		return null === $data ? ! in_array( $name, $default_disabled, true ) : in_array( $name, $data['features'], true );
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

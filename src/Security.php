<?php
namespace Falcon;

use WP_Error;

class Security extends Base {
	protected $features = [
		'no_rest_api',
		'no_xmlrpc',
		'no_login_errors',
		'restrict_upload',
		'block_ai_bots',
		'force_login',
		'comment_spam_protection',
	];

	public function no_rest_api(): void {
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

	public function no_xmlrpc(): void {
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'xmlrpc_methods', '__return_empty_array' );
		add_filter( 'pings_open', '__return_false' );
	}

	public function no_login_errors(): void {
		add_filter( 'login_errors', [ $this, 'custom_login_errors' ] );
	}

	public function custom_login_errors( $error ): string {
		return __( 'There is something wrong. Please try again.', 'falcon' );
	}

	public function restrict_upload(): void {
		add_filter( 'upload_mimes', [ $this, 'restrict_upload_mimes' ] );
	}

	public function restrict_upload_mimes(): array {
		return [
			'jpg|jpeg' => 'image/jpeg',
			'gif'      => 'image/gif',
			'png'      => 'image/png',
			'webp'     => 'image/webp',
			'mp4'      => 'video/mp4',
			'pdf'      => 'application/pdf',
			'docx'     => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'xlsx'     => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'pptx'     => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		];
	}

	public function block_ai_bots(): void {
		add_action( 'do_robotstxt', [ $this, 'block_ai_bots_in_robots_txt' ] );
	}

	/**
	 * Block AI bots via robots.txt
	 *
	 * @link https://neil-clarke.com/block-the-bots-that-feed-ai-models-by-scraping-your-website/
	 */
	public function block_ai_bots_in_robots_txt(): void {
		$user_agents = [
			'CCBot',
			'ChatGPT-User',
			'GPTBot',
			'Google-Extended',
			'anthropic-ai',
			'Omgilibot',
			'Omgili',
			'FacebookBot',
			'Bytespider',
		];
		$content     = [];
		foreach ( $user_agents as $user_agent ) {
			$content[] = "User-agent: $user_agent";
			$content[] = 'Disallow: /';
			$content[] = '';
		}

		echo "\n", implode( "\n", $content ), "\n"; // phpcs:ignore
	}

	public function force_login(): void {
		add_action( 'template_redirect', [ $this, 'redirect_non_logged_in_users' ] );
	}

	/**
	 * Simple technique to prevent spam comments. Credit to Maarten Belmans.
	 * @link https://x.com/PoeHaH/status/2043964375959048388
	 */
	public function comment_spam_protection(): void {
		add_filter( 'pre_comment_approved', [ $this, 'check_comment_spam' ], 10, 2 );
		add_action( 'comment_form_after', [ $this, 'add_comment_spam_protection_script' ] );
	}

	public function check_comment_spam( $approved ) {
		if ( ! is_admin() && ( empty( $_POST['comment-check'] ) || $_POST['comment-check'] !== 'ok' ) ) {
			return new WP_Error( 'comment_lang', __( 'Your comment is detected as spam.', 'falcon' ), 429 );
		}

		return $approved;
	}

	public function add_comment_spam_protection_script(): void {
		?>
		<script>
		{
			let form = document.querySelector( '#commentform' );
			if ( form ) {
				setTimeout( function() {
					let input = document.createElement( 'input' );
					input.type = 'hidden';
					input.name = 'comment-check';
					input.value = 'ok';
					form.appendChild( input );
				}, 2000 );
			}
		}
		</script>
		<?php
	}

	public function redirect_non_logged_in_users(): void {
		if ( is_user_logged_in() ) {
			return;
		}

		nocache_headers();

		wp_safe_redirect( wp_login_url(), 302 );
		die;
	}
}

<?php
namespace Falcon\Components;

class DisableComments {
	public function __construct() {
		// Set comment status.
		add_filter( 'comments_open', '__return_false' );
		add_filter( 'pings_open', '__return_false' );

		// Remove comments from feed.
		add_filter( 'post_comments_feed_link', '__return_false' );
		add_filter( 'comments_link_feed', '__return_false' );
		add_filter( 'feed_links_show_comments_feed', '__return_false' );
		add_action( 'template_redirect', [ $this, 'disable_feed_access' ], 9 ); // Before redirect_canonical.

		// Do not return anything.
		add_filter( 'comment_link', '__return_false' );
		add_filter( 'get_comments_number', '__return_zero' );
		add_filter( 'comments_array', '__return_empty_array' );

		// Remove from post types.
		add_action( 'init', [ $this, 'remove_post_types_support' ], 9999 );

		// No widgets.
		add_action( 'widgets_init', [ $this, 'remove_widget' ] );

		// No admin menu.
		add_action( 'admin_menu', [ $this, 'remove_admin_menus' ] );

		// No admin bar.
		add_action( 'admin_init', [ $this, 'remove_admin_bar_items' ] );
		add_action( 'template_redirect', [ $this, 'remove_admin_bar_items' ] );

		// Hide admin elements.
		add_action( 'admin_print_styles-index.php', [ $this, 'hide_elements_with_css' ] );
		add_action( 'admin_print_styles-profile.php', [ $this, 'hide_elements_with_css' ] );
	}

	public function disable_feed_access(): void {
		if ( is_comment_feed() ) {
			die;
		}
	}

	public function remove_widget(): void {
		unregister_widget( 'WP_Widget_Recent_Comments' );
	}

	public function remove_admin_menus(): void {
		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	}

	public function remove_admin_bar_items(): void {
		remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
	}

	public function remove_post_types_support(): void {
		$post_types = get_post_types();
		foreach ( $post_types as $post_type ) {
			remove_post_type_support( $post_type, 'comments' );
			remove_post_type_support( $post_type, 'trackbacks' );
		}
	}

	public function hide_elements_with_css(): void {
		echo '<style>
			#dashboard_right_now .comment-count,
			#dashboard_right_now .comment-mod-count,
			#latest-comments,
			#welcome-panel .welcome-comments,
			.user-comment-shortcuts-wrap {
				display: none !important;
			}
		</style>';
	}
}

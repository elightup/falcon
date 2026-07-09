<?php
namespace Falcon\Components;

class Cleanup {
	private static ?array $counts = null;

	public function __construct() {
		add_action( 'wp_ajax_falcon_run_cleanup', [ $this, 'run' ] );
		add_action( 'wp_ajax_falcon_cleanup_counts', [ $this, 'get_counts_ajax' ] );
	}

	public function run(): void {
		check_ajax_referer( 'cleanup' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action.', 'falcon' ) );
		}

		$items = ! empty( $_POST['items'] ) ? (array) wp_unslash( $_POST['items'] ) : [];
		$items = array_map( 'sanitize_text_field', $items );

		if ( empty( $items ) ) {
			wp_send_json_error( __( 'Please select at least one item to clean.', 'falcon' ) );
		}

		$results = [];
		$actions = [
			'optimize_tables' => __( 'Optimized', 'falcon' ),
		];
		$nouns   = [
			'optimize_tables' => __( 'database tables', 'falcon' ),
		];
		foreach ( $items as $item ) {
			$method = str_replace( '-', '_', $item );
			if ( ! method_exists( __CLASS__, $method ) ) {
				continue;
			}
			$cleaned = self::$method();
			if ( $cleaned <= 0 ) {
				continue;
			}
			$verb = $actions[ $method ] ?? __( 'Cleaned', 'falcon' );
			$noun = $nouns[ $method ] ?? strtolower( self::get_labels()[ $item ] ?? $item );
			$results[] = sprintf(
				/* translators: %1$s - action verb, %2$d - number of items, %3$s - item type */
				__( '%1$s %2$d %3$s.', 'falcon' ),
				$verb,
				$cleaned,
				$noun
			);
		}

		if ( empty( $results ) ) {
			wp_send_json_success( [
				'message' => __( 'Nothing to clean. Your database is already clean!', 'falcon' ),
				'counts'  => self::get_counts(),
			] );
		}

		self::$counts = null;
		wp_send_json_success( [
			'message' => implode( '<br>', $results ),
			'counts'  => self::get_counts(),
		] );
	}

	public function get_counts_ajax(): void {
		check_ajax_referer( 'cleanup' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action.', 'falcon' ) );
		}

		self::$counts = null;
		wp_send_json_success( self::get_counts() );
	}

	public static function get_labels(): array {
		return [
			'revisions'             => __( 'Revisions', 'falcon' ),
			'auto_drafts'           => __( 'Auto drafts', 'falcon' ),
			'trashed_posts'         => __( 'Trashed posts', 'falcon' ),
			'spam_comments'         => __( 'Spam comments', 'falcon' ),
			'trashed_comments'      => __( 'Trashed comments', 'falcon' ),
			'expired_transients'    => __( 'Expired transients', 'falcon' ),
			'orphaned_post_meta'    => __( 'Orphaned post meta', 'falcon' ),
			'orphaned_comment_meta' => __( 'Orphaned comment meta', 'falcon' ),
			'orphaned_user_meta'    => __( 'Orphaned user meta', 'falcon' ),
			'orphaned_term_meta'    => __( 'Orphaned term meta', 'falcon' ),
			'orphaned_post_terms'   => __( 'Orphaned post terms', 'falcon' ),
			'unused_terms'          => __( 'Unused terms', 'falcon' ),
			'optimize_tables'       => __( 'Optimize database tables', 'falcon' ),
		];
	}

	public static function get_counts(): array {
		global $wpdb;

		if ( self::$counts !== null ) {
			return self::$counts;
		}

		return self::$counts = [
			'revisions'             => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'" ),
			'auto_drafts'           => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'auto-draft'" ),
			'trashed_posts'         => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'trash'" ),
			'spam_comments'         => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam'" ),
			'trashed_comments'      => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'trash'" ),
			'expired_transients'    => self::count_expired_transients(),
			'orphaned_post_meta'    => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts p ON p.ID = pm.post_id WHERE p.ID IS NULL" ),
			'orphaned_comment_meta' => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->commentmeta cm LEFT JOIN $wpdb->comments c ON c.comment_ID = cm.comment_id WHERE c.comment_ID IS NULL" ),
			'orphaned_user_meta'    => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->usermeta um LEFT JOIN $wpdb->users u ON u.ID = um.user_id WHERE u.ID IS NULL" ),
			'orphaned_term_meta'    => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->termmeta tm LEFT JOIN $wpdb->terms t ON t.term_id = tm.term_id WHERE t.term_id IS NULL" ),
			'orphaned_post_terms'   => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->term_relationships tr LEFT JOIN $wpdb->posts p ON p.ID = tr.object_id WHERE p.ID IS NULL" ),
			'unused_terms'          => (int) $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->terms t LEFT JOIN $wpdb->term_taxonomy tt ON tt.term_id = t.term_id WHERE tt.term_id IS NULL OR tt.count = 0" ),
			'optimize_tables'       => 0,
		];
	}

	private static function count_expired_transients(): int {
		global $wpdb;
		$count = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->options WHERE (option_name LIKE %s OR option_name LIKE %s) AND option_value < %d",
				$wpdb->esc_like( '_transient_timeout_' ) . '%',
				$wpdb->esc_like( '_site_transient_timeout_' ) . '%',
				time()
			)
		);
		return $count;
	}

	private static function revisions(): int {
		global $wpdb;
		$count = self::count( 'revisions' );
		if ( $count <= 0 ) {
			return 0;
		}
		$wpdb->delete( $wpdb->posts, [ 'post_type' => 'revision' ] );
		return $count;
	}

	private static function auto_drafts(): int {
		global $wpdb;
		$count = self::count( 'auto_drafts' );
		if ( $count <= 0 ) {
			return 0;
		}
		$wpdb->delete( $wpdb->posts, [ 'post_status' => 'auto-draft' ] );
		return $count;
	}

	private static function trashed_posts(): int {
		global $wpdb;
		$ids = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_status = 'trash'" );
		foreach ( $ids as $id ) {
			wp_delete_post( (int) $id, true );
		}
		return count( $ids );
	}

	private static function spam_comments(): int {
		global $wpdb;
		$ids = $wpdb->get_col( "SELECT comment_ID FROM $wpdb->comments WHERE comment_approved = 'spam'" );
		foreach ( $ids as $id ) {
			wp_delete_comment( (int) $id, true );
		}
		return count( $ids );
	}

	private static function trashed_comments(): int {
		global $wpdb;
		$ids = $wpdb->get_col( "SELECT comment_ID FROM $wpdb->comments WHERE comment_approved = 'trash'" );
		foreach ( $ids as $id ) {
			wp_delete_comment( (int) $id, true );
		}
		return count( $ids );
	}

	private static function expired_transients(): int {
		global $wpdb;
		$count = self::count( 'expired_transients' );
		if ( $count <= 0 ) {
			return 0;
		}
		$time = time();
		$wpdb->query(
			$wpdb->prepare(
				"DELETE a, b FROM $wpdb->options a, $wpdb->options b
				WHERE a.option_name LIKE %s
				AND a.option_name NOT LIKE %s
				AND b.option_name = CONCAT( '_transient_timeout_', SUBSTRING( a.option_name, 12 ) )
				AND b.option_value < %d",
				'_transient_%',
				'_transient_timeout_%',
				$time
			)
		);
		$wpdb->query(
			$wpdb->prepare(
				"DELETE a, b FROM $wpdb->options a, $wpdb->options b
				WHERE a.option_name LIKE %s
				AND a.option_name NOT LIKE %s
				AND b.option_name = CONCAT( '_site_transient_timeout_', SUBSTRING( a.option_name, 17 ) )
				AND b.option_value < %d",
				'_site_transient_%',
				'_site_transient_timeout_%',
				$time
			)
		);
		return $count;
	}

	private static function orphaned_post_meta(): int {
		global $wpdb;
		$count = self::count( 'orphaned_post_meta' );
		if ( $count <= 0 ) {
			return 0;
		}
		$wpdb->query( "DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts p ON p.ID = pm.post_id WHERE p.ID IS NULL" );
		return $count;
	}

	private static function orphaned_comment_meta(): int {
		global $wpdb;
		$count = self::count( 'orphaned_comment_meta' );
		if ( $count <= 0 ) {
			return 0;
		}
		$wpdb->query( "DELETE cm FROM $wpdb->commentmeta cm LEFT JOIN $wpdb->comments c ON c.comment_ID = cm.comment_id WHERE c.comment_ID IS NULL" );
		return $count;
	}

	private static function orphaned_user_meta(): int {
		global $wpdb;
		$count = self::count( 'orphaned_user_meta' );
		if ( $count <= 0 ) {
			return 0;
		}
		$wpdb->query( "DELETE um FROM $wpdb->usermeta um LEFT JOIN $wpdb->users u ON u.ID = um.user_id WHERE u.ID IS NULL" );
		return $count;
	}

	private static function orphaned_term_meta(): int {
		global $wpdb;
		$count = self::count( 'orphaned_term_meta' );
		if ( $count <= 0 ) {
			return 0;
		}
		$wpdb->query( "DELETE tm FROM $wpdb->termmeta tm LEFT JOIN $wpdb->terms t ON t.term_id = tm.term_id WHERE t.term_id IS NULL" );
		return $count;
	}

	private static function orphaned_post_terms(): int {
		global $wpdb;
		$count = self::count( 'orphaned_post_terms' );
		if ( $count <= 0 ) {
			return 0;
		}
		$wpdb->query( "DELETE tr FROM $wpdb->term_relationships tr LEFT JOIN $wpdb->posts p ON p.ID = tr.object_id WHERE p.ID IS NULL" );
		return $count;
	}

	private static function unused_terms(): int {
		global $wpdb;
		$count = self::count( 'unused_terms' );
		if ( $count <= 0 ) {
			return 0;
		}
		$term_ids = $wpdb->get_col( "SELECT t.term_id FROM $wpdb->terms t WHERE t.term_id NOT IN (SELECT term_id FROM $wpdb->term_taxonomy WHERE count > 0)" );
		if ( empty( $term_ids ) ) {
			return 0;
		}
		$ids = implode( ',', array_map( 'intval', $term_ids ) );
		$wpdb->query( "DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id IN (SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE term_id IN ($ids))" );
		$wpdb->query( "DELETE FROM $wpdb->term_taxonomy WHERE term_id IN ($ids)" );
		$wpdb->query( "DELETE FROM $wpdb->terms WHERE term_id IN ($ids)" );
		return $count;
	}

	private static function optimize_tables(): int {
		global $wpdb;
		$tables = $wpdb->get_col( "SHOW TABLES LIKE '{$wpdb->prefix}%'" );
		foreach ( $tables as $table ) {
			$wpdb->query( "OPTIMIZE TABLE $table" ); // phpcs:ignore
		}
		return count( $tables );
	}

	private static function count( string $item ): int {
		$counts = self::get_counts();
		return $counts[ $item ] ?? 0;
	}
}

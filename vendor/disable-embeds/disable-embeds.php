<?php
/**
 * Plugin Name: Disable Embeds
 * Description: Don't like the enhanced embeds in WordPress 4.4? Easily disable the feature using this plugin.
 * Version:     1.4.0
 * Author:      Pascal Birchler
 * Author URI:  https://pascalbirchler.com
 * License:     GPLv2+
 *
 * @package disable-embeds
 */

/**
 * Disable embeds on init.
 *
 * - Removes the needed query vars.
 * - Disables oEmbed discovery.
 * - Completely removes the related JavaScript.
 * - Disables the core-embed/wordpress block type (WordPress 5.0+)
 *
 * @since 1.0.0
 */
function disable_embeds_init() {
	/* @var WP $wp */
	global $wp;

	// Remove the embed query var.
	$wp->public_query_vars = array_diff( $wp->public_query_vars, array(
		'embed',
	) );

	// Remove the oembed/1.0/embed REST route.
	add_filter( 'rest_endpoints', 'disable_embeds_remove_embed_endpoint' );

	// Disable handling of internal embeds in oembed/1.0/proxy REST route.
	add_filter( 'oembed_response_data', 'disable_embeds_filter_oembed_response_data' );

	// Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );

	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );

	// Remove all embeds rewrite rules.
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

	// Remove filter of the oEmbed result before any HTTP requests are made.
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );

	// Load block editor JavaScript.
	add_action( 'enqueue_block_editor_assets', 'disable_embeds_enqueue_block_editor_assets' );

	// Remove wp-embed dependency of wp-edit-post script handle.
	add_action( 'wp_default_scripts', 'disable_embeds_remove_script_dependencies' );
}

add_action( 'init', 'disable_embeds_init', 9999 );

/**
 * Removes the 'wpembed' TinyMCE plugin.
 *
 * @since 1.0.0
 *
 * @param array $plugins List of TinyMCE plugins.
 * @return array The modified list.
 */
function disable_embeds_tiny_mce_plugin( $plugins ) {
	return array_diff( $plugins, array( 'wpembed' ) );
}

/**
 * Remove all rewrite rules related to embeds.
 *
 * @since 1.2.0
 *
 * @param array $rules WordPress rewrite rules.
 * @return array Rewrite rules without embeds rules.
 */
function disable_embeds_rewrites( $rules ) {
	foreach ( $rules as $rule => $rewrite ) {
		if ( false !== strpos( $rewrite, 'embed=true' ) ) {
			unset( $rules[ $rule ] );
		}
	}

	return $rules;
}

/**
 * Remove embeds rewrite rules on plugin activation.
 *
 * @since 1.2.0
 */
function disable_embeds_remove_rewrite_rules() {
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	flush_rewrite_rules( false );
}

register_activation_hook( __FILE__, 'disable_embeds_remove_rewrite_rules' );

/**
 * Flush rewrite rules on plugin deactivation.
 *
 * @since 1.2.0
 */
function disable_embeds_flush_rewrite_rules() {
	remove_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	flush_rewrite_rules( false );
}

register_deactivation_hook( __FILE__, 'disable_embeds_flush_rewrite_rules' );

/**
 * Removes the oembed/1.0/embed REST route.
 *
 * @since 1.4.0
 *
 * @param array $endpoints Registered REST API endpoints.
 * @return array Filtered REST API endpoints.
 */
function disable_embeds_remove_embed_endpoint( $endpoints ) {
	unset( $endpoints['/oembed/1.0/embed'] );

	return $endpoints;
}

/**
 * Disables sending internal oEmbed response data in proxy endpoint.
 *
 * @since 1.4.0
 *
 * @param array $data The response data.
 * @return array|false Response data or false if in a REST API context.
 */
function disable_embeds_filter_oembed_response_data( $data ) {
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return false;
	}

	return $data;
}

/**
 * Enqueues JavaScript for the block editor.
 *
 * @since 1.4.0
 *
 * This is used to unregister the `core-embed/wordpress` block type.
 */
function disable_embeds_enqueue_block_editor_assets() {
	wp_enqueue_script(
		'disable-embeds',
		plugins_url( 'js/editor.js', __FILE__ ),
		array(
			'wp-edit-post',
			'wp-editor',
			'wp-dom',
		),
		'20181202',
		true
	);
}

/**
 * Removes wp-embed dependency of core packages.
 *
 * @since 1.4.0
 *
 * @param \WP_Scripts $scripts WP_Scripts instance, passed by reference.
 */
function disable_embeds_remove_script_dependencies( $scripts ) {
	if ( ! empty( $scripts->registered['wp-edit-post'] ) ) {
		$scripts->registered['wp-edit-post']->deps = array_diff(
			$scripts->registered['wp-edit-post']->deps,
			array( 'wp-embed' )
		);
	}
}

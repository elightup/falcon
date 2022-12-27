<?php
/**
 * Plugin Name: Falcon
 * Plugin URI:  https://elightup.com
 * Description: WordPress optimizations & tweaks
 * Version:     2.0.5
 * Author:      eLightUp
 * Author URI:  https://elightup.com
 * License:     GPL2+
 * Text Domain: falcon
 * Domain Path: /languages/
 */

namespace Falcon;

require __DIR__ . '/vendor/autoload.php';

new General;
new Settings;

if ( Settings::is_feature_active( 'no_embeds' ) ) {
	require __DIR__ . '/vendor/disable-embeds/disable-embeds.php';
}

if ( ! is_admin() ) {
	new Header;
}

add_action( 'init', function () {
	load_plugin_textdomain( 'falcon', false, plugin_basename( __DIR__ ) . '/languages/' );
} );

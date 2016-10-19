<?php
/**
 * Plugin Name: Falcon
 * Plugin URI: https://gretathemes.com
 * Description: The best WordPress optimization plugin.
 * Version: 1.2.1
 * Author: GretaThemes
 * Author URI: https://gretathemes.com
 * License: GPL2+
 * Text Domain: falcon
 * Domain Path: /languages/
 */

namespace Falcon;

require __DIR__ . '/inc/Autoloader.php';
$loader = new Autoloader;
$loader->addNamespace( 'Falcon', __DIR__ . '/inc' );
$loader->register();

new General;
new Embed;

if ( is_admin() ) {
	new Settings;
} else {
	new Header;
	new Media;
	new AsyncCSS;
}

// Load plugin textdomain.
add_action( 'init', function () {
	load_plugin_textdomain( 'falcon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );
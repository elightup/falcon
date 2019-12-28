<?php
/**
 * Plugin Name: Falcon
 * Plugin URI:  https://elightup.com/products/
 * Description: The best WordPress optimization plugin.
 * Version:     1.3.1
 * Author:      eLightUp
 * Author URI:  https://elightup.com
 * License:     GPL2+
 * Text Domain: falcon
 * Domain Path: /languages/
 *
 * @package Falcon
 */

namespace Falcon;
require __DIR__ . '/vendor/autoload.php';

new General;
new Embed;
new Settings;

if ( ! is_admin() ) {
	new Header;
	new Media;
} else {
	new Recommendation;
}

add_action( 'init', function () {
	load_plugin_textdomain( 'falcon', false, plugin_basename( __DIR__ ) . '/languages/' );
} );
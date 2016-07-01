<?php
/**
 * Plugin Name: Falcon
 * Plugin URI: http://gretathemes.com
 * Description: The best WordPress optimization plugin.
 * Version: 1.1
 * Author: GretaThemes
 * Author URI: http://gretathemes.com
 * License: GPL2+
 * Text Domain: falcon
 * Domain Path: /lang/
 */

namespace Falcon;

require plugin_dir_path( __FILE__ ) . 'inc/autoloader.php';
$loader = new Psr4AutoloaderClass;
$loader->addNamespace( 'Falcon', plugin_dir_path( __FILE__ ) . 'inc' );
$loader->register();

new General;
new Embed;

if ( ! is_admin() ) {
	new Header;
	new Media;
}

<?php
/**
 * Plugin Name: Falcon
 * Plugin URI:  https://elightup.com
 * Description: WordPress optimizations & tweaks
 * Version:     2.8.1
 * Author:      eLightUp
 * Author URI:  https://elightup.com
 * License:     GPL2+
 * Text Domain: falcon
 * Domain Path: /languages/
 */

namespace Falcon;

define( 'FALCON_URL', plugin_dir_url( __FILE__ ) );
define( 'FALCON_DIR', __DIR__ );

require __DIR__ . '/vendor/autoload.php';

new Settings;

new General;
new Admin;
new Security;
new Email;

if ( is_admin() ) {
	new Core;
} else {
	new Header;
	new Media;
	new LazyLoadCSS;
}

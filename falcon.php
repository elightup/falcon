<?php
/**
 * Plugin Name: Falcon
 * Plugin URI: http://gretathemes.com
 * Description: The best WordPress optimization plugin.
 * Version: 1.0.0
 * Author: GretaThemes
 * Author URI: http://gretathemes.com
 * License: GPL2+
 * Text Domain: falcon
 * Domain Path: /lang/
 */

require plugin_dir_path( __FILE__ ) . 'inc/general.php';
new Falcon_General;

if ( ! is_admin() )
{
	require plugin_dir_path( __FILE__ ) . 'inc/frontend.php';
	new Falcon_Frontend;
}

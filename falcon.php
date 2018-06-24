<?php
/**
 * Plugin Name: Falcon
 * Plugin URI: https://gretathemes.com
 * Description: The best WordPress optimization plugin.
 * Version: 1.3.0
 * Author: GretaThemes
 * Author URI: https://gretathemes.com
 * License: GPL2+
 * Text Domain: falcon
 * Domain Path: /languages/
 *
 * @package Falcon
 */

namespace Falcon;
require 'vendor/autoload.php';

new General;
new Embed;

if ( ! is_admin() ) {
	new Header;
	new Media;
}

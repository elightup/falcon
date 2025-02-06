<?php
/**
 * Plugin Name: Falcon
 * Plugin URI:  https://elightup.com
 * Description: WordPress optimizations & tweaks
 * Version:     2.8.5
 * Author:      eLightUp
 * Author URI:  https://elightup.com
 * License:     GPL2+
 * Text Domain: falcon
 * Domain Path: /languages/
 *
 * Copyright (C) 2010-2025 Tran Ngoc Tuan Anh. All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
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

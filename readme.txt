=== Falcon ===
Contributors: elightup, rilwis, truongwp
Tags: optimization, optimize, optimizer, loading speed, performance, speed, clean, clean up, cleaner, ping, pingback, heartbeat, emoji, emojis
Requires at least: 4.3
Tested up to: 5.6
Stable tag: 2.0.4
Requires PHP: 5.6
License: GPLv2 or later

Falcon is a WordPress plugin which cleans up your website and optimizes it for a better performance.

== Description ==

**Falcon** is a WordPress plugin which **cleans up your website and optimizes it for a better performance**.

### What does Falcon do?

* Disable heartbeat
* Disable emojis
* Disable embeds, e.g. prevent others from embedding your site and vise-versa
* Disable self pings
* Remove query string for JavaScript and CSS files
* Set scheme-less URLs for JavaScript and CSS files, e.g. remove `http:` and `https:` from URLs
* Removes styles for recent comments widget.
* Cleanup header

**Falcon** is created and maintained FREE on [Github](https://github.com/elightup/falcon). Please open a [new issue](https://github.com/elightup/falcon/issues) to add a suggestion or report a bug.

If you like this plugin, you might also like our other WordPress products:

- [Meta Box](https://metabox.io) - Lightweight yet powerful WordPress custom fields plugin
- [Slim SEO](https://wpslimseo.com) - Automated & fast SEO plugin for WordPress
- [GretaThemes](https://gretathemes.com) - Premium WordPress themes that clean, simple and just work

== Installation ==

Go to *Dashboad | Plugins | Add New* and search for **Falcon**. Then install and activate the plugin.

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 2.0.4 =
- Fix missing vendor folder

= 2.0.3 =
- Remove jQuery Migrate in the WordPress admin as well

= 2.0.2 =
- Fix PHP warning when blocking self-pings
- Fix textdomain

= 2.0.1 =
- Fix auto-deployment

= 2.0.0 =
- Re-add settings page
- Update disble embeds module

= 1.3.1 =
* Update compatibility with the latest version of WordPress
* Fix not working with WP-CLI

= 1.3.0 =
* Remove settings page
* Do not use jQuery from Google CDN for better compatibility
* Remove support for loading CSS async

= 1.2.4 =
* Add option to use latest version of jQuery
* Add option to exclude static resources from removing query string

= 1.2.3 =
* Downgrade jQuery to 2.2.4 for better compatibility

= 1.2.2 =
* Fix: File name case-sensitive

= 1.2.1 =
* Fix: Load PHP file using absolute path.

= 1.2 =
* New: Load CSS asynchronously (and selectively).
* Fix: No error in the login page.
* Requires PHP 5.4

= 1.1.1 =
* Fix not loading file for media.

= 1.1 =
* Remove Jetpack devicex script.
* Requires PHP 5.3.

= 1.0.3 =
* Update jQuery to 2.2.4

= 1.0.2 =
* Use jQuery from Google CDN

= 1.0.1 =
* Sets scheme-less URLs for JS and CSS files, e.g. removes 'http:' and 'https:' from URLs.

= 1.0.0 =
* Initial release

== Upgrade Notice ==

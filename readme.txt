=== Falcon - WordPress Optimization ===
Contributors: elightup, rilwis
Tags: optimization, optimize, optimizer, loading speed, performance, speed, clean, clean up, cleaner, ping, pingback, heartbeat, emoji, emojis
Requires at least: 4.3
Tested up to: 5.3
Stable tag: 1.3.1
License: GPLv2 or later

Falcon is a minimalist WordPress plugin which cleans up your website and optimizes it for best performance.

== Description ==

**Falcon** is a minimalist WordPress plugin which **cleans up your website and optimizes it for best performance**.

### What does Falcon do?

* Disables heartbeats.
* Disables emojis.
* Disables self ping.
* Removes query string in JS and CSS file.
* Sets scheme-less URLs for JS and CSS files, e.g. removes 'http:' and 'https:' from URLs.
* Cleans up header.
* Removes styles for recent comments widget.
* Remove Jetpack devicex script.

Note that Falcon requires PHP 5.4+ to run.

**Falcon** is created and maintained FREE on [Github](https://github.com/rilwis/falcon). Please open a [new issue](https://github.com/rilwis/falcon/issues) to add a suggestion or report a bug.

If you like this plugin, you might also like our [WordPress custom fields](https://metabox.io) plugin and [premium WordPress themes](https://gretathemes.com).

== Installation ==

Go to *Dashboad | Plugins | Add New* and search for **Falcon**. Then install and activate the plugin.

== Frequently Asked Questions ==

== Screenshots ==

The plugin doesn't have any settings page or configuration. Just install and forget!

== Changelog ==

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

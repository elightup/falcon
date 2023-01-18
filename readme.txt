=== Falcon - WordPress Optimizations & Tweaks ===
Contributors: elightup, rilwis, truongwp
Tags: optimization, optimize, optimizer, loading speed, performance, speed, clean, clean up, cleaner, ping, pingback, heartbeat, emoji, emojis
Requires at least: 5.9
Tested up to: 6.1.1
Stable tag: 2.2.0
Requires PHP: 7.2
License: GPLv2 or later

A lightweight WordPress optimization and tweak plugin for a better performance

== Description ==

**Falcon** is lightweight WordPress plugin that provide a list of optimizations and tweaks that help you improve the performance and user experience for your WordPress sites.

### Features

Falcon offers a comprehensive list of options for you to tweak and optimize your WordPress websites. These options are divided into the following categories:

#### General

- [Disable Gutenberg](https://metabox.io/disable-gutenberg-without-using-plugins/) (the block editor)
- Disable REST API for unauthenticated requests
- Disable heartbeat
- [Disable XML-RPC](https://deluxeblogtips.com/disable-xml-rpc-wordpress/)
- Disable emojis
- Disable embeds, e.g. prevent others from embedding your site and vise-versa
- Disable revisions
- Disable self pings
- Disable privacy tools

#### Assets

- Remove query string for JavaScript and CSS files
- Remove jQuery Migrate
- Set scheme-less URLs for JavaScript and CSS files, e.g. remove `http:` and `https:` from URLs
- Remove styles for recent comments widget
- Cleanup nav menu item ID & classes

#### Header

- Remove feed links
- Remove RSD link
- Remove wlwmanifest link
- Remove adjacent posts links
- Remove WordPress version number
- Remove shortlink
- Remove REST API link

#### Admin

- Show site icon on login page
- Remove update nags
- Remove footer text
- Remove default dashboard widgets
- Remove WordPress logo in the admin bar
- Remove admin email confirmation
- Remove application passwords

### You might also like

If you like this plugin, you might also like our other WordPress products:

- [Meta Box](https://metabox.io) - The most powerful WordPress plugin for creating custom post types and custom fields.
- [Slim SEO](https://wpslimseo.com) - A fast, lightweight and full-featured SEO plugin for WordPress with minimal configuration.
- [Slim SEO Schema](https://wpslimseo.com/slim-seo-schema/) - The best plugin to add schemas (structured data, rich snippets) to WordPress.
- [GretaThemes](https://gretathemes.com) - Free and premium WordPress themes that clean, simple and just work.

== Installation ==

Go to *Dashboard | Plugins | Add New* and search for **Falcon**. Then install and activate the plugin.

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 2.2.0 - 2023-01-18 =
- Cleanup nav menu item ID & classes
- Disable privacy tools
- Remove application passwords

= 2.1.0 - 2022-12-27 =
- Add disable XML-RPC
- Add disable Gutenberg
- Add disable REST API
- Add a set of admin tweaks
- Organize features in tabs and update styling

= 2.0.5 =
- Update compatibility tag

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

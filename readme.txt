=== Falcon - WordPress Optimizations & Tweaks ===
Contributors: elightup, rilwis, truongwp
Tags: optimize, performance, speed, tweaks, admin
Requires at least: 5.9
Tested up to: 6.4.3
Stable tag: 2.8.1
Requires PHP: 7.4
License: GPLv2 or later

A lightweight WordPress optimization and tweak plugin for a better performance

== Description ==

**Falcon** is lightweight WordPress plugin that provide a list of optimizations and tweaks that help you improve the performance and user experience for your WordPress sites.

### FEATURES & MODULES

Falcon offers a comprehensive list of 49 FREE modules (and growing) for you to tweak and optimize your WordPress websites. These options are divided into the following categories:

### General

- [Disable Gutenberg](https://metabox.io/disable-gutenberg-without-using-plugins/) (the block editor)
- Disable heartbeat
- Disable embeds, e.g. prevent others from embedding your site and vise-versa
- Disable comments
- Remove website field from comment form
- Disable revisions
- Disable self pings
- Disable privacy tools
- Disable cron
- Disable auto updates
- Block external requests
- Search only posts
- Disable replacing text with formatted entities like smart quotes, dashes, ellipses, etc.

### Header cleanup

- Remove feed links
- Remove RSD link
- Remove wlwmanifest link
- Remove adjacent posts links
- Remove WordPress version number
- Remove shortlink
- Remove REST API link

### Media

- **Asynchronous load CSS** to avoid blocking load of CSS files
- Remove query string for JavaScript and CSS files
- Remove jQuery Migrate
- Set scheme-less URLs for JavaScript and CSS files, e.g. remove `http:` and `https:` from URLs
- Remove styles for recent comments widget
- Cleanup nav menu item ID & classes
- Disable emojis
- Disable scaling down big images
- Disable automatic image rotation based on EXIF data
- Disable thumbnail generation
- Enable maintenance mode

### Email

- Remove admin email confirmation
- Disable auto update email notification
- Disable admin email notification when a new user is registered
- Disable admin email notification when users reset passwords
- [Change default WordPress from name and email](https://deluxeblogtips.com/change-wordpress-default-email/)
- SMTP configuration

### Admin

- Show site icon on login page
- Remove update nags
- Remove footer text
- Remove default dashboard widgets
- Remove WordPress logo in the admin bar
- Remove application passwords

### Security

- Disable REST API for unauthenticated requests
- [Disable XML-RPC](https://deluxeblogtips.com/disable-xml-rpc-wordpress/)
- Restrict upload file types
- Disable detailed login errors
- Block AI bots from crawling/stealing your content, which also affect the performance
- Force login to view the website

### You might also like

If you like this plugin, you might also like our other WordPress products:

- [Meta Box](https://metabox.io) - A powerful WordPress plugin for creating custom post types and custom fields.
- [Slim SEO](https://wpslimseo.com) - A fast, lightweight and full-featured SEO plugin for WordPress with minimal configuration.
- [Slim SEO Schema](https://wpslimseo.com/products/slim-seo-schema/) - An advanced, powerful and flexible plugin to add schemas to WordPress.
- [Slim SEO Link Manager](https://wpslimseo.com/products/slim-seo-link-manager/) - Build internal link easier in WordPress with real-time reports.
- [GretaThemes](https://gretathemes.com) - Free and premium WordPress themes that clean, simple and just work.
- [Auto Listings](https://wpautolistings.com) - A car sale and dealership plugin for WordPress.

== Installation ==

Go to *Dashboard | Plugins | Add New* and search for **Falcon**. Then install and activate the plugin.

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 2.8.1 - 2024-02-28 =
- Add options to change default email from name and address
- Add more AI bots to the block list

= 2.8.0 - 2024-02-20 =
- Add SMTP configuration
- Add maintenance mode
- Add block AI bots option
- Add force login option

= 2.7.2 - 2023-12-11 =
- Fix not loading email options
- Fix remove dashboard widgets not removing welcome panel

= 2.7.1 - 2023-12-07 =
- Add options for no emails when a new user is registered and users reset their passwords
- Fix error when disabling all features. Closes #17.

= 2.7.0 - 2023-12-07 =
- Update hook for disable core update email notification
- Fix removing update nag not working
- Add a new security tab with options to disable login errors and restrict upload file types

= 2.6.0 - 2023-12-03 =
- Update the plugin style

= 2.5.0 - 2023-09-18 =
- Add: Disable comments
- Add: Remove website field from comment form
- Add: Search only posts
- Add: Disable replacing text with formatted entities like smart quotes, dashes, ellipses, etc.
- Add: Disable scaling down big images
- Add: Disable automatic image rotation based on EXIF data
- Add: Disable thumbnail generation

= 2.4.1 - 2023-08-14 =
- Remove classic theme styles for button blocks
- Remove Jetpack widget

= 2.4.0 - 2023-08-02 =
- Add asynchronous load CSS

= 2.3.0 - 2023-07-11 =
- Disable cron
- Disable auto updates
- Block external requests
- Fix missing current ancestor class for menu

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

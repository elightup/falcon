=== Falcon - WordPress Optimizations & Tweaks ===
Contributors: elightup, rilwis, truongwp, paracetamol27
Tags: optimize, performance, speed, tweaks, admin
Requires at least: 6.7
Tested up to: 7.0.1
Stable tag: 2.11.0
Requires PHP: 7.4
License: GPLv2 or later

A lightweight WordPress plugin for performance, security, cleanup, and everyday tweaks.

== Description ==

**[Falcon](https://wpfalcon.pro)** is a lightweight WordPress plugin that helps you speed up your site, harden security, clean up bloat, and fine-tune how WordPress works — without a heavy stack of separate tools.

Toggle only what you need from a clear settings UI. Export and import settings to reuse the same setup across sites.

### Features

#### [Performance](https://wpfalcon.pro/features/performance/)

Make pages load faster and cut unnecessary front-end work.

- [HTML page cache](https://wpfalcon.pro/features/performance/cache/) — serve static HTML and skip WordPress, themes, and plugins on cache hits
- Disable Heartbeat to reduce server load
- Remove `?ver=` query strings from CSS/JS for better browser caching
- Disable emoji scripts and styles
- Remove jQuery Migrate on the front end
- Load selected CSS files asynchronously to reduce render-blocking

#### [Security](https://wpfalcon.pro/features/security/)

Reduce common attack surfaces and protect your content.

- Limit login attempts (block an IP after 3 failed tries for 1 hour)
- Hide detailed login error messages
- Force login to view the site
- Disable the REST API for unauthenticated requests
- Disable XML-RPC
- Restrict upload file types (including WebP and AVIF when enabled)
- Comment spam protection with a simple honeypot
- Block common AI crawlers via `robots.txt`
- Use scheme-less asset URLs to avoid mixed content warnings

#### [Cleanup](https://wpfalcon.pro/features/cleanup/header/)

Strip unused markup and keep the database lean.

**[Header](https://wpfalcon.pro/features/cleanup/header/)**

- Hide WordPress version
- Remove shortlink, REST API link, adjacent posts links, feed links, RSD link, and WLW manifest link

**[Admin](https://wpfalcon.pro/features/cleanup/admin/)**

- Use the site icon on the login screen
- Hide update nags
- Remove default dashboard widgets
- Remove admin footer text and the WordPress admin bar logo

**[Frontend](https://wpfalcon.pro/features/cleanup/frontend/)**

- Disable embeds
- Clean up menu item classes and IDs
- Remove Recent Comments widget CSS

**[Database](https://wpfalcon.pro/features/cleanup/database/)**

- Clean revisions, auto drafts, trashed posts, spam/trashed comments, expired transients, orphaned meta, unused terms, and optimize tables

#### [Content](https://wpfalcon.pro/features/content/editor/)

Control the editor, comments, media, and publishing behavior.

**[Editor](https://wpfalcon.pro/features/content/editor/)**

- Disable the block editor (Gutenberg) and use the classic editor
- Disable post revisions
- Disable texturize (smart quotes and auto formatting)

**[Comments](https://wpfalcon.pro/features/content/comments/)**

- Disable comments site-wide
- Remove the website field from the comment form

**[Media](https://wpfalcon.pro/features/content/media/)**

- Disable thumbnail generation
- Disable big image scaling
- Disable EXIF-based image rotation

**[Publishing](https://wpfalcon.pro/features/content/publishing/)**

- Limit front-end search to posts only
- Disable self pingbacks

#### [System](https://wpfalcon.pro/features/system/)

Site-wide controls for maintenance and WordPress internals.

- Maintenance mode (customize the message with `maintenance.php` in the active theme)
- Block external HTTP requests
- Disable application passwords
- Disable auto-updates
- Disable WP-Cron (use a real server cron instead)
- Remove privacy tools from the admin menu

#### [Email](https://wpfalcon.pro/features/email/notifications/)

Cut noisy notifications and send mail reliably.

**[Notifications](https://wpfalcon.pro/features/email/notifications/)**

- Disable admin email verification prompts
- Disable update notification emails
- Disable new user emails to admins
- Disable password change/reset emails

**[Delivery](https://wpfalcon.pro/features/email/delivery/)**

- Change the default from name and email address
- Send WordPress email via SMTP

### You might also like

If you like this plugin, you might also like our other WordPress products:

- [Meta Box](https://metabox.io) - A powerful WordPress plugin for creating custom post types and custom fields.
- [Slim SEO](https://wpslimseo.com) - A fast, lightweight and full-featured SEO plugin for WordPress with minimal configuration.
- [GretaThemes](https://gretathemes.com) - Free and premium WordPress themes that clean, simple and just work.
- [Auto Listings](https://wpautolistings.com) - A car sale and dealership plugin for WordPress.

== Installation ==

Go to *Dashboard | Plugins | Add New* and search for **Falcon**. Then install and activate the plugin.

== Frequently Asked Questions ==

= Where do I report security bugs found in this plugin? =

Please report security bugs found in the source code of the Falcon – WordPress Optimizations & Tweaks plugin through the [Patchstack Vulnerability Disclosure Program](https://patchstack.com/database/vdp/9e5fc06c-9bf1-4893-92b9-2cac4d0f24f0). The Patchstack team will assist you with verification, CVE assignment, and notify the developers of this plugin.

== Screenshots ==

== Changelog ==

= 2.11.0 - 2026-07-12 =

**Highlight**

- **Database cleanup:** Clean revisions, drafts, trash, spam, expired transients, orphaned meta, unused terms, and optimize tables. (#26)
- **Limit login attempts:** Block an IP after 3 failed logins for 1 hour. (#25)
- Allow `.avif` uploads when restricting file types. (#24)
- Customize maintenance mode via `maintenance.php` in the active theme.

**Other changes**

- Reorganize settings into grouped tabs with sub-tabs.
- Polish settings copy and option descriptions.
- Add a documentation link in the settings tabs bar.
- Fix cache clearing when an approved comment is inserted.
- Harden cache cleanup and request URI handling on PHP 8.

= 2.10.1 - 2026-05-12 =

- Fix serving the same cache for URL with and without query string
- Clear cache only when a comment is approve

= 2.10.0 - 2026-04-18 =

- Add new security module: Comment spam protection. Enable it under Security tab.

= 2.9.3 - 2026-01-21 =

- Improve check for not caching search result pages

= 2.9.2 - 2025-12-23 =

- Allow to upload WEBP images if enable restrict upload file types module
- Fix PHP warning when deleting cache dir

= 2.9.1 - 2025-12-19 =

- Fix not creating cache dir after cleaning

= 2.9.0 - 2025-12-19 =

- **[Cache module](https://wpfalcon.pro/features/cache/) (HTML page cache):** A lightweight yet powerful caching module to improve page load times and reduce server load by caching full HTML pages. Just enable it in the **General** tab and you're good to go.
  - Recommended for standard blogs and brochure-style websites.
  - Not recommended for highly dynamic or eCommerce sites.
- **Settings export & import:** Export your plugin settings to a file and import them on other sites to quickly reuse the same configuration.

= 2.8.6 - 2025-10-20 =
- Remove admin bar item when disable comments

= 2.8.5 - 2025-02-06 =
- Search posts only: exclude admin and rest API requests
- Do not enable "No Gutenberg" and "Search Posts Only" by default
- No Gutenberg: remove support for post types

= 2.8.4 - 2024-11-22 =
- Fix not showing site icon in the login page
- Fix security for sending test email

= 2.8.3 - 2024-05-17 =
- Disable XML-RPC methods
- Fix: activation error on ajax request
- Update fix word disable detailed login errors

= 2.8.2 - 2024-03-17 =
- Add a link to the settings page in the plugin meta row
- Auto redirect to the settings page after activation

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

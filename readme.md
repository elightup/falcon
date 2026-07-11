# Falcon - WordPress Optimizations & Tweaks

**[Falcon](https://wpfalcon.pro)** is a lightweight WordPress plugin that helps you speed up your site, harden security, clean up bloat, and fine-tune how WordPress works — without a heavy stack of separate tools.

Toggle only what you need from a clear settings UI. Export and import settings to reuse the same setup across sites.

## Features

### [Performance](https://wpfalcon.pro/features/performance/)

Make pages load faster and cut unnecessary front-end work.

- [HTML page cache](https://wpfalcon.pro/features/performance/cache/) — serve static HTML and skip WordPress, themes, and plugins on cache hits
- Disable Heartbeat to reduce server load
- Remove `?ver=` query strings from CSS/JS for better browser caching
- Disable emoji scripts and styles
- Remove jQuery Migrate on the front end
- Load selected CSS files asynchronously to reduce render-blocking

### [Security](https://wpfalcon.pro/features/security/)

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

### [Cleanup](https://wpfalcon.pro/features/cleanup/header/)

Strip unused markup and keep the database lean.

#### [Header](https://wpfalcon.pro/features/cleanup/header/)

- Hide WordPress version
- Remove shortlink, REST API link, adjacent posts links, feed links, RSD link, and WLW manifest link

#### [Admin](https://wpfalcon.pro/features/cleanup/admin/)

- Use the site icon on the login screen
- Hide update nags
- Remove default dashboard widgets
- Remove admin footer text and the WordPress admin bar logo

#### [Frontend](https://wpfalcon.pro/features/cleanup/frontend/)

- Disable embeds
- Clean up menu item classes and IDs
- Remove Recent Comments widget CSS

#### [Database](https://wpfalcon.pro/features/cleanup/database/)

- Clean revisions, auto drafts, trashed posts, spam/trashed comments, expired transients, orphaned meta, unused terms, and optimize tables

### [Content](https://wpfalcon.pro/features/content/editor/)

Control the editor, comments, media, and publishing behavior.

#### [Editor](https://wpfalcon.pro/features/content/editor/)

- Disable the block editor (Gutenberg) and use the classic editor
- Disable post revisions
- Disable texturize (smart quotes and auto formatting)

#### [Comments](https://wpfalcon.pro/features/content/comments/)

- Disable comments site-wide
- Remove the website field from the comment form

#### [Media](https://wpfalcon.pro/features/content/media/)

- Disable thumbnail generation
- Disable big image scaling
- Disable EXIF-based image rotation

#### [Publishing](https://wpfalcon.pro/features/content/publishing/)

- Limit front-end search to posts only
- Disable self pingbacks

### [System](https://wpfalcon.pro/features/system/)

Site-wide controls for maintenance and WordPress internals.

- Maintenance mode (customize the message with `maintenance.php` in the active theme)
- Block external HTTP requests
- Disable application passwords
- Disable auto-updates
- Disable WP-Cron (use a real server cron instead)
- Remove privacy tools from the admin menu

### [Email](https://wpfalcon.pro/features/email/notifications/)

Cut noisy notifications and send mail reliably.

#### [Notifications](https://wpfalcon.pro/features/email/notifications/)

- Disable admin email verification prompts
- Disable update notification emails
- Disable new user emails to admins
- Disable password change/reset emails

#### [Delivery](https://wpfalcon.pro/features/email/delivery/)

- Change the default from name and email address
- Send WordPress email via SMTP

## You might also like

If you like this plugin, you might also like our other WordPress products:

- [Meta Box](https://metabox.io) - A powerful WordPress plugin for creating custom post types and custom fields.
- [Slim SEO](https://wpslimseo.com) - A fast, lightweight and full-featured SEO plugin for WordPress with minimal configuration.
- [GretaThemes](https://gretathemes.com) - Free and premium WordPress themes that clean, simple and just work.
- [Auto Listings](https://wpautolistings.com) - A car sale and dealership plugin for WordPress.

<?php
// phpcs:ignore Squiz.Commenting.FileComment.WrongStyle
// Translators: %s - Link to the help docs.
$this->checkbox( 'no_gutenberg', __( 'Disable Gutenberg (the block editor)', 'falcon' ), sprintf( __( 'Disable the block editor for all post types and use classic editor only. <a href="%s" target="_blank">Learn more</a>.', 'falcon' ), 'https://metabox.io/disable-gutenberg-without-using-plugins/' ) );
$this->checkbox( 'no_heartbeat', __( 'Disable heartbeat', 'falcon' ), __( 'Reduce the CPU load on the server by disabling the WordPress heartbeat API.', 'falcon' ) );
$this->checkbox( 'no_embeds', __( 'Disable embeds', 'falcon' ), __( 'Prevent other websites from embedding your site and vise-versa.', 'falcon' ) );
$this->checkbox( 'no_comments', __( 'Disable comments', 'falcon' ), __( 'Disable comments for all post types. Existing comments will also be hidden on the frontend. And there will be no UI in the admin.', 'falcon' ) );
$this->checkbox( 'no_comment_url', __( 'Remove website field from comment form', 'falcon' ), __( 'Prevent people from spamming your website with their website URL.', 'falcon' ) );
$this->checkbox( 'no_revisions', __( 'Disable revisions', 'falcon' ), __( 'Reduce your database bloat by not storing revisions of posts.', 'falcon' ) );
$this->checkbox( 'no_self_pings', __( 'Disable self pings', 'falcon' ), __( 'Do not send trackbacks and pingbacks to your website when publishing new posts with internal links.', 'falcon' ) );
$this->checkbox( 'no_privacy', __( 'Disable privacy tools', 'falcon' ), __( 'Remove the privacy tools from the admin.', 'falcon' ) );
$this->checkbox( 'no_auto_updates', __( 'Disable auto updates', 'falcon' ), __( 'Do not let WordPress auto update. You have to update manually.', 'falcon' ) );
$this->checkbox( 'no_cron', __( 'Disable cron', 'falcon' ), __( 'Disable scheduled tasks. If you need to run cron jobs, You need to run from the server via a command line, or with an external service.', 'falcon' ) );
$this->checkbox( 'no_external_requests', __( 'Block external requests', 'falcon' ), __( 'Do not allow to connect to other websites. This will increase the performance, but also prevent the auto updates, license checking, or similar tasks that require remote connections.', 'falcon' ) );
$this->checkbox( 'search_posts_only', __( 'Search only posts', 'falcon' ), __( 'Do not search other post types when users perform a search.', 'falcon' ) );
$this->checkbox( 'no_texturize', __( 'Disable texturize', 'falcon' ), __( 'Do not allow WordPress to auto replace some characters with their formatted forms like quotes, dashes, ellipses, etc.', 'falcon' ) );
$this->checkbox( 'maintenance_mode', __( 'Enable maintenance mode', 'falcon' ), __( 'Put your website under the maintenance mode. This option will display a maintenance message to non-admin users when viewing the website on the front end.', 'falcon' ) );
$this->checkbox( 'cache', __( 'Cache', 'falcon' ), __( 'Cache pages for faster loading. Cache is automatically cleared when content changes.', 'falcon' ) );

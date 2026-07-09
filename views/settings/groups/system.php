<?php
$this->checkbox( 'maintenance_mode', __( 'Maintenance mode', 'falcon' ), __( 'Show a maintenance message to non-admin visitors.', 'falcon' ) );
$this->checkbox( 'no_external_requests', __( 'Block external requests', 'falcon' ), __( 'Block outbound HTTP requests from WordPress (can break updates and some plugins).', 'falcon' ) );
$this->checkbox( 'no_application_passwords', __( 'Disable application passwords', 'falcon' ), __( 'Disable application passwords if you don\'t use external integrations.', 'falcon' ) );
$this->checkbox( 'no_auto_updates', __( 'Disable auto-updates', 'falcon' ), __( 'Turn off automatic updates for WordPress, themes, and plugins.', 'falcon' ) );
$this->checkbox( 'no_cron', __( 'Disable WP-Cron', 'falcon' ), __( 'Disable WP-Cron. Use a real server cron job instead.', 'falcon' ) );
$this->checkbox( 'no_privacy', __( 'Remove privacy tools', 'falcon' ), __( 'Remove privacy tools from the admin menu.', 'falcon' ) );

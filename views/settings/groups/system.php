<?php
$this->checkbox( 'no_cron', __( 'Disable cron', 'falcon' ), __( 'Disable scheduled tasks. If you need to run cron jobs, You need to run from the server via a command line, or with an external service.', 'falcon' ) );
$this->checkbox( 'no_auto_updates', __( 'Disable auto updates', 'falcon' ), __( 'Do not let WordPress auto update. You have to update manually.', 'falcon' ) );
$this->checkbox( 'no_privacy', __( 'Disable privacy tools', 'falcon' ), __( 'Remove the privacy tools from the admin.', 'falcon' ) );
$this->checkbox( 'no_external_requests', __( 'Block external requests', 'falcon' ), __( 'Do not allow to connect to other websites. This will increase the performance, but also prevent the auto updates, license checking, or similar tasks that require remote connections.', 'falcon' ) );
$this->checkbox( 'maintenance_mode', __( 'Enable maintenance mode', 'falcon' ), __( 'Put your website under the maintenance mode. This option will display a maintenance message to non-admin users when viewing the website on the front end.', 'falcon' ) );

<?php
$this->checkbox( 'no_admin_email_confirm', __( 'Remove admin email confirmation', 'falcon' ), __( 'Do not ask whether the admin email is correct.', 'falcon' ) );
$this->checkbox( 'no_update_emails', __( 'Disable auto update email notification', 'falcon' ), __( 'Do not send emails when there are any updates on your website.', 'falcon' ) );
$this->checkbox( 'no_new_user_emails', __( 'Disable admin email notification when a new user is registered', 'falcon' ), __( 'Do not send emails to admins when there are new users registered on your website.', 'falcon' ) );
$this->checkbox( 'no_password_reset_emails', __( 'Disable email notification when users reset passwords', 'falcon' ), __( 'Do not send emails when users reset their passwords on your website.', 'falcon' ) );

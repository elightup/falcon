<?php
$this->checkbox( 'no_admin_email_confirm', __( 'Disable admin email verification', 'falcon' ), __( 'Stop the periodic "confirm admin email" prompt.', 'falcon' ) );
$this->checkbox( 'no_update_emails', __( 'Disable update emails', 'falcon' ), __( 'Stop sending WordPress update notification emails.', 'falcon' ) );
$this->checkbox( 'no_new_user_emails', __( 'Disable new user emails (admin)', 'falcon' ), __( 'Do not email admins when new users register.', 'falcon' ) );
$this->checkbox( 'no_password_reset_emails', __( 'Disable password change emails', 'falcon' ), __( 'Do not email users after password changes/resets.', 'falcon' ) );

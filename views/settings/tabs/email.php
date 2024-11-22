<?php
$option        = get_option( 'falcon', [] );
$smtp          = $option['smtp'] ?? [];
$default_email = $option['default_email'] ?? [];

$this->checkbox( 'no_admin_email_confirm', __( 'Remove admin email confirmation', 'falcon' ), __( 'Do not ask whether the admin email is correct.', 'falcon' ) );
$this->checkbox( 'no_update_emails', __( 'Disable auto update email notification', 'falcon' ), __( 'Do not send emails when there are any updates on your website.', 'falcon' ) );
$this->checkbox( 'no_new_user_emails', __( 'Disable admin email notification when a new user is registered', 'falcon' ), __( 'Do not send emails to admins when there are new users registered on your website.', 'falcon' ) );
$this->checkbox( 'no_password_reset_emails', __( 'Disable email notification when users reset passwords', 'falcon' ), __( 'Do not send emails when users reset their passwords on your website.', 'falcon' ) );
?>
<div class="featureBox">
	<label class="featureBox_switch">
		<input class="featureBox_input" type="checkbox" name="falcon[features][]" value="change_default_email"<?php checked( self::is_feature_active( 'change_default_email' ) ) ?>>
		<span class="featureBox_icon"></span>
	</label>
	<div class="featureBox_body">
		<div class="featureBox_title"><?php esc_html_e( 'Change default email', 'falcon' ) ?></div>
		<?php // Translators: %s - Link to the help docs ?>
		<div class="featureBox_description"><?= wp_kses_post( sprintf( __( 'Change WordPress default email from name and address. <a href="%s">Learn more</a>.', 'falcon' ), 'https://deluxeblogtips.com/change-wordpress-default-email/' ) ); ?></div>
		<div class="featureBox_more">
			<div class="formControls">
				<label for="falcon[default_email][from_name]"><?php esc_html_e( 'From name', 'falcon' ) ?></label>
				<input type="text" class="regular-text" name="falcon[default_email][from_name]" id="falcon[default_email][from_name]" value="<?= esc_attr( $default_email['from_name'] ?? get_bloginfo( 'name' ) ); ?>">
				<label for="falcon[default_email][from_email]"><?php esc_html_e( 'From email', 'falcon' ) ?></label>
				<input type="text" class="regular-text" name="falcon[default_email][from_email]" id="falcon[default_email][from_email]" value="<?= esc_attr( $default_email['from_email'] ?? get_option( 'admin_email' ) ); ?>">
			</div>
		</div>
	</div>
</div>
<div class="featureBox">
	<label class="featureBox_switch">
		<input class="featureBox_input" type="checkbox" name="falcon[features][]" value="smtp"<?php checked( self::is_feature_active( 'smtp' ) ) ?>>
		<span class="featureBox_icon"></span>
	</label>
	<div class="featureBox_body">
		<div class="featureBox_title"><?php esc_html_e( 'SMTP', 'falcon' ) ?></div>
		<div class="featureBox_description"><?php esc_html_e( 'Send emails via a SMTP service.', 'falcon' ) ?></div>
		<div class="featureBox_more">
			<div class="formControls">
				<label for="falcon[smtp][host]"><?php esc_html_e( 'Host', 'falcon' ) ?></label>
				<input type="text" class="regular-text" name="falcon[smtp][host]" id="falcon[smtp][host]" value="<?= esc_attr( $smtp['host'] ?? '' ); ?>">
				<label for="falcon[smtp][port]"><?php esc_html_e( 'Port', 'falcon' ) ?></label>
				<input type="text" class="regular-text" name="falcon[smtp][port]" id="falcon[smtp][port]" value="<?= esc_attr( $smtp['port'] ?? '' ); ?>">
				<label for="falcon[smtp][username]"><?php esc_html_e( 'Username', 'falcon' ) ?></label>
				<input type="text" class="regular-text" name="falcon[smtp][username]" id="falcon[smtp][username]" value="<?= esc_attr( $smtp['username'] ?? '' ); ?>">
				<label for="falcon[smtp][password]"><?php esc_html_e( 'Password', 'falcon' ) ?></label>
				<input type="password" name="falcon[smtp][password]" id="falcon[smtp][password]" value="<?= esc_attr( $smtp['password'] ?? '' ); ?>">
				<label><?php esc_html_e( 'Encryption', 'falcon' ) ?></label>
				<div>
					<label><input type="radio" name="falcon[smtp][encryption]" value=""<?php checked( $smtp['encryption'] ?? '', '' ) ?>> <?php esc_html_e( 'None', 'falcon' ) ?></label>
					<label><input type="radio" name="falcon[smtp][encryption]" value="ssl"<?php checked( $smtp['encryption'] ?? '', 'ssl' ) ?>> <?php esc_html_e( 'SSL', 'falcon' ) ?></label>
					<label><input type="radio" name="falcon[smtp][encryption]" value="tls"<?php checked( $smtp['encryption'] ?? '', 'tls' ) ?>> <?php esc_html_e( 'TLS', 'falcon' ) ?></label>
				</div>
			</div>
			<br>
			<button class="button" type="button" id="smtp-test"><?php esc_html_e( 'Send test email', 'falcon' ) ?></button>
			<p class="description"><?php esc_html_e( 'This action will send a test email to your account email.', 'falcon' ) ?>
		</div>
	</div>
</div>
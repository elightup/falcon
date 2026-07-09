<?php
$option     = get_option( 'falcon', [] );
$cloudflare = $option['cloudflare'] ?? [];
?>
<div class="featureBox">
	<label class="featureBox_switch">
		<input class="featureBox_input" type="checkbox" name="falcon[features][]" value="cache"<?php checked( self::is_feature_active( 'cache' ) ) ?>>
		<span class="featureBox_icon"></span>
	</label>
	<div class="featureBox_body">
		<div class="featureBox_title"><?php esc_html_e( 'Cache', 'falcon' ) ?></div>
		<div class="featureBox_description"><?php esc_html_e( 'Cache pages for faster loading. Cache is automatically cleared when content changes.', 'falcon' ) ?></div>
		<div class="featureBox_more">
			<div class="formControls">
				<label for="falcon[cloudflare][api_token]"><?php esc_html_e( 'Cloudflare API token', 'falcon' ) ?></label>
				<input type="password" class="regular-text" name="falcon[cloudflare][api_token]" id="falcon[cloudflare][api_token]" value="" autocomplete="off" placeholder="<?= ! empty( $cloudflare['api_token'] ) ? esc_attr__( 'Saved (leave blank to keep)', 'falcon' ) : ''; ?>">
				<label for="falcon[cloudflare][zone_id]"><?php esc_html_e( 'Cloudflare zone ID', 'falcon' ) ?></label>
				<input type="text" class="regular-text" name="falcon[cloudflare][zone_id]" id="falcon[cloudflare][zone_id]" value="<?= esc_attr( $cloudflare['zone_id'] ?? '' ); ?>">
			</div>
			<p class="description">
				<?php
				// Translators: %1$s - Link to Cloudflare API tokens page, %2$s - Link to the cache documentation.
				echo wp_kses_post( sprintf( __( 'If your site uses Cloudflare, enter your <a href="%1$s" target="_blank">API token</a> and zone ID to cache pages at the edge. The token must have <strong>Zone.Cache Purge</strong> permission. <a href="%2$s" target="_blank">Learn more</a>.', 'falcon' ), 'https://dash.cloudflare.com/profile/api-tokens', 'https://wpfalcon.pro/features/cache/' ) );
				?>
			</p>
		</div>
	</div>
</div>
<?php
$this->checkbox( 'no_heartbeat', __( 'Disable heartbeat', 'falcon' ), __( 'Reduce the CPU load on the server by disabling the WordPress heartbeat API.', 'falcon' ) );
$this->checkbox( 'no_query_string', __( 'Remove query string for JavaScript and CSS files', 'falcon' ), __( 'Remove "?ver=xxx" and other query string from JavaScript and CSS files. This will make browsers cache these files better.', 'falcon' ) );
$this->checkbox( 'no_jquery_migrate', __( 'Remove jQuery Migrate', 'falcon' ), __( 'Remove the old jQuery Migrate from both admin and frontend.', 'falcon' ) );
$this->checkbox( 'schema_less_urls', __( 'Set scheme-less URLs for JavaScript and CSS files', 'falcon' ), __( 'Remove <code>http:</code> and <code>https:</code> from JavaScript and CSS file URLs to make the URL shorter.', 'falcon' ) );
$this->checkbox( 'no_emojis', __( 'Disable emojis', 'falcon' ), __( 'Remove WordPress scripts to auto convert HTML entities to emojis. This won\'t affect the emoji characters that you use in your content.', 'falcon' ) );
?>
<fieldset>
	<label for="lazy-load-css"><?php esc_html_e( 'Asynchronous load CSS', 'falcon' ) ?></label>
	<p class="description"><?php esc_html_e( 'Improve your website performance by not letting the CSS files to block your pages rendering.', 'falcon' ) ?></p>
	<textarea id="lazy-load-css" class="large-text code" rows="10" name="falcon[lazy_load_css]"><?= esc_textarea( $option['lazy_load_css'] ?? '' ); ?></textarea>
	<p class="description"><?php esc_html_e( 'Enter CSS handles or keywords of CSS files that you want to load asynchronously, one per line. This feature should be used only for unimportant CSS.', 'falcon' ) ?></p>
</fieldset>

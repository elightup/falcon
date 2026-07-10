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
		<?php // Translators: %s - Link to the cache documentation. ?>
		<div class="featureBox_description"><?= wp_kses_post( sprintf( __( 'Cache pages for faster loading. Cache is automatically cleared when content changes. <a href="%s" target="_blank">Learn more</a>.', 'falcon' ), esc_url( add_query_arg( [ 'utm_campaign' => 'falcon', 'utm_source' => 'settings_page', 'utm_medium' => 'documentation_link' ], 'https://wpfalcon.pro/features/performance/cache/' ) ) ) ); ?></div>
		<div class="featureBox_more">
			<div class="formControls">
				<label for="falcon[cloudflare][api_token]"><?php esc_html_e( 'Cloudflare API token', 'falcon' ) ?></label>
				<input type="password" class="regular-text" name="falcon[cloudflare][api_token]" id="falcon[cloudflare][api_token]" value="" autocomplete="off" placeholder="<?= ! empty( $cloudflare['api_token'] ) ? esc_attr__( 'Saved (leave blank to keep)', 'falcon' ) : ''; ?>">
			</div>
			<?php if ( ! empty( $cloudflare['zone_name'] ) ) : ?>
				<p class="description">
					<?php
					// Translators: %s - Detected Cloudflare zone name.
					echo esc_html( sprintf( __( 'Detected zone: %s', 'falcon' ), $cloudflare['zone_name'] ) );
					?>
				</p>
			<?php elseif ( ! empty( $cloudflare['api_token'] ) ) : ?>
				<p class="description"><?php esc_html_e( 'No Cloudflare zone matched this site domain. Check the API token permissions and that the domain is in your Cloudflare account.', 'falcon' ); ?></p>
			<?php endif; ?>
			<p class="description">
				<?php
				// Translators: %s - Link to Cloudflare API tokens page.
				echo wp_kses_post( sprintf( __( 'If your site uses Cloudflare, enter your <a href="%s" target="_blank">API token</a> to cache pages at the edge. The token needs <strong>Zone.Zone Read</strong> and <strong>Zone.Cache Purge</strong> permissions.', 'falcon' ), 'https://dash.cloudflare.com/profile/api-tokens' ) );
				?>
			</p>
		</div>
	</div>
</div>
<?php
$this->checkbox( 'no_heartbeat', __( 'Disable heartbeat', 'falcon' ), __( 'Reduce the CPU load on the server by disabling the WordPress heartbeat API.', 'falcon' ) );
$this->checkbox( 'no_query_string', __( 'Remove asset version query strings', 'falcon' ), __( 'Remove the <code>?ver=</code> parameter from CSS/JS URLs to improve browser caching.', 'falcon' ) );
$this->checkbox( 'no_emojis', __( 'Disable emojis', 'falcon' ), __( 'Remove WordPress emoji scripts and styles.', 'falcon' ) );
$this->checkbox( 'no_jquery_migrate', __( 'Remove jQuery Migrate', 'falcon' ), __( 'Remove the legacy jQuery Migrate script on the front end.', 'falcon' ) );
?>
<fieldset>
	<label for="lazy-load-css"><?php esc_html_e( 'Asynchronous load CSS', 'falcon' ) ?></label>
	<p class="description"><?php esc_html_e( 'Load selected CSS files asynchronously to reduce render-blocking.', 'falcon' ) ?></p>
	<textarea id="lazy-load-css" class="large-text code" rows="10" name="falcon[lazy_load_css]"><?= esc_textarea( $option['lazy_load_css'] ?? '' ); ?></textarea>
	<p class="description"><?php esc_html_e( 'Enter CSS handles or keywords, one per line.', 'falcon' ) ?></p>
</fieldset>

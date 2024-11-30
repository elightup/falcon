<?php
$option = get_option( 'falcon', [] );

$this->checkbox( 'no_query_string', __( 'Remove query string for JavaScript and CSS files', 'falcon' ), __( 'Remove "?ver=xxx" and other query string from JavaScript and CSS files. This will make browsers cache these files better.', 'falcon' ) );
$this->checkbox( 'no_jquery_migrate', __( 'Remove jQuery Migrate', 'falcon' ), __( 'Remove the old jQuery Migrate from both admin and frontend.', 'falcon' ) );
$this->checkbox( 'schema_less_urls', __( 'Set scheme-less URLs for JavaScript and CSS files', 'falcon' ), __( 'Remove <code>http:</code> and <code>https:</code> from JavaScript and CSS file URLs to make the URL shorter.', 'falcon' ) );
$this->checkbox( 'no_recent_comments_widget_style', __( 'Remove styles of the recent comments widget', 'falcon' ), __( 'If you are using classic widgets, this feature will remove inline styles of the recent comments widget, outputted by WordPress.', 'falcon' ) );
$this->checkbox( 'cleanup_menu', __( 'Cleanup nav menu item IDs & classes', 'falcon' ), __( 'Remove IDs and all CSS classes for menu items, except "menu-item", "current-menu-item" and "current-page-item". If your website requires a specific menu item class, turn off this feature.', 'falcon' ) );
$this->checkbox( 'no_emojis', __( 'Disable emojis', 'falcon' ), __( 'Remove WordPress scripts to auto convert HTML entities to emojis. This won\'t affect the emoji characters that you use in your content.', 'falcon' ) );
$this->checkbox( 'no_image_threshold', __( 'Disable scaling down big images', 'falcon' ), __( 'Do not let WordPress auto created a scale down version of big images. This feature will save your disk storage.', 'falcon' ) );
$this->checkbox( 'no_exif_rotate', __( 'Disable automatic image rotation based on EXIF data', 'falcon' ), __( 'Do not let WordPress auto rotate your images based on EXIF data. Your images will always be as they are.', 'falcon' ) );
$this->checkbox( 'no_thumbnails', __( 'Disable thumbnail generation', 'falcon' ), __( 'Disable generating images with all image sizes.', 'falcon' ) );
?>
<fieldset>
	<label for="lazy-load-css"><?php esc_html_e( 'Asynchronous load CSS', 'falcon' ) ?></label>
	<p class="description"><?php esc_html_e( 'Improve your website performance by not letting the CSS files to block your pages rendering.', 'falcon' ) ?></p>
	<textarea id="lazy-load-css" class="large-text code" rows="10" name="falcon[lazy_load_css]"><?= esc_textarea( $option['lazy_load_css'] ?? '' ); ?></textarea>
	<p class="description"><?php esc_html_e( 'Enter CSS handles or keywords of CSS files that you want to load asynchronously, one per line. This feature should be used only for unimportant CSS.', 'falcon' ) ?></p>
</fieldset>

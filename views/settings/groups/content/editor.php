<?php
// phpcs:ignore Squiz.Commenting.FileComment.WrongStyle
// Translators: %s - Link to the help docs.
$this->checkbox( 'no_gutenberg', __( 'Disable Gutenberg (the block editor)', 'falcon' ), sprintf( __( 'Disable the block editor for all post types and use classic editor only. <a href="%s" target="_blank">Learn more</a>.', 'falcon' ), 'https://metabox.io/disable-gutenberg-without-using-plugins/' ) );
$this->checkbox( 'no_revisions', __( 'Disable revisions', 'falcon' ), __( 'Reduce your database bloat by not storing revisions of posts.', 'falcon' ) );
$this->checkbox( 'no_texturize', __( 'Disable texturize', 'falcon' ), __( 'Do not allow WordPress to auto replace some characters with their formatted forms like quotes, dashes, ellipses, etc.', 'falcon' ) );

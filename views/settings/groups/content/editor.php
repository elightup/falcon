<?php
// phpcs:ignore Squiz.Commenting.FileComment.WrongStyle
// Translators: %s - Link to the help docs.
$this->checkbox( 'no_gutenberg', __( 'Disable block editor (Gutenberg)', 'falcon' ), sprintf( __( 'Use the classic editor instead of the block editor. <a href="%s" target="_blank">Learn more</a>.', 'falcon' ), 'https://metabox.io/disable-gutenberg-without-using-plugins/' ) );
$this->checkbox( 'no_revisions', __( 'Disable post revisions', 'falcon' ), __( 'Stop saving post revisions to reduce database size.', 'falcon' ) );
$this->checkbox( 'no_texturize', __( 'Disable texturize', 'falcon' ), __( 'Disable smart quotes and other automatic text formatting.', 'falcon' ) );

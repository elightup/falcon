<?php
$this->checkbox( 'no_thumbnails', __( 'Disable thumbnail generation', 'falcon' ), __( 'Stop generating additional image sizes on upload.', 'falcon' ) );
$this->checkbox( 'no_image_threshold', __( 'Disable big image scaling', 'falcon' ), __( 'Do not create scaled-down copies of large uploads.', 'falcon' ) );
$this->checkbox( 'no_exif_rotate', __( 'Disable EXIF-based rotation', 'falcon' ), __( 'Do not auto-rotate images based on EXIF data.', 'falcon' ) );

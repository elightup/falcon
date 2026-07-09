<?php
$this->checkbox( 'no_image_threshold', __( 'Disable scaling down big images', 'falcon' ), __( 'Do not let WordPress auto created a scale down version of big images. This feature will save your disk storage.', 'falcon' ) );
$this->checkbox( 'no_exif_rotate', __( 'Disable automatic image rotation based on EXIF data', 'falcon' ), __( 'Do not let WordPress auto rotate your images based on EXIF data. Your images will always be as they are.', 'falcon' ) );
$this->checkbox( 'no_thumbnails', __( 'Disable thumbnail generation', 'falcon' ), __( 'Disable generating images with all image sizes.', 'falcon' ) );

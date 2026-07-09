<?php
$this->checkbox( 'no_comments', __( 'Disable comments', 'falcon' ), __( 'Disable comments for all post types. Existing comments will also be hidden on the frontend. And there will be no UI in the admin.', 'falcon' ) );
$this->checkbox( 'no_comment_url', __( 'Remove website field from comment form', 'falcon' ), __( 'Prevent people from spamming your website with their website URL.', 'falcon' ) );

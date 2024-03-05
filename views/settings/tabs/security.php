<?php
$this->checkbox( 'no_rest_api', __( 'Disable REST API for unauthenticated requests', 'falcon' ), __( 'Improve your website security by disabling REST API access for non-authenticated users.', 'falcon' ) );
// Translators: %s - Link to the help docs.
$this->checkbox( 'no_xmlrpc', __( 'Disable XML-RPC', 'falcon' ), sprintf( __( 'Protect your site from brute force, DOS and DDOS attacks via XML-RPC. Also disables trackbacks, pingbacks, and brakes the mobile apps. <a href="%s" target="_blank">Learn more</a>.', 'falcon' ), 'https://deluxeblogtips.com/disable-xml-rpc-wordpress/' ) );
$this->checkbox( 'restrict_upload', __( 'Restrict upload file types', 'falcon' ), __( 'Allow users to upload only common file types, including: images (jpg, jpeg, png, gif), office files (docx, xlsx, pptx), PDF, and videos (mp4).', 'falcon' ) );
$this->checkbox( 'no_login_errors', __( 'Disable detailed login errors', 'falcon' ), __( 'Show a general error message when the login is incorrect, not specifically whether the username or password is incorrect.', 'falcon' ) );
$this->checkbox( 'block_ai_bots', __( 'Block AI bots', 'falcon' ), __( 'Stop AI bots from crawling/stealing your website content, which also affects the website performance.', 'falcon' ) );
$this->checkbox( 'force_login', __( 'Force login', 'falcon' ), __( 'Force users to login to view the website.', 'falcon' ) );

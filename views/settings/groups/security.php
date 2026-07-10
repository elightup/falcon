<?php
$this->checkbox( 'limit_logins', __( 'Limit login attempts', 'falcon' ), __( 'Block an IP after 3 failed attempts for 1 hour.', 'falcon' ) );
$this->checkbox( 'no_login_errors', __( 'Hide login error details', 'falcon' ), __( 'Show a generic error message on failed logins.', 'falcon' ) );
$this->checkbox( 'force_login', __( 'Force login', 'falcon' ), __( 'Force users to login to view the website.', 'falcon' ) );

$this->checkbox( 'no_rest_api', __( 'Disable REST API for unauthenticated requests', 'falcon' ), __( 'Improve your website security by disabling REST API access for non-authenticated users.', 'falcon' ) );
$this->checkbox( 'no_xmlrpc', __( 'Disable XML-RPC', 'falcon' ), __( 'Disable XML-RPC to reduce brute-force and pingback abuse.', 'falcon' ) );
$this->checkbox( 'restrict_upload', __( 'Restrict upload file types', 'falcon' ), __( 'Allow only common file types for uploads.', 'falcon' ) );
$this->checkbox( 'comment_spam_protection', __( 'Comment spam protection', 'falcon' ), __( 'Protect your site from spam comments with a simple honeypot.', 'falcon' ) );

$this->checkbox( 'block_ai_bots', __( 'Block AI bots', 'falcon' ), __( 'Block common AI crawlers via <code>robots.txt</code>.', 'falcon' ) );
$this->checkbox( 'schema_less_urls', __( 'Use scheme-less asset URLs', 'falcon' ), __( 'Avoid mixed content warnings by using the current scheme (HTTP/HTTPS).', 'falcon' ) );

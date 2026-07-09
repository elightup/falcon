<?php
$this->checkbox( 'no_feed_links', __( 'Remove feed links', 'falcon' ), __( 'Remove all RSS and Atom feed URLs from the website\'s head. This includes feeds for posts, categories, tags, comments, authors and search.', 'falcon' ) );
$this->checkbox( 'no_rsd_link', __( 'Remove RSD link', 'falcon' ), __( 'Remove the RDF URL from the website\'s head.', 'falcon' ) );
$this->checkbox( 'no_wlwmanifest_link', __( 'Remove wlwmanifest link', 'falcon' ), __( 'Remove the wlwmanifest URL from the website\'s head.', 'falcon' ) );
$this->checkbox( 'no_adjacent_posts_links', __( 'Remove adjacent posts links', 'falcon' ), __( 'Remove the next and previous post(s) URLs from the website\'s head.', 'falcon' ) );
$this->checkbox( 'no_wp_generator', __( 'Remove WordPress version number', 'falcon' ), __( 'Remove the WordPress version number from the website\'s head. This helps improving the website security by not exposing the current WordPress version to hackers.', 'falcon' ) );
$this->checkbox( 'no_shortlink', __( 'Remove shortlink', 'falcon' ), __( 'Remove the short link, e.g. "?p=123" from the website\'s head.', 'falcon' ) );
$this->checkbox( 'no_rest_link', __( 'Remove REST API link', 'falcon' ), __( 'Remove the REST API URL from the website\'s head.', 'falcon' ) );

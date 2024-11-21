<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

// Remove the HTML meta tag from <head>:
// <link rel="shortlink" href="https://website.com/?p=post_id" />
remove_action('wp_head', 'wp_shortlink_wp_head', 10);

// Remove the HTTP header:
// Link: <https://website.com/?p=post_id>; rel="shortlink"
remove_action( 'template_redirect', 'wp_shortlink_header', 11);

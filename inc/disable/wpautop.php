<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

// Prevent WordPress from automatically adding extra <p> tags to your page content.
remove_filter('get_the_excerpt', 'wpautop');
remove_filter('the_excerpt', 'wpautop');
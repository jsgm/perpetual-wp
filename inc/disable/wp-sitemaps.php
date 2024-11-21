<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    die;
}

add_filter( 'wp_sitemaps_enabled', '__return_false' );

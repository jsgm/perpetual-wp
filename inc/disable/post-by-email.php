<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    die;
}

if(!defined("POST_BY_EMAIL")){
    define("POST_BY_EMAIL", false);
}

add_filter('enable_post_by_email_configuration', '__return_false');

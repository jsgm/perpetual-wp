<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    die;
}

// Fully disable the Capital P Dangit filters, which automatically convert "Wordpress" to "WordPress".
remove_filter('the_content', 'capital_P_dangit', 11);
remove_filter('the_title', 'capital_P_dangit', 11);
remove_filter('wp_title', 'capital_P_dangit', 11);
remove_filter('document_title', 'capital_P_dangit', 11);
remove_filter('comment_text', 'capital_P_dangit', 31);

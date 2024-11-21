<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

// Remove the RSD link.
// Example: <link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://website.com/xmlrpc.php?rsd" />
remove_action("wp_head", "rsd_link");

// Disable XMLRPC.
add_filter("xmlrpc_enabled", "__return_false");

add_filter("xmlrpc_methods", function(){
    return [];
}, PHP_INT_MIN);

add_filter('wp_headers', function($headers){
    unset($headers['X-Pingback']);
    return $headers;
}, PHP_INT_MIN);

add_action('wp', function() {
    @header_remove('X-Pingback');
}, 1000);

add_action("init", function(){
    // Make /xmlrpc.php return 403.
    if(defined("XMLRPC_REQUEST")){
        http_response_code(403);
        header("Content-type:text/plain; charset=utf-8");
        exit("XMLRPC disabled!");
    }
});

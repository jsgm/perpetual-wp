<?php
if(!defined('ABSPATH')){
	http_response_code(403);
	exit;
}

remove_action('wp_head', 'wp_generator');

add_filter('the_generator', function(){
	return '';
});

if(class_exists("WPSEO_Options")){
    // Remove debug comments from Yoast, if it's installed.
    add_filter('wpseo_debug_markers', '__return_false');
}

if(defined('THE_SEO_FRAMEWORK_PRESENT')){
    add_filter("the_seo_framework_indicator", '__return_false');
    add_filter("the_seo_framework_indicator_timing", '__return_false');
}

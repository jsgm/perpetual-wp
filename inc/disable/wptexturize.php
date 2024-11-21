<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

global $wp_version;

// WordPress automatically converts quotation marks (") into symbols like »; this file prevents that behavior
if(version_compare ($wp_version, '4.0') === -1){
    // WP <= 4.0
    foreach( array(
        'bloginfo',
        'the_content',
        'the_excerpt',
        'the_title',
        'comment_text',
        'comment_author',
        'link_name',
        'link_description',
        'link_notes',
        'list_cats',
        'nav_menu_attr_title',
        'nav_menu_description',
        'single_post_title',
        'single_cat_title',
        'single_tag_title',
        'single_month_title',
        'term_description',
        'term_name',
        'widget_title',
        'wp_title'
    ) as $hook)
    remove_filter($hook, 'wptexturize' );
}else{
    // WP > 4.0
    add_filter( 'run_wptexturize', '__return_false' );
}
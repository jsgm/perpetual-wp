<?php
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

if(!defined("WP_POST_REVISIONS")){
    // The configuration can be overridden by defining 'WP_POST_REVISIONS' in the wp-config.php file.
    define("WP_POST_REVISIONS", false);

    add_action("admin_init", function(){
        // Might be improved with more options in the future.
        foreach(apply_filters('pw_revisions_post_types', get_post_types()) as $post_type){
            remove_post_type_support( $post_type, 'revisions' );
        }
    });
}

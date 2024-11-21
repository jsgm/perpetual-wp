<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

define("PW_DEFAULT_COMMENT_EMAIL", "wapuu@wordpress.example");

add_action("admin_init", function(){
    if(get_option("show_comments_cookies_opt_in")){
        delete_option("show_comments_cookies_opt_in");
    }
});

add_filter('comment_form_default_fields', function($fields){
    // Disable URL field in comments.
    if(isset($fields['url'])) unset($fields["url"]);

    // Do not save user details in cookies.
    if(isset($fields["cookies"])) unset($fields["cookies"]);
    
    return $fields;
});

add_action( 'admin_bar_menu', function(\WP_Admin_Bar $admin_bar){
    // Remove the comments node if there are no comments pending moderation.
    if(current_user_can('edit_posts')){
        $awaiting_mod  = wp_count_comments();
        if($awaiting_mod->moderated <= 0){
            $admin_bar->remove_node('comments');
        }
    }
}, PHP_INT_MAX);

add_filter("pre_comment_approved", function($approved, $data){
    // Note: The WP_Error '200' allows the message to be printed on the screen.
    if(isset($data["comment_content"]) && strlen(trim($data["comment_content"]))<=1){
        return new WP_Error('require_user_rate_comment', __("Your comment is too short. Please enter at least one meaningful word.", "perpetual-wp"), 200);
    }
    if(isset($data["comment_author_url"]) && strlen(trim($data["comment_author_url"]))>0){
        return new WP_Error('require_user_rate_comment', __("Including a URL in your comment is not allowed.", "perpetual-wp"), 200);
    }
    if(isset($data["comment_author_email"]) && strlen(trim($data["comment_author_email"]))>0 && (
        preg_replace( '!^.+?([^@]+)$!', '$1', $data["comment_author_email"]) == preg_replace( '!^.+?([^@]+)$!', '$1', PW_DEFAULT_COMMENT_EMAIL)

    )){
        return new WP_Error('require_user_rate_comment', __("Using a test email address to submit a comment is not allowed.", "perpetual-wp"), 200);
    }
    return $approved;
}, 99, 2);

<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

add_filter('rest_authentication_errors', function($access){
    if(!is_user_logged_in()) return new WP_Error('rest_api_login_required', __('Sorry, you are not allowed to use the API.', 'perpetual-wp'), array('status' => rest_authorization_required_code()));

    return $access;
});

// Remove REST API Links.
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('template_redirect', 'rest_output_link_header', 11, 0);

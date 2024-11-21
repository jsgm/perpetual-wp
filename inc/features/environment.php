<?php
if(!defined("ABSPATH")){
    http_response_code(403);
}

var_dump(WP_ENVIRONMENT_TYPE);
var_dump(wp_get_environment_type());

if(!defined("WP_ENVIRONMENT_TYPE")){
    define("WP_ENVIRONMENT_TYPE", "production");
}

// TO-DO: Red adminbar.

add_action('admin_bar_menu', function($admin_bar){
    // Don not show in production.
    if(wp_get_environment_type() == "production") return;

    // Do not show for visitors.
    if(!is_user_logged_in()) return;

    // Only available for admin.
    $user = wp_get_current_user();
    if(!in_array("admin", (array)$user->roles)) return;

    $admin_bar->add_node($args = array(
        'parent' => 'top-secondary',
        'id'     => 'pw-environment-type',
        'title'  => wp_get_environment_type(),
        'href'   => false,
        'meta'   => false
    ));
}, -PHP_INT_MAX);

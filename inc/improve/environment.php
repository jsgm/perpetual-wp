<?php
if(!defined("ABSPATH")){
    http_response_code(403);
}

if(!defined("WP_ENVIRONMENT_TYPE")){
    define("WP_ENVIRONMENT_TYPE", "production");
}

add_action('admin_bar_menu', function($admin_bar){
    // Don not show in production.
    if(wp_get_environment_type() == "production") return;

    // Do not show for visitors.
    if(!is_user_logged_in()) return;

    // Only available for admin.
    $user = wp_get_current_user();
    if(!in_array("administrator", (array)$user->roles)) return;

    $admin_bar->add_menu([
        'id'     => 'pw-environment-type',
        'parent' => 'top-secondary',
        'title'  => esc_html(wp_get_environment_type()).' <span class="ab-icon dashicons dashicons-marker"></span>',
        'href'   => false,
        'meta'   => [
            'title' => __("Your site environment is different from production.", "perpetual-wp")
        ]
    ]);
});

if(!function_exists("pw_staging_adminbar_bg_color")){
    function pw_staging_adminbar_bg_color(){
        if(wp_get_environment_type() == "production" || !is_admin_bar_showing() || !is_user_logged_in()) return;

        $bg_color = apply_filters("pw_staging_adminbar_bg_color", "#ff5b3f");
        if($bg_color && is_string($bg_color) && preg_match('/^#[a-f0-9]{6}$/i', $bg_color)){
            echo '<style>#wpadminbar{background:'.$bg_color.' !important;}</style>';
        }
    }
}

add_action("admin_footer", "pw_staging_adminbar_bg_color");
add_action("wp_footer", "pw_staging_adminbar_bg_color");

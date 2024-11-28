<?php
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}
remove_filter( 'block_type_metadata', 'wp_migrate_old_typography_shape' );
remove_all_filters('block_type_metadata');
if(!function_exists("pw_user_maintenance_mode")){
    function pw_user_maintenance_mode(): bool{
        return true;
    }
}

add_action('admin_bar_menu', function($admin_bar){
    // Do not show for visitors.
    if(!is_user_logged_in()) return;

    // Only available for admin.
    $user = wp_get_current_user();
    if(!in_array("administrator", (array)$user->roles)) return;

    if(!pw_user_maintenance_mode()) return;

    $admin_bar->add_menu([
        'id'     => 'pw-user-maintenance',
        'parent' => 'top-secondary',
        'title'  => __( 'Maintenance' ).' <span class="ab-icon dashicons dashicons-admin-generic"></span>',
        'href'   => false,
        'meta'   => [
            'title' => __("Visitors cannot access the website at the moment.", "perpetual-wp")
        ]
    ]);
});

add_action( 'all', 'th_show_all_hooks' );
	
global $debug_tags;
$debug_tags = [];

function th_show_all_hooks( $tag ) {
	global $debug_tags;
    if(!isset($debug_tags[$tag]))  $debug_tags[$tag] = 0;
    $debug_tags[$tag]++;
}
/*
add_filter("shutdown", function(){
    global $debug_tags;
    asort($debug_tags);
    echo '<pre>';
    var_dump($debug_tags);
});
*/
add_action("wp_loaded", function(){
    if(pw_user_maintenance_mode() && !is_favicon() && !is_admin() && !is_login() && !is_user_logged_in()){
        $maintenance_message = apply_filters('pw_user_maintenance_message', __("We're currently undergoing maintenance. Please check back shortly!", "perpetual-wp"));

        wp_die(
            // Use a custom message or a different one than "Briefly unavailable for scheduled maintenance. Check back in a minute." 
            // to distinguish between core maintenance (for updates) and user-initiated maintenance (enabled by an admin).
            esc_html($maintenance_message),
            __( 'Maintenance' ),
            503
        );
    }
});

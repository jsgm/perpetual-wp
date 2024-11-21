<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

if(!defined("PW_DEFAULT_PRIVACY_POLICY_ID")){
    define("PW_DEFAULT_PRIVACY_POLICY_ID", 3);
}

if(!function_exists("pw_disable_core_privacy_tools")){
    function pw_disable_core_privacy_tools( $caps, $cap ){
        switch ( $cap ) {
            case 'export_others_personal_data':
            case 'erase_others_personal_data':
            case 'manage_privacy_options':
                $caps[] = 'do_not_allow';
                break;
        }
        return $caps;
    }
}
add_filter('map_meta_cap', 'pw_disable_core_privacy_tools', 10, 2);
add_filter('pre_option_wp_page_for_privacy_policy', '__return_zero');
remove_action('init', 'wp_schedule_delete_old_privacy_export_files');
remove_action('wp_privacy_delete_old_export_files', 'wp_privacy_delete_old_export_files');


// Admin will be redirected to the main admin page.
add_action('load-options-privacy.php', 'pw_admin_redirect'); // TO-DO: Not redirecting
add_action('load-erase-personal-data.php', 'pw_admin_redirect');
add_action('load-export-personal-data.php', 'pw_admin_redirect');

add_action("pw_activation_hook", function(){
    if((int)get_option("wp_page_for_privacy_policy") === PW_DEFAULT_PRIVACY_POLICY_ID){
        delete_option("wp_page_for_privacy_policy");
    }
});

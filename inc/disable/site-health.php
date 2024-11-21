<?php
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

if(!function_exists("pw_site_health_enabled")){
    function pw_site_health_enabled(): bool{
        $opt = get_option("pw_site_health");
        return ($opt && ($opt == "on" || (int)$opt === 1));
    }
}

add_action('admin_init', function(){
    add_settings_field(
        'pw_site_health',
        __('Site Health'), // Default WordPress translation
        function($args){
            echo '<fieldset>
                <label for="pw_site_health">
                    <input name="pw_site_health" type="checkbox" id="pw_site_health"'.(pw_site_health_enabled() ? ' checked' : '').'>
                    '.__("Enable");
                    echo '
                </label>
                <p class="description">'.__("Site Health is disabled by default.", "perpetual-wp").'</p>
            </fieldset>';
        },
        'general',
        'default',
        array(
            'pw_site_health'
        )
    ); 
    
    register_setting('general', 'pw_site_health', 'esc_attr');
});

if(!pw_site_health_enabled()){
    add_action('wp_dashboard_setup', function(){
        remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    });

    add_action( 'admin_menu', function(){
        remove_submenu_page( 'tools.php', 'site-health.php' );
    }, PHP_INT_MAX);

    add_action('load-site-health.php', 'pw_admin_redirect');
}

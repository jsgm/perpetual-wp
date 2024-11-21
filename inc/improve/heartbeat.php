<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    die;
}

/* Limit heartbeat control. */
add_filter( 'heartbeat_settings', function(){
    // Set the interval to 60 seconds (default is 15 seconds).
    $seconds = apply_filters("pw_heartbeat_seconds", 60);
    if(!is_numeric($seconds) || $seconds < 10){
        if(is_admin()){
            wp_die(
                __("The heartbeat interval must be a numeric value and cannot be set to less than 10 seconds, as doing so may significantly impact your site's performance.", "perpetual-wp"), 
                __("Invalid heartbeat value", "perpetual-wp"),
                [
                    "response" => 503,
                    "exit" => true
                ]
            );
        }else{
            $seconds = 60;
        }
    }
        
	$settings['interval'] = $seconds;
    
	return $settings;
}, PHP_INT_MAX);

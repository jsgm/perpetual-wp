<?php
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

if(!defined("PW_LOGIN_MAX_ATTEMPTS")){
    define("PW_LOGIN_MAX_ATTEMPTS", 5);
}

if(!defined("PW_LOGIN_LOCKOUT_DURATION")){
    define("PW_LOGIN_LOCKOUT_DURATION", 360);
}

add_filter('login_errors', function($errors){
    // A generic message such as "Invalid username or password" prevents revealing whether the username exists in the system.
    // Consistency in error responses makes it more challenging for automated systems to determine if they are making progress toward valid credentials.
    return '<p>'.__('<strong>Error:</strong> Please check your username and password and try again.', 'perpetual-wp').'</p>';
});

if(!function_exists("pw_limit_login_attempts")){
    function pw_limit_login_attempts() {
        $max_attempts = PW_LOGIN_MAX_ATTEMPTS;
        $lockout_duration = PW_LOGIN_LOCKOUT_DURATION; // Lockout for 5 minutes

        $ip = $_SERVER['REMOTE_ADDR'];
        $failed_attempts = get_transient("login_attempts_$ip");

        if ($failed_attempts >= $max_attempts) {
            wp_die('Too many failed login attempts. Please try again later.');
        }

        add_action('wp_login_failed', function () use ($ip, $failed_attempts, $max_attempts, $lockout_duration) {
            $failed_attempts = $failed_attempts ? $failed_attempts + 1 : 1;
            set_transient("login_attempts_$ip", $failed_attempts, $lockout_duration);
        });
    }
    add_action('init', 'pw_limit_login_attempts');
}

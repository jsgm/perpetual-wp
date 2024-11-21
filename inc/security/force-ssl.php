<?php
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

if(!defined("FORCE_SSL_ADMIN")){
    // Forces SSL on the admin panel
    define("FORCE_SSL_ADMIN", TRUE);

    if(isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && strpos($_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== FALSE){
        $_SERVER['HTTPS'] = 'on';
    }
}

if(!defined("FORCE_SSL_LOGIN")){
    // Forces SSL on 'wp-login.php'
    define("FORCE_SSL_LOGIN", true);
}

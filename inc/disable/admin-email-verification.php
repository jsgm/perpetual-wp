<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

// Disable admin email verifications. For more details, check here: https://make.wordpress.org/core/2019/10/17/wordpress-5-3-admin-email-verification-screen/
add_filter( 'admin_email_check_interval', '__return_false' );

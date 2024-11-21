<?php
/*
Plugin Name: PerpetualWP
Plugin URI: https://github.com/jsgm/perpetual-wp
Description: 
Author: José Aguilera
Version: 1.0
Author URI: https://jsgm.dev
Update URI: false
*/
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

define("PW_NAME", "Perpetual WP");
define("PW_VERSION", "1.0");
define("PW_HOMEPAGE", "https://github.com/jsgm/perpetual-wp");
define("PW_PLUGIN_FILE", __FILE__);

if(!defined("PHP_INT_MIN")){
    // For WP versions < 5.5
    define('PHP_INT_MIN', ~PHP_INT_MAX);
}

if(!defined("FORCE_SSL_ADMIN")){
    // Forces SSL on admin panel.
    define("FORCE_SSL_ADMIN", TRUE);

    if(isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && strpos($_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== FALSE){
        $_SERVER['HTTPS'] = 'on';
    }
}

if(!defined('DISALLOW_FILE_EDIT')){
    // Disables embebbed file editor.
    define('DISALLOW_FILE_EDIT', TRUE);
}

if(!function_exists("pw_disable_module")){
    function pw_disable_module($module=""){
        // TO-DO.
    }
}
add_action("init", function(){
    load_plugin_textdomain('perpetual-wp', false, 'perpetual-wp/lang/' );
});

$modules = apply_filters("pw_modules", [
    "helpers",
    "activation-hook",
    "updater",

    // Functionalities that are completely disabled.
    // TO-DO: "disable/pingbacks"
    // TO-DO: "disable/password-strength"
    // TO-DO: "disable/application-passwords"
    // TO-DO: "disable/howdy-admin-bar"
    // TO-DO: "disable/adjacent-posts"
    "disable/hello-dolly",
    "disable/initial-content",
    "disable/admin-email-verification",
    "disable/capital-p-dangit",
    "disable/emojis",
    "disable/generators",
    "disable/oembed",
    "disable/post-by-email",
    "disable/post-revisions",
    "disable/privacy-tools",
    "disable/update-services",
    "disable/wp-sitemaps",
    "disable/xmlrpc",
    "disable/import-export",
    "disable/shortlinks",
    "disable/guess-permalinks",
    "disable/site-health",
    "disable/wpautop",
    "disable/wptexturize",

    // TO-DO: "features/avatars" (Remove Gravatar -Automattic owned company- and restore classic avatar upload)
    // TO-DO: "features/blank-favicon"
    // TO-DO: "features/email" (SMTP, Fully Disable emails)
    // TO-DO: "features/maintenance"
    // TO-DO: "features/auto-empty-spam-trash"
    "features/page-priorize",

    // Security enhancements.
    // TO-DO: "security/salt-shuffler"
    // TO-DO: "security/2fa"
    // TO-DO: "security/limit-login-attempts"
    // TO-DO: "security/logout-idle-users"

    // Revert to the classic editor and widgets interface.
    "classic/widgets",
    "classic/editor",

    // Changes or minor enhancements that do not necessarily remove WordPress features.
    // TO-DO: "improve/media"
    // TO-DO: "improve/woocommerce" (Deablot, Disable woocommerce logout confirmation)
    // TO-DO: "improve/missed-schedule"
    "improve/comments",
    "improve/environment",
    "improve/wp-admin",
    "improve/rest-api",
]);

if(is_array($modules) && !empty($modules)){
    foreach($modules as $file){
        $route = dirname(__FILE__)."/inc/$file.php";
        if(!file_exists($route)){
            wp_die(sprintf(__("The module '%s' does not exist.", "perpetual-wp"), $file));
        }
        require $route;
    }
}


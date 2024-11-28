<?php
/*
Plugin Name: PerpetualWP
Plugin URI: https://github.com/jsgm/perpetual-wp
Description: 
Author: José Aguilera
Version: 1.01
Author URI: https://jsgm.dev
Update URI: false
*/
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

define("PW_NAME", "Perpetual WP");
define("PW_VERSION", "1.01");
define("PW_HOMEPAGE", "https://github.com/jsgm/perpetual-wp");
define("PW_PLUGIN_FILE", __FILE__);

if(!defined("PHP_INT_MIN")){
    // For WP versions < 5.5
    define('PHP_INT_MIN', ~PHP_INT_MAX);
}

$modules = apply_filters("pw_modules", [
    "helpers",
    "languages",
    "activation-hook",
    "updater",

    // Functionalities that are completely disabled.
    // TO-DO: "disable/pingbacks"
    // TO-DO: "disable/password-strength"
    // TO-DO: "disable/application-passwords"
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
    "disable/wp-favicon",
    
    // TO-DO: "features/avatars" (Remove Gravatar -Automattic owned company- and restore classic avatar upload)
    // TO-DO: "features/email" (SMTP, Fully Disable emails)
    // "features/maintenance",
    "features/page-priorize",

    // Security enhancements.
    // TO-DO: "security/salt-shuffler"
    // TO-DO: "security/2fa"
    // TO-DO: "security/logout-idle-users"
    "security/login",
    "security/disallow-file-edit",
    "security/force-ssl",

    // Revert to the classic editor and widgets interface.
    "classic/widgets",
    "classic/editor",

    // Changes or minor enhancements that do not necessarily remove WordPress features.
    // TO-DO: "improve/woocommerce" (Deablot, Disable woocommerce logout confirmation)
    "improve/media",
    "improve/wp-admin",
    "improve/comments",
    "improve/environment",
    "improve/rest-api",
    "improve/heartbeat",
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

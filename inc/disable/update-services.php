<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    die;
}

add_action("init", function(){
    // Ensures that the "Ping-o-Matic!" URL, owned by Automattic, is completely removed from the database.
    if(get_option("ping_sites")){
        delete_option("ping_sites");
    }
});

add_filter('enable_update_services_configuration', '__return_false');

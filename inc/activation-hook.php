<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

register_activation_hook(__FILE__, function(){
    do_action("pw_activation_hook");
});

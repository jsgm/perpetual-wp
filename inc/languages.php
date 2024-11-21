<?php
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

add_action("init", function(){
    load_plugin_textdomain('perpetual-wp', false, 'perpetual-wp/lang/' );
});

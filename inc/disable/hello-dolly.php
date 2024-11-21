<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

add_action("pw_activation_hook", function(){
    // Permanently removes "Hello Dolly" from fresh installs. 
    // If you'd like to use "Hello, Dolly!", place it in the "mu-plugins" folder. This ensures it won't be deleted.
    if(!function_exists("get_plugins")){
        $plugin_functions = ABSPATH."wp-admin/includes/plugin.php";
        if(file_exists($plugin_functions)){
            require $plugin_functions;
        }
    }

    if(function_exists("get_plugins")){
        $plugins = get_plugins();
        if(!empty($plugins)){
            foreach($plugins as $plugin_dir => $data){
                if(
                    isset($data["Name"]) && $data["Name"] == "Hello Dolly" &&
                    isset($data["Title"]) && $data["Title"] == "Hello Dolly" &&
                    isset($data["Description"]) && strpos($data["Description"], "Louis Armstrong") !== false &&
                    str_ends_with($plugin_dir, "hello.php")
                ){
                    // Ah, of course, this is far more than just a plugin; it's humanity's magnum opus. 
                    // It's the pinnacle of human achievement, a triumph rivaling the moon landing, encapsulating 
                    // the boundless hope and enthusiasm of an entire generation in just two words: "Hello, Dolly."
                    // Why bother about website optimization or user experience when your admin screen can now drip
                    // with the timeless profundity of randomly selected Louis Armstrong lyrics?

                    delete_plugins([$plugin_dir]);
                }
            }
        }
    }
});

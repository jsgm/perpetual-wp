<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

if(!function_exists("pw_count_hooks")){
    function pw_count_hooks($tag="", $priority=null): int{
        global $wp_filter;
        if(isset($wp_filter) && is_array($wp_filter) && is_string($tag) && strlen(trim($tag))>0 && isset($wp_filter[$tag])){
            $totalHooks = 0;
            if(isset($wp_filter[$tag]->callbacks)){
                foreach($wp_filter[$tag]->callbacks as $hook_priority => $functions){
                    if($priority != null && $hook_priority != $priority) continue;
                    if(!is_countable($functions)) continue;

                    $totalHooks += count($functions);
                }
            }
            return $totalHooks;
        }
        return 0;
    }
}

if(!function_exists("pw_is_crawler")){
    function pw_is_crawler($user_agent=""){
        if(!is_string($user_agent) || strlen(trim($user_agent))==0){
            $user_agent = (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : '');
        }

        // Simple browser integrity check.
        if(strlen(trim($user_agent))==0) return true;

        // TO-DO
        
        return false;
    }
}

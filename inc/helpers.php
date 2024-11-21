<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

if(!function_exists("pw_count_hooks")){
    function pw_count_hooks($tag = false): int{
        global $wp_filter;
        if(isset($wp_filter) && is_array($wp_filter) && $tag && is_string($tag) && isset($wp_filter[$tag])){
            $totalHooks = 0;
            if(isset($wp_filter[$tag]->callbacks)){
                foreach($wp_filter[$tag]->callbacks as $priority => $functions){
                    if(!is_countable($functions)) continue;

                    $totalHooks += count($functions);
                }
            }
            return $totalHooks;
        }
        return 0;
    }
}

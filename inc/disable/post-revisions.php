<?php
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

if(!function_exists("pw_revisions_enabled")){
    function pw_revisions_enabled(){
        $post_types = apply_filters('pw_revisions_post_type', get_option("pw_revisions_enabled") ? get_post_types() : []);
        if(!empty($post_types)){
            foreach($post_types as $post_type){
                remove_post_type_support( $post_type, 'revisions' );
            }
        }
        return $post_types;
    }
}

add_action('admin_init', function(){
    add_settings_field(
        'pw_post_revisions_label', 
        __('Post Revisions', 'perpetual-wp'),
        'pw_revisions',
        'writing'
    );
});

if(!function_exists("pw_revisions")){
    function pw_revisions(){
        echo '<fieldset>
            <label for="enable_post_revisions">
                <input name="pw_enable_post_revisions" type="checkbox" id="enable_post_revisions" value="0">
                '.__("Enable").'
            </label>
            <p class="description">'.__("Revisions are disabled by default for all post types.", "perpetual-wp").'</p>
        </fieldset>';
    }
}

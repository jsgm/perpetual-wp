<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

add_filter('request', function(array $query_vars){
    if(is_admin()) return $query_vars;
    
    if(isset($query_vars["category_name"]) && (!isset($query_vars["name"]) || (isset($query_vars["name"]) && strlen($query_vars["name"])==0))){
        $pagename = $query_vars["category_name"];
        $page = get_page_by_path( $pagename , OBJECT );
        if(isset($page)){
            $query_vars = array('pagename' => "$pagename");
        }
    }

    return $query_vars;
});

add_action('load-page.php', function(){
    add_action("edit_form_top", function(){
        $page_id = (isset($_GET["post"]) && is_numeric($_GET["post"]) ? (int)$_GET["post"] : 0);
        $page_slug = ($page_id>0 ? get_post_field('post_name', $page_id) : null);
        if($page_id>0 && $page_slug != null){
            $category = get_category_by_slug($page_slug);
            if($category && isset($category->term_id)){

                $edit_link = get_admin_url();
                wp_admin_notice(
                    sprintf(__('This page replaces the <a href="%s">%s</a> category, which has the same slug.'), esc_attr(get_category_link($category->term_id)), esc_html($category->name)),
                    array(
                        'type'               => 'info',
                        'dismissible'        => false,
                        'id'                 => 'pw-page-priorize'
                    )
                );
            }
		}
	});
}, PHP_INT_MAX);


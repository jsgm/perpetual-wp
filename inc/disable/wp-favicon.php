<?php
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

add_action('wp', function(){
	// Prevents the default 'images/w-logo-blue.png' from being shown. Restores the default behavior displaying a 404 page when /favicon.ico is hit.
	if(is_favicon() && !has_site_icon()){
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
	}
});

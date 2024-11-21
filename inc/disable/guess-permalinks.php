<?php
if(!defined('ABSPATH')){
	http_response_code(403);
	exit;
}

// WordPress attempts to automatically guess and redirect to a potential URL when a 404 error occurs, often resulting in unnecessary 301 redirects.
add_filter( 'do_redirect_guess_404_permalink', '__return_false' );

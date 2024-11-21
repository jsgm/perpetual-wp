<?php
if(!defined("ABSPATH")){
	http_response_code(403);
	die;
}

if(!defined('DISALLOW_FILE_EDIT')){
    // Disables the built-in WordPress file editor for themes and plugins in the admin dashboard for enhanced security.
    define('DISALLOW_FILE_EDIT', TRUE);
}
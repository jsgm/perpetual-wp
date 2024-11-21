<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    die;
}

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

add_action('after_setup_theme', function(){
    remove_theme_support( 'widgets-block-editor' );	
});

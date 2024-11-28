<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    die;
}

add_filter('use_block_editor_for_post','__return_false');

remove_action( 'admin_menu', 'gutenberg_menu' );
remove_action( 'admin_init', 'gutenberg_redirect_demo' );

// Remove each of the block registration actions.
remove_action('init', '_register_block_bindings_pattern_overrides_source');
remove_action('init', '_register_block_bindings_post_meta_source');
remove_action('init', 'register_block_core_legacy_widget');
remove_action('init', 'register_block_core_widget_group');
remove_action('init', 'register_block_core_archives');
remove_action('init', 'register_block_core_avatar');
remove_action('init', 'register_block_core_block');
remove_action('init', 'register_block_core_button');
remove_action('init', 'register_block_core_calendar');
remove_action('init', 'register_block_core_categories');
remove_action('init', 'register_block_core_comment_author_name');
remove_action('init', 'register_block_core_comment_content');
remove_action('init', 'register_block_core_comment_date');
remove_action('init', 'register_block_core_comment_edit_link');
remove_action('init', 'register_block_core_comment_reply_link');
remove_action('init', 'register_block_core_comment_template');
remove_action('init', 'register_block_core_comments');
remove_action('init', 'register_block_core_comments_pagination');
remove_action('init', 'register_block_core_comments_pagination_next');
remove_action('init', 'register_block_core_comments_pagination_numbers');
remove_action('init', 'register_block_core_comments_pagination_previous');
remove_action('init', 'register_block_core_comments_title');
remove_action('init', 'register_block_core_cover');
remove_action('init', 'register_block_core_file');
remove_action('init', 'register_block_core_footnotes');
remove_action('init', 'register_block_core_gallery');
remove_action('init', 'register_block_core_heading');
remove_action('init', 'register_block_core_home_link');
remove_action('init', 'register_block_core_image');
remove_action('init', 'register_block_core_latest_comments');
remove_action('init', 'register_block_core_latest_posts');
remove_action('init', 'register_block_core_list');
remove_action('init', 'register_block_core_loginout');
remove_action('init', 'register_block_core_media_text');
remove_action('init', 'register_block_core_navigation');
remove_action('init', 'register_block_core_navigation_link');
remove_action('init', 'register_block_core_navigation_submenu');
remove_action('init', 'register_block_core_page_list');
remove_action('init', 'register_block_core_page_list_item');
remove_action('init', 'register_block_core_pattern');
remove_action('init', 'register_block_core_post_author');
remove_action('init', 'register_block_core_post_author_biography');
remove_action('init', 'register_block_core_post_author_name');
remove_action('init', 'register_block_core_post_comments_form');
remove_action('init', 'register_block_core_post_content');
remove_action('init', 'register_block_core_post_date');
remove_action('init', 'register_block_core_post_excerpt');
remove_action('init', 'register_block_core_post_featured_image');
remove_action('init', 'register_block_core_post_navigation_link');
remove_action('init', 'register_block_core_post_template');
remove_action('init', 'register_block_core_post_terms');
remove_action('init', 'register_block_core_post_title');
remove_action('init', 'register_block_core_query');
remove_action('init', 'register_block_core_query_no_results');
remove_action('init', 'register_block_core_query_pagination');
remove_action('init', 'register_block_core_query_pagination_next');
remove_action('init', 'register_block_core_query_pagination_numbers');
remove_action('init', 'register_block_core_query_pagination_previous');
remove_action('init', 'register_block_core_query_title');
remove_action('init', 'register_block_core_read_more');
remove_action('init', 'register_block_core_rss');
remove_action('init', 'register_block_core_search');
remove_action('init', 'register_block_core_shortcode');
remove_action('init', 'register_block_core_site_logo');
remove_action('init', 'register_block_core_site_tagline');
remove_action('init', 'register_block_core_site_title');
remove_action('init', 'register_block_core_social_link');
remove_action('init', 'register_block_core_tag_cloud');
remove_action('init', 'register_block_core_template_part');
remove_action('init', 'register_block_core_term_description');
remove_action('init', 'register_core_block_types_from_metadata');
remove_action('init', '_register_theme_block_patterns');
remove_action('init', 'register_legacy_post_comments_block', 21);
remove_action('init', 'register_core_block_style_handles', 9);
remove_action('init', 'wp_register_core_block_metadata_collection', 9);
remove_action('init', '_register_core_block_patterns_and_categories');
remove_action('init', 'register_block_core_footnotes_post_meta', 20);
remove_action( 'plugins_loaded', 'wp_initialize_theme_preview_hooks', 1 );
remove_action( 'wp_default_scripts', 'wp_default_script_modules' );

// Gutenberg 5.3+
remove_action( 'wp_enqueue_scripts', 'gutenberg_register_scripts_and_styles' );
remove_action( 'admin_enqueue_scripts', 'gutenberg_register_scripts_and_styles' );
remove_action( 'admin_notices', 'gutenberg_wordpress_version_notice' );
remove_action( 'rest_api_init', 'gutenberg_register_rest_widget_updater_routes' );
remove_action( 'admin_print_styles', 'gutenberg_block_editor_admin_print_styles' );
remove_action( 'admin_print_scripts', 'gutenberg_block_editor_admin_print_scripts' );
remove_action( 'admin_print_footer_scripts', 'gutenberg_block_editor_admin_print_footer_scripts' );
remove_action( 'admin_footer', 'gutenberg_block_editor_admin_footer' );
remove_action( 'admin_enqueue_scripts', 'gutenberg_widgets_init' );
remove_action( 'admin_notices', 'gutenberg_build_files_notice' );

remove_filter( 'load_script_translation_file', 'gutenberg_override_translation_file' );
remove_filter( 'block_editor_settings', 'gutenberg_extend_block_editor_styles' );
remove_filter( 'default_content', 'gutenberg_default_demo_content' );
remove_filter( 'default_title', 'gutenberg_default_demo_title' );
remove_filter( 'block_editor_settings', 'gutenberg_legacy_widget_settings' );
remove_filter( 'rest_request_after_callbacks', 'gutenberg_filter_oembed_result' );

// Previously used, compat for older Gutenberg versions.
remove_filter( 'wp_refresh_nonces', 'gutenberg_add_rest_nonce_to_heartbeat_response_headers' );
remove_filter( 'get_edit_post_link', 'gutenberg_revisions_link_to_editor' );
remove_filter( 'wp_prepare_revision_for_js', 'gutenberg_revisions_restore' );

remove_action( 'rest_api_init', 'gutenberg_register_rest_routes' );
remove_action( 'rest_api_init', 'gutenberg_add_taxonomy_visibility_field' );
remove_filter( 'registered_post_type', 'gutenberg_register_post_prepare_functions' );

remove_action( 'do_meta_boxes', 'gutenberg_meta_box_save' );
remove_action( 'submitpost_box', 'gutenberg_intercept_meta_box_render' );
remove_action( 'submitpage_box', 'gutenberg_intercept_meta_box_render' );
remove_action( 'edit_page_form', 'gutenberg_intercept_meta_box_render' );
remove_action( 'edit_form_advanced', 'gutenberg_intercept_meta_box_render' );
remove_filter( 'redirect_post_location', 'gutenberg_meta_box_save_redirect' );
remove_filter( 'filter_gutenberg_meta_boxes', 'gutenberg_filter_meta_boxes' );

remove_filter( 'body_class', 'gutenberg_add_responsive_body_class' );
remove_filter( 'admin_url', 'gutenberg_modify_add_new_button_url' ); // old
remove_action( 'admin_enqueue_scripts', 'gutenberg_check_if_classic_needs_warning_about_blocks' );
remove_filter( 'register_post_type_args', 'gutenberg_filter_post_type_labels' );

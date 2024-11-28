<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    die;
}

remove_action( 'init', array( 'WP_Block_Supports', 'init' ), 22 );

add_action('admin_enqueue_scripts', function() {
    remove_action( 'admin_print_styles', 'wp_print_font_faces', 50 );
    remove_action( 'admin_print_styles', 'wp_print_font_faces_from_style_variations', 50 ); 
});

add_action("init", function(){
    wp_register_style( 'perpetual-wp_css', plugins_url('/assets/css/pw.css', PW_PLUGIN_FILE), false, rand(1,2223232322).PW_VERSION);
});

add_action('admin_enqueue_scripts', function(){
    wp_enqueue_style('perpetual-wp_css');
});

add_action('wp_enqueue_scripts', function(){
    if(is_admin_bar_showing()){
        wp_enqueue_style('perpetual-wp_css');
    }
});

add_filter('admin_footer_text', function(){
    // Remove the admin footer text.
    return '';
});

add_action("admin_menu", function(){
    // Ensures that the WordPress version is removed from the footer text, 
    // even if the 'admin_footer_text' filter is manually modified by the user.
    remove_filter('update_footer', 'core_update_footer');

    // Remove "Available Tools" from the Tools menu as it is redundant.
    remove_submenu_page('tools.php', 'tools.php');
}, PHP_INT_MAX);

add_action("admin_init", function(){
    // Completely removes the welcome panel on both fresh installs and updates.
    remove_action('welcome_panel', 'wp_welcome_panel');

    // Do not include the profile in the "Users" menu, as it would be redundant since it is already available in the wpadminbar.
    remove_submenu_page('users.php', 'profile.php');
    remove_action( 'admin_init', 'default_password_nag_handler', 10);
remove_action( 'admin_notices', 'default_password_nag' );
});

add_action("admin_head", function(){
    // Remove contextual help tabs from the WordPress admin interface.
    $screen = get_current_screen();
    if($screen && $screen != null){
        $screen->remove_help_tabs();
    }
});

// Disable color schemes, restricting users to the default color scheme only.
remove_action('admin_init', 'register_admin_color_schemes', 1);
remove_action('admin_head', 'wp_color_scheme_settings');
remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
add_action("admin_init", function(){
    global $_wp_admin_css_colors;
    if(isset($_wp_admin_css_colors["fresh"])){
        $_wp_admin_css_colors = ["fresh" => $_wp_admin_css_colors["fresh"]];
    }
});

// Remove the 'tools.php' page if it contains no options or functionality.
if(is_admin()){
    add_action( 'admin_menu', function(){
        global $submenu;

        if(
            !current_user_can("import") && 
            pw_count_hooks("tool_box") <= 0 && 
            (!isset($submenu["tools.php"]) || (isset($submenu["tools.php"]) && empty($submenu["tools.php"])))
        ){
            remove_menu_page('tools.php');
            add_action('load-tools.php', 'pw_admin_redirect');
        }
    }, PHP_INT_MAX);
}

// Admin will be redirected to the main admin page.
add_action('load-about.php', 'pw_admin_redirect');
add_action('load-credits.php', 'pw_admin_redirect');
add_action('load-privacy.php', 'pw_admin_redirect');
add_action('load-contribute.php', 'pw_admin_redirect');
add_action('load-freedoms.php', 'pw_admin_redirect');

if(!function_exists("pw_admin_redirect")){
    function pw_admin_redirect(){
        wp_redirect(get_admin_url(), 302, PW_NAME);
        die;
    }
}

// Avoid displaying the language selection dropdown on the login screen.
add_filter("login_display_language_dropdown", "__return_false");

add_action( 'admin_bar_menu', function(\WP_Admin_Bar $admin_bar){
    // Simplify account button.
    $profile_url = false;
	$current_user = wp_get_current_user();
	$user_id      = get_current_user_id();
    if ( current_user_can( 'read' ) ) {
		$profile_url = get_edit_profile_url( $user_id );
	} elseif ( is_multisite() ) {
		$profile_url = get_dashboard_url( $user_id, 'profile.php' );
	}

    $admin_bar->add_node(
		array(
			'id'     => 'pw-my-account',
			'parent' => 'top-secondary',
			'title'  => $current_user->display_name." ".'<span class="ab-icon dashicons-admin-users"></span>',
			'href'   => false,
			'meta'   => array(
                'class' => 'ab-item',
				'menu_title' => __("This is you!", "perpetual-wp"),
				'tabindex'   => 0
			),
		)
	);
    $admin_bar->add_group(
		array(
			'parent' => 'pw-my-account',
			'id'     => 'pw-user-actions',
		)
	);

	$admin_bar->add_node(
		array(
			'parent' => 'pw-user-actions',
			'id'     => 'pw-user-info',
			'title'  => __("Profile"),
			'href'   => $profile_url
		)
	);

	$admin_bar->add_node(
		array(
			'parent' => 'pw-user-actions',
			'id'     => 'pw-logout',
			'title'  => __( 'Log Out' ),
			'href'   => wp_logout_url(),
		)
	);

    // Remove the WordPress logo.
    $admin_bar->remove_menu('wp-logo');

    // Remove the search button from the "wpadminbar" element.
    $admin_bar->remove_node('search');

    // Remove "my account".
    $admin_bar->remove_node('my-account');

    // Remove "Visit Site" from the "site-name" menu as it is redundant.
    // TO-DO: Add title="" tag with __("Visit Site")
    $admin_bar->remove_node("view-site");
}, PHP_INT_MAX);

add_action('wp_dashboard_setup', function(){
    global $wp_meta_boxes;

    if(isset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'])){
        // Quick Draft.
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    }

    if(isset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'])){
        // Activity.
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    }

    if(isset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'])){
        // WordPress Events and News.
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    }
});

if(defined("PW_REPLACE_WP_BRANDING") && PW_REPLACE_WP_BRANDING == true){
    add_action('login_enqueue_scripts', function(){
        echo '<style type="text/css">
        #login h1 a {
            background-image: url(\''.plugin_dir_url(PW_PLUGIN_FILE).'assets/images/icon.svg\');
            background-repeat: no-repeat;
        }
        #login{
            padding: 0px !important;
            height: 100vh;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }
        </style>';
    }, PHP_INT_MAX);

    add_action( 'admin_bar_menu', function(\WP_Admin_Bar $admin_bar){
        $admin_bar->add_node([
            'id'    => 'pw-logo',
            'title' => '<span class="ab-icon pw-logo"></span>',
            'href'  => PW_HOMEPAGE,
            'meta'  => array(
                'target' => '_blank',
                'rel' => 'nofollow',
                'title' => __( 'About Perpetual WP', 'perpetual-wp' ),
            )
        ]);
    }, PHP_INT_MIN);
}

// Remove block-related hooks.
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

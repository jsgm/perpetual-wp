<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    die;
}

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

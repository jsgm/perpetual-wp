<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    die;
}

add_action("admin_init", function(){
    // Do not include the profile in the "Users" menu, as it would be redundant since it is already available in the wpadminbar.
    remove_submenu_page('tools.php', 'import.php');
    remove_submenu_page('tools.php', 'export.php');
});

add_action('load-import.php', 'pw_admin_redirect');
add_action('load-export.php', 'pw_admin_redirect');

add_action('init', function(){
    $role = get_role('administrator');
    $role->remove_cap("import");
    $role->remove_cap("export");
});

<?php
if(!defined("ABSPATH")){
    http_response_code(403);
    exit;
}

// TO-DO: Multisite support.

add_action("pw_activation_hook", function(){
    // Only delete the initial posts—"Hello world!", "Sample Page," and "Privacy Policy"—if they have been modified.
    foreach([
        [
            "post_id" => 1,
            "post_type" => "post",
            "post_title" => __( 'Hello world!'),
            "post_name" => sanitize_title(_x( 'hello-world', 'Default post slug' ))
        ],
        [
            "post_id" => 2,
            "post_type" => "page",
            "post_title" => __( 'Sample Page' ),
            "post_name" => __( 'sample-page' )
        ],
        [
            "post_id" => 3,
            "post_type" => "page",
            "post_title" => __( 'Privacy Policy' ),
            "post_name" => __( 'privacy-policy' ),
            "post_status" => "draft"
        ]
    ] as $p){
        $first_post = get_post($p["post_id"]);

        $canBeDeleted = (
            // The post must still be present.
            ($first_post && $first_post != null) &&

            // Ensure the post has not been previously modified by the user before proceeding.
            $first_post->post_date_gmt == $first_post->post_modified_gmt &&

            // Ensure the titles and slugs match their default values.
            $first_post->post_title == $p["post_title"] &&
            $first_post->post_name == $p["post_name"] &&
            $first_post->post_type == $p["post_type"] &&

            // The status must remain unchanged.
            (!isset($p["post_status"]) || (isset($first_post->post_status) && isset($p["post_status"]) && $first_post->post_status === $p["post_status"]))
        );

        if($canBeDeleted){
            wp_delete_post($first_post->ID, true);
        }
    }
});

<?php
// Menu icons for Custom Post Types
// https://developer.wordpress.org/resource/dashicons/
function add_menu_icons_styles(){
?>

<style>
#menu-posts-portrait .dashicons-admin-post:before {
    content: '\f126';
}
</style>

<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );


//Register Custom Post Types
add_action( 'init', 'register_cpt_portrait' );

function register_cpt_portrait() {

    $labels = array(
        'name' => _x( 'Portraits', 'portrait' ),
        'singular_name' => _x( 'Portrait', 'portrait' ),
        'add_new' => _x( 'Add New', 'portrait' ),
        'add_new_item' => _x( 'Add New Portrait', 'portrait' ),
        'edit_item' => _x( 'Edit Portrait', 'portrait' ),
        'new_item' => _x( 'New Portrait', 'portrait' ),
        'view_item' => _x( 'View Portrait', 'portrait' ),
        'search_items' => _x( 'Search Portraits', 'portrait' ),
        'not_found' => _x( 'No portraits found', 'portrait' ),
        'not_found_in_trash' => _x( 'No portraits found in Trash', 'portrait' ),
        'parent_item_colon' => _x( 'Parent Portrait:', 'portrait' ),
        'menu_name' => _x( 'Portraits', 'portrait' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,

        'supports' => array( 'title', 'editor', 'thumbnail' ),

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,

        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'portrait', $args );
}

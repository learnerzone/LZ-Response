<?php

/*
 /////////////////////////////////////////////////////////////////////////////////////////
 *
 * Qualifications Post Type
 *
 ////////////////////////////////////////////////////////////////////////////////////////
*/
// Register Custom Post Type
function qualification_init() {

  $labels = array(
    'name'                => _x( 'Qualifications', 'Post Type General Name', 'text_domain' ),
    'singular_name'       => _x( 'Qualification', 'Post Type Singular Name', 'text_domain' ),
    'menu_name'           => __( 'Qualifications', 'text_domain' ),
    'name_admin_bar'      => __( 'Qualification', 'text_domain' ),
    'parent_item_colon'   => __( 'Parent Qualification:', 'text_domain' ),
    'all_items'           => __( 'All Qualifications', 'text_domain' ),
    'add_new_item'        => __( 'Add New Qualification', 'text_domain' ),
    'add_new'             => __( 'Add New', 'text_domain' ),
    'new_item'            => __( 'New Qualification', 'text_domain' ),
    'edit_item'           => __( 'Edit Qualification', 'text_domain' ),
    'update_item'         => __( 'Update Qualification', 'text_domain' ),
    'view_item'           => __( 'View Qualification', 'text_domain' ),
    'search_items'        => __( 'Search Qualifications', 'text_domain' ),
    'not_found'           => __( 'Not found', 'text_domain' ),
    'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
  );
  $args = array(
    'label'               => __( 'qualifications', 'text_domain' ),
    'description'         => __( 'Qualification Description', 'text_domain' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields', ),
    'taxonomies'          => array( 'qualification_area' ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'menu_position'       => 5,
    'menu_icon'           => '',
    'show_in_admin_bar'   => true,
    'show_in_nav_menus'   => true,
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'capability_type'     => 'page',
  );
  register_post_type( 'qualifications', $args );

}

// Hook into the 'init' action
add_action( 'init', 'qualification_init', 0 );

/*
 /////////////////////////////////////////////////////////////////////////////////////////
 *
 * Qualification Area Tax
 *
 ////////////////////////////////////////////////////////////////////////////////////////
*/

// Register Custom Taxonomy
function qualification_taxonomy() {

  $labels = array(
    'name'                       => _x( 'Qualification Areas', 'Taxonomy General Name', 'text_domain' ),
    'singular_name'              => _x( 'Qualification Area', 'Taxonomy Singular Name', 'text_domain' ),
    'menu_name'                  => __( 'Qualification Area', 'text_domain' ),
    'all_items'                  => __( 'All Qualification Areas', 'text_domain' ),
    'parent_item'                => __( 'Parent Qualification Area', 'text_domain' ),
    'parent_item_colon'          => __( 'Parent Qualification Area:', 'text_domain' ),
    'new_item_name'              => __( 'New Qualification Area Name', 'text_domain' ),
    'add_new_item'               => __( 'Add New Qualification Area', 'text_domain' ),
    'edit_item'                  => __( 'Edit Qualification Area', 'text_domain' ),
    'update_item'                => __( 'Update Qualification Area', 'text_domain' ),
    'view_item'                  => __( 'View Qualification Area', 'text_domain' ),
    'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
    'add_or_remove_items'        => __( 'Add or remove Qualification Area', 'text_domain' ),
    'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
    'popular_items'              => __( 'Popular Qualification Area', 'text_domain' ),
    'search_items'               => __( 'Search Qualification Area', 'text_domain' ),
    'not_found'                  => __( 'Not Found', 'text_domain' ),
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
  );
  register_taxonomy( 'qualification_area', array( 'qualifications' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'qualification_taxonomy', 0 );

function add_qualifications_icons_styles(){
?>
 
<style>
#adminmenu .menu-icon-units div.wp-menu-image:before {
content: "\f118";
}
</style>
 
<?php
}
add_action( 'admin_head', 'add_unit_icons_styles' );

?>
<?php

/*
 /////////////////////////////////////////////////////////////////////////////////////////
 *
 * Units Post Type
 *
 ////////////////////////////////////////////////////////////////////////////////////////
*/


// The register_post_type() function is not to be used before the 'init'.
add_action( 'init', 'unit_init' );

/* Here's how to create your customized labels */
function unit_init() {
	$labels = array(
		'name' => _x( 'Units', 'post type general name' ), // Tip: _x('') is used for localization
		'singular_name' => _x( 'Unit', 'post type singular name' ),
		'add_new' => _x( 'Add New', 'unit' ),
		'add_new_item' => __( 'Add New Unit' ),
		'edit_item' => __( 'Edit Unit' ),
		'new_item' => __( 'New Unit' ),
		'view_item' => __( 'View Unit' ),
		'search_items' => __( 'Search Units' ),
		'not_found' =>  __( 'No units found' ),
		'not_found_in_trash' => __( 'No units found in Trash' ),
		'parent_item_colon' => ''
	);

	// Create an array for the $args
	$args = array( 'labels' => $labels, /* NOTICE: the $labels variable is used here... */
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 5,
		'menu_icon' => '',
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
	); 

	register_post_type( 'units', $args ); /* Register it and move on */
}



// hook into the init action and call create_book_taxonomies() when it fires
add_action( 'init', 'create_unit_taxonomies', 0 );

// create two taxonomies, genres and writers for the post type "book"
function create_unit_taxonomies() {

	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name' => _x( 'Unit Area', 'taxonomy general name' ),
		'singular_name' => _x( 'Unit Area', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Unit Areas' ),
		'all_items' => __( 'All Unit Areas' ),
		'parent_item' => __( 'Parent Unit Area' ),
		'parent_item_colon' => __( 'Parent Unit Area:' ),
		'edit_item' => __( 'Edit Unit Area' ),
		'update_item' => __( 'Update Unit Area' ),
		'add_new_item' => __( 'Add New Unit Area' ),
		'new_item_name' => __( 'New Unit Area' ),
	); 	

	register_taxonomy( 'unit_area', array( 'units' ), array(
		'hierarchical' => true,
		'labels' => $labels, // NOTICE: Here is where the $labels variable is used 
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'unit_area' ),
	));
	
} // End of create_book_taxonomies() function.

add_action( 'save_post' );

  // now we can actually save the data
  $allowed = array(
    'a' => array( // on allow a tags
      'href' => array() // and those anchors can only have href attribute
    )
  );

function add_unit_icons_styles(){
?>
 
<style>
#adminmenu .menu-icon-units div.wp-menu-image:before {
content: "\f119";
}
</style>
 
<?php
}
add_action( 'admin_head', 'add_unit_icons_styles' );

?>
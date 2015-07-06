<?php
/**
 * LearnerZone functions and definitions
 * @package WordPress
 * @subpackage LearnerZone 
 * @since Diesel 1.0
*/
 
/*
 /////////////////////////////////////////////////////////////////////////////////////////
 *
 * Sessions Post Type
 *
 ////////////////////////////////////////////////////////////////////////////////////////
*/


// The register_post_type() function is not to be used before the 'init'.
add_action( 'init', 'session_init' );

/* Here's how to create your customized labels */
function session_init() {
	$labels = array(
		'name' => _x( 'Sessions', 'post type general name' ), // Tip: _x('') is used for localization
		'singular_name' => _x( 'Session', 'post type singular name' ),
		'add_new' => _x( 'Add New', 'session' ),
		'add_new_item' => __( 'Add New Session' ),
		'edit_item' => __( 'Edit Session' ),
		'new_item' => __( 'New Session' ),
		'view_item' => __( 'View Session' ),
		'search_items' => __( 'Search Sessions' ),
		'not_found' =>  __( 'No sessions found' ),
		'not_found_in_trash' => __( 'No sessions found in Trash' ),
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
		'menu_position' => null,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
		'taxonomies' => array('post_tag') // this is IMPORTANT
	); 

	register_post_type( 'sessions', $args ); /* Register it and move on */
}


// hook into the init action and call create_book_taxonomies() when it fires
add_action( 'init', 'create_session_taxonomies', 0 );

// create two taxonomies, genres and writers for the post type "book"
function create_session_taxonomies() {

	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name' => _x( 'Session Area', 'taxonomy general name' ),
		'singular_name' => _x( 'Session Area', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Session Areas' ),
		'all_items' => __( 'All Session Areas' ),
		'parent_item' => __( 'Parent Session Area' ),
		'parent_item_colon' => __( 'Parent Session Area:' ),
		'edit_item' => __( 'Edit Session Area' ),
		'update_item' => __( 'Update Session Area' ),
		'add_new_item' => __( 'Add New Session Area' ),
		'new_item_name' => __( 'New Session Area' ),
	); 	

	register_taxonomy( 'session_area', array( 'sessions' ), array(
		'hierarchical' => true,
		'labels' => $labels, // NOTICE: Here is where the $labels variable is used 
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'session_area' ),
	));
	
} // End of create_book_taxonomies() function.




/* Add Custom meta boxes */

add_action( 'add_meta_boxes', 'session_meta_box_add' );  

function session_meta_box_add()  
{  
	add_meta_box( 'session-meta-box-id', 'Session Information', 'session_meta_box_cb', 'sessions', 'normal', 'high' );
}

function session_meta_box_cb( $post )  
{  
	$values = get_post_custom( $post->ID );  
	$session_trainer = isset( $values['session-trainer'] ) ? esc_attr( $values['session-trainer'][0] ) : '';
	$session_number = isset( $values['session-number'] ) ? esc_attr( $values['session-number'][0] ) : '';
	$session_length = isset( $values['session-length'] ) ? esc_attr( $values['session-length'][0] ) : '';
	$session_description = isset( $values['session-description'] ) ? esc_attr( $values['session-description'][0] ) : '';
	$session_download = isset( $values['session-download'] ) ? esc_attr( $values['session-download'][0] ) : '';
	
	// We'll use this nonce field later on when saving.  
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	
	// Start the table	
	echo '<table id="list-table"><tbody class="list:meta" id="the-list">';
	
	// Trainer
	echo '<tr><th class="left" width="15%" style="text-align: left"><label for="session-trainer">Trainer</label></th> <td><input type="text" name="session-trainer" id="session-trainer" size="50" value="' . $session_trainer . '" /></td></tr>';
	
	// Session Number
	echo '<tr><th class="left" style="text-align: left"><label for="session-number">Session Number</label></th> <td><input type="text" name="session-number" id="session-number" value="' . $session_number . '" /></td></tr>';
	
	// Length
	echo '<tr><th class="left" style="text-align: left"><label for="session-length">Length (hours)</label></th> <td><input type="text" name="session-length" id="session-length" value="' . $session_length . '" /></td></tr>';
	
	// Description
	echo '<tr><th class="left" style="text-align: left; vertical-align: top; padding-top: 10px;"><label for="session-description">Description</label></th> <td><textarea rows="5" cols="50" name="session-description" id="session-description" />' . $session_description .'</textarea></td></tr>'; 
	
	// Session Downloads
	echo '<tr><th class="left" style="text-align: left; vertical-align: top; padding-top: 10px;"><label for="session-download">Session Download (url)</label></th> <td><input name="session-download" id="session-download" size="70" value="' . $session_download . '" /></td></tr>'; 
	
	// Finish the table
	echo "</tbody></table>";
}

add_action( 'save_post', 'session_meta_box_save' );

function session_meta_box_save( $post_id )
{
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;

	// now we can actually save the data
	$allowed = array(
		'a' => array( // on allow a tags
			'href' => array() // and those anchors can only have href attribute
		)
	);

	// Make sure your data is set before trying to save it
	if( isset( $_POST['session-trainer'] ) )
		update_post_meta( $post_id, 'session-trainer', wp_kses( $_POST['session-trainer'], $allowed ) );
		
	if( isset( $_POST['session-number'] ) )
		update_post_meta( $post_id, 'session-number', wp_kses( $_POST['session-number'], $allowed ) );
	
	if( isset( $_POST['session-length'] ) )
		update_post_meta( $post_id, 'session-length', wp_kses( $_POST['session-length'], $allowed ) );
	
	if( isset( $_POST['session-description'] ) )
		update_post_meta( $post_id, 'session-description', wp_kses( $_POST['session-description'], $allowed ) );
		
	if( isset( $_POST['session-download'] ) )
		update_post_meta( $post_id, 'session-download', wp_kses( $_POST['session-download'], $allowed ) );
}


/*
 * Changing the session columns
*/

add_filter( "manage_sessions_posts_columns", "change_columns" );
add_action( "manage_posts_custom_column", "custom_columns", 10, 2 );
add_filter( "manage_edit-sessions_sortable_columns", "sortable_columns" );

// Make these columns sortable
function sortable_columns() {
	return array(
		'title'      => 'title',
		'session_area' => 'session_area',
		'author'     => 'author'
	);
}


/*
 /////////////////////////////////////////////////////////////////////////////////////////
 *
 * Activities Post Type
 *
 ////////////////////////////////////////////////////////////////////////////////////////
*/


// The register_post_type() function is not to be used before the 'init'.
add_action( 'init', 'activity_init' );

/* Here's how to create your customized labels */
function activity_init() {
	$labels = array(
		'name' => _x( 'Activities', 'post type general name' ), // Tip: _x('') is used for localization
		'singular_name' => _x( 'Activity', 'post type singular name' ),
		'add_new' => _x( 'Add New', 'activity' ),
		'add_new_item' => __( 'Add New Activity' ),
		'edit_item' => __( 'Edit Activity' ),
		'new_item' => __( 'New Activity' ),
		'view_item' => __( 'View Activity' ),
		'search_items' => __( 'Search Activities' ),
		'not_found' =>  __( 'No activities found' ),
		'not_found_in_trash' => __( 'No activities found in Trash' ),
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
		'menu_position' => null,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
		'taxonomies' => array('post_tag') // this is IMPORTANT
	); 

	register_post_type( 'activities', $args ); /* Register it and move on */
}



// hook into the init action and call create_book_taxonomies() when it fires
add_action( 'init', 'create_activity_taxonomies', 0 );

// create two taxonomies, genres and writers for the post type "book"
function create_activity_taxonomies() {

	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name' => _x( 'Activity Area', 'taxonomy general name' ),
		'singular_name' => _x( 'Activity Area', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Activity Areas' ),
		'all_items' => __( 'All Activity Areas' ),
		'parent_item' => __( 'Parent Activity Area' ),
		'parent_item_colon' => __( 'Parent Activity Area:' ),
		'edit_item' => __( 'Edit Activity Area' ),
		'update_item' => __( 'Update Activity Area' ),
		'add_new_item' => __( 'Add New Activity Area' ),
		'new_item_name' => __( 'New Activity Area' ),
	); 	

	register_taxonomy( 'activity_area', array( 'activities' ), array(
		'hierarchical' => true,
		'labels' => $labels, // NOTICE: Here is where the $labels variable is used 
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'activity_area' ),
	));
	
} // End of create_book_taxonomies() function.



/* Add Custom meta boxes */

add_action( 'add_meta_boxes', 'activity_meta_box_add' );  

function activity_meta_box_add()  
{  
	add_meta_box( 'activity-meta-box-id', 'Activity Information', 'activity_meta_box_cb', 'activities', 'normal', 'high' );
}

function activity_meta_box_cb( $post )  
{  
	$values = get_post_custom( $post->ID );  
	$activity_time = isset( $values['activity-time'] ) ? esc_attr( $values['activity-time'][0] ) : '';
	$activity_description = isset( $values['activity-description'] ) ? esc_attr( $values['activity-description'][0] ) : '';
	$activity_download = isset( $values['activity-download'] ) ? esc_attr( $values['activity-download'][0] ) : '';
	
	// We'll use this nonce field later on when saving.  
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	
	// Start the table	
	echo '<table id="list-table"><tbody class="list:meta" id="the-list">';
	
	// Time
	echo '<tr><th class="left" style="text-align: left"><label for="activity-time">Activity Time</label></th> <td><input type="text" name="activity-time" id="activity-time" value="' . $activity_time . '" /></td></tr>';
	
	// Description
	echo '<tr><th class="left" style="text-align: left; vertical-align: top; padding-top: 10px;"><label for="activity-description">Description</label></th> <td><textarea rows="5" cols="50" name="activity-description" id="activity-description" />' . $activity_description .'</textarea></td></tr>'; 
	
	// Activity Downloads
	echo '<tr><th class="left" style="text-align: left; vertical-align: top; padding-top: 10px;"><label for="activity-download">Activity Download (url)</label></th> <td><input name="activity-download" id="activity-download" size="70" value="' . $activity_download . '" /></td></tr>'; 
	
	// Finish the table
	echo "</tbody></table>";
}

add_action( 'save_post', 'activity_meta_box_save' );

function activity_meta_box_save( $post_id )
{
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;

	// now we can actually save the data
	$allowed = array(
		'a' => array( // on allow a tags
			'href' => array() // and those anchors can only have href attribute
		)
	);

	// Make sure your data is set before trying to save it
	if( isset( $_POST['activity-time'] ) )
		update_post_meta( $post_id, 'activity-time', wp_kses( $_POST['activity-time'], $allowed ) );
		
	if( isset( $_POST['activity-description'] ) )
		update_post_meta( $post_id, 'activity-description', wp_kses( $_POST['activity-description'], $allowed ) );
		
	if( isset( $_POST['activity-download'] ) )
		update_post_meta( $post_id, 'activity-download', wp_kses( $_POST['activity-download'], $allowed ) );
}



/*
 * Changing the activity columns
*/

add_filter( "manage_activities_posts_columns", "change_activity_columns" );
add_action( "manage_posts_custom_column", "custom_activity_columns", 10, 2 );
add_filter( "manage_edit-activities_sortable_columns", "sortable_activity_columns" );

// Change the columns for the edit CPT screen
function change_activity_columns( $cols ) {
	$cols = array(
		'cb'			=> '<input type="checkbox" />',
		'title'     	=> __( 'Activity',      'trans' ),
		'activity_area' => __( 'Activity Area', 'trans' ),
		'author'     	=> __( 'Author', 'trans' ),
	);
	
	return $cols;
}

function custom_activity_columns( $column, $post_id ) {
	switch ( $column ) {
		case "title":
			$title = get_post_meta( $post_id, 'title', true);
			echo '<a href="' . $title . '">' . $title. '</a>';
		break;
			case "activity_area":
			echo get_the_term_list($post->ID,'activity_area','',', ','');
		break;
			case "author":
			echo get_post_meta( $post_id, 'author', true);
		break;
	}
}

// Make these columns sortable
function sortable_activity_columns() {
	return array(
		'title'      	=> 'title',
		'activity_area' => 'activity_area',
		'author'     	=> 'author'
	);
}




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
		'menu_position' => null,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
		'taxonomies' => array('post_tag') // this is IMPORTANT
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



/* Add Custom meta boxes */

add_action( 'add_meta_boxes', 'unit_meta_box_add' );  

function unit_meta_box_add()  
{  
	add_meta_box( 'unit-meta-box-id', 'Unit Information', 'unit_meta_box_cb', 'units', 'normal', 'high' );
}

function unit_meta_box_cb( $post )  
{  
	$values = get_post_custom( $post->ID );
	$unit_level = isset( $values['unit-level'] ) ? esc_attr( $values['unit-level'][0] ) : '';
	$unit_number = isset( $values['unit-number'] ) ? esc_attr( $values['unit-number'][0] ) : '';
	$unit_value = isset( $values['unit-value'] ) ? esc_attr( $values['unit-value'][0] ) : '';
	$unit_info = isset( $values['unit-info'] ) ? esc_attr( $values['unit-info'][0] ) : '';
	$unit_download = isset( $values['unit-download'] ) ? esc_attr( $values['unit-download'][0] ) : '';
	
	// We'll use this nonce field later on when saving.  
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	
	// Start the table	
	echo '<table id="list-table"><tbody class="list:meta" id="the-list">';
	
	// Level
	echo '<tr><th class="left" style="text-align: left"><label for="unit-level">Unit Level</label></th> <td><input type="text" name="unit-level" id="unit-level" value="' . $unit_level . '" /></td></tr>';
	
	// Ref
	echo '<tr><th class="left" style="text-align: left"><label for="unit-number">Unit Number</label></th> <td><input type="text" name="unit-number" id="unit-number" value="' . $unit_number . '" /></td></tr>';
	
	// Value
	echo '<tr><th class="left" style="text-align: left; vertical-align: top; padding-top: 10px;"><label for="unit-value">Unit Value</label></th> <td><input type="text" name="unit-value" id="unit-value" value="' . $unit_value . '" /></td></tr>';
	
	// Info
	echo '<tr><th class="left" style="text-align: left; vertical-align: top; padding-top: 10px;"><label for="unit-info">Unit Info</label></th> <td><textarea rows="5" cols="50" name="unit-info" id="unit-value" />' . $unit_info .'</textarea></td></tr>'; 
	
	// Unit Downloads
	echo '<tr><th class="left" style="text-align: left; vertical-align: top; padding-top: 10px;"><label for="unit-download">Unit Download (url)</label></th> <td><input name="unit-download" id="unit-download" size="70" value="' . $unit_download . '" /></td></tr>'; 
	
	// Finish the table
	echo "</tbody></table>";
}

add_action( 'save_post', 'unit_meta_box_save' );

function unit_meta_box_save( $post_id )
{
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;

	// now we can actually save the data
	$allowed = array(
		'a' => array( // on allow a tags
			'href' => array() // and those anchors can only have href attribute
		)
	);

	// Make sure your data is set before trying to save it
	if( isset( $_POST['unit-level'] ) )
		update_post_meta( $post_id, 'unit-level', wp_kses( $_POST['unit-level'], $allowed ) );
	
	if( isset( $_POST['unit-number'] ) )
		update_post_meta( $post_id, 'unit-number', wp_kses( $_POST['unit-number'], $allowed ) );
		
	if( isset( $_POST['unit-value'] ) )
		update_post_meta( $post_id, 'unit-value', wp_kses( $_POST['unit-value'], $allowed ) );
		
	if( isset( $_POST['unit-info'] ) )
		update_post_meta( $post_id, 'unit-info', wp_kses( $_POST['unit-info'], $allowed ) );
		
	if( isset( $_POST['unit-download'] ) )
		update_post_meta( $post_id, 'unit-download', wp_kses( $_POST['unit-download'], $allowed ) );
}



/*
 * Changing the unit columns
*/

add_filter( "manage_units_posts_columns", "change_unit_columns" );
add_action( "manage_posts_custom_column", "custom_unit_columns", 10, 2 );
add_filter( "manage_edit-units_sortable_columns", "sortable_unit_columns" );

// Change the columns for the edit CPT screen
function change_unit_columns( $cols ) {
	$cols = array(
		'cb'			=> '<input type="checkbox" />',
		'title'     	=> __( 'Unit',      'trans' ),
		'unit_area' => __( 'Unit Area', 'trans' ),
		'author'     	=> __( 'Author', 'trans' ),
	);
	
	return $cols;
}

function custom_unit_columns( $column, $post_id ) {
	switch ( $column ) {
		case "title":
			$title = get_post_meta( $post_id, 'title', true);
			echo '<a href="' . $title . '">' . $title. '</a>';
		break;
			case "unit_area":
			echo get_the_term_list($post->ID,'unit_area','',', ','');
		break;
			case "author":
			echo get_post_meta( $post_id, 'author', true);
		break;
	}
}

// Make these columns sortable
function sortable_unit_columns() {
	return array(
		'title'      	=> 'title',
		'unit_area' => 'unit_area',
		'author'     	=> 'author'
	);
}
/*
 /////////////////////////////////////////////////////////////////////////////////////////
 *
 * quals Post Type
 *
 ////////////////////////////////////////////////////////////////////////////////////////
*/


// The register_post_type() function is not to be used before the 'init'.
add_action( 'init', 'qual_init' );

/* Here's how to create your customized labels */
function qual_init() {
	$labels = array(
		'name' => _x( 'quals', 'post type general name' ), // Tip: _x('') is used for localization
		'singular_name' => _x( 'qual', 'post type singular name' ),
		'add_new' => _x( 'Add New', 'qual' ),
		'add_new_item' => __( 'Add New qual' ),
		'edit_item' => __( 'Edit qual' ),
		'new_item' => __( 'New qual' ),
		'view_item' => __( 'View qual' ),
		'search_items' => __( 'Search quals' ),
		'not_found' =>  __( 'No quals found' ),
		'not_found_in_trash' => __( 'No quals found in Trash' ),
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
		'menu_position' => null,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
		'taxonomies' => array('post_tag') // this is IMPORTANT
	); 

	register_post_type( 'quals', $args ); /* Register it and move on */
}


// hook into the init action and call create_book_taxonomies() when it fires
add_action( 'init', 'create_qual_taxonomies', 0 );

// create two taxonomies, genres and writers for the post type "book"
function create_qual_taxonomies() {

	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name' => _x( 'qual Area', 'taxonomy general name' ),
		'singular_name' => _x( 'qual Area', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search qual Areas' ),
		'all_items' => __( 'All qual Areas' ),
		'parent_item' => __( 'Parent qual Area' ),
		'parent_item_colon' => __( 'Parent qual Area:' ),
		'edit_item' => __( 'Edit qual Area' ),
		'update_item' => __( 'Update qual Area' ),
		'add_new_item' => __( 'Add New qual Area' ),
		'new_item_name' => __( 'New qual Area' ),
	); 	

	register_taxonomy( 'qual_area', array( 'quals' ), array(
		'hierarchical' => true,
		'labels' => $labels, // NOTICE: Here is where the $labels variable is used 
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'qual_area' ),
	));
	
} // End of create_book_taxonomies() function.

add_action( 'save_post' );

	// now we can actually save the data
	$allowed = array(
		'a' => array( // on allow a tags
			'href' => array() // and those anchors can only have href attribute
		)
	);

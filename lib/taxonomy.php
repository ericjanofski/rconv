<?php
/**
 * @package WordPress
 * @subpackage appcms
 */
 
function appcms_custom_taxonomy_init() {
  
  $labels = array(
		'name'              => _x( 'Categories', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Categories', 'textdomain' ),
		'all_items'         => __( 'All Categories', 'textdomain' ),
		'parent_item'       => __( 'Parent Category', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Category:', 'textdomain' ),
		'edit_item'         => __( 'Edit Category', 'textdomain' ),
		'update_item'       => __( 'Update Category', 'textdomain' ),
		'add_new_item'      => __( 'Add New Category', 'textdomain' ),
		'new_item_name'     => __( 'New Category Name', 'textdomain' ),
		'menu_name'         => __( 'Categories', 'textdomain' ),
	);
	register_taxonomy(
		'pledge_category',
		'pledge',
		array(
			'labels' => $labels,
			'rewrite' => array( 'slug' => 'pledge-category' ),
			'hierarchical' => true,
		)
	);
	
}
add_action( 'init', 'appcms_custom_taxonomy_init' );
<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

// add custom taxonomy for attachments
function appcms_media_add_custom_media_taxonomies() {
	register_taxonomy('mediacategories', 'attachment', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Media Category', 'taxonomy general name' ),
			'singular_name' => _x( 'Media Category', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Media Categories' ),
			'all_items' => __( 'All Media Categories' ),
			'parent_item' => __( 'Parent Media Category' ),
			'parent_item_colon' => __( 'Parent Media Category:' ),
			'edit_item' => __( 'Edit Media Category' ),
			'update_item' => __( 'Update Media Category' ),
			'add_new_item' => __( 'Add New Media Category' ),
			'new_item_name' => __( 'New Media Category Name' ),
			'menu_name' => __( 'Media Categories' ),
			'show_admin_column' => true,
			'show_ui' => true,
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'mediacategories',
			'with_front' => false, 
			'hierarchical' => true, 
		),
	));
}
add_action( 'init', 'appcms_media_add_custom_media_taxonomies', 0 );

// add column to media library
add_filter( 'manage_taxonomies_for_attachment_columns', 'appcms_media_mediacategories_columns' );
function appcms_media_mediacategories_columns( $taxonomies ) {
  $taxonomies[] = 'mediacategories';
  return $taxonomies;
}
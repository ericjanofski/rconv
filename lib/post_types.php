<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

add_action( 'init', 'appcms_post_types_create' );
function appcms_post_types_create() {
	register_post_type( 'app_user',
		array(
			'labels' => array(
				'name' => __( 'Manage Users' ),
				'singular_name' => __( 'User' ),
        'add_new_item'       => __( 'Add New User' ),
        'edit_item'          => __( 'Edit User' ),
        'new_item'           => __( 'New User' ),
        'all_items'          => __( 'All Users' ),
        'view_item'          => __( 'View User' ),
      ),
		'public' => true,
		'has_archive' => false,
		'supports' => array('title'),
		'exclude_from_search' => false,
		)
	);
    register_post_type( 'pledge',
		array(
			'labels' => array(
				'name' => __( 'Pledges' ),
				'singular_name' => __( 'Pledge' ),
        'add_new_item'       => __( 'Add New Pledge' ),
        'edit_item'          => __( 'Edit Pledge' ),
        'new_item'           => __( 'New Pledge' ),
        'all_items'          => __( 'All Pledges' ),
        'view_item'          => __( 'View Pledge' ),
      ),
		'public' => true,
		'has_archive' => false,
		'supports' => array('title'),
		'exclude_from_search' => false,
		)
	);
    register_post_type( 'custom_pledge',
        array(
            'labels' => array(
                'name' => __( 'Custom Pledges' ),
                'singular_name' => __( 'Custom Pledge' ),
        'add_new_item'       => __( 'Add New Custom Pledge' ),
        'edit_item'          => __( 'Edit Custom Pledge' ),
        'new_item'           => __( 'New Custom Pledge' ),
        'all_items'          => __( 'All Custom Pledges' ),
        'view_item'          => __( 'View Custom Pledge' ),
    ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title'),
        'exclude_from_search' => false,
        )
    );
}
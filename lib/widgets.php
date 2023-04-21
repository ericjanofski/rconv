<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

/**
 * Register widgetized area and update sidebar with default widgets
 */
function appcms_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Sidebar', 'appcms' ),
		'id' => 'sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s" role="complementary">',
		'after_widget' => "</aside>",
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
}
add_action( 'init', 'appcms_widgets_init' );
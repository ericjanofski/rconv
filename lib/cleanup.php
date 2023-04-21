<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

/**
 * Remove code from the <head>
 */
remove_action('wp_head', 'rsd_link'); // Might be necessary if you or other people on this site use remote editors.
remove_action('wp_head', 'wlwmanifest_link'); // Might be necessary if you or other people on this site use Windows Live Writer.
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_filter( 'the_content', 'capital_P_dangit' ); // Get outta my Wordpress codez dangit!
remove_filter( 'the_title', 'capital_P_dangit' );
remove_filter( 'comment_text', 'capital_P_dangit' );
remove_action('wp_head', 'wp_generator');
function appcms_remove_version() {return '';}

add_filter('the_generator', 'appcms_remove_version');

function appcms_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'appcms_remove_recent_comments_style' );

/**
 * Remove meta boxes from Post and Page Screens
 */
function appcms_customize_meta_boxes() {
   /* These remove meta boxes from POSTS */
  //remove_post_type_support("post","excerpt"); //Remove Excerpt Support
  //remove_post_type_support("post","author"); //Remove Author Support
  //remove_post_type_support("post","revisions"); //Remove Revision Support
  //remove_post_type_support("post","comments"); //Remove Comments Support
  //remove_post_type_support("post","trackbacks"); //Remove trackbacks Support
  //remove_post_type_support("post","editor"); //Remove Editor Support
  //remove_post_type_support("post","custom-fields"); //Remove custom-fields Support
  //remove_post_type_support("post","title"); //Remove Title Support
  
  /* These remove meta boxes from PAGES */
  //remove_post_type_support("page","revisions"); //Remove Revision Support
  //remove_post_type_support("page","comments"); //Remove Comments Support
  //remove_post_type_support("page","author"); //Remove Author Support
  //remove_post_type_support("page","trackbacks"); //Remove trackbacks Support
  //remove_post_type_support("page","custom-fields"); //Remove custom-fields Support
  
}
//add_action('admin_init','appcms_customize_meta_boxes');

/**
 * Remove superfluous elements from the admin bar (uncomment as necessary)
 */
function appcms_remove_admin_bar_links() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
	//$wp_admin_bar->remove_menu('updates');	
	//$wp_admin_bar->remove_menu('my-account');
	//$wp_admin_bar->remove_menu('site-name');
	//$wp_admin_bar->remove_menu('my-sites');
	//$wp_admin_bar->remove_menu('get-shortlink');
	//$wp_admin_bar->remove_menu('edit');
	//$wp_admin_bar->remove_menu('new-content');
	$wp_admin_bar->remove_menu('comments');
	$wp_admin_bar->remove_menu('search');
}
//add_action('wp_before_admin_bar_render', 'appcms_remove_admin_bar_links');

/**
 *	Replace the default welcome 'Howdy' in the admin bar with something more professional.
 */
function appcms_admin_bar_replace_howdy($wp_admin_bar) {
	$account = $wp_admin_bar->get_node('my-account');
	$replace = str_replace('Howdy,', 'Welcome,', $account->title);            
	$wp_admin_bar->add_node(array('id' => 'my-account', 'title' => $replace));
}
add_filter('admin_bar_menu', 'appcms_admin_bar_replace_howdy', 25);


/*
 * Remove senseless dashboard widgets for non-admins. 
 */
function appcms_remove_dashboard_widgets() {
	global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // Plugins widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPress Blog widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // Other WordPress News widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // Right Now widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // Quick Press widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // Incoming Links widget
	//unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // Recent Drafts widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Recent Comments widget

	//wp_add_dashboard_widget('appcms_dashboard_widget', 'Recent Content', 'appcms_dashboard_content');

}
add_action('wp_dashboard_setup', 'appcms_remove_dashboard_widgets'); // Add action to hide dashboard widgets

/**
 *	Hide Menu Items in Admin
 */
function appcms_configure_dashboard_menu() {
	global $menu,$submenu;

	global $current_user;
		wp_get_current_user();

		// $menu and $submenu will return all menu and submenu list in admin panel
		
		//$menu[2] = ""; // Dashboard
		// $menu[5] = ""; // Posts
		$menu[15] = ""; // Links
		$menu[25] = ""; // Comments
		$menu[65] = ""; // Plugins

		unset($submenu['themes.php'][5]); // Themes
		unset($submenu['themes.php'][12]); // Editor

    remove_submenu_page('tools.php', 'tools.php');

}

function appcms_remove_tools() {
	remove_menu_page( 'tools.php' );
}

// For non-admins, add action to Hide Dashboard Widgets and Admin Menu Items you just set above
// Don't forget to comment out the admin check to see that changes :)
if (!current_user_can('manage_options')) {
	//add_action('admin_head', 'appcms_configure_dashboard_menu'); // Add action to hide admin menu items
	//add_action( 'admin_menu', 'appcms_remove_tools', 99);
}



function appcms_dashboard_content() {
	echo '<ul>';
	$args = array(
		'post_type' => 'any',
		'posts_per_page' => 20,
		'orderby' => 'modified',
	);
	$the_query = new WP_Query($args);
	while ( $the_query->have_posts() ) :
		$the_query->the_post();
		echo '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a><a class="alignright" title="edit" href="' . get_edit_post_link() . '">edit</a></li>';
	endwhile;
	wp_reset_postdata();
	echo '<ul>';
}
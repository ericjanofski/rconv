<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

/**
 * CUSTOM GLOBAL VARIABLES
 */
function appcms_global_vars() {

   global $appcms;
	
	// MOBILE DETECT
  require_once(get_theme_root() . '/appcms/includes/Mobile_Detect.php');
  $detect = new Mobile_Detect;
  
  $appcms = array(
  	'mobile' => $detect->isMobile(), // handheld, phone or tablet
  	'tablet' => $detect->isTablet(),
  	'phone' => ($detect->isMobile() && !$detect->isTablet()) ? true : false,
  );

}
add_action( 'parse_query', 'appcms_global_vars' );

/**
 * MOBILE DETECT BODY CLASSES
 */

function appcms_device_body_class($classes = ''){
  
  // add body classes
  if($GLOBALS['appcms']['mobile']) {
  	$classes[] =  'mobile'; 
  	if($GLOBALS['appcms']['tablet']) $classes[] =  'tablet';
  	if($GLOBALS['appcms']['phone']) $classes[] =  'phone';
  } else {
  	$classes[] =  'desktop'; 
  }
    
	return $classes;
}
add_action('body_class', 'appcms_device_body_class');


/**
 * WORDPRESS DEFAULTS
 *
 */
function appcms_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 */
	load_theme_textdomain( 'appcms', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	//add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
	
}
add_action( 'after_setup_theme', 'appcms_setup' );

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 */
function appcms_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'appcms_wp_title', 10, 2 );

// print nicely
function print_pre($print_this) {
  echo '<pre>';
  print_r($print_this);
  echo '</pre>';
}

function appcms_curl_request($request) {
  // create curl resource
  $ch = curl_init();
  
  // set url
  curl_setopt($ch, CURLOPT_URL, $request);
  
  //return the transfer as a string
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  
  // $output contains the output string
  $output = curl_exec($ch);
  
  return $output;
  
  // close curl resource to free up system resources
  curl_close($ch);
}
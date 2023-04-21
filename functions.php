<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

/**
 * Library includes of common functions 
 */
require_once('lib/admin_css.php');
require_once('lib/admin_js.php');
require_once('lib/api.php');
require_once('lib/appcms-email/appcms_email.php');
require_once('lib/init.php');
require_once('lib/cleanup.php');
//require_once('lib/admin_pages.php');
require_once('lib/export_csv.php');
require_once('lib/login.php');
require_once('lib/moderation.php');
require_once('lib/post_types.php');
require_once('lib/date_range_filter.php');
require_once('lib/taxonomy.php');
require_once('lib/taxonomy-filter.php');


// Header Imports
function appcms_add_theme_scripts() {
  
  wp_deregister_script('jquery');  
  wp_register_script('jquery', '/wp-includes/js/jquery/jquery.js', FALSE, '1.12.4', TRUE);    
  wp_enqueue_script('jquery');
  
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');     
  wp_enqueue_style( 'default', get_stylesheet_directory_uri() . '/assets/css/default.css',false,'1.0','all');  
  wp_enqueue_style( 'loaded', get_stylesheet_directory_uri() . '/assets/css/loaded-front-and-back.css',false,'1.0','all');  
  wp_enqueue_style( 'layout', get_stylesheet_directory_uri() . '/assets/css/layout.css',false,'1.0','all');  
  wp_enqueue_style( 'style', get_stylesheet_uri() );
  wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/assets/js/main.js', array ( 'jquery' ), '1.0', true);

}
add_action( 'wp_enqueue_scripts', 'appcms_add_theme_scripts', 100);

// OPTIONS PAGE
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page();
    acf_add_options_page(array(
        'page_title' 	=> 'Global Settings',
        'menu_title'	=> 'Global Settings',
        'menu_slug' 	=> 'global-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));	
}

// Setup the header menu, utility menu and footer menu
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'appcms' ),
));

function appcms_get_the_content_with_formatting($more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

// ADD MENU TO BACKEND
function appcms_admin_header() {
  echo '<nav id="access"><div class="container">';
  echo '<a href="#" class="menu-toggle"><i class="fa fa-bars" aria-hidden="true"></i></a>';
  wp_nav_menu();
  echo '</div></nav>';
  echo '<div class="container"><div id="logo"><img src="/wp-content/themes/appcms/images/logo.png" alt="' . esc_attr(get_bloginfo( 'name', 'display' ) ) . '" /></div></div>';
}
add_action( 'in_admin_header', 'appcms_admin_header', 10);


// HIDE ADMIN BAR ON FRONT END FOR NON ADMINISTRATORS
if( is_user_logged_in() ) {
    $user = wp_get_current_user();
    $roles = ( array ) $user->roles;
    if ( !in_array( 'administrator', $roles ) ) {
        add_filter( 'show_admin_bar', '__return_false' );
    }
}


// ADMIN FONT
function custom_admin_open_sans_font() {
    echo '<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">' . PHP_EOL;
    echo '<style>body, #wpadminbar *:not([class="ab-icon"]), .wp-core-ui, .media-menu, .media-frame *, .media-modal *{font-family:"Open Sans",sans-serif !important;}</style>' . PHP_EOL;
}
add_action( 'admin_head', 'custom_admin_open_sans_font' );


// CHANGE ACF FILE ICON
function acf_change_icon_on_files ( $icon, $mime, $attachment_id ){ // Display thumbnail instead of document.png
		
  if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) === false){
  	$get_image = wp_get_attachment_image_src ( $attachment_id, 'thumbnail' );
  	if ( $get_image ) {
  		$icon = $get_image[0];
  	} 
  }
  return $icon;
}
add_filter( 'wp_mime_type_icon', 'acf_change_icon_on_files', 10, 3 );


// REMOVE MEDIA LIBRARY COLUMNS
function appcms_remove_media_columns( $columns ) {
    unset( $columns['author'] );
    unset( $columns['comments'] );
    unset( $columns['parent'] );
    return $columns;
}	
add_filter( 'manage_media_columns', 'appcms_remove_media_columns' );


// CHANGE TRASH REDIRECT
function appcms_trashed_redirect(){

  $screen = get_current_screen();
  if('edit-tribe_events' == $screen->id){
    $user = wp_get_current_user();
    if ( !in_array( 'administrator', (array) $user->roles ) ) {
      if( isset($_GET['trashed']) &&  intval($_GET['trashed']) >0){
        $redirect = '/';
        
        $type = ($_REQUEST['post_type'] == 'tribe_events') ? 'Event' : 'Item';
        
        $response = $type . ' deleted.';
        
        set_transient("appcms_save_response", $response, 45);
                    
        wp_redirect($redirect);
        exit();
      }
    }
  }
}
add_action('load-edit.php','appcms_trashed_redirect');


// CUSTOM NOTIFICATIONS/ERRORS

function appcms_error_message($content) {
  if ( $response = get_transient( "appcms_save_response" ) ) { 
    $content = '<div class="notice"><p>' . $response . '</p></div>' . $content;
    delete_transient("appcms_save_response");
  }
  return $content;      
}
add_filter( 'the_content', 'appcms_error_message', 20);

add_filter( 'acf/fields/wysiwyg/toolbars' , 'email_toolbar'  );
function email_toolbar( $toolbars ) {
	$toolbars['Email' ] = array();
	$toolbars['Email' ][1] = array('link' );

    return $toolbars;
}

add_filter('manage_pledge_posts_columns', 'appcms_columns_id', 5);
add_filter('manage_custom_pledge_posts_columns', 'appcms_columns_id', 5);
function appcms_columns_id($columns){


    $new = array();
    foreach($columns as $key => $title) {
        if ($key=='title')
            $new['appcms_post_id'] = 'ID';

        $new[$key] = $title;
    }
    return $new;
}

add_action('manage_pledge_posts_custom_column', 'appcms_custom_id_columns', 5, 2);
add_action('manage_custom_pledge_posts_custom_column', 'appcms_custom_id_columns', 5, 2);
function appcms_custom_id_columns($column_name, $id){
    if($column_name === 'appcms_post_id'){
            echo $id;
    }
}

// APP USERS

add_filter('manage_app_user_posts_columns', 'appcms_app_user_columns');
function appcms_app_user_columns($columns){
    $new = array();
    foreach($columns as $k => $v) {
        $new[$k] = $v;
        if($k == 'title') {
            $new['country'] = __( 'Country', 'appcms' );
            $new['pledge'] = __( 'Pledge', 'appcms' );
            $new['approved'] = __( 'Approved for Projection', 'appcms' );
        }
    }
    return $new;
}

add_action( 'manage_app_user_posts_custom_column', 'appcms_app_user_column_content', 10, 2);
function appcms_app_user_column_content( $column, $post_id ) {
    switch ( $column ) {

        case 'country' :
            echo get_field('country', $post_id);
            break;

        case 'pledge' :
            $pledge = get_field('pledge', $post_id);
            echo '<a href="/wp-admin/post.php?post=' . $pledge . '&action=edit">' . get_the_title($pledge) . '</a>';
            break;

        case 'approved' :
            $approved = get_field('approved', $post_id);
            echo $approved ? '<strong style="color:green;">Approved</strong><br /> <a data-id="' . $post_id . '" class="app-user-unapprove" href="#">(make "unapproved")</a>' : 'Unapproved<br /> <a data-id="' . $post_id . '" class="app-user-approve" href="#">(make "approved")</a>';
            break;
            
    }
}

add_filter('manage_edit-app_user_sortable_columns', 'appcms_app_user_sortable_columns');
function appcms_app_user_sortable_columns( $columns ) {
    $columns['country'] = 'country';
    $columns['approved'] = 'approved';
    return $columns;
}

add_action( 'pre_get_posts', 'appcms_app_user_orderby');
function appcms_app_user_orderby( $query ) {
    if( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    
    if ( 'country' === $query->get( 'orderby') ) {
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'meta_key', 'country' );
        //$query->set( 'meta_type', 'numeric' );
    }

    if ( 'approved' === $query->get( 'orderby') ) {
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'meta_key', 'approved' );
        $query->set( 'meta_type', 'numeric' );
    }
}

function appcms_approval_callback_function() {
    if(!isset($_REQUEST['approved']) || !isset($_REQUEST['id'])) wp_die();
    $approved = $_REQUEST['approved'];
    $id = $_REQUEST['id'];
    update_field('approved', intval($approved), $id);
    wp_die();
}
add_action( 'wp_ajax_app_cms_approval', 'appcms_approval_callback_function' );    // If called from admin panel
add_action( 'wp_ajax_nopriv_app_cms_approval', 'my_ajax_callback_function' );
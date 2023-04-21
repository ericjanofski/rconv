<?php
/**
 * @package WordPress
 * @subpackage appcms
 */
 
function appcms_admin_css() {
   
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
  
  $user = wp_get_current_user();
  if ( !in_array( 'administrator', (array) $user->roles ) ) {
    echo '<link rel="stylesheet" type="text/css" href="/wp-content/themes/appcms/assets/css/non-admin.css" />';
  } else {
    echo '<link rel="stylesheet" type="text/css" href="/wp-content/themes/appcms/assets/css/admin.css" />';
  }
  
  if(is_user_logged_in()) {
    echo '<link rel="stylesheet" type="text/css" href="/wp-content/themes/appcms/assets/css/all-admin.css" />';
  }

  echo '<link rel="stylesheet" type="text/css" href="/wp-content/themes/appcms/assets/css/loaded-front-and-back.css" />';
  
}

add_action('admin_head', 'appcms_admin_css');
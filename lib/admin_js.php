<?php
/**
 * @package WordPress
 * @subpackage appcms
 */
 
function appcms_admin_js() {
    if(is_admin()){
        wp_enqueue_script('appcms_admin_js', get_bloginfo('template_url').'/assets/js/appcms_admin.js', array('jquery'));
        wp_enqueue_script( 'magnific', get_stylesheet_directory_uri() . '/assets/vendor/magnific/jquery.magnific-popup.min.js', array ( 'jquery' ), '1.0', true);

        $args = array(
            'url'   => admin_url( 'admin-ajax.php' ),
        );
        wp_localize_script( 'appcms_admin_js', 'appcmsajax', $args );
      
    }   
}
add_action('init', 'appcms_admin_js');
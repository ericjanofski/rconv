<?php
/**
 * @package WordPress
 * @subpackage appcms
 */
 
function appcms_login_logo() { 
  $logo = get_field('site_logo', 'options');
  if(!empty($logo)) {
    ?>
      <style type="text/css">
          #login h1 a, .login h1 a {
              background-image: url(<?php echo $logo['url']; ?>);
              width: <?php echo $logo['width']; ?>px;
              background-size: <?php echo $logo['width']; ?>px;
          }
          #backtoblog {
            display: none;
          }
      </style>
    <?php 
  }
}
add_action( 'login_enqueue_scripts', 'appcms_login_logo' );

function appcms_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'appcms_login_logo_url' );

function appcms_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'appcms_login_logo_url_title' );
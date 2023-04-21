<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

function appcms_fixed_header_body_class($classes = ''){

  // add body classes
  if(!$GLOBALS['appcms']['mobile']) $classes[] = 'fixed-header';
    
	return $classes;
}
add_action('body_class', 'appcms_fixed_header_body_class');
<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

function appcms_enable_more_buttons($buttons) {
 
  /* 
  Repeat with any other buttons you want to add, e.g.
	  $buttons[] = 'hr';
	  $buttons[] = 'fontselect';
	  $buttons[] = 'sup';
  */
 
  return $buttons;
}
add_filter("mce_buttons", "appcms_enable_more_buttons");

// to add a TABLE
// install the mce_table_buttons plugin
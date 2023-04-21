<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

// get the the role object
$role_object = get_role( 'editor' );

// add $cap capability to this role object
$role_object->add_cap( 'edit_theme_options' );
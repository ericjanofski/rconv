<?php
/**
 * @package WordPress
 * @subpackage appcms
 */


$locale = get_locale();
$locale_file = get_template_directory() . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

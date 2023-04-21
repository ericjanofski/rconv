<?php

$reroute = true;
if(get_the_ID() != 2 || !isset($_REQUEST['u'])) {
    //header("Location: " . "https://" .$_SERVER['HTTP_HOST'] . "/wp-admin");
    header("Location: https://www.rotary.org/en");
    exit;
}

// check for url variable
if(isset($_GET['u'])) {
	$u_var = $_GET['u'];
	if($u_var != '') $reroute = false;
	
	// unencode the id
	$id = base64_decode($u_var) - 13810;
    $user = get_post($id);
}

if($reroute) header("Location: https://www.rotary.org/en");


if(get_post_type( get_field('pledge', $id) ) == 'custom_pledge') {
    $pledge_email_body = get_field('email_body_custom', 'options');
    $pledge_text = get_the_title(get_field('pledge', $id));
} else {
    $pledge_email_body = get_field('email_body', get_field('pledge', $id));
    $pledge_text = get_field('wall_text', get_field('pledge', $id));
}
$url = get_site_url() . '/?u=' . $u_var;
$facebook_description = get_field('facebook_share_text', 'options') . ' ' . $pledge_text;
$twitter_description = get_field('twitter_share_text', 'options') . ' ' . $pledge_text;
$title = "View your Rotary Pledge";

/**
 * @package WordPress
 * @subpackage appcms
 */
?><!DOCTYPE html>
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="author" content="">
	<meta content='IE=10; IE=9; IE=8; IE=7; IE=EDGE; chrome=1' http-equiv='X-UA-Compatible'/>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<title><?php echo $title; ?></title>
	
    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,400italic,700italict' rel='stylesheet' type='text/css'>

    <meta property="og:site_name" content="<?php echo $title; ?>" />
 	<meta property="og:url" content="<?php echo $url; ?>" />
     <meta property="og:title" content="<?php echo $title; ?>" />
     <meta property="og:description" content="<?php echo $facebook_description; ?>" />

    <meta property="og:image" content="<?php echo get_site_url(); ?>/images/logo-full.jpg" /> 
    <meta property="og:type" content="website" /> 

    <meta name="twitter:site" content="">
    <meta name="twitter:title" content="<?php echo $title; ?>">
    <meta name="twitter:description" content="<?php echo $twitter_description; ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="<?php echo get_site_url(); ?>/images/logo-full.jpg" />
	
	<?php wp_head(); ?>
		
</head>
<body <?php body_class(); ?>>
	<div id="page" class="hfeed">
		<header id="branding" role="banner">
            <img src="/images/logo.jpg" alt="Rotary Pledge" />
		</header><!-- #branding -->		

		<div id="main">
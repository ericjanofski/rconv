<?php 

function appcms_email_setup_smtp( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'email-smtp.us-east-1.amazonaws.com';
    $phpmailer->Port       = '587';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = 'AKIAYDMBTKCQFOCRZM5G';
    $phpmailer->Password   = 'BI8ORYWx8m7FEg6gDpPMjkekdwbg1TtlYRJQsIxXphUr';
    $phpmailer->From       = 'noreply@rconv.exhibitmediahost.com';
    $phpmailer->FromName   = 'Rotary Pledge';
    $phpmailer->CharSet = 'UTF-8';
}

function appcms_email_set_content_type() {
    return "text/html";
}

// fail error
function appcms_email_failed($wp_error) {
    return error_log(print_r($wp_error, true));
}
add_action('wp_mail_failed', 'appcms_email_failed', 10, 1);

function appcms_email_send($user) {
    $args = array();

    $site_root_url = get_site_url();
    $user_var = base64_encode($user['id'] + 13810);
    $user_url = $site_root_url . '/?u=' . $user_var;

    $copy = appcms_email_copy($user);
    include('wp-content/themes/appcms/lib/appcms-email/appcms_email_template.php');
    $body = preg_replace('/\\\\/', '', $body);	
    
    //$attachment_1_location = '/home/adconst/public_html/files/' . $user['photo_name'];
    //$attachment_1_name = $user['photo_name'];	
    //if($attachment_1_location) $mail->AddAttachment("$attachment_1_location");

    add_filter( 'wp_mail_content_type', 'appcms_email_set_content_type');
    add_action( 'phpmailer_init', 'appcms_email_setup_smtp' );

    add_filter('wp_mail_smtp_custom_options', function($phpmailer) {
        $phpmailer->addCustomHeader('X-SES-CONFIGURATION-SET','RotaryConv');
        return $phpmailer;
    });

    wp_mail( $user['email'], $copy['email_subject'], $body );

    remove_filter( 'wp_mail_content_type','appcms_email_set_content_type' );
    remove_action( 'phpmailer_init', 'appcms_email_setup_smtp' );
}

function appcms_email_copy($user, $lang = 'en') {
    $site_root_url = get_site_url();

    if(!isset($lang) || $lang == '') $lang = 'en';
    
    $copy = array();

    $p_start = '<p style="margin-bottom:30px;">';
    $a_start = '<a style="outline:none;border:none;text-decoration:underline;" href';


    // PLEDGE EMAIL BODY
    $email_header = '<tr><td style="padding-bottom:10px;padding-left:20px;padding-right:20px;">';
    
    $copy['pledge_color'] = "#405465";

    if(get_post_type( $user['pledge_id'] ) == 'custom_pledge') {
        $email_header .= get_field('email_body_custom', 'options') . '</td></tr>';
        $pledge_text = get_the_title($user['pledge_id']);
        $pledge_email_body = get_field('email_body_resources', 'options');
    } else {
        $email_header .= get_field('email_header', 'options') . '</td></tr>';
        $pledge_text = get_field('wall_text', $user['pledge_id']);
        $pledge_email_body = get_field('email_body', $user['pledge_id']);

        $categories = get_the_terms($user['pledge_id'], "pledge_category");
        if($categories) {
            $cat = $categories[0];
            $copy['pledge_color'] = get_field('category_color', "pledge_category_" . $cat->term_id);
        }
    }

    $email_body_bottom = '<tr><td style="padding-bottom:30px;" class="pad">' .
        get_field('email_body_bottom', 'options') . '</td></tr>';

    $email_header = str_replace('<p>', $p_start, $email_header);
    $email_body_bottom = str_replace('<p>', $p_start, $email_body_bottom);
    $email_body_bottom = str_replace('<a href', $a_start, $email_body_bottom);

    $pledge_email_body = str_replace('<p>', $p_start, $pledge_email_body);
    $pledge_email_body = str_replace('<a href', $a_start, $pledge_email_body);
    $pledge_email_body = '<tr><td style="padding-left:20px;padding-right:20px;">' . $pledge_email_body . '</td></tr>';

    $pledge_email_body = str_replace('<p style="', '<p style="color:' . $copy['pledge_color'] . ';', $pledge_email_body);
    $pledge_email_body = str_replace('<a style="', '<a style="color:' . $copy['pledge_color'] . ';', $pledge_email_body);

    // EMAIL FOOTER
    $email_footer = get_field('email_footer', 'options');
    $email_footer = str_replace('<p>', '<p class="light" style="font-size:16px;text-align:left;font-style:italic;">', $email_footer);
    $email_footer = str_replace('<a href', '<a style="font-size:16px;text-align:center;font-style:italic;text-decoration:underline;" href', $email_footer);
    $email_footer = '<tr><td style="padding-bottom:30px;" class="pad">' . $email_footer . '</td></tr>';

    switch($lang) {
      case 'en' :
        $copy['logo_file_name'] = 'logo2.jpg';
        
        $copy['email_subject'] = get_field('email_subject', 'options');
        $copy['email_header'] = $email_header;
        $copy['pledge_text'] = $pledge_text;
        $copy['pledge_email_body'] = $pledge_email_body;
        $copy['email_body_bottom'] = $email_body_bottom;
        $copy['email_footer'] = $email_footer;
        $copy['email_share_text'] = 'Share text';
        $copy['email_image_logo'] = 'https://rconv.exhibitmediahost.com' . '/images/logo.png';
        $copy['email_image_line'] = 'https://rconv.exhibitmediahost.com' . '/images/line.png';
        $copy['email_image_line_white'] = 'https://rconv.exhibitmediahost.com' . '/images/line.png';
        $copy['email_image_facebook'] = 'https://rconv.exhibitmediahost.com' . '/images/facebook.png';
        $copy['email_image_twitter'] = 'https://rconv.exhibitmediahost.com' . '/images/twitter.png';

  
        $copy['microsite_title'] = 'Microsite Title';
        $copy['microsite_body_1'] = 'Microsite Body 1';
        $copy['microsite_body_facebook_img'] = 'facebook2.jpg';
        $copy['microsite_body_twitter_img'] = 'twitter2.jpg';
        $copy['microsite_twitter_share'] = "Here's my thing! #tag";
        $copy['microsite_facebook_share'] = "Here's my thing! #tag";
        $copy['microsite_share_text'] = 'Share it with us on social media using #tag';
  
        break;
        
      case 'es' : 
        $copy['KEY'] = 'VALUE';
        foreach($copy as $k =>$v) {
          if($k == 'email_subject' || $k == 'sms')
            continue;    
          $copy[$k] = appcms_span_accent($v);
        }
        break;    
    }
    return $copy;
  }
  
  function appcms_span_accent($wordz) {
    $wordz = str_replace( "�","&Aacute;",$wordz);
    $wordz = str_replace( "�","&Eacute;",$wordz);
    $wordz = str_replace( "�","&Iacute;",$wordz);
    $wordz = str_replace( "�","&Oacute;",$wordz);
    $wordz = str_replace( "�","&Uacute;",$wordz);
    $wordz = str_replace( "�","&Ntilde;",$wordz);
    $wordz = str_replace( "�","&Uuml;",$wordz);
    
    $wordz = str_replace( "�","&aacute;",$wordz);
    $wordz = str_replace( "�","&eacute;",$wordz);
    $wordz = str_replace( "�","&iacute;",$wordz);
    $wordz = str_replace( "�","&oacute;",$wordz);
    $wordz = str_replace( "�","&uacute;",$wordz);
    $wordz = str_replace( "�","&ntilde;",$wordz);
    $wordz = str_replace( "�","&uuml;",$wordz);
    
    $wordz = str_replace( "�","&iquest;",$wordz);
    $wordz = str_replace( "�","&iexcl;",$wordz);
    $wordz = str_replace( "�","&euro;",$wordz);
    $wordz = str_replace( "�","&laquo;",$wordz);
    $wordz = str_replace( "�","&raquo;",$wordz);
    $wordz = str_replace( "�","&lsaquo;",$wordz);
    $wordz = str_replace( "�","&rsaquo;",$wordz);
    return $wordz;
  }
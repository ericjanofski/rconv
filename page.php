<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

get_header();

$pledge_color = "#405465";
if(get_post_type( get_field('pledge', $id) ) == 'custom_pledge') {
    $email_header = get_field('email_body_custom', 'options');
    $pledge_text = get_the_title(get_field('pledge', $id));
    $pledge_email_body = get_field('email_body_resources', 'options');
} else {
    $email_header = get_field('email_header', 'options');
    $pledge_text = get_field('wall_text', get_field('pledge', $id));
    $pledge_email_body = get_field('email_body', get_field('pledge', $id));

    $categories = get_the_terms(get_field('pledge', $id), "pledge_category");
    if($categories) {
        $cat = $categories[0];
        $pledge_color = get_field('category_color', "pledge_category_" . $cat->term_id);
    }
}

$email_body_bottom = get_field('email_body_bottom', 'options');


// $facebook_description = get_field('facebook_share_text', 'options') . ' \"' . $pledge_text . '\"';
// $twitter_description = get_field('twitter_share_text', 'options') . ' \"' . $pledge_text . '\"';
$facebook_description = get_field('facebook_share_text', 'options');
$twitter_description = get_field('twitter_share_text', 'options');

 ?>
 <style type="text/css">
    .email-body p, .email-body a, .email-body a:visited, 
    .pledge p, .pledge a, .pledge a:visited {
        color: <?php echo $pledge_color; ?>;
    }
 </style>

<div id="body">
    <div class="container">
        <p>Dear <?php echo get_field('first_name', $id) . ' ' . get_field('last_name', $id); ?>,</p>
        <div class="email-header"><?php echo $email_header; ?></div>
        <div class="pledge">
            <p>"<?php echo $pledge_text; ?>"</p>
        </div>

        <?php if($pledge_email_body != '') : ?>
            <div class="email-body">
                <p><?php echo $pledge_email_body; ?></p>
            </div>
        <?php endif; ?>

        <?php echo $email_body_bottom; ?>

    </div>
</div>

<div id="footer">          
    <div class="container">
        <div class="social">
            <div class="a2a_kit a2a_kit_size_32 a2a_default_style" data-a2a-url="<?php the_field('social_share_url', 'options'); ?>">
                <a class="a2a_button_facebook"></a>
                <a class="a2a_button_twitter"></a>
            </div>
            <script><?php //https://www.addtoany.com/buttons/customize/templates ?>
                var a2a_config = a2a_config || {};
                a2a_config.templates = a2a_config.templates || {};
                a2a_config.linkurl = "<?php the_field('social_share_url', 'options'); ?>";
                a2a_config.templates.facebook = {
                    quote: "<?php echo $facebook_description; ?>",
                };
                a2a_config.templates.twitter = {
                    text: "<?php echo $twitter_description; ?>",
                };
            </script>
            <script async src="https://static.addtoany.com/menu/page.js"></script>
        </div>
        <div class="line-white">
            <p><img style="border:none;outline:none;width:220px;margin-left:auto;margin-right:auto;" src="/images/line.png" /></p>
        </div>
        
        <div class="email-footer">
            <?php the_field('email_footer', 'options'); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
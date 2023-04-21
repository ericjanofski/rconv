<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

get_header();

if(get_post_type( get_field('pledge', $id) ) == 'custom_pledge') {
    $email_header = get_field('email_body_custom', 'options');
    $pledge_text = get_the_title(get_field('pledge', $id));
    $pledge_email_body = get_field('email_body_resources', 'options');
} else {
    $email_header = get_field('email_header', 'options');
    $pledge_text = get_field('wall_text', get_field('pledge', $id));
    $pledge_email_body = get_field('email_body', get_field('pledge', $id));
}

// $facebook_description = get_field('facebook_share_text', 'options') . ' \"' . $pledge_text . '\"';
// $twitter_description = get_field('twitter_share_text', 'options') . ' \"' . $pledge_text . '\"';
$facebook_description = get_field('facebook_share_text', 'options');
$twitter_description = get_field('twitter_share_text', 'options');

 ?>

<div id="body">
    <div class="container">
        <p>Dear <?php echo get_field('first_name', $id) . ' ' . get_field('last_name', $id); ?>,</p>
        <div class="email-header"><?php echo $email_header; ?></div>
        <div class="pledge">
            <p>"<?php echo $pledge_text; ?>"</p>
        </div>
        
        <div class="line">
            <p><img src="/images/line.jpg" /></p>
        </div>

        <?php if($pledge_email_body != '') : ?>
            <div class="email-body">
                <p><?php echo $pledge_email_body; ?></p>
            </div>
        <?php endif; ?>
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
            <p><img style="border:none;outline:none;width:220px;margin-left:auto;margin-right:auto;" src="/images/line-white.jpg" /></p>
        </div>
        
        <div class="email-footer">
            <?php the_field('email_footer', 'options'); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
<?php 
 
/**
 *
 * Get The latest post from a category !
 * @param array $params Options for the function.
   * @return string|null Post title for the latest,? * or null if none
 *
 */

/**
 *
 * Register Routes
 *
 */
add_action( 'rest_api_init', function () {
    
    // content
    register_rest_route( 'appcms/v1', '/content', array(
    
        'methods'  => 'GET',
        'callback' => 'appcms_content'
    
    ));

    // create user
    register_rest_route( 'appcms/v1', '/user', array(
    
        'methods'  => 'POST',
        'callback' => 'appcms_create_user'
    
    ));


});


/**
 *
 * Content
 *
 */
function appcms_content() {
    $data = array();

    $data['general_setting'] = array(
        'timeout_duration' => get_field('timeout_duration', 'options'),
    );

    // CATEGORIES
    $data['categories'] = array();
    $cats = get_terms('pledge_category');
    foreach($cats as $item) {
        $data['categories'][] = array(
            'id' => $item->term_id,
            'name' => $item->name,
        );
    }

    // USERS
    $data['users'] = array();
    $approved_pledge_ids = array();
    $args = array(
        'posts_per_page' => 100,
        'post_type' => 'app_user',  
        'order' => 'DESC',
        'meta_key' => 'approved',
        'meta_value' => 1,
    );
    $items = get_posts($args);
    foreach($items as $item) {
        $id = $item->ID;
        $pledge_id = get_field('pledge', $id);
        $data['users'][] = array(
            'id' => $id,
            'first_name' => get_field('first_name', $id),
            'last_name' => get_field('last_name', $id),
            'email' => get_field('email', $id),
            'country' => get_field('country', $id),
            'pledge_id' => $pledge_id,
            'created' => $item->post_date,
        );
        $approved_pledge_ids[] = $pledge_id;
    }

    // PLEDGES
    $data['pledges'] = array();
    $args = array(
        'posts_per_page' => -1,
        'post_type' => array('pledge', 'custom_pledge'),
        'order' => 'DESC',
    );
    $items = get_posts($args);
    $custom_pledges = 0;
    foreach($items as $item) {
        
        $id = $item->ID;
        $terms = get_the_terms($id, 'pledge_category');
        $term_id = $terms ? (is_array($terms) ? $terms[0]->term_id : $terms->term->id) : 1;
        $app_text = html_entity_decode(get_the_title($id));
        $wall_text = get_field('wall_text', $id) ? get_field('wall_text', $id) : null;

        if(get_post_type($id) == 'custom_pledge') { // custom
            $wall_text = $app_text;
            $custom_pledges++;
            if(!in_array($id, $approved_pledge_ids)) continue;
        }

        $args = array(
            'post_type' => 'app_user',
            'posts_per_page' => -1,
            'meta_key' => 'pledge',
            'meta_value' => $id,
        );
        // $users = get_posts($args);
        // $user_ids = array();
        // foreach($users as $user) {
        //     $user_ids[] = $user->ID;
        // }

        $data['pledges'][] = array(
            'id' => $id,
            'category_id' => $term_id,
            'app_text' => $app_text,
            'wall_text' => $wall_text,
            //'users' => ($user_ids) ? $user_ids : null,
        );
    }

    $output = array(
        'status'=> true,
        'data' => $data,
    );
    return $output;
}

/**
 *
 * Create User
 *
 */
function appcms_create_user() {
    $post = isset($_REQUEST) ? $_REQUEST : false;

    $first_name = isset($_REQUEST['first_name']) ? $_REQUEST['first_name'] : '';
    $last_name = isset($_REQUEST['last_name']) ? $_REQUEST['last_name'] : '';
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
    $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : '';
    $pledge_id = isset($_REQUEST['pledge_id']) && $_REQUEST['pledge_id'] != '' ? $_REQUEST['pledge_id'] : false;
    $custom_pledge = isset($_REQUEST['custom_pledge']) && $_REQUEST['custom_pledge'] != '' ? $_REQUEST['custom_pledge'] : false;

    if($custom_pledge) {
        $new_pledge = array(
            'post_type'     => 'custom_pledge',
            'post_title'    => wp_strip_all_tags( $custom_pledge ),
            'post_status'   => 'publish',
            'post_author'   => 1,
        );
        $pledge_id = wp_insert_post( $new_pledge );
    }

    $new_user = array(
        'post_type'     => 'app_user',
        'post_title'    => wp_strip_all_tags( $first_name . " " . $last_name ),
        'post_status'   => 'publish',
        'post_author'   => 1,
    );
    $new_user_id = wp_insert_post( $new_user );
    $new_user = get_post($new_user_id);

    update_field('first_name', $first_name, $new_user_id);
    update_field('last_name', $last_name, $new_user_id);
    update_field('email', $email, $new_user_id);
    update_field('country', $country, $new_user_id);
    if($pledge_id) update_field('pledge', get_post($pledge_id), $new_user_id);

    $user_data = array(
        'id' => $new_user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'country' => $country, 
        'email' => $email,
        'pledge_id' => $pledge_id,
    );

    // send email
    appcms_email_send($user_data);


    $data['user_id'] = $new_user->ID;
    $output = array(
        'status'=> true,
        'data' => $data,
    );
    return $output;
}

function appcms_convert_ascii($string) { 
    // Replace Single Curly Quotes
    $search[]  = chr(226).chr(128).chr(152);
    $replace[] = "'";
    $search[]  = chr(226).chr(128).chr(153);
    $replace[] = "'";

    // Replace Smart Double Curly Quotes
    $search[]  = chr(226).chr(128).chr(156);
    $replace[] = '"';
    $search[]  = chr(226).chr(128).chr(157);
    $replace[] = '"';

    // Replace En Dash
    $search[]  = chr(226).chr(128).chr(147);
    $replace[] = '--';

    // Replace Em Dash
    $search[]  = chr(226).chr(128).chr(148);
    $replace[] = '---';

    // Replace Bullet
    $search[]  = chr(226).chr(128).chr(162);
    $replace[] = '*';

    // Replace Middle Dot
    $search[]  = chr(194).chr(183);
    $replace[] = '*';

    // Replace Ellipsis with three consecutive dots
    $search[]  = chr(226).chr(128).chr(166);
    $replace[] = '...';

    // Apply Replacements
    $string = str_replace($search, $replace, $string);

    // Remove any non-ASCII Characters
    $string = preg_replace("/[^\x01-\x7F]/","", $string);

    return $string; 
}
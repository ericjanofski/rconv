<?php
  
// Header Imports
function appcms_add_leaderboard_scripts() {
    if(is_admin()){
      wp_enqueue_style( 'leaderboard', get_stylesheet_directory_uri() . '/assets/css/appcms_admin_leaderboard.css',false,'1.0','all');  
      wp_enqueue_script( 'leaderboard', get_stylesheet_directory_uri() . '/assets/js/appcms_admin_leaderboard.js', array ( 'jquery' ), '1.0', true);  
    }   
}
add_action('init', 'appcms_add_leaderboard_scripts');

  
// CUSTOM PAGES
function appcms_admin_menu() {
	add_menu_page(
		__( 'Leaderboard', 'my-textdomain' ),
		__( 'Leaderboard', 'my-textdomain' ),
		'edit_posts',
		'leaderboard',
		'appcms_leaderboard_contents',
		'dashicons-schedule',
		3
	);
	add_menu_page(
		__( 'Admin Options', 'my-textdomain' ),
		__( 'Admin Options', 'my-textdomain' ),
		'edit_posts',
		'admin-options',
		'appcms_admin_options_contents',
		'dashicons-schedule',
		3
	);
}
add_action( 'admin_menu', 'appcms_admin_menu' );

function appcms_admin_options_contents() {
	?>
    <div class="wrap acf-settings-wrap">
      <div id="admin-options">
        <h2>Admin Options</h2>
        
        <button name="clear-leaderboard" id="clear-leaderboard">Clear Leaderboard</button>
        <div id="results">&nbsp;</div>
     
      </div>
    </div>
	<?php
}



function appcms_leaderboard_contents() {
	?>
    <div class="wrap acf-settings-wrap">
      <div id="leaderboard" class="custom-panel">
        <h2>Leaderboard</h2>
        
        <div class="loading">&nbsp;</div>
        <div id="content">&nbsp;</div>
     
      </div>
    </div>
	<?php
}


// LOAD GAME USERS
function appcms_load_game_users() {
  
  $is_web_display = ( $_REQUEST['is_web_display'] ) ? $_REQUEST['is_web_display'] : 0;
  $paged = ( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1;
  $location_id = ( $_REQUEST['location_id'] ) ? $_REQUEST['location_id'] : false;
  $sort = ( $_REQUEST['sort'] ) ? $_REQUEST['sort'] : '';
  ?>
  <div id="leaderboard" class="custom-panel">
    <form id="filters" name="filters">
      <span class="location">
        <label>Filter</label>
        <select name="location" id="location-select">
          <option value="">All Locations</option>
          <?php $args = array(
              'post_type' => 'location',
              'order' => 'ASC',
              'orderby' => 'title'
            );
            $locations = get_posts($args); 
          ?>
          <?php foreach($locations as $item) : ?>
            <option value="<?php echo $item->ID; ?>"<?php if($location_id == $item->ID) echo ' selected="selected"'; ?>><?php echo $item->post_title; ?></option>
          <?php endforeach; ?>
        </select>
      </span>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <span class="field-sort">
        <label>Sort</label>
        <select name="sort" id="sort-select">
          <option value="points">Points</option>
          <option value="name-asc"<?php if($sort == 'name-asc') echo ' selected="selected"'; ?>>Name Asc.</option>
          <option value="name-desc"<?php if($sort == 'name-desc') echo ' selected="selected"'; ?>>Name Desc.</option>
        </select>
      </span>
    </form>


    <?php 
  
      $nextpage = $paged + 1;


      $args = array(
        'post_type' => 'game_user',
        'posts_per_page' => 50,
        'paged' => $paged,
        'meta_query'	=> array(
          'relation' => 'AND',
          'total_points_clause' => array(
            'key' => 'total_points_scored',
            'compare' => 'EXISTS',
            'type' => 'NUMERIC',
          ),
          'elapsed_time_clause' => array(
            'key' => 'elapsed_time_raw',
            'compare' => 'EXISTS',
            'type' => 'NUMERIC',
          ),
          'number_of_misses_clause' => array(
            'key' => 'number_of_misses',
            'compare' => 'EXISTS',
            'type' => 'NUMERIC',
          ),
          'game_played_clause' => array(
     			  'key'	  	=> 'game_played',
      			  'value'	  	=> 1,
          ),
        ),
        'orderby' => array(
          'total_points_clause' => 'DESC',
          'elapsed_time_clause' => 'ASC',
          'date' => 'DESC',
        ),
      );
      if($location_id) $args['meta_query']['location_clause'] = array(
        'key' => 'location_id',
        'value' => $location_id,
      );
      
      if($sort == 'name-asc') {
        $args['orderby'] = array(
          'title' => 'ASC',
          'total_points_clause' => 'DESC',
          'elapsed_time_clause' => 'ASC',
          'date' => 'DESC',
        );
      } elseif($sort == 'name-desc') {
        $args['orderby'] = array(
          'title' => 'DESC',
          'total_points_clause' => 'DESC',
          'elapsed_time_clause' => 'ASC',
          'date' => 'DESC',
        );
      }
      
      if($is_web_display) {
        $args['posts_per_page'] = 10;
      }
      
    ?>
    <?php $the_query = new WP_Query( $args ); ?>
    <?php $max_pages = $the_query->max_num_pages; ?>
    <?php if ( $the_query->have_posts() ) : ?>
      <table id="leaders" border="0" cellpadding="0" cellspacing="0">
        <?php if(!$is_web_display) : ?>
          <thead>
            <tr>            
              <th>Name</th>
              <th>Employee ID</th>
              <th>Location</th>
              <th># of misses</th>
              <th>Elapsed Time</th>
              <th>Total Points</th>
              <th>Date</th>
              <?php if(!$is_web_display) : ?>
                <th><a href="#" class="leaderboard-delete">Delete</a></th>
              <?php endif; ?>
            </tr>
          </thead>
        <?php endif; ?>
        <tbody>
          <?php $i = 1; ?>
          <?php 	while ( $the_query->have_posts() ) : ?>
            <?php $the_query->the_post(); ?>
            <?php $user_location_id = get_field('location_id'); ?>
            <?php $location = get_post($user_location_id); ?>
            <tr>
              <?php if($is_web_display) : ?>
                <td><?php echo $i; ?>.</td>
              <?php endif; ?>
              
              <td><?php the_title(); ?></td>
              
              <?php if(!$is_web_display) : ?>
                <td><?php the_field('employee_id'); ?></td>
                <td><?php echo $location->post_title; ?></td>
                <td><?php the_field('number_of_misses'); ?></td>
              <?php endif; ?>
              
              <td><?php the_field('elapsed_time'); ?></td>
              <td><?php the_field('total_points_scored'); ?></td>

              <?php if(!$is_web_display) : ?>
                <td><?php echo get_the_date() . ' ' . get_the_time(); ?></td>            
                <td><input type="checkbox" value="<?php echo get_the_ID(); ?>" class="delete-me" /></td>
              <?php endif; ?>
            </tr>
            <?php $i++; ?>
            <?php wp_reset_postdata(); ?>
          <?php endwhile; ?>
        </tbody>
      </table>
      <div class="pagination container clearfix">
        <?php 
          $prevpage = max( ($paged - 1), 0 ); //max() will discard any negative value
          if ($prevpage !== 0) {
             echo '<a class="previous" data-pager="' . $prevpage . '" href="#"><< Previous</a>';
          }
          
          echo '<span class="detail">Page ' . $paged . ' of ' . $max_pages . '</span>';
          
          if ($max_pages > $paged) {
            echo '<a class="next" data-pager="' . $nextpage . '"  href="#">Next >></a>';
          }        
        ?>
      </div>
    </div>
    
    <?php else : ?>
    
      <div id="no-results">No Results</div>

    <?php endif;?>
    
    <a class="export-csv" href="/wp-admin/admin.php?action=export-leaderboard-csv<?php if($location_id) echo '&location_id=' . $location_id; ?>"><?php _e('Export to CSV', 'appcms');?></a>

    <?php wp_die();   
}
add_action( 'wp_ajax_appcms_leaderboard_ajax_load_more', 'appcms_load_game_users' );
add_action( 'wp_ajax_nopriv_appcms_leaderboard_ajax_load_more', 'appcms_load_game_users' );


// CLEAR LEADERBOARD
function appcms_clear_leaderboard() {
  $args = array(
    'post_type' => 'game_user',
    'posts_per_page' => -1,
    'meta_query'	=> array(
    		array(
      		'relation' => 'OR',
      		array(
      			'key'	  	=> 'hide_from_leaderboard',
          'compare' 	=> 'NOT EXISTS',
        ),
      		array(
      			'key'	  	=> 'hide_from_leaderboard',
      			'value' => 0,
          'compare' 	=> '=',
        ), 
    		),
    ),
  );
  $game_users = get_posts($args);
  foreach($game_users as $item) {
    update_field('hide_from_leaderboard', 1, $item->ID);    
  }
  echo 'cleared';
  wp_die();     
}
add_action( 'wp_ajax_appcms_leaderboard_ajax_clear_leaderboard', 'appcms_clear_leaderboard' );
add_action( 'wp_ajax_nopriv_appcms_leaderboard_ajax_clear_leaderboard', 'appcms_clear_leaderboard' );


// DELETE LEADERBOARD ITEMS
function appcms_delete_leaderboard_items() {
  $ids_string = ( $_REQUEST['ids'] ) ? $_REQUEST['ids'] : false;
  $ids = explode(',', $ids_string);
  foreach($ids as $id) {
    wp_delete_post($id);
  }
  echo 'deleted';
  wp_die();
}
add_action( 'wp_ajax_appcms_leaderboard_ajax_delete_items', 'appcms_delete_leaderboard_items' );
add_action( 'wp_ajax_nopriv_appcms_leaderboard_ajax_delete_items', 'appcms_delete_leaderboard_items' );


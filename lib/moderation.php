<?php 
  
// Header Imports
function appcms_add_moderation_scripts() {
    if(is_admin()){
      wp_enqueue_style( 'moderation', get_stylesheet_directory_uri() . '/assets/css/appcms_admin_moderation.css',false,'1.0','all');  
      wp_enqueue_script( 'moderation', get_stylesheet_directory_uri() . '/assets/js/appcms_admin_moderation.js', array ( 'jquery' ), '1.0', true);  
    }   
}
add_action('init', 'appcms_add_moderation_scripts');

  
// CUSTOM PAGES
function appcms_admin_menu() {
	add_menu_page(
		__( 'Moderation Unapproved', 'appcms' ),
		__( 'Moderation Unapproved', 'appcms' ),
		'edit_posts',
		'moderation',
		'appcms_moderation_contents',
		'dashicons-schedule',
		3
	);
	add_menu_page(
		__( 'Moderation Approved', 'appcms' ),
		__( 'Moderation Approved', 'appcms' ),
		'edit_posts',
		'moderation-approved',
		'appcms_moderation_approved_contents',
		'dashicons-schedule',
		3
	);
}
add_action( 'admin_menu', 'appcms_admin_menu' );


function appcms_moderation_contents() {
	?>
    <div class="wrap acf-settings-wrap">
      <div id="moderation" class="custom-panel">
        <h2>Moderation: Unapproved</h2>
        <div class="description">
          <p>This page contains all unapproved content.</p>
          <p>To approve content for show display, check the box to the right of the content, then click the "Approve" button at the top of the table.</p><br />
        </div>
        
        <div class="loading">&nbsp;</div>
        <div id="content">&nbsp;</div>
     
      </div>
    </div>
	<?php
}

function appcms_moderation_approved_contents() {
	?>
    <div class="wrap acf-settings-wrap">
      <div id="moderation" class="custom-panel">
        <h2>Moderation: Approved</h2>
        <div class="description">
          <p>This page contains content that's been approved for show display.</p>
          <p>To remove approval of content for show display, check the box to the right of the content, then click the "Remove" button at the top of the table.</p><br />
        </div>
        <div class="loading">&nbsp;</div>
        <div id="content">&nbsp;</div>
     
      </div>
    </div>
	<?php
}

// LOAD MODERATION
function appcms_moderation_list() {
  
  $paged = ( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1;
  $sort = ( $_REQUEST['sort'] ) ? $_REQUEST['sort'] : 'date-desc';
  $display = ( $_REQUEST['display'] ) ? $_REQUEST['display'] : 'unapproved'; 
  $search = ( $_REQUEST['search'] ) ? $_REQUEST['search'] : false; 
  
  ?>
  <div id="moderation" class="custom-panel">
    <div id="filter-bar">
      <form id="filters" name="filters">
        <span class="field-sort">
          <label>Sort</label>
          <select name="sort" id="sort-select">
            <option value="date-desc"<?php if($sort == 'date-desc') echo ' selected="selected"'; ?>>Date Desc.</option>
            <option value="date-asc"<?php if($sort == 'date-asc') echo ' selected="selected"'; ?>>Date Asc.</option>
          </select>
        </span>
        <a href="#" class="refresh"><img src="/wp-content/uploads/refresh-1.png" /></a>
      </form>
      <form id="search" name="search">
        <input type="text" name="search" class="search" placeholder="Search"<?php if($search) echo ' value="' . $search . '"'; ?> /><input type="submit" name="submit" value="Search" class="button" />
      </form>      
    </div>

    <?php 
      $args = array(
        'post_type' => 'app_user',
        'posts_per_page' => 80,
        'paged' => $paged,
        'meta_query' => array(
          'relation' => 'OR',
          array(
              'key'     => 'approved',
              'value'   => 0,
              'compare' => '='
          ),
          array(
              'key'     => 'approved',
              'compare' => 'NOT EXISTS',
          ),
        ),
        'order' => 'DESC',
      );      
      if($sort == 'date-asc') {
        $args['order'] = 'ASC';
      }
      
      if($display == 'approved') {
        $args['meta_query'] = array(
          array(
              'key'     => 'approved',
              'value'   => 1,
              'compare' => '='
          ),
        );
                
      }
      
      if($search) {
        $args['s'] = $search;        
      }
    ?>
    <?php $the_query = new WP_Query( $args ); ?>
    <?php $max_pages = $the_query->max_num_pages; ?>
    <?php if ( $the_query->have_posts() ) : ?>
    
      <?php
        $big = 999999999; // need an unlikely integer
        
        $pagination = paginate_links( array(
        	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        	'format' => '?paged=%#%',
        	'current' => max( 1, $paged ),
        	'mid_size' => 4,
        	'total' => $max_pages,
        ) );
      ?>

      <?php if($pagination) : ?>
        <div class="pagination container clearfix top-pager">
            <div class="row">
              <div class="col-12">
                <?php echo $pagination; ?>
              </div>
            </div>
        </div>
      <?php endif; ?>

    
    
      <table border="0" cellpadding="0" cellspacing="0">
        <thead>
        <tr>            
            <th class="first-name">Title</th>
            <!-- <th class="pledge-id">Pledge ID</th> -->
            <th class="pledge">Pledge Text</th>
            <th class="center btn" align="center">
            <?php if($display == 'unapproved') : ?>
                <a href="#" class="moderation-approve cta">Approve</a>
            <?php else : ?>
                <a href="#" class="moderation-unapprove cta">Unapprove</a>
            <?php endif; ?>
            </th>
        </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          <?php while ( $the_query->have_posts() ) : ?>
            <?php $the_query->the_post(); ?>
            <?php $pledge_id = get_field('pledge'); ?>
            <tr>
              <td class="title"><a href="/wp-admin/post.php?post=<?php echo get_the_ID(); ?>&action=edit"><?php the_field('first_name'); ?> <?php the_field('last_name'); ?></a></td>
              <!-- <td class="pledge-id"><?php echo $pledge_id; ?></td> -->
              <td class="pledge"><?php echo get_the_title($pledge_id) ?><?php if(get_post_type($pledge_id) == 'custom_pledge') echo '<div><a style="font-size:12px;padding-top:5px;color:#999;" href="/wp-admin/post.php?post=' . $pledge_id . '&action=edit">edit custom pledge text</a></div>'; ?></td>    
              <td class="center btn"><input type="checkbox" value="<?php echo get_the_ID(); ?>" class="<?php echo ($display == 'unapproved') ? 'approve-me' : 'unapprove-me'; ?>" /></td>
            </tr>
            <?php $i++; ?>
            <?php wp_reset_postdata(); ?>
          <?php endwhile; ?>
        </tbody>
        <!-- <tfoot>
        <tr>            
            <td class="type">Type</th>
            <td class="title">Title</th>
            <td class="image">Image</th>
            <td class="date">Date</th>
            <td class="center btn">
            <?php if($display == 'unapproved') : ?>
                <a href="#" class="moderation-approve cta">Approve</a>
            <?php else : ?>
                <a href="#" class="moderation-unapprove cta">Unapprove</a>
            <?php endif; ?>
            </td>
        </tr>
        </tfoot> -->
      </table>

      <?php if($pagination) : ?>
        <div class="pagination container clearfix">
            <div class="row">
              <div class="col-12">
                <?php echo $pagination; ?>
              </div>
            </div>
        </div>
      <?php endif; ?>
    </div>
    
    <?php else : ?>
    
      <div id="no-results">No Results</div>

    <?php endif;?>
    
    <?php wp_die();   
}
add_action( 'wp_ajax_appcms_moderation_ajax_load_more', 'appcms_moderation_list' );
add_action( 'wp_ajax_nopriv_appcms_moderation_ajax_load_more', 'appcms_moderation_list' );



// APPROVE MODERATION ITEMS
function appcms_moderation_approve_items() {
  $ids_string = ( $_REQUEST['ids'] ) ? $_REQUEST['ids'] : false;
  $ids = explode(',', $ids_string);
  foreach($ids as $id) {
    update_field('approved', 1, $id);
  }
  echo 'approved';
  wp_die();
}
add_action( 'wp_ajax_appcms_moderation_ajax_approve_items', 'appcms_moderation_approve_items' );
add_action( 'wp_ajax_nopriv_appcms_moderation_ajax_approve_items', 'appcms_moderation_approve_items' );


// UNAPPROVE MODERATION ITEMS
function appcms_moderation_unapprove_items() {
  $ids_string = ( $_REQUEST['ids'] ) ? $_REQUEST['ids'] : false;
  $ids = explode(',', $ids_string);
  foreach($ids as $id) {
    update_field('approved', 0, $id);
  }
  echo 'unapproved';
  wp_die();
}
add_action( 'wp_ajax_appcms_moderation_ajax_unapprove_items', 'appcms_moderation_unapprove_items' );
add_action( 'wp_ajax_nopriv_appcms_moderation_ajax_unapprove_items', 'appcms_moderation_unapprove_items' );

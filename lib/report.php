<?php 
  
// Header Imports
function appcms_add_report_scripts() {
    if(is_admin()){
      wp_enqueue_style( 'report', get_stylesheet_directory_uri() . '/assets/css/appcms_admin_report.css',false,'1.0','all');  
      wp_enqueue_script( 'report', get_stylesheet_directory_uri() . '/assets/js/appcms_admin_report.js', array ( 'jquery' ), '1.0', true);  
    }   
}
add_action('init', 'appcms_add_report_scripts');

  
// CUSTOM PAGES
function appcms_admin_menu() {
	add_menu_page(
		__( 'Admin Report', 'appcms' ),
		__( 'Admin Report', 'appcms' ),
		'edit_posts',
		'report',
		'appcms_report_contents',
		'dashicons-schedule',
		3
	);
}
add_action( 'admin_menu', 'appcms_admin_menu' );


function appcms_report_contents() {
	?>
    <div class="wrap acf-settings-wrap">
      <div id="report" class="custom-panel">
        <h2>Admin Report</h2>
        <div class="description">
          <p>Description</p>
        </div>
        
        <div class="loading">&nbsp;</div>
        <div id="content">&nbsp;</div>
     
      </div>
    </div>
	<?php
}

// LOAD REPORT
function appcms_report_list() {
  
  $paged = ( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1;
  $post_type = ( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : '';
  $sort = ( $_REQUEST['sort'] ) ? $_REQUEST['sort'] : 'date-desc';
  $search = ( $_REQUEST['search'] ) ? $_REQUEST['search'] : false; 
  
  ?>
  <div id="report" class="custom-panel">
    <div id="filter-bar">
      <form id="filters" name="filters">
        <!-- <span class="post-type">
          <label>Filter</label>
          <select name="post_type" id="post-type-select">
            <option value="">All</option>
            <?php foreach($post_type as $item) : ?>
                <option value="<?php echo $item; ?>"<?php if($post_type == $item) echo ' selected="selected"'; ?>><?php echo $item; ?></option>
            <?php endforeach; ?>
          </select>
        </span> -->
        &nbsp;&nbsp;&nbsp;&nbsp;
        <span class="field-sort">
          <label>Sort</label>
          <select name="sort" id="sort-select">
            <option value="date-desc"<?php if($sort == 'date-desc') echo ' selected="selected"'; ?>>Date Desc.</option>
            <option value="date-asc"<?php if($sort == 'date-asc') echo ' selected="selected"'; ?>>Date Asc.</option>
          </select>
        </span>
        <!-- <a href="#" class="refresh"><img src="/wp-content/uploads/refresh-1.png" /></a> -->
      </form>
      <form id="search" name="search">
        <input type="text" name="search" class="search" placeholder="Search"<?php if($search) echo ' value="' . $search . '"'; ?> /><input type="submit" name="submit" value="Search" class="button" />
      </form>      
    </div>

    <?php 
  
      $nextpage = $paged + 1;
      if($post_type == '') $post_type = array('app_user');

      $args = array(
        'post_type' => $post_type,
        'posts_per_page' => 80,
        'paged' => $paged,
      );      
      
      if($sort == 'date-asc') {
        $args['order'] = 'ASC';
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
        <?php if(!$is_web_display) : ?>
          <thead>
            <tr>            
              <th class="type">Type</th>
              <th class="title">Title</th>
              <th class="date">Date</th>
            </tr>
          </thead>
        <?php endif; ?>
        <tbody>
          <?php $i = 1; ?>
          <?php 	while ( $the_query->have_posts() ) : ?>
            <?php $the_query->the_post(); ?>
            <tr>
              <td class="type"><a href="<?php echo get_edit_post_link(); ?>"><?php echo get_post_type(); ?></a></td>
              <td class="title"><?php the_title(); ?></td>
              <td class="date"><?php echo get_the_date(); ?></td>    
            </tr>
            <?php $i++; ?>
            <?php wp_reset_postdata(); ?>
          <?php endwhile; ?>
        </tbody>
        <?php if(!$is_web_display) : ?>
          <tfoot>
            <tr>            
              <td class="type">Type</th>
              <td class="title">Title</th>
              <td class="date">Date</th>
            </tr>
          </tfoot>
        <?php endif; ?>
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
    
    <a class="export-csv" href="/wp-admin/admin.php?action=export-report-csv"><?php _e('Export CSV', 'appcms');?></a>

    
    <?php wp_die();   
}
add_action( 'wp_ajax_appcms_report_ajax_load_more', 'appcms_report_list' );
add_action( 'wp_ajax_nopriv_appcms_report_ajax_load_more', 'appcms_report_list' );
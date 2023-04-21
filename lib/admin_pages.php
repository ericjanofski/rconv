<?php 
  
// CUSTOM  ADMIN PAGES
function appcms_admin_menu2() {
  
	add_menu_page(
		__( 'Export Users'),
		__( 'Export Users'),
		'edit_posts',
		'export-users',
		'appcms_custom_page_contents',
		'dashicons-schedule',
		3
	);
	
}
add_action( 'admin_menu', 'appcms_admin_menu2' );

function appcms_custom_page_contents() {
	?>
    <div class="wrap acf-settings-wrap">
      <div id="leaderboard">
        <h2>Export users</h2>
        
        <div class="loading">&nbsp;</div>
        <div id="content">&nbsp;</div>
     
      </div>
    </div>
	<?php
}

function appcms_custom_page_async() {
  
    $paged = ( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1;
    $sort = ( $_REQUEST['sort'] ) ? $_REQUEST['sort'] : 'date-desc';
    $search = ( $_REQUEST['search'] ) ? $_REQUEST['search'] : false;     
    ?>
    <div id="moderation" class="custom-panel">
        <form style="margin-bottom: 20px;" action="#" method="POST" id="export-csv"><input type="hidden" id="base1_export_csv" name="base1_export_csv" value="1" /><input class="button button-primary user_export_button" style="margin-top:3px;" type="submit" value="<?php esc_attr_e('Export CSV', 'base1');?>" /><input type="hidden" id="base1-export-from" name="base1_export_from" value="" /><input type="hidden" id="base1-export-to" name="base1_export_to" value="" /></form>

      <div id="filter-bar" style="height:40px;">
        <form id="filters" name="filters">
          <!-- <span class="field-sort">
            <label>Sort</label>
            <select name="sort" id="sort-select">
              <option value="date-desc"<?php if($sort == 'date-desc') echo ' selected="selected"'; ?>>Date Desc.</option>
              <option value="date-asc"<?php if($sort == 'date-asc') echo ' selected="selected"'; ?>>Date Asc.</option>
            </select>
          </span> -->

            <?php// mishaDateRange(); ?>
     


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
            'order' => 'DESC',
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
          <thead>
          <tr>            
              <th class="first-name">First Name</th>
              <th class="last-name">Last Name</th>
              <th class="pledge">Pledge Text</th>
          </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            <?php while ( $the_query->have_posts() ) : ?>
              <?php $the_query->the_post(); ?>
              <?php $pledge_id = get_field('pledge'); ?>
              <tr>
                <td class="first-name"><?php the_field('first_name'); ?></td>
                <td class="last-name"><?php the_field('last_name'); ?></td>
                <td class="pledge"><?php echo get_the_title($pledge_id) ?></td>    
              </tr>
              <?php $i++; ?>
              <?php wp_reset_postdata(); ?>
            <?php endwhile; ?>
          </tbody>
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
  add_action( 'wp_ajax_appcms_admin_pages_ajax_load_more', 'appcms_custom_page_async' );
  add_action( 'wp_ajax_nopriv_appcms_admin_pages_ajax_load_more', 'appcms_custom_page_async' );
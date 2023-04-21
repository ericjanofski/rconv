<?php 
  
function appcms_new_log_entry($log_content) {
  
  
          // Create post object
        $new_post = array(
          'post_title'    => $log_content,
          'post_status'   => 'publish',
          'post_author'   => 1,
          'post_type' => 'log',
        );
        
        $post_id = wp_insert_post( $new_post );

  
}

function appcms_log_to_file($log_content) {
  date_default_timezone_set('America/Chicago');
  $date = date('F j, Y, g:i a');
  
  $fp = fopen('/home/omronroot/public_html/wp-content/uploads/log.txt', 'a');
  fwrite($fp, $date . ': ' . $log_content . PHP_EOL);
  fclose($fp);
  
}
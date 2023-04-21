<?php 
    
  // add this to wp-config.php:
  // define('DISABLE_WP_CRON', true);
  
  // a cron job to cpanel:
  //curl --silent https://omron.codebase1.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1
  
  // install wp crontrol to manage cron events




  
  // Default Intervals: hourly, twicedaily, daily
  // Custom Interval, great for testing at 5 second intervals
  function appcms_interval( $schedules ) {
    $schedules['five_seconds'] = array(
        'interval' => 5,
        'display'  => esc_html__( 'Every Five Seconds' ),
    );
    $schedules['every_other_minute'] = array(
        'interval' => 120,
        'display'  => esc_html__( 'Every Other Minute' ),
    );
    $schedules['every_minute'] = array(
        'interval' => 60,
        'display'  => esc_html__( 'Every Other Minute' ),
    );
  
    return $schedules;
  }
  add_filter( 'cron_schedules', 'appcms_interval' );



// CREATE THE HOOK and the the function the hook calls
add_action( 'appcms_cron_hook', 'appcms_cron_exec' );



// SCHEDULE THE HOOK: check if hook is scheduled, if not, schedule it
if ( ! wp_next_scheduled( 'appcms_cron_hook' ) ) {
    wp_schedule_event( time(), 'every_other_minute', 'appcms_cron_hook' );
}

// EXECUTE THE CRON ACTION
function appcms_cron_exec() {
  
  // code to execute goes here
  
}








// DESCHEDULE the hook if no longer needed
//$timestamp = wp_next_scheduled( 'appcms_cron_hook' );
//wp_unschedule_event( $timestamp, 'appcms_cron_hook' );
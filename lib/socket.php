<?php
  
 // UPDATE FIELDS
 
 // action
function base1_acf_update_action( $value, $post_id, $field  ) {
  if($value == 'select') return;
  $value = "select";
  return $value;    
}
add_filter('acf/update_value/key=field_5be48054af8c2', 'base1_acf_update_action', 10, 3);


// restart
function base1_acf_update_restart( $value, $post_id, $field  ) {
  if($value == 'select') return;

  $message_type = 4;
  $client_app = $value;
  $restart_status = 1;
  $response =  base1_send_socket_message($message_type, $client_app, $restart_status);
  
  set_transient("base1_save_socket_response", $response, 45);

  $value = "select";
  return $value;
}
add_filter('acf/update_value/key=field_5b8596aff43bb', 'base1_acf_update_restart', 10, 3);


// turn on
function base1_acf_update_turn_on( $value, $post_id, $field  ) {
  if($value == 'select') return;
 
  $message_type = 2;
  $client_app = $value;
  $restart_status = 1;
  $response = base1_send_socket_message($message_type, $client_app, $restart_status);

  set_transient("base1_save_socket_response", $response, 45);

  $value = "select";
  return $value;  
}
add_filter('acf/update_value/key=field_5b8599640b7ec', 'base1_acf_update_turn_on', 10, 3);


// turn off
function base1_acf_update_turn_off( $value, $post_id, $field  ) {
  if($value == 'select') return;
 
  $message_type = 3;
  $client_app = $value;
  $restart_status = 1;
  $response = base1_send_socket_message($message_type, $client_app, $restart_status);
  
  set_transient("base1_save_socket_response", $response, 45);

  $value = "select";
  return $value;
}
add_filter('acf/update_value/key=field_5b8599fa16fb1', 'base1_acf_update_turn_off', 10, 3);


// BASE1 CONTENT UPDATE
function base1_content_update( $post_id ) {

  $post = get_post($post_id);
  if (isset($post->post_status) && 'auto-draft' == $post->post_status) 
    return;
  
  if ( wp_is_post_revision( $post_id ) )
		return;

  $message_type = 5;
  
  $client_app = 0;
  $restart_status = 1;
  
  $screen = get_current_screen();
  $client_app_map = array(
    'touch-table-attract' => 1,
    'touch_table' => 1,
    'gameday-experience' => 2,
    'logo-display' => 3,
    'helmet-display' => 4,
    'nfl-display' => 5,
    'default-video-wall' => 6,
    'tribe_events' => 6,  
    'motion-video-reel' => 7,  
  );
  
  $client_app = array();
  foreach($client_app_map as $k=>$v) {
    if (strpos($screen->id, $k) !== false) {
      //print_r($k);
      $client_app = $v;
  	}    
  }
  
  if($client_app)
    $response = base1_send_socket_message($message_type, $client_app, $restart_status);

}
add_action('acf/save_post', 'base1_content_update');
add_action( 'save_post', 'base1_content_update' );
//add_action( 'edit_attachment', 'base1_content_update' );



// SEND SOCKET MESSAGE

function base1_send_socket_message($message_type, $client_app, $restart_status) {
  $ip = '129.252.86.254';
  $port = '8080';
  $handshake = "gamecocks";
  
  /*
  MessageType - CMS uses 2-5
  1 = Handshake
  2 = Turn On
  3 = Turn Off
  4 = Restart
  5 = New Content
  6 = Successful Shutdown
  7 = Successful Turn On
  8 = Recruit mode on
  9 = Recruit mode off
  
  ClientApp
  1 - Touch Table
  2 - Game Day
  3 - Logo Display
  4 - Helmet Display
  5 - NFL Experience
  6 - Video Wall
  7 - Motion Reel
  8 - All
  9 - CMS
  
  Restart Status
  0 - True
  1 - False
  */
  
  $fp = fsockopen($ip, $port, $errno, $errstr, 30);
  if (!$fp) {
      $result = "Server response: could not open connection";
  } else {
      $result = 'Server response: success!';
  	// Pack the data into integer values that are associated to enumerated types on server end
  	$binData = pack('a9i*', $handshake, $message_type, $client_app);
  	fwrite($fp, $binData);
  	fclose ($fp);
  }
  
  return $result;
}


// CUSTOM NOTIFICATIONS/ERRORS

function base1_error_message() {
  
  if ( $response = get_transient( "base1_save_socket_response" ) ) { ?>
    <div class="notice">
        <p><?php echo $response; ?></p>
    </div><?php

    delete_transient("base1_save_socket_response");
}
  
}
add_action( 'admin_notices', 'base1_error_message' );


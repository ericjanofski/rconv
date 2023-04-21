jQuery(document).ready(function($){
  
  handleLeaderboardDelete();
    	
	if($('body').hasClass('toplevel_page_leaderboard')) {
  	  load_posts('appcms_leaderboard_ajax_load_more', 1, 0);
	};
	
	$('#clear-leaderboard').click(function(){
  	  clear_leaderboard();
	});
			
});

function clear_leaderboard() {
  var data = {
    action: 'appcms_leaderboard_ajax_clear_leaderboard',
  };
  
  jQuery.post(ajaxurl, data, function(res) {
    if(res) {
    	  var $results = jQuery( jQuery.parseHTML(jQuery.trim(res)) );
      if($results.length){
        jQuery('#results').html('<div class="inner">Leaderboard Cleared</div>');
        setTimeout(function () {
          jQuery('#results .inner').fadeOut();
        }, 5000);
      }
    }
  }).fail(function(xhr, textStatus, e) {});
}

function load_posts($action, $paged, $location_id, $sort){

  jQuery('#content').hide();
  jQuery(".loading").show();
   
  var data = {
    action: $action,
    paged: $paged,
    location_id: $location_id,
    sort : $sort
  };
  
  jQuery.post(ajaxurl, data, function(res) {
    if(res) {
    	var $results = jQuery( jQuery.parseHTML(jQuery.trim(res)) );
      if($results.length){
        
        jQuery("#content").html($results);
        handleLeaderboardDelete();
          
      } else{
          //jQuery(".load-more").hide();
      }        	  	        
    }
    jQuery('#content').show();
    jQuery(".loading").hide();
    
    // handle the dropdown
    	jQuery('#location-select').change(function(){
      load_posts('appcms_leaderboard_ajax_load_more', 1, this.value, jQuery('#sort-select').val());
	  });

    // handle the dropdown
    	jQuery('#sort-select').change(function(){
      load_posts('appcms_leaderboard_ajax_load_more', 1, jQuery('#location-select').val(), this.value);
	  });


    // handle the next link
    jQuery('#leaderboard .next, #leaderboard .previous').click(function(e){
      load_posts('appcms_leaderboard_ajax_load_more', jQuery(this).data('pager'), jQuery('#location-select').val());
      e.preventDefault();
    });

  }).fail(function(xhr, textStatus, e) {});
}

function handleLeaderboardDelete() {
  jQuery('.leaderboard-delete').click(function(e){
    
    var ids = '';
    var count = 0;
    jQuery.each(jQuery("input.delete-me:checked"), function(){       
      if(count > 0) ids += ',';
      ids += jQuery(this).val();
      count++;
    });
    
    if(ids.length > 0) {
      var r = confirm('Are you sure you want to delete these items?');
      if(r) {

        var data = {
          action: 'appcms_leaderboard_ajax_delete_items',
          ids: ids
        };
        
        jQuery.post(ajaxurl, data, function(res) {
          if(res) {            
            location.reload(true);
          }
        }).fail(function(xhr, textStatus, e) {});
        
      }
    } else {
      alert('Please select items to delete.');
    }
    
    
    
    e.preventDefault();
  });
    
}

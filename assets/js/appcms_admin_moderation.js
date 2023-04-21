jQuery(document).ready(function($){

  handleModerationApprove();
  handleModerationUnapprove();
  	
	if($('body').hasClass('toplevel_page_moderation')) {
  	  load_posts('appcms_moderation_ajax_load_more', 1, '', 'date-desc', 'unapproved', '');
	};

	if($('body').hasClass('toplevel_page_moderation-approved')) {
  	  load_posts('appcms_moderation_ajax_load_more', 1, '', 'date-desc', 'approved', '');
	};
	

				
});

function load_posts($action, $paged, $post_type, $sort, $display, $search){

  jQuery('#content').hide();
  jQuery(".loading").show();
   
  var data = {
    action: $action,
    paged: $paged,
    post_type: $post_type,
    sort : $sort,
    display : $display,
    search: $search
  };
  
  jQuery.post(ajaxurl, data, function(res) {
    if(res) {
    	var $results = jQuery( jQuery.parseHTML(jQuery.trim(res)) );
      if($results.length){
        
        jQuery("#content").html($results);
        handleModerationApprove();
        handleModerationUnapprove();

          // handle Magnific
        	jQuery('.popup').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          image: {
            verticalFit: false
          }
        });
      
        // handle the dropdown
        	jQuery('#post-type-select').change(function(){
          load_posts('appcms_moderation_ajax_load_more', 1, this.value, jQuery('#sort-select').val(), $display, '');
        });
      
        // handle the dropdown
        	jQuery('#sort-select').change(function(){
          load_posts('appcms_moderation_ajax_load_more', 1, jQuery('#post-type-select').val(), this.value, $display, jQuery('#search .search').val());
        });
      
      
        // handle the next/previous links
        jQuery('.pagination a').click(function(e){
          var hrefArray = jQuery(this).attr('href').split('=');
          var targetPage = hrefArray[1];
          console.log(targetPage);
          
          load_posts('appcms_moderation_ajax_load_more', targetPage, $post_type, $sort, $display, jQuery('#search .search').val());
          e.preventDefault();
        });
        
        
        // handle the reload button 
        jQuery('#moderation .refresh').click(function(e){
          
          load_posts('appcms_moderation_ajax_load_more', 1, jQuery('#post-type-select').val(), jQuery('#sort-select').val(), $display, jQuery('#search .search').val());
          
          e.preventDefault();
        });
        
        // handle the search submit
        jQuery('#moderation #search').submit(function(e){  	  
        	  var searchText = jQuery('#moderation #search input.search').val();
        	  if(searchText.length > 0) {
          	  load_posts('appcms_moderation_ajax_load_more', 1, '', '', $display, searchText);
        	  }
        	  e.preventDefault();
      	  });

        // handle tag click
        jQuery('#moderation #tags a').click(function(e){  	  
        	  var txt = jQuery(this).attr('href');
        	  console.log(txt);
          jQuery('#moderation #search input.search').val(txt);
          jQuery('#moderation #search').submit();
        	  e.preventDefault();
      	  });
      	
        	          
      } else{
          //jQuery(".load-more").hide();
      }        	  	        
    }
    jQuery('#content').show();
    jQuery(".loading").hide();
    
  }).fail(function(xhr, textStatus, e) {});
}


function handleModerationApprove() {
  jQuery('.moderation-approve').click(function(e){
    var ids = '';
    var count = 0;
    jQuery.each(jQuery("input.approve-me:checked"), function(){       
      if(count > 0) ids += ',';
      ids += jQuery(this).val();
      count++;
    });
    
    if(ids.length > 0) {
      var r = confirm('Are you sure you want to approve these items?');
      if(r) {
        var data = {
          action: 'appcms_moderation_ajax_approve_items',
          ids: ids
        };
        jQuery.post(ajaxurl, data, function(res) {
          if(res) {            
            location.reload(true);
          }
        }).fail(function(xhr, textStatus, e) {});
      }
    } else {
      alert('Please select items to moderate.');
    }
    e.preventDefault();
  });
}



    
function handleModerationUnapprove() {
  jQuery('.moderation-unapprove').click(function(e){
    var ids = '';
    var count = 0;
    jQuery.each(jQuery("input.unapprove-me:checked"), function(){       
      if(count > 0) ids += ',';
      ids += jQuery(this).val();
      count++;
    });
    if(ids.length > 0) {
      var r = confirm('Are you sure you want to remove approval of these items?');
      if(r) {
        var data = {
          action: 'appcms_moderation_ajax_unapprove_items',
          ids: ids
        };
        jQuery.post(ajaxurl, data, function(res) {
          if(res) {            
            location.reload(true);
          }
        }).fail(function(xhr, textStatus, e) {});
      }
    } else {
      alert('Please select items to moderate.');
    }
    e.preventDefault();
  });

}

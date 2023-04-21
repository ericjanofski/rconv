jQuery(document).ready(function($){
	
	if($('body').hasClass('toplevel_page_report')) {
  	  load_posts('appcms_report_ajax_load_more', 1, '', 'date-desc', '');
	};
				
});

function load_posts($action, $paged, $post_type, $sort, $search){

  jQuery('#content').hide();
  jQuery(".loading").show();
   
  var data = {
    action: $action,
    paged: $paged,
    post_type: $post_type,
    sort : $sort,
    search: $search
  };
  
  jQuery.post(ajaxurl, data, function(res) {
    if(res) {
    	var $results = jQuery( jQuery.parseHTML(jQuery.trim(res)) );
      if($results.length){
        
        jQuery("#content").html($results);

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
          load_posts('appcms_report_ajax_load_more', 1, this.value, jQuery('#sort-select').val(), $display, '');
        });
      
        // handle the dropdown
        	jQuery('#sort-select').change(function(){
          load_posts('appcms_report_ajax_load_more', 1, jQuery('#post-type-select').val(), this.value, $display, jQuery('#search .search').val());
        });
      
      
        // handle the next/previous links
        jQuery('.pagination a').click(function(e){
          var hrefArray = jQuery(this).attr('href').split('=');
          var targetPage = hrefArray[1];
          console.log(targetPage);
          
          load_posts('appcms_report_ajax_load_more', targetPage, $post_type, $sort, $display, jQuery('#search .search').val());
          e.preventDefault();
        });
        
        
        // handle the reload button 
        jQuery('#report .refresh').click(function(e){
          
          load_posts('appcms_report_ajax_load_more', 1, jQuery('#post-type-select').val(), jQuery('#sort-select').val(), $display, jQuery('#search .search').val());
          
          e.preventDefault();
        });
        
        // handle the search submit
        jQuery('#report #search').submit(function(e){  	  
        	  var searchText = jQuery('#report #search input.search').val();
        	  if(searchText.length > 0) {
          	  load_posts('appcms_report_ajax_load_more', 1, '', '', $display, searchText);
        	  }
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
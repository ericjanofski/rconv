jQuery(window).load(function(){});
jQuery(document).ready(function($){
  
  	// LAST MENU ITEM
	$('#access li:last-child').addClass('last');

    $('a.submitdelete').text('Delete this Item'); 
  
    // MOBILE MENU TOGGLE
    $('a.menu-toggle').click(function(e){
        $('#menu-main-menu').toggleClass('open');
        e.preventDefault();
    });
    
    $('.post-type-pledge #titlewrap').before('<div class="acf-field"><div class="acf-label"><label for="acf-field_60b8f820b1456">Touchscreen Button Pledge Text</label><p class="description">Don’t use punctuation for this shorter version of the pledge, which will be used in the app for buttons and alerts.</p></div></div>');
    $('.post-type-pledge #title-prompt-text').text('');
    

    $('#publish').click(function(){
        var testervar = $('[id^=\"titlediv\"]').find('#title');
        if (testervar.lenth && testervar.var() != '') {
            $( '#titlewrap' ).after('<div class="acf-field title-error"><div class="acf-notice -error acf-error-message"><p>This field is required</p></div></div>');
            return false;
        } else {
          $('.title-error').hide();
        }
    });

    $('.post-type-pledge #publish').on('click', function(e){
        const checkboxes = $('#taxonomy-pledge_category input[type="checkbox"]:checked')
        if(checkboxes.length == 0) {
            alert('Please assign a category.')
            e.preventDefault()
        }
    })

    // $('.post-type-app_user td.title a').on('click', function(e){
    //     e.preventDefault();
    // })

    if($('body').hasClass('post-type-app_user')) {
        $('h1.wp-heading-inline').after('<p style="max-width: 600px;" class="description">To export a database of users and pledges, use the Date From and Date To fields to filter to the desired range, then click the Export CSV button.</p>')
    }

    // if($('body').hasClass('toplevel_page_export-users')) {
    //     admin_pages_async_load_posts('appcms_admin_pages_ajax_load_more', 1, 'date-desc', 
    //     '');

    //     $('form#export-csv').on('submit', function(e) {
    //         $date_from = $('input[name="mishaDateFrom"]').val();
    //         $date_to = $('input[name="mishaDateTo"]').val();
    //         $(this).find('#base1-export-from').val($date_from)
    //         $(this).find('#base1-export-to').val($date_to)
    //     })
    // }

    handleApprovalClicks($)
});

function handleApprovalClicks($) {

    $('.app-user-unapprove').on('click', function(e){
        const id = $(this).attr('data-id')
        const container = $(this).closest('td')
        var data = {
            action: 'app_cms_approval',
            id: id,
            approved: 0
        }

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data,
            success: function(data){
                container.html('Unapproved<br /> <a data-id="' + id + '" class="app-user-approve" href="#">(make "approved")</a>')
                handleApprovalClicks($)
            }
        })    
       
        e.preventDefault()
    })

    $('.app-user-approve').on('click', function(e){
        const id = $(this).attr('data-id')
        const container = $(this).closest('td')
        console.log(id);
        var data = {
            action: 'app_cms_approval',
            id: id,
            approved: 1
        }
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: data,
            success: function(data){
                container.html('<strong style="color:green;">Approved</strong><br /> <a data-id="' + id + '" class="app-user-unapprove" href="#">(make "unapproved")</a>')
                handleApprovalClicks($)
            }
        })   

        e.preventDefault()
    })
}

// function admin_pages_async_load_posts($action, $paged, $sort, $search){

//     jQuery('#content').hide();
//     jQuery(".loading").show();
     
//     var data = {
//       action: $action,
//       paged: $paged,
//       sort : $sort,
//       search: $search
//     };
    
//     jQuery.post(ajaxurl, data, function(res) {
//       if(res) {
//           var $results = jQuery( jQuery.parseHTML(jQuery.trim(res)) );
//         if($results.length){
          
//           jQuery("#content").html($results);
//           handleModerationApprove();
//           handleModerationUnapprove();
  
//             // handle Magnific
//               jQuery('.popup').magnificPopup({
//             type: 'image',
//             closeOnContentClick: true,
//             image: {
//               verticalFit: false
//             }
//           });
    
        
//           // handle the dropdown
//         jQuery('#sort-select').change(function(){
//             admin_pages_async_load_posts('appcms_admin_pages_ajax_load_more', 1, this.value, jQuery('#search .search').val());
//         });
        
        
//           // handle the next/previous links
//           jQuery('.pagination a').click(function(e){
//             var hrefArray = jQuery(this).attr('href').split('=');
//             var targetPage = hrefArray[1];
//             console.log(targetPage);
            
//             admin_pages_async_load_posts('appcms_admin_pages_ajax_load_more', targetPage, $sort, jQuery('#search .search').val());
//             e.preventDefault();
//           });
          
//           // handle the search submit
//           jQuery('#moderation #search').submit(function(e){  	  
//                 var searchText = jQuery('#moderation #search input.search').val();
//                 if(searchText.length > 0) {
//                   admin_pages_async_load_posts('appcms_admin_pages_ajax_load_more', 1, $sort, searchText);
//                 }
//                 e.preventDefault();
//               });
  
//           // handle tag click
//           jQuery('#moderation #tags a').click(function(e){  	  
//                 var txt = jQuery(this).attr('href');
//                 console.log(txt);
//             jQuery('#moderation #search input.search').val(txt);
//             jQuery('#moderation #search').submit();
//                 e.preventDefault();
//               });
            
                        
//         } else{
//             //jQuery(".load-more").hide();
//         }        	  	        
//       }
//       jQuery('#content').show();
//       jQuery(".loading").hide();
      
//     }).fail(function(xhr, textStatus, e) {});
//   }
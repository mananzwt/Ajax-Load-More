var $ = jQuery.noConflict();

/* Script on ready
------------------------------------------------------------------------------*/
$(document).ready(function() {
    //do jQuery stuff when DOM is ready

    // fires button click event
    $(document).on('click', '#load_my_blog_posts', function() {
        jQuery.ajax({
            type: "post",
            url: myChildAjax.ajaxurl, // AJAX handler
            data: {
                'action': 'load_posts',
                'security': myChildAjax.ajax_nonce,
                'title' : 'Hello World',
                'content': 'lorem ipsum dummy content',
            },
            dataType: 'json',
            cache: false,
            success: function(result) {
                // success response code here
            }
        });
    });
    
    
    
    // pagination numbers click event
    $(document).on( 'click', '.pagination a', function( event ) {
    
        event.preventDefault();
        var paged = $(this).attr('data-id');
        var posts_per_page = jQuery(document).find('#posts_per_page').val();

        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: myChildAjax.ajaxurl,
            data: {
                'action': 'more_post_ajax',
                'security': myChildAjax.ajax_nonce,
                'paged': paged,
                'posts_per_page': posts_per_page
            },
            success: function (data) {
                
                var $data = jQuery(data);
                
                if ($data.length) {
                    jQuery("#ajax-posts").html(data.message);
                }

                var next_page_id = parseInt(paged) + 1;
                if(paged > 0){
                    var prev_page_id = parseInt(paged) - 1;
                }else{
                    var prev_page_id = 0;
                }
                
                jQuery("#prev-post").attr('data-id',prev_page_id);
                jQuery("#next-post").attr('data-id',next_page_id);
                
            }
        });

    });
    
    
    // javascript version of the ajax
    // pagination numbers click event
    $(document).on( 'click', '.pagination a', function( event ) {
    
        //event.preventDefault();
        var paged 			= $(this).attr('data-id');
        var posts_per_page  = jQuery(document).find('#posts_per_page').val();

	    var xhttp = new XMLHttpRequest();

	    xhttp.open("POST", myChildAjax.ajaxurl, true);
	    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");

	    xhttp.onload = function () {

	        if (this.status >= 200 && this.status < 400) {

	            let response = JSON.parse(this.responseText);
	    		document.getElementById("ajax-posts").innerHTML = response.message;

	    		var next_page_id = parseInt(paged) + 1;
                if(paged > 0){
                    var prev_page_id = parseInt(paged) - 1;
                }else{
                    var prev_page_id = 0;
                }

                //jQuery("#prev-post").attr('data-id',prev_page_id);
                if(document.getElementsByClassName("prev-post")[0] != undefined){
                	document.getElementsByClassName("prev-post")[0].setAttribute("data-id", prev_page_id);	
                }
                
                //jQuery("#next-post").attr('data-id',next_page_id);
                if(document.getElementsByClassName("next-post")[0] != undefined){
	                document.getElementsByClassName("next-post")[0].setAttribute("data-id", next_page_id);
            	}


	        } else {
	            // If fail
	            console.log(this.response);
	        }
	    };
	    xhttp.onerror = function() {
	        // Connection error
	    };

	    // send the data with httprequest
    	xhttp.send('_ajax_nonce='+myChildAjax.ajax_nonce+'&action=more_post_ajax&paged='+paged+'&posts_per_page='+posts_per_page);
    
	});
    
    
});

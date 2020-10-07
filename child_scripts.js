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
});

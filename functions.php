<?php 

/**
 * Enqueues scripts for ajax loadmore.
 *
 *
 */
add_action( 'wp_enqueue_scripts', 'my_child_theme_register_scripts' );

function my_child_theme_register_scripts() {
   
    // Register new JS file, this is the path of the JS file
    wp_register_script( 'child_scripts', get_template_directory_uri() .'/assets/js/child_scripts.js', array('jquery') ); 

    // localize the script
    wp_localize_script( 'child_scripts', 'myChildAjax', array( 
        'ajaxurl'    => admin_url( 'admin-ajax.php' ),
        'ajax_nonce' => wp_create_nonce('posts_load_nonce'),
    ));
   
   // enqueue the script that we have just registered
   wp_enqueue_script( 'child_scripts' );

}


/**
 * ajax function to call when click on pagination links
 *
 *
 */
add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax');
add_action('wp_ajax_more_post_ajax', 'more_post_ajax');

function more_post_ajax() {
  
    $ppp  = (isset($_POST["posts_per_page"])) ? $_POST["posts_per_page"] : 1;
    $page = (isset($_POST['paged'])) ? $_POST['paged'] : 0;

    $args = array(
        'post_type' 	      => 'post',
        'posts_per_page'   => $ppp,
        'paged' 		      => $page,
    );
  
    $loop = new WP_Query($args);
    $out  = '';
  
    if ($loop->have_posts()) : 

    	   while ($loop->have_posts()) : $loop->the_post();
            $out .= '<div class="small-12"><h3>' . get_the_title() . '</h3></div>';
         endwhile;

   
         if(!empty($out)){

	    	   $success 	  = true;
		      $result_array = array( 'success' => $success, 'message' => $out );

			   echo json_encode($result_array);
			   wp_die();

	    }else{

	    	   $success 	  = false;
		      $result_array = array( 'success' => $success, 'message' => 'Error in response' );

			   echo json_encode($result_array);
			   wp_die();
            
	    }

	    wp_reset_postdata();

    else: 

		   $success 	  = false;
	      $result_array = array( 'success' => $success, 'message' => 'No Posts Found' );

		   echo json_encode($result_array);
		   wp_die();

    endif;

}
  


/**
 * wordpress numeric pagination function
 *
 *
 */
function pagination($pages = '', $range = 4){

    $showitems = ($range * 2) + 1;  
 
    global $paged;
    if(empty($paged)) $paged = 1;
 
    if($pages == ''){

        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages){
            $pages = 1;
        }
    }
 
    if(1 != $pages){

        echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
       
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) {
           echo "<a href='javascript: void(0);' data-id='1' >&laquo; First</a>";
        }
       
        if($paged > 1 && $showitems < $pages) {
           echo "<a href='javascript: void(0);' data-id='".($paged - 1)."' id=\"prev-post\">&lsaquo; Previous</a>";
        }
 
        for ($i=1; $i <= $pages; $i++){
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
                echo ($paged == $i)? "<a href='javascript: void(0);' data-id='".($i)."' class=\"current\">".$i."</a>":"<a data-id='".($i)."' href='javascript: void(0);' class=\"inactive\">".$i."</a>";
            }
        }
 
        if ($paged < $pages && $showitems < $pages) {
           echo "<a href='javascript: void(0);' data-id='".($paged + 1)."' id=\"next-post\">Next &rsaquo;</a>";
        }
       
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) {
           echo "<a href='javascript: void(0);' data-id='".($pages)."' >Last &raquo;</a>";
        }
       
        echo "</div>\n";
     }
}

?>

<?php 
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

} ?>

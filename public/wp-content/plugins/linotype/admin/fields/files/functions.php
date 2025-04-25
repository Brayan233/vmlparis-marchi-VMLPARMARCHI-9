<?php 

//include 'handypress.uploader/handypress.uploader.class.php';

if ( isset( $META['id'] ) && $META['id'] ) {

    $default_options = array(
        "placeholder" => false,
        "input" => true,
    );

    $META['options'] = wp_parse_args( $META['options'], $default_options );

    //create uploader
    $META['uploader_object'] = new handypress_uploader( 'printflight_uploader_' . $META['id'], $META['options'] );

}

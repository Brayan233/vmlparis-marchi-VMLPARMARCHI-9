<?php 

//remove old woocommerce select2 scripts
if ( !function_exists('filter_script_loader_src') ) {

	function filter_script_loader_src( $version, $wpemoji ) { 

	    if( $wpemoji == 'select2' ) $version = false;
	    if( $wpemoji == 'wc-enhanced-select' ) $version = false;

	    return $version; 

	}; 

	//add_filter( 'script_loader_src', 'filter_script_loader_src', 10, 2 );

}

?>
<?php 

block( 'wc_get_template', array(
	'cart/mini-cart.php' => dirname( __FILE__ ) . '/mini-cart.php',
));

function get_wc_block_cart_mini_count( $return = false ) {
	
	$count = WC()->cart->get_cart_contents_count();
	if ( $count == 0 ) $count = "";

	if ( $return ) {
		return '<div class="wc_block_cart_mini-count">' . $count . '</div>';
	} else {
		echo '<div class="wc_block_cart_mini-count">' . $count . '</div>';
	}

}

add_action( 'wc_block_cart_mini_count', 'get_wc_block_cart_mini_count' );

function iconic_cart_count_fragments( $fragments ) {
    
    $fragments['div.wc_block_cart_mini-count'] = get_wc_block_cart_mini_count(true);
    
    return $fragments;
    
}

add_filter( 'woocommerce_add_to_cart_fragments', 'iconic_cart_count_fragments', 10, 1 );

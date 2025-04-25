<?php 

//overwrite template
block( 'wc_get_template', array(
	'checkout/form-checkout.php' => dirname( __FILE__ ) . '/checkout/form-checkout.php',
	'checkout/thankyou.php' => dirname( __FILE__ ) . '/checkout/thankyou.php',
));

//customize the checkout item
add_filter( 'woocommerce_cart_item_name', function( $product_name, $cart_item, $cart_item_key ){
	
	if ( is_checkout() ) {
		
		$thumbnail   = $cart_item['data']->get_image(array( 80, 80));

		$category = "";
		$tsf = function_exists( 'the_seo_framework' ) ? the_seo_framework() : null;
		if ( $tsf ) {
			$primary_term = $tsf->get_primary_term( $cart_item['product_id'], 'product_cat' );
			if ( $primary_term ) $category = $primary_term->name;
		} else {
			$terms = get_the_terms ( $cart_item['product_id'], 'product_cat' );
			if ( isset( $terms[0]->term_id ) ) $category = $terms[0]->name;
		}
		
		$product_name = '<div class="product-thumbnail">' . $thumbnail . '</div><div class="product-name"><div class="category">' . $category . '</div>' . '<div class="name">' . $product_name . '</div></div>';

    }

	return $product_name;

}, 20, 3 );

//add apple pay and google pay on checkout
add_filter( 'wc_stripe_show_payment_request_on_checkout', '__return_true' );
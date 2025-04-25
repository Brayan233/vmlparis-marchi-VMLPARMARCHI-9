<?php block( 'header', $settings ); 
	
	while ( have_posts() ) : the_post();

		woocommerce_cart_totals();
  
	endwhile;

block( 'footer', $settings );
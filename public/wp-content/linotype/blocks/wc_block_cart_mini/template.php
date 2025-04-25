<?php block( 'header', $settings ); 

	echo '<div class="widget_shopping_cart_content">';
		woocommerce_mini_cart();
	echo '</div>';

block( 'footer', $settings );

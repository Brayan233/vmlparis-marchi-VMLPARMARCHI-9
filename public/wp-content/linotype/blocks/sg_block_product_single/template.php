<?php 

block( 'header', $settings );

	do_action( 'woocommerce_before_main_content' );
	
	while ( have_posts() ) :
		
		the_post(); 

		global $product;

		do_action( 'woocommerce_before_single_product' );

		if ( post_password_required() ) {
			echo get_the_password_form(); // WPCS: XSS ok.
			return;
		}
		
		?> <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>> <?php
		
			echo '<div class="left">';

				do_action( 'woocommerce_before_single_product_summary' );

			echo '</div>';

			echo '<div class="right">';

				echo '<div class="product-info">';

					$category = "";
					$tsf = function_exists( 'the_seo_framework' ) ? the_seo_framework() : null;
					if ( $tsf ) {
						$primary_term = $tsf->get_primary_term( get_the_ID(), 'product_cat' );
						if ( $primary_term ) $category = $primary_term->name;
					} else {
						$terms = get_the_terms ( get_the_ID(), 'product_cat' );
						if ( isset( $terms[0]->term_id ) ) $category = $terms[0]->name;
					}

					echo '<p class="product-collection">' . $category . '</p>';

					echo '<h2 class="product-name">' . get_the_title() . '</h2>';

					if ( $options['presentation'] ) echo '<div class="product-description">' . $options['presentation'] . '</div>';

					woocommerce_template_single_add_to_cart();

					echo '<div class="product-details">';
						if ( $options['description'] ) {
							echo '<div class="accordion-block">';
								echo '<h3 id="descriptionTitle">';
									echo '<button type="button" class="btn-unstyled accordion-toggle" aria-expanded="false" aria-controls="descriptionBlock">' . linotrad('Description') . '</button>';
								echo '</h3>';
								echo '<div class="accordion-collapse" aria-labelledby="descriptionTitle"><div class="inner">' . $options['description']. '</div></div>';
							echo '</div>';
						}
						if ( $options['details'] ) {
							echo '<div class="accordion-block">';
								echo '<h3 id="detailsTitle">';
									echo '<button type="button" class="btn-unstyled accordion-toggle" aria-expanded="false" aria-controls="detailsBlock">' . linotrad('Details') . '</button>';
								echo '</h3>';
								echo '<div class="accordion-collapse" aria-labelledby="detailsTitle"><div class="inner">' . $options['details']. '</div></div>';
							echo '</div>';
						}
						if ( $options['more'] ) {
							echo '<div class="accordion-block">';
								echo '<h3 id="careTitle">';
									echo '<button type="button" class="btn-unstyled accordion-toggle" aria-expanded="false" aria-controls="careBlock">' . linotrad('Use') . '</button>';
								echo '</h3>';
								echo '<div class="accordion-collapse" aria-labelledby="careTitle"><div class="inner">' . $options['more']. '</div></div>';
							echo '</div>';
						}
					echo'</div>';

				echo '</div>';

			echo '</div>';

			do_action( 'woocommerce_single_product_summary' );
			
			do_action( 'woocommerce_after_single_product_summary' );

		?> </div> <?php

		do_action( 'woocommerce_after_single_product' ); 

	endwhile; 

	do_action( 'woocommerce_after_main_content' );

block( 'footer', $settings );
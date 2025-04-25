<?php 

block( 'header', $settings );

	echo '<div class="container">';

		echo '<div class="col2-set account-layout">';

			echo '<div class="col-1">';

				if ( is_user_logged_in() ) { 

					echo '<h2 class="checkout-lvl1-title account-title">' . linotrad('Welcome') . '<br/>' . wp_get_current_user()->display_name . '</h2>';
				
				} else {
					
					echo '<h2 class="checkout-lvl1-title account-title">' . linotrad('Account') . '</h2>';

				}

				if ( is_user_logged_in() ) { 
	
					echo linotype_field_wp_menu( $options['menu'], array( 
						"level_1" => array(
							"div" => "",
							"ul" => "nav account-nav", 
							"li" => "nav-item",
							"a" => "nav-link",
							"a_child" => "nav-link sub-nav-toggle",
							"before" => "",
							"after" => ""
						),
						"level_2" => array(
							"div" => "sub-nav-wrap",
							"ul" => "nav sub-nav", 
							"li" => "nav-item",
							"a" => "nav-link",
							"a_child" => "nav-link sub-nav-toggle",
							"before" => "",
							"after" => ""
						),
						"level_3" => array(
							"div" => "sub-nav-wrap",
							"ul" => "nav sub-nav", 
							"li" => "nav-item",
							"a" => "nav-link",
							"a_child" => "",
							"before" => "",
							"after" => ""
						)
					));

				}
				
				echo '<div class="btn-list account-actions">';

					echo '<a href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '" class="btn btn-block">' . linotrad('Back to Shop') . '</a>';
					if ( is_user_logged_in() ) echo '<a href="' . wp_logout_url('/') . '" class="btn btn-block btn-block-hollow">' . linotrad('Logout') . '</a>';

				echo '</div>';

			echo '</div>';

			echo '<div class="col-2">';

				block( 'content', $settings );

			echo '</div>';

		echo '</div>';

	echo '</div>';

block( 'footer', $settings );

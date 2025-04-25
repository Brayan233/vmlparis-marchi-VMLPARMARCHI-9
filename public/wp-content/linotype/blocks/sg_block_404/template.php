<?php 

block( 'header', $settings );

	echo '<div class="container">';

		echo '<div class="lvl1-title error-title">' . $options['title'] . '</div>';

		if ( $options['desc'] ) echo '<div class="error-text">' . $options['desc'] . '</div>';

		if ( $options['link_url'] ) echo '<a href="' . $options['link_url'] . '" class="btn btn-primary btn-arrow">' . $options['link_title'] . '</a>';

	echo '</div>';

block( 'footer', $settings );

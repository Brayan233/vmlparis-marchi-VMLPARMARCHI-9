<?php 

block( 'header', $settings );

	echo '<div class="container">';

		echo '<div class="content-text">' . $options['content'] . '</div>';

	echo '</div>';

block( 'footer', $settings );

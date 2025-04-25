<?php

block('header', $settings );
	
echo '<div class="container">';
         
    echo '<div class="quote-text">' . $options['quote'] . '</div>';

    echo '<div class="content-text">' . $options['content'] . '</div>';

echo '</div>';

block('footer', $settings );

<?php

$hasAuthor = '';
if ( $options['author'] ) $hasAuthor = 'has-author';

block('header', $settings, array( 'class' => $hasAuthor ) );

	
echo '<div class="quote-content">';
         
    echo '<div class="quote-text">' . $options['quote'] . '</div>';
    if ( $options['author'] )
        echo '<div class="quote-author">' . $options['author'] . '</div>';

echo '</div>';

block('footer', $settings );

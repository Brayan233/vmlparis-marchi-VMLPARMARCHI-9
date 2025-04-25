<?php

block('header', $settings );

echo '<div class="container hero-content">';
         
  echo '<h2 class="hero-title">' . $options['quote'] . '</h2>';

  if ( $link = get_linotype_field_link( $options['link'] ) ) {
    
    echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '" class="btn btn-secondary btn-arrow hero-link">' . $link['title'] . '</a>';

  }

echo '</div>';

block('footer', $settings );

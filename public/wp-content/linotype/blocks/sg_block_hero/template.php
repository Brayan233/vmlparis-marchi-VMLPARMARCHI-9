<?php

block('header', $settings );

if ( $options['img'] ) {

  echo '<div class="hero-banner">';
  
    echo get_linotype_field_image( array(
      'alt' => '',
      'class' => '',
      'sources' => array(
        array(
          'id' => $options['img'],
          'break' => 1400,
          'crop' => true,
          'x' => 1620,
          'y' => 720,
        ),
        array(
          'id' => $options['img'],
          'break' => 700,
          'crop' => true,
          'x' => 1080,
          'y' => 440,
        ),
        array(
          'id' => $options['img_mobile'],
          'break' => 0,
          'crop' => true,
          'x' => 375,
          'y' => 480,
        ),
      ),
      'compress' => $options['compress'],
      'lazyload' => true,
      'fadein' => true,
      'webp' => true,
    ));

    if ( $link = get_linotype_field_link( $options['link'] ) ) {

      $noopener = '';
      if ( $link['target'] == '_blank' ) $noopener = 'rel="noopener"';

      echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '" ' . $noopener . ' class="btn btn-secondary btn-arrow hero-link desktop-hide">' . $link['title'] . '</a>';

    }

  echo '</div>';

}

echo '<div class="container hero-content">';
         
  echo '<h2 class="hero-title">' . $options['quote'] . '</h2>';

  if ( $link = get_linotype_field_link( $options['link'] ) ) {
    
    echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '" class="btn btn-secondary btn-arrow hero-link mobile-hide">' . $link['title'] . '</a>';

  }

echo '</div>';

block('footer', $settings );

<?php

if ( $options['title'] ) {

  block('header', $settings );

  if ( $options['img'] ) {

    echo get_linotype_field_image( array(
      'alt' => '',
      'class' => '',
      'sources' => array(
        array(
          'id' => $options['img'],
          'break' => 1400,
          'crop' => true,
          'x' => 1620,
          'y' => 232,
        ),
        array(
          'id' => $options['img'],
          'break' => 768,
          'crop' => true,
          'x' => 1080,
          'y' => 232,
        ),
        array(
          'id' => $options['img_mobile'],
          'break' => 0,
          'crop' => true,
          'x' => 375,
          'y' => 232,
        )
      ),
      'compress' => $options['compress'],
      'lazyload' => true,
      'fadein' => true,
      'webp' => true,
    ));
 
    echo '<div class="title">';
      echo '<div class="container">';
        echo '<p>' . $options['title'] . '</p>';
      echo '</div>';
    echo '</div>';

  }

  block('footer', $settings );

}

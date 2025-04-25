<?php

echo '<div class="row">';

  echo '<div class="column padding" style="min-width: 200px;">';
    echo '<h6>Titre:</h6>';
    echo '<h2 class="value">' . $options['title'] . '</h2>';
  
    echo '<h6>Link:</h6>';
    if ( $link = get_linotype_field_link( $options['link'] ) ) {
        
        echo '<p class="hero-link">';
            echo $link['title'] . ' (' . $link['url'] . ')';
        echo '</p>';

    }
  echo '</div>';



    if ( $options['links'] ) {
      foreach ( $options['links'] as $item ) {
        
        echo '<div class="image">';
            
          echo get_linotype_field_image( array(
              'alt' => '',
              'class' => '',
              'sources' => array(
                  array(
                      'id' => $item['img'],
                      'break' => 0,
                      'crop' => false,
                      'x' => 400,
                      'y' => 0,
                      )
                  ),
              'compress' => $options['compress'],
              'lazyload' => false,
              'ratio' => false,
              'webp' => true,
          ));

        echo '</div>';

      }
    }

 

echo '</div>';

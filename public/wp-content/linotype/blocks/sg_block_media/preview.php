<?php

if ( $options['image_pos'] == 'image_full' ) {

    $image = get_linotype_field_image( array(
      'alt' => '',
      'class' => '',
      'sources' => array(
        array(
          'id' => $options['img'],
          'break' => 1200,
          'crop' => true,
          'x' => 1600,
          'y' => 838,
        ),
        array(
          'id' => $options['img'],
          'break' => 768,
          'crop' => true,
          'x' => 1080,
          'y' => 566,
        ),
        array(
          'id' => $options['img_mobile'],
          'break' => 0,
          'crop' => true,
          'x' => 750,
          'y' => 1300,
        )
      ),
      'compress' => $options['compress'],
      'lazyload' => false,
      'webp' => true,
    ));

  } else {

    $image = get_linotype_field_image( array(
      'alt' => '',
      'class' => '',
      'sources' => array(
          array(
            'id' => $options['img'],
            'break' => 0,
            'crop' => true,
            'x' => 800,
            'y' => 800,
          )
      ),
      'compress' => $options['compress'],
      'lazyload' => false,
      'webp' => true,
    ));

  }

echo '<div class="row" style="background-color:' . $options['color_bg'] . '">';

    $pre = '';
    
    if ( $options['image_pos'] == "" ) {
        
        echo '<div class="column column-100 column-bg">' . $image . '</div>';
        $pre = 'column-pre-60';

    }

    if ( $options['image_pos'] == "image_full" ) {
        
        echo '<div class="column column-100 column-bg">' . $image . '</div>';
        $pre = 'column-pre-60';

    }

    if ( $options['image_pos'] == "image_left" ) echo '<div class="column padding">' . $image . '</div>';

    echo '<div class="column ' . $pre . ' padding" style="padding: 100px 30px;">';

        echo '<h6  style="color:' . $options['color_text'] . '">Title:</h6>';
        echo '<h2 class="value"  style="color:' . $options['color_text'] . '">' . $options['title'] . '</h2>';
        
        echo '<h6  style="color:' . $options['color_text'] . '">Description:</h6>';
        echo '<p class="value"  style="color:' . $options['color_text'] . '">' . $options['desc'] . '</p>';

        echo '<h6  style="color:' . $options['color_link'] . '">Link:</h6>';
        if ( $link = get_linotype_field_link( $options['link'] ) ) {
          
            echo '<p class="hero-link"  style="color:' . $options['color_link'] . '">';
                echo $link['title'] . ' (' . $link['url'] . ')';
            echo '</p>';
  
        }

    echo '</div>';

    if ( $options['image_pos'] == "image_right" ) echo '<div class="column padding">' . $image . '</div>';

echo '</div>';

<?php 

block('header', $settings );

  echo '<div class="container block-heading">';
        
    echo '<h2 class="lvl2-title">' . $options['title'] . '</h2>';

    if ( $link = get_linotype_field_link( $options['link'] ) ) {
      
      $noopener = '';
      if ( $link['target'] == '_blank' ) $noopener = 'rel="noopener"';

      echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '" ' . $noopener . ' class="btn btn-primary btn-arrow block-heading-link">' . $link['title'] . '</a>';

    }

  echo '</div>';

  echo '<div class="category-list">';

    foreach ( get_sg_block_categories( $options ) as $item ) {

      echo '<a href="' . $item['url'] . '" class="category">';
        
        echo get_linotype_field_image( array(
          'alt' => '',
          'class' => '',
          'sources' => array(
            array(
              'id' => $item['img'],
              'break' => 768,
              'crop' => true,
              'x' => 534,
              'y' => 534
            ),
            array(
              'id' => $item['img'],
              'break' => 0,
              'crop' => true,
              'x' => 750,
              'y' => 560,
            ),
          ),
          'compress' => $options['compress'],
          'lazyload' => true,
          'fadein' => true,
          'webp' => true,
        ));
      
        echo '<div class="category-name">' . $item['name'] . ' (' . $item['count'] . ')</div>';
        
      echo '</a>';

    }

  echo '</div>';

block('footer', $settings );

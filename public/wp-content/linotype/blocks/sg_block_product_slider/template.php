<?php 

block('header', $settings );

  echo '<div class="container">';

    echo '<div class="block-heading">';

      echo '<h2 class="lvl2-title title">' . $options['title'] . '</h2>';

      if ( $link = get_linotype_field_link( $options['link'] ) ) {
        $noopener = '';
        if ( $link['target'] == '_blank' ) $noopener = 'rel="noopener"';
        echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '" ' . $noopener . ' class="btn btn-primary btn-arrow block-heading-link mobile-hide">' . $link['title'] . '</a>';
      }

    echo '</div>';

    $products = new sg_block_product_slider( $options );

    echo '<div class="product-slider">';

      foreach( $products->loop() as $item ) {

        echo '<div>';

          echo '<a href="' . $item['url'] . '" class="product-thumbnail">';

            echo get_linotype_field_image( array(
              'alt' => '',
              'class' => '',
              'sources' => array(
                array(
                  'id' => $item['image'],
                  'break' => 0,
                  'crop' => true,
                  'x' => 360,
                  'y' => 360
                  )
                ),
              'compress' => $options['compress'],
              'ratio' => false,
              'lazyload' => false,
              'srcsetname' => 'srcset',
              'srcname' => 'lazy',
              'webp' => true,
            ));

            echo '<div class="product-info">';
              echo '<div>';
                echo '<p class="product-category">' . $item['category'] . '</p>';
                echo '<h4 class="product-title">' . $item['title'] . '</h4>';
                echo '<p class="product-price">';
                  echo $item['price'];
                echo '</p>';
              echo '</div>';
              echo '<div>';
                echo '<div class="product-colors">' . $item['colors'] . '</div>';
                echo '<div class="product-sizes">' . $item['sizes'] . '</div>';
              echo '</div>';
            echo '</div>';

          echo '</a>';

        echo '</div>';
      };

    echo '</div>';

  echo '</div>';


  if ( $link = get_linotype_field_link( $options['link'] ) ) {
    $noopener = '';
    if ( $link['target'] == '_blank' ) $noopener = 'rel="noopener"';
    echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '" ' . $noopener . ' class="btn btn-primary btn-arrow bottom-link desktop-hide">' . $link['title'] . '</a>';
  }
  
block('footer', $settings );

<?php 

// image sizes : 211x211 (retina 422x422)

block('header', $settings );

  $products = new sg_block_product_grid( $options );

  echo '<div class="product-grid">';
  
    foreach( $products->loop() as $item ) {
        
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
              'y' => 360,
            )
          ),
          'compress' => $options['compress'],
          'lazyload' => true,
          'fadein' => true,
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
    };

  echo '</div>';

block('footer', $settings );

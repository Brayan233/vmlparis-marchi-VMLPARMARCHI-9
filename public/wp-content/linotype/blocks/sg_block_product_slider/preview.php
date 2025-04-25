<?php

echo '<div class="row">';

  echo '<div class="column padding">';

    echo '<h6>Title:</h6>';
    echo '<h2 class="value">' . $options['title'] . '</h2>';

    echo '<h6>Link:</h6>';
    if ( $link = get_linotype_field_link( $options['link'] ) ) {
        
        echo '<p>';
            echo $link['title'] . ' (' . $link['url'] . ')';
        echo '</p>';

    }

  echo '</div>';

  echo '<div class="column padding">';

    echo '<h6>Products:</h6>';

    $products = array();

    $ids = explode( ',', $options['post_ids'] );
        
    foreach( $ids as $id ) {

        $product = wc_get_product( $id );

        $category = "";
        $tsf = function_exists( 'the_seo_framework' ) ? the_seo_framework() : null;
        if ( $tsf ) {
            $primary_term = $tsf->get_primary_term( $id, 'product_cat' );
            if ( $primary_term ) $category = $primary_term->name;
        } else {
            $terms = get_the_terms ( $id, 'product_cat' );
            if ( isset( $terms[0]->term_id ) ) $category = $terms[0]->name;
        }

        $products[] = array(
            "title" => get_the_title( $id ),
            "category" => $category,
        );
        
    }
    
    echo '<ul>' ; 
    foreach( $products as $item ) {
      echo '<li>' ; 
        echo $item['category'];
        echo $item['title'];
      echo '</li>' ;
    };
    echo '</ul>' ;
    
  echo '</div>';

echo '</div>';

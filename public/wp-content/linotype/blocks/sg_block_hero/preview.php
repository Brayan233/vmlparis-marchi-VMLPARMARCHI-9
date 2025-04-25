<?php

$image = get_linotype_field_image( array(
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
    'lazyload' => false,
    'fadein' => true,
    'webp' => true,
));

echo '<div class="row">';

    echo '<div class="column-100">';

        if ( $image ) echo $image . '<br/>';

        echo '<h6>Quote:</h6>';
        echo '<p class="value" style="font-size: 24px;">' . $options['quote'] . '</p>';

        echo '<h6>Link:</h6>';
        if ( $link = get_linotype_field_link( $options['link'] ) ) {
          
            echo '<p class="hero-link">';
                echo $link['title'] . ' (' . $link['url'] . ')';
            echo '</p>';
  
        }
        
    echo '</div>';

echo '</div>';

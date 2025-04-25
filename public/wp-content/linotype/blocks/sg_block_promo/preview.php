<?php

$image = get_linotype_field_image( array(
    'alt' => '',
    'class' => '',
    'sources' => array(
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
    'lazyload' => false,
    'webp' => true,
));

echo '<div class="row">';

    if ( $image ) echo '<div class="column bg">' . $image . '</div>';

    echo '<div class="column ' . $pre . ' padding" style="padding: 100px 30px;">';

        echo '<h6>Title:</h6>';
        echo '<h2 class="value"  style="font-size: 20px;">' . $options['title'] . '</h2>';

    echo '</div>';

echo '</div>';

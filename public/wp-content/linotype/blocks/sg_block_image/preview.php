<?php

$image = get_linotype_field_image( array(
    'alt' => '',
    'class' => '',
    'sources' => array(
        array(
        'id' => $options['img'],
        'break' => 1200,
        'crop' => true,
        'x' => 1620,
        'y' => 720,
        ),
        array(
        'id' => $options['img'],
        'break' => 768,
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
    'webp' => true,
));

echo '<div class="row">';

    echo '<div class="column-100 hero">';

        if ( $image ) echo '<div class="column bg">' . $image . '</div>';

    echo '</div>';

echo '</div>';

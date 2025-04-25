<?php 
echo get_linotype_field_image( array(
    'alt' => '',
    'class' => '',
    'sources' => array(
        array(
        'id' => $options['picto'],
        'break' => 0,
        'crop' => true,
        'x' => 300,
        'y' => 300,
        )
    ),
    'lazyload' => false,
    'webp' => true,
));
if ( $options['title'] ) echo '<h1>' . $options['title'] . '</h1>';
if ( $options['desc'] ) echo '<p>' . nl2br( $options['desc'] ) . '</p>';
<?php

if ( ! $options['container'] ) $options['container'] = 'container';

block( 'header', $settings, array( 'class' => array( 'grid', $options['position'], $options['container'], $options['wrap'], $options['pos'], $options['align_h'], $options['align_v'], $options['bleed'] ) ) );
  
	block( 'content', $settings );

block( 'footer', $settings ); 

// block('styles', $settings, array(

//   "_root" => array(
//     "height" => "calc( " . $options['height'] . " - " . $options['offset'] . " )",
//   )
  
// ));

?>
<?php 

block( 'header', $settings ); 

block( 'styles', $settings, array( 
  '.linotype_block_margin' => array( 
    'height' => floor( $options['height'] ) . 'px',
  )
)); 

block( 'footer', $settings ); 

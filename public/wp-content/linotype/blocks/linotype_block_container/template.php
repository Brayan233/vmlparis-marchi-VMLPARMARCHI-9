<?php 

block( 'styles', $settings, array( 
  '.linotype_block_container' => array( 
    'padding-top' => floor( $options['padding_top'] ) . 'px',
    'padding-bottom' => floor( $options['padding_bottom'] ) . 'px',
  )
)); 

block( 'header', $settings ); 

  block( 'content', $settings );

block( 'footer', $settings ); 

?>
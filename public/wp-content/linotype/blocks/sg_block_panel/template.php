<?php 

block( 'header', $settings, array( 'class' => array( $options['position'] ), 'data' => array( 'trigger' => $options['trigger'] ) ) ); 

  echo '<div class="sg_block_panel-close" aria-label="' . linotrad('Close') . '"></div>';
  
  echo '<div class="sg_block_panel-content">';

    block( 'content', $settings );

  echo '</div>';

block( 'footer', $settings ); 

?>

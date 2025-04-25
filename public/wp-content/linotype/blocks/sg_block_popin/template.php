<?php 

block( 'header', $settings, array( 'data' => array( 
  'uid' => $options['uid'], 
  'trigger' => $options['trigger'], 
  'trigger_by_delay' => $options['trigger_by_delay'],
  'force_date_start' => $options['force_date_start'],
  'force_date_end' => $options['force_date_end'],
) ) );

  echo '<div class="sg_block_popin-close-wrapper">';
    echo '<div class="container">';
      echo '<button type="button" class="btn-unstyled tap-expand-before sg_block_popin-close" aria-label="Fermer"></button>';
    echo '</div>';
  echo '</div>';
  
  echo '<div class="sg_block_popin-container">';
    echo '<div class="sg_block_popin-content">';
      block( 'content', $settings );
    echo '</div>';
  echo '</div>';

block( 'footer', $settings ); 

?>

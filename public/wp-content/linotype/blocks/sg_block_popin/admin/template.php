<?php block_composer('header', $settings ); ?>

  <div class="composer-item-content">

    <?php 
    echo '<div style="padding:0 20px 20px 20px">';
      echo 'Unique ID: ' . $options['uid'] . '<br/>';
      echo 'Trigger by delay: ' . $options['trigger_by_delay'] . '<br/>';
      echo 'Force display on date: ' . $options['force_date_start'] . ' - ' . $options['force_date_end'] . '<br/>';
      echo 'Trigger by delay: ' . $options['trigger_by_delay'] . '<br/>';
      if ( $options['trigger'] ) echo 'Trigger: ' . $options['trigger'] . '<br/>';
    echo '</div>';
    ?>

    <div class="container-full">

      <?php 
      
      $params = null;
      if ( isset( $options['inline'] ) && $options['inline'] ) $params = array( 'inline' => true, 'add_button_pos' => 'right', );
      
      block_composer('content', $settings, $params );
      
      ?>

    </div>
    
 	</div>

<?php block_composer('footer', $settings ); ?>
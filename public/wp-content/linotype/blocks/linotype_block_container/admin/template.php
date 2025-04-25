<?php block_composer('header', $settings ); ?>

  <div class="composer-item-content">
        
    <div class="container-full">

      <?php 
      
      $params = null;
      if ( isset( $options['inline'] ) && $options['inline'] ) $params = array( 'inline' => true, 'add_button_pos' => 'right', );
      
      block_composer('content', $settings, $params ); 
      
      ?>

    </div>
    
 	</div>

<?php block_composer('footer', $settings ); ?>
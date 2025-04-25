<?php block_composer('header', $settings ); ?>

  <div class="composer-item-content">
        
    <div class="container-full">

      <?php block_composer('content', $settings, array( 'inline' => true, 'add_button_pos' => 'right', ) ); ?>

    </div>
    
 	</div>

<?php block_composer('footer', $settings ); ?>
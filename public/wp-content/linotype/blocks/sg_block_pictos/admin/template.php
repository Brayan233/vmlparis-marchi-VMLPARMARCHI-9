<?php block_composer('header', $settings ); ?>

  <?php //block_composer( 'preview', $settings ); ?>

  <div class="composer-item-content">
    
    <div class="container-full">

      <?php block_composer('content', $settings, array( 'inline' => true, 'add_button_pos' => 'bottom', ) ); ?>

    </div>
    
 	</div>

<?php block_composer('footer', $settings ); ?>
<?php block_composer('header', $settings ); ?>

  <div class="composer-item-preview" style="padding: 10px;">
    
    <ul class="composer-item-preview-list">
    	<?php if ( $options['id'] ) { ?><li><b>ID:</b><?php echo $options['id']; ?></li><?php } ?>
      <?php if ( $options['title'] ) { ?><li><b>Title:</b><?php echo $options['title']; ?></li><?php } ?>
      <?php if ( $options['info'] ) { ?><li><b>Info:</b><?php echo $options['info']; ?></li><?php } ?>
      <?php if ( $options['desc'] ) { ?><li><b>Desc:</b><?php echo $options['desc']; ?></li><?php } ?>
      <?php if ( $options['default'] ) { ?><li><b>Default:</b><?php echo $options['default']; ?></li><?php } ?>
      <?php if ( $options['dummy'] ) { ?><li><b>Dummy:</b><?php echo $options['dummy']; ?></li><?php } ?>
    </ul>
   
 	</div>
  
<?php block_composer('footer', $settings ); ?>
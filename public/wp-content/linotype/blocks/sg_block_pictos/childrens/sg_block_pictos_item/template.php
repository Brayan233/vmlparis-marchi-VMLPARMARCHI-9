<?php 

block('header', $settings ); 

?>

  <div class="bloc-picto">
    <div class="picto">
      <?php 
      echo get_linotype_field_image( array(
        'alt' => '',
        'class' => '',
        'sources' => array(
            array(
            'id' => $options['picto'],
            'break' => 0,
            'crop' => true,
            'x' => 300,
            'y' => 300,
            )
        ),
        'lazyload' => true,
				'fadein' => true,
        'webp' => true,
      ));
      ?>
    </div>
    <h3 class="title"><?php echo $options['title']; ?></h3>
    <p class="regular-text"><?php echo nl2br( $options['desc'] ); ?></p>
  </div>

<?php block('footer', $settings ); ?>

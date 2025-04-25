<?php block('header', $settings );

  // taille picto : 24x24 (SVG)
  //                48x48 ou plus si PNG

?>

  <div class="container">

    <div class="grid">
      
      <?php block( 'content', $settings ); ?>
      
    </div>

  </div>

<?php block('footer', $settings ); ?>

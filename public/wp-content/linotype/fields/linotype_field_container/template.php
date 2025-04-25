<?php
$container_style = '';

$options['container_type'] = 'container';
  
switch ( $options['position'] ) {
  case 'container-relative': $container_style .= 'position:relative;'; break;
  case 'container-absolute': $container_style .= 'position:absolute;'; break;
  case 'container-fixed':    $container_style .= 'position:fixed;'; break;
}

switch ( $options['position_align'] ) {
  case 'container-top':          $container_style .= 'top:0px;left:0px;right:0px;'; break;
  case 'container-top-left':     $container_style .= 'top:0px;left:0px;'; break;
  case 'container-top-right':    $container_style .= 'top:0px;right:0px;'; break;
  case 'container-bottom':       $container_style .= 'bottom:0px;left:0px;right:0px;'; break;
  case 'container-bottom-left':  $container_style .= 'bottom:0px;left:0px;'; break;
  case 'container-bottom-right': $container_style .= 'bottom:0px;right:0px;'; break;
}

if ( $options['index'] ) $container_style .= 'z-index:' . $options['index'] . ';';
if ( $options['height'] ) $container_style .= 'height:' . $options['height'] . ';';
if ( $options['margin'] ) $container_style .= 'margin:' . $options['margin'] . ';';
if ( $options['padding'] ) $container_style .= 'padding:' . $options['padding'] . ';';
if ( $options['bg_color'] ) $container_style .= 'background-color:' . $options['bg_color'] . ';';

if ( $options['bg_image'] ) {    
  $image = wp_get_attachment_image_src( $options['bg_image'], $options['bg_image_size'] );
  $options['bg_image'] = $image[0];
}

$style = '';
$style .= '#' . $item['id'] . ' {' . $container_style . '}';
$style .= '#' . $item['id'] . ' .container-bg {';
  if ( $options['bg_image'] ) $style .= 'background-image:url(' . $options['bg_image'] . ');';
  if ( $options['padding'] ) $style .= 'opacity:' . $options['bg_opacity'] . ';';
$style .= '}';

block( 'add_style', $style );

?>

<?php if ( $options['cover'] ) { ?>

  <?php block( 'header', $settings, array( 'class' => array( 'site-wrapper container-bg-cover' ) ) );  ?>
		
    <?php if ( $options['bg_image'] ) { ?>
    	<div class="container-bg"></div>
		<?php } ?>
    
    <div class="site-wrapper-inner">

      <div class="cover-container">

        <div class="inner cover">

          <div class="<?php echo $options['container_type']; ?>">
		
            <?php block( 'content', $settings ); ?>

					</div>
          
        </div>

      </div>

    </div>

  <?php block( 'footer', $settings ); ?>

<?php } else { ?>
	
<?php block( 'header', $settings );  ?>
	
  <div class="<?php echo $options['container_type']; ?>" >
		
    <?php if ( $options['bg_image'] ) { ?>
    	<div class="container-bg" style="background-image:url(<?php echo $options['bg_image']; ?>);opacity:<?php echo $options['bg_opacity']; ?>;"></div>
		<?php } ?>
    
    <?php block( 'content', $settings ); ?>

  </div>
  
<?php block( 'footer', $settings ); ?>

<?php } ?>

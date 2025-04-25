<?php 

block('header', $settings, array( 
  'class' => array( 
    $options['image_pos'],
    $options['image_height'],
    // $options['color_bg'],
    // $options['color_text'],
    // $options['color_link']
  ), 
  'data' => array( 
    'video_id' => $options['video_id'] 
  ) 
));

$link = get_linotype_field_link( $options['link'] );

$noopener = '';
if ( $link['target'] == '_blank' ) $noopener = 'rel="noopener"';

if ( $link && $options['video_id'] == "" ) {
  $hasParentLink = true;
  echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '"' . $noopener . 'class="block-media"  style="background-color:' . $options['color_bg'] . '">';
} else {
  $hasParentLink = false;
  echo '<div class="block-media" style="background-color:' . $options['color_bg'] . '">';
}
?>

  <div class="col-text">
    <div class="inner">
    
        <?php if ( $options['surtitle'] ) echo '<p class="media-surtitle" style="color:' . $options['color_text'] . '">' . $options['surtitle'] . '</p>'; ?>
        
        <?php if ( $options['title'] ) echo '<h2 class="lvl1-title media-title" style="color:' . $options['color_text'] . '">' . $options['title'] . '</h2>'; ?>

        <?php if ( $options['desc'] ) echo '<p class="media-text" style="color:' . $options['color_text'] . '">' . $options['desc'] . '</p>'; ?>

      <?php if ( $link && $hasParentLink == false ) echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '"' . $noopener . '>'; ?>
        <?php if ( $link && $link['title'] ) echo '<div class="btn btn-primary btn-arrow media-link"  style="color:' . $options['color_link'] . '">' . $link['title'] . '<svg style="margin: 3px 5px;" width="9" height="9" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><path d="M4.232.414l4.243 4.243-.026.024.026.026L4.232 8.95l-.707-.707 3.263-3.265L0 4.98v-.958h6.424l-2.899-2.9.707-.707z" id="a"/></defs><use fill="' . $options['color_link'] . '" xlink:href="#a" fill-rule="evenodd"/></svg></div>'; ?>
      <?php if ( $link && $hasParentLink == false ) echo '</a>'; ?>

    </div>
  </div>
  <div class="col-image<?php if ( $options['video_id'] ) { echo ' video'; } ?>">
    <?php 

      if ( $options['video_id'] ) echo '<div class="block-media-play"></div>';
   
      if ( $options['image_pos'] == 'image_full' ) {

        echo get_linotype_field_image( array(
          'alt' => '',
          'class' => '',
          'sources' => array(
            array(
              'id' => $options['img'],
              'break' => 1200,
              'crop' => true,
              'x' => 1600,
              'y' => 838,
            ),
            array(
              'id' => $options['img'],
              'break' => 768,
              'crop' => true,
              'x' => 1080,
              'y' => 566,
            ),
            array(
              'id' => $options['img_mobile'],
              'break' => 0,
              'crop' => true,
              'x' => 750,
              'y' => 1300,
            )
          ),
          'compress' => $options['compress'],
          'lazyload' => true,
          'fadein' => true,
          'webp' => true,
        ));

      } else {
    
        echo get_linotype_field_image( array(
          'alt' => '',
          'class' => '',
          'sources' => array(
              array(
                'id' => $options['img'],
                'break' => 0,
                'crop' => true,
                'x' => 800,
                'y' => 800,
              )
          ),
          'compress' => $options['compress'],
          'lazyload' => true,
          'fadein' => true,
          'webp' => true,
        ));

      }

    ?>
  </div>

<?php if ( $link && $options['video_id'] == ""  )
  echo '</a>';
else
  echo '</div>';
?>

<?php block('footer', $settings ); ?>

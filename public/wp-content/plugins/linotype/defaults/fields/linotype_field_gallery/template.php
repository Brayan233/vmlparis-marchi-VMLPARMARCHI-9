<?php

wp_enqueue_media();

wp_enqueue_script('jquery-ui-sortable');

wp_enqueue_style( 'field-gallery', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script('field-gallery', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array('jquery'), '1.0', true );

$default_options = array(
    "output" => "url",
    "height" => "",
    "wrapstyle" => "",
    "imgstyle" => "",
    "input" => false,
    "placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-gallery <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" wp-field-output="<?php echo $field['options']['output']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<span class="spinner"></span>

	<div class="field-content" style="visibility:hidden;">

		<div class="field-gallery-master" style="<?php if( $field['options']['height'] ) { echo 'height:' . $field['options']['height'] . ';'; } ?><?php if( $field['options']['wrapstyle'] ) { echo $field['options']['wrapstyle']; } ?>;">

			<div class="field-gallery-action">

			  	<input style="display:<?php if( $field['options']['input'] ) { echo 'inline-block'; } else { echo 'none'; } ?>;vertical-align: top; margin: 0px 4px 0px 0px; line-height: 21px;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" value="<?php echo $field['value']; ?>" />

				<input id="file_button_select" data-update="Select File" data-choose="Choose a File" type="button" value="Select" class="button-primary field-gallery-select force-align-middle">

				<input id="file_button_remove" data-update="Remove" data-choose="Remove" type="button" value="Remove" class="button button-small field-gallery-remove force-align-middle">


			</div>

			<div id="preview" class="field-gallery-preview<?php if( ! $field['options']['height'] ) { echo ' auto-height'; } ?>" >

				<ul style="<?php if( $field['options']['imgstyle'] ) { echo $field['options']['imgstyle']; } ?>" >

					<?php 
          
          $images = explode( ',', $field['value'] );
          
          if ( $images ) {
            
            foreach( $images as $image ) {
              
              $image_src = wp_get_attachment_image_src( $image, 'thumbnails' );

              if ( isset( $image_src[0] ) && $image_src[0] ) {

                echo '<li data-id="' . $image . '"><img style="width:150px;" src="' . $image_src[0] . '" /></li>';

              } 
              
            }
           
          }
          
          ?>

				</ul>

			</div>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

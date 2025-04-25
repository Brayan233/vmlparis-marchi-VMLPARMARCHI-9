<?php

wp_enqueue_media();

wp_enqueue_style( 'field-image', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script('field-image', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array('jquery'), '1.0', true );

$default_options = array(
    "output" => "id",
    "height" => "",
    "wrapstyle" => "",
    "imgstyle" => "",
    "input" => false,
    "placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field linotype_field_image wp-field-image <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" wp-field-output="<?php echo $field['options']['output']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content" style="visibility:hidden;">

			<div class="field-image-action">

			  <input style="display:<?php if( $field['options']['input'] ) { echo 'inline-block'; } else { echo 'none'; } ?>;vertical-align: top; margin: 0px 4px 0px 0px; line-height: 21px;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" value="<?php echo $field['value']; ?>" />

				<input id="file_button_select" data-update="Select File" data-choose="Choose a File" type="button" value="Select" class="button-primary field-image-select force-align-middle">

				<input id="file_button_remove" data-update="Remove" data-choose="Remove" type="button" value="Remove" class="button field-image-remove force-align-middle">

			</div>

			<div id="preview" class="field-image-preview" >

					<?php if ( isset($field['options']['output']) && $field['options']['output'] == "url" ) { ?>

						<?php if ( isset( $field['value'] ) && $field['value'] && substr( $field['value'], 0, 8 ) !== "?nocache" ) { ?>

							<img src="<?php echo $field['value']; ?>"/>

						<?php } ?>

					<?php } else { ?>

						<?php $image_src = wp_get_attachment_image_src( $field['value'], 'medium' ); ?>

						<?php if ( isset( $image_src[0] ) && $image_src[0] && substr( $image_src[0], 0, 8 ) !== "?nocache" ) { ?>

							<img src="<?php echo $image_src[0]; ?>" />

						<?php } ?>

					<?php } ?>

			</div>

	</div>

	<?php if( $field['desc'] ) { ?>
		<div class="field-description" ><?php echo $field['desc']; ?></div>
	<?php } ?>

</li>

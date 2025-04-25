<?php

wp_enqueue_media();

wp_enqueue_style( 'field-file', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script('field-file', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array('jquery'), '1.0', true );

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

<li class="wp-field linotype_field_file wp-field-file <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" wp-field-output="<?php echo $field['options']['output']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content" style="visibility:hidden;">

			<div class="field-file-action">

				<input id="file_button_select" data-update="Select File" data-choose="Choose a File" type="button" value="Select" class="button-primary field-file-select force-align-middle">

				<input name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" value="<?php echo $field['value']; ?>" />

			</div>

	</div>

	<?php if( $field['desc'] ) { ?>
		<div class="field-description" ><?php echo $field['desc']; ?></div>
	<?php } ?>

</li>

<?php

wp_enqueue_style( 'bgrins_library_spectrum', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/bgrins_library_spectrum/style.css', false, false, 'screen' );
wp_enqueue_script('bgrins_library_spectrum', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/bgrins_library_spectrum/script.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'linotype_field_color', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script( 'linotype_field_color', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array( 'jquery' ), false, true );

$default_options = array(
	"alpha" => false,
	"default" => "transparent",
	"storecolor" => false,
	"palette_only" => false,
	"palette" => ""
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="linotype_field_color wp-field wp-field-color <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="visibility:visible;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

    <div class="field-content">

		<input type="text" id="<?php echo $field['id']; ?>" name="<?php echo $field['id']; ?>" class="wp-field-value meta-field ADMINBLOCKS-field-color spectrum" value="<?php echo $field['value']; ?>" autocorrect="off" autocomplete="off" spellcheck="false" >
		
		<textarea class="options" style="display:none"><?php echo json_encode( $field['options'] ); ?></textarea>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

<?php

wp_enqueue_style( 'field-json', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/jsoneditor.css', false, false, 'screen' );
wp_enqueue_script('field-json', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/jquery.jsoneditor.js', array('jquery'), '1.0', true );

$default_options = array(
    "data" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( $field['options']['data'] ) $field['options']['data'] = stripslashes( $field['options']['data'] );

if ( is_array( $field['value'] ) ) $field['value'] = json_encode( $field['value'] );

?>

<li class="wp-field wp-field-json <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>


    <div class="json-editor"></div>

    <div class="button json-editor-reset">Reset</div>

		<div class="field-input" style="display:none">
			<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php echo stripslashes( $field['value'] ); ?></textarea>
		</div>

    <div class="field-input" style="display:none">
			<textarea class="wp-field-data" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php echo $field['options']['data']; ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

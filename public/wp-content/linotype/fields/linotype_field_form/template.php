<?php

wp_enqueue_script('jdorn_library_jsoneditor', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/jdorn_library_jsoneditor/script.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'linotype_field_form', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script('linotype_field_form', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array('jquery'), '1.0', true );

$default_options = array(
    "schema" => array(),
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

//if ( $field['options']['schema'] ) $field['options']['schema'] = stripslashes( $field['options']['schema'] );

if ( is_array( $field['value'] ) ) $field['value'] = json_encode( $field['value'], JSON_UNESCAPED_UNICODE );



?>

<li class="wp-field linotype_field_form <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="linotype_field_form-editor"></div>

		<div class="field-input" style="display:none">
			<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php echo  $field['value']; ?></textarea>
		</div>

    	<div class="field-input" style="display:none">
			<textarea class="linotype_field_form-schema" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false"><?php echo json_encode( $field['options']['schema'], JSON_UNESCAPED_UNICODE ); ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

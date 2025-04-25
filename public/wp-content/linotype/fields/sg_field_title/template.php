<?php

$default_options = array();
$default_options_data = "";
if ( file_exists( dirname( __FILE__ ) .'/options.json' ) ) $default_options_data = file_get_contents( dirname( __FILE__ ) .'/options.json' );
if ( $default_options_data ) $default_options = json_decode( $default_options_data, true );

$field['options'] = wp_parse_args( $field['options'], $default_options );

wp_enqueue_style( 'slab_library_quill', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/slab_library_quill/style.css', false, false, 'screen' );
wp_enqueue_script('slab_library_quill', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/slab_library_quill/script.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'sg_field_title', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script( 'sg_field_title', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array( 'jquery' ), false, true );

?>

<li class="sg_field_title wp-field wp-field-quill <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="visibility:visible;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

    <div class="field-content">

		<div class="ql-editor-wrapper <?php echo $field['options']['theme']; ?>">
			<div class="editor-container"><?php echo $field['value']; ?></div>
		</div>

		<div class="editor-html dashicons dashicons-editor-code" style="min-height:<?php echo $field['options']['height']; ?>"></div>
		
		<textarea style="display: none" id="<?php echo $field['id']; ?>" name="<?php echo $field['id']; ?>" class="wp-field-value meta-field ADMINBLOCKS-field-color" autocorrect="off" autocomplete="off" spellcheck="false" ><?php echo $field['value']; ?></textarea>

		<textarea style="display:none" class="sg_field_title-options"><?php echo json_encode( $field['options'] ); ?></textarea>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

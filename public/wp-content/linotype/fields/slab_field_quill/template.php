<?php

$default_options = array(
	"theme" => "snow",
	"p" => true,
	"syntax" => false,
	"toolbar" => [
		[ [ "font" => [] ], [ "size" => [] ]],
		[ "bold", "italic", "underline", "strike" ],
		[ [ "color" => [] ], [ "background" => [] ]],
		[ [ "script" => "super" ], [ "script" => "sub" ]],
		[ [ "header" => "1" ], [ "header" => "2" ], "blockquote", "code-block" ],
		[ [ "list" => "ordered" ], [ "list" => "bullet"], [ "indent" => "-1" ], [ "indent" => "+1" ]],
		[ ["direction" => "rtl"], [ "align" => [] ]],
		[ "link", "image", "video", "formula" ],
		[ "clean" ]
	],
	"placeholder" => "",
	"height" => "15px"
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

wp_enqueue_style( 'slab_library_quill', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/slab_library_quill/style.css', false, false, 'screen' );
wp_enqueue_style( 'slab_library_quill-style', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/slab_library_quill/style.css', false, false, 'screen' );
wp_enqueue_script('slab_library_quill', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/slab_library_quill/script.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'slab_field_quill', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script( 'slab_field_quill', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array( 'jquery' ), false, true );

?>

<li class="slab_field_quill wp-field wp-field-quill <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="visibility:visible;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

    <div class="field-content">

		<div class="ql-editor-wrapper <?php echo $field['options']['theme']; ?>">
			<div class="editor-container"><?php echo $field['value']; ?></div>
		</div>

		<div class="editor-html dashicons dashicons-editor-code" style="min-height:<?php echo $field['options']['height']; ?>"></div>
		
		<textarea style="display: none" id="<?php echo $field['id']; ?>" name="<?php echo $field['id']; ?>" class="wp-field-value meta-field ADMINBLOCKS-field-color" autocorrect="off" autocomplete="off" spellcheck="false" ><?php echo $field['value']; ?></textarea>

		<textarea style="display:none" class="slab_field_quill-options"><?php echo json_encode( $field['options'] ); ?></textarea>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

<?php

wp_enqueue_script('jquery-ui-sortable');

wp_enqueue_style( 'handypress-customposts', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/customposts.css', false, false, 'screen' );
wp_enqueue_script('handypress-customposts', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/customposts.js', array('jquery'), '1.0', true );

$default_options = array(
	"map" => array(),
	"default" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<div class="wp-field wp-field-customposts <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		customposts
		
		<div class="field-input" style="display:block">
			<textarea style="height: 78vh;"name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php echo json_encode( $field['value'], JSON_PRETTY_PRINT ); ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</div>

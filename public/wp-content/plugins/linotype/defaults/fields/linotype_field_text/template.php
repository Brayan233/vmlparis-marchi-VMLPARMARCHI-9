<?php

wp_enqueue_style( 'victorjonsson_library_formvalidator', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/guillaumepotier_library_parsley/style.css', false, false, 'screen' );
wp_enqueue_script('victorjonsson_library_formvalidator', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/guillaumepotier_library_parsley/script.js', array('jquery'), '1.0', true );
wp_enqueue_script('linotype_field_text', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array('jquery'), '1.0', true );

$default_options = array(
	"min" => "1",
	"max" => "100",
    "placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( $field['value'] ) $field['value'] =  stripslashes( $field['value'] );

if ( $field['template_value'] ) $field['options']['placeholder'] = $field['template_value'];

?>

<li class="linotype_field_text wp-field wp-field-text <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="field-input">
			<input type="text" data-parsley-length="[<?php echo $field['options']['min']; ?>, <?php echo $field['options']['max']; ?>]" data-parsley-validation-threshold="0" data-parsley-validate-if-empty="true" data-parsley-trigger="change keydown" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" value="<?php echo $field['value']; ?>" <?php if( $field['disabled'] ) { echo "disabled"; } ?> />
		</div>
		
		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

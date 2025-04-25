<?php

wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_style( 'wp-field-color', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/color.css', false, false, 'screen' );

wp_enqueue_script( 'wp-color-picker-alpha', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.2', true );

$default_options = array(
	'alpha' => false,
	'default' => '#F1F1F1',
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-color <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="visibility:visible;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

    <div class="field-content">

		<input data-alpha="<?php echo $field['options']['alpha']; ?>" --data-default-color="<?php echo $field['options']['default']; ?>" type="text" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field LINOADMIN-field-color color-picker" <?php if(!isset($field['id_multiple'])) echo 'name="' . $field['id'] . '"'; ?> value="<?php echo $field['value']; ?>" autocorrect="off" autocomplete="off" spellcheck="false" >

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

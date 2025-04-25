<?php

wp_enqueue_style( 'fontselect', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/fontselect/fontselect.css', false, false, 'screen' );
wp_enqueue_script('fontselect', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/fontselect/jquery.fontselect.min.js', array('jquery'), '0.12.1', true );

//wp_enqueue_style( 'field-selectize', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/googlefonts.css', false, false, 'screen' );
wp_enqueue_script('field-selectize', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/googlefonts.js', array('jquery'), '1.0', true );

$default_options = array(

);

$field['options'] = array_merge( $default_options, $field['options'] );

?>

<li class="wp-field wp-field-googlefonts <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="field-input">

			<input name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $field['value']; ?>" />

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

<?php

wp_enqueue_style( 'signature-pad', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/signature-pad.css', false, false, 'screen' );
wp_enqueue_script('signature-pad', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/signature_pad.min.js', array('jquery'), '0.12.1', true );
wp_enqueue_script('field-signature', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/signature.js', array('jquery'), '0.12.1', true );

$default_options = array(
	"width" => "400px",
	"height" => "200px",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-signature <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" wp-field-output="<?php if ( isset( $field['options'] ) && isset( $field['options']['output'] ) ) { echo $field['options']['output']; } ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if ( $field['title'] ) { ?>
	<div class="field-title"><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="signature-pad">

			<?php if( $field['value'] ) { ?>

				<img class="signature-pad-saved" style="width:<?php echo $field['options']['width']; ?>;height:<?php echo $field['options']['height']; ?>;" alt="star" src="<?php echo stripslashes( $field['value'] ); ?>"/>

			<?php } ?>

			<div class="signature-pad-body" style="width:<?php echo $field['options']['width']; ?>;height:<?php echo $field['options']['height']; ?>">

				<canvas class="signature-canvas"></canvas>

			</div>

			<br/>

			<div class="button-actions">

				<button type="button" class="button button-clear" >Clear</button>

				<button type="button" class="button button-fullscreen" ><span class="fullscreen-off">Fullscreen</span><span class="fullscreen-on">OK</span></button>

			</div>

		</div>

		<div class="field-input" style="display:none;">
			<textarea style="<?php if( $field['options']['height'] ) { echo $field['options']['height'] . ';'; } ?><?php if( $field['options']['style'] ) { echo $field['options']['style']; } ?>" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" /><?php echo stripslashes( $field['value'] ); ?></textarea>
		</div>

		<?php if ( $field['desc'] ) { ?>
		<div class="field-desc"><?php echo $field['desc']; ?></div>
		<?php } ?>

	<div>

</li>

<?php

$default_options = array(
    "placeholder" => false,
    "input" => true,
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( $field['value'] ) {

	$field['value'] = json_decode( stripslashes( $field['value'] ), true )[0];

} else {

	$field['value'] = array();

}

?>

<li class="wp-field wp-field-uploader <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<?php echo $field['uploader_object']->get(); ?>

		<div class="field-input">
			<textarea style="display:<?php if( $field['options']['input'] ) { echo 'block'; } else { echo 'none'; } ?>;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field <?php echo $field['uploader_object']->uploader_id; ?>" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>"><?php if ( $field['value'] ) { echo '[' . json_encode( $field['value'] ) . ']'; } ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

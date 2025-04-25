<?php

$default_options = array(
    "style" => "",
    "placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( $field['value'] ) $field['value'] =  stripslashes( $field['value'] );

?>

<li class="wp-field wp-field-text <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="field-input">
			<input style="<?php if( $field['options']['style'] ) { echo $field['options']['style']; } ?>" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" value="<?php echo $field['value']; ?>" <?php if( $field['disabled'] ) { echo "disabled"; } ?> />
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

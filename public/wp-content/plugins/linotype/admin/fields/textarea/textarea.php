<?php

$default_options = array(
    "height" => "",
    "style" => "border:1px solid #E5E5E5;",
    "placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( is_array( $field['value'] ) ) $field['value'] =  json_encode( $field['value'] );
if ( $field['value'] ) $field['value'] =  stripslashes( $field['value'] );

?>

<li class="wp-field wp-field-textarea <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="field-input">
			<textarea style="    border: 1px solid #ddd;box-shadow: inset 0 1px 2px rgba(0,0,0,.07);background-color: #fff;color: #32373c;outline: 0;transition: 50ms border-color ease-in-out;<?php if( $field['options']['height'] ) { echo $field['options']['height'] . ';'; } ?><?php if( $field['options']['style'] ) { echo $field['options']['style']; } ?>" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" /><?php echo stripslashes( $field['value'] ); ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

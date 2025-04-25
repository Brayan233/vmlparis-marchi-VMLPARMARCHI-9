<?php

$default_options = array(
    "label" => "",
    "style" => "",
    "value" => "true",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

//if ( isset( $field['default'] ) ) $field['options']['value'] = $field['default'];

//if ( ! $field['value'] ) $field['options']['value'] = $field['default'];

?>

<li class="wp-field wp-field-checkbox <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="field-input" >

			<?php

			if ( $field['value'] ) {

				$checked = 'checked="checked"';

			} else {

				$checked = '';
			}

      $disabled = '';
			if ( $field['disabled'] ) $disabled = 'disabled="disabled"';
			?>

			<input type="checkbox" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $field['options']['value']; ?>" <?php echo $disabled; ?> <?php echo $checked; ?> />

			<?php if ( $field['options']['label'] ) { ?>
				<label style="<?php if ( $field['options']['style'] ) { echo $field['options']['style']; } ?>" for="<?php echo $field['id']; ?>"><?php echo $field['options']['label']; ?></label>
			<?php } ?>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

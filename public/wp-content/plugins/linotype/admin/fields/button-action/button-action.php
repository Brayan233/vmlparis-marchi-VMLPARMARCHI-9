<?php

$default_options = array(
    "class" => "",
    "label" => "",
    "desc" => "",
    "placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-textarea <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<?php $action_link = esc_url( add_query_arg( array( $field['id'] . '_action' => 'exec' ), $_SERVER['REQUEST_URI'] ) ); ?>

		<div class="field-input" style="line-height: 28px;">
			<a href="<?php if( $action_link ) { echo $action_link; } else { echo '#'; } ?>" class="<?php if( $field['options']['class'] ) { echo $field['options']['class']; } ?>"><?php echo $field['options']['label']; ?></a> <?php if( $field['options']['desc'] ) { echo $field['options']['desc']; } ?>
			<textarea style="display:none;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" /><?php echo $field['value']; ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

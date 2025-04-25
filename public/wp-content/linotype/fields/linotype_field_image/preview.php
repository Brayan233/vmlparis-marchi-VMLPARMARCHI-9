<?php

$default_options = array(
    "output" => "id",
    "height" => "",
    "wrapstyle" => "",
    "imgstyle" => "",
    "input" => false,
    "placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<div style="display:block;width:80px;height:80px;overflow:hidden;">

	<?php if ( isset($field['options']['output']) && $field['options']['output'] == "url" ) { ?>

		<?php if ( isset( $field['value'] ) && $field['value'] && substr( $field['value'], 0, 8 ) !== "?nocache" ) { ?>

			<img src="<?php echo $field['value']; ?>"/>

		<?php } ?>

		<?php } else { ?>

		<?php $image_src = wp_get_attachment_image_src( $field['value'], 'thumbnail' ); ?>

		<?php if ( isset( $image_src[0] ) && $image_src[0] && substr( $image_src[0], 0, 8 ) !== "?nocache" ) { ?>

			<img src="<?php echo $image_src[0]; ?>" />

		<?php } ?>

	<?php } ?>

</div>


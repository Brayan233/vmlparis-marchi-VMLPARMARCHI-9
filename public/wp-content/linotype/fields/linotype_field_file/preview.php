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

	<?php if ( isset( $field['value'] ) && $field['value'] && substr( $field['value'], 0, 8 ) !== "?nocache" ) { ?>

		<?php echo $field['value']; ?>

	<?php } ?>

</div>


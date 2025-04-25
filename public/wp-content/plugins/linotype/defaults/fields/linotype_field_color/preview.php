<?php

$default_options = array(
	'alpha' => true,
	'default' => '#F1F1F1',
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<div style="display:block;width:20px;height:20px;background-color:<?php echo $field['value']; ?>;"></div>

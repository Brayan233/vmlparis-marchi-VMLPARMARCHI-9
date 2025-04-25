<?php

$default_options = array(
    "data" => array(),
    "display" => 'inline',
    "style" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( $field['options']['data'] ) { 

	foreach ( $field['options']['data'] as $data_key => $data ) {

		if ( $data['value'] == $field['value'] ) {

			echo $data['title'];
		
		}

	}

} 

?>

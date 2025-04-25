<?php


$default_options = array(
	"data" => array(),
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

foreach ( $field['options']['data'] as $script_id => $script_uri ) {

	wp_enqueue_script('script-' . $script_id, $script_uri, array('jquery'), '1.0', true );

}


?>

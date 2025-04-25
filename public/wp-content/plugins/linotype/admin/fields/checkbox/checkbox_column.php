<?php

$default_options = array(
    "label" => "",
    "style" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( $field['value'] ) {

	echo '<span class="dashicons dashicons-yes"></span>';

} else {

	echo '<span class="dashicons dashicons-no-alt"></span>';

}

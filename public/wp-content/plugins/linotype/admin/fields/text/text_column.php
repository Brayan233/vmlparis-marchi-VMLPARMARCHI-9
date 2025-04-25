<?php

$default_options = array(
    "style" => "",
    "placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

echo stripslashes( $field['value'] );

?>
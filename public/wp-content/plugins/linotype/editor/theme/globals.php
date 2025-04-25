<?php

$field = array(
    'id' => 'globals',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $LINOTYPE_EDITOR['data']['globals'],
    'options' => array(
        'height' => '87vh',
        'json' => true,
    ),
    'padding' => '20px',
    'fullwidth' => true,
    'fullheight' => true,
);

LINOTYPE::$FIELDS->display('linotype_field_textarea', $field );

?>
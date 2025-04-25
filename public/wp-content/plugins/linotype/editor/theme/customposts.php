<?php


$field = array(
    'id' => 'customposts',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $LINOTYPE_EDITOR['data']['customposts'],
    'options' => array(
        'height' => '87vh',
        'json' => true,
    ),
    'padding' => '20px',
    'fullwidth' => true,
    'fullheight' => true,
);

LINOTYPE::$FIELDS->display('linotype_field_textarea', $field );



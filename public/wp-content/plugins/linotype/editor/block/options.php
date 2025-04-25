<?php

$field = array(
    'id' => 'options',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $LINOTYPE_EDITOR['data']['config']['options'],
    'options' => array(
        'items' => LINOTYPE::$FIELDS->get(),
        'data' => array(),
        'min_height' => '500',
        'border' => true,
        'toolbar' => true,
        'actions' => array( 'add', 'edit' ,'sort', 'clone', 'copy', 'delete', 'source', 'link' ),
        'empty' => 'Add Options',
        "type" => 'field', 
        "layout" => 'split', 
    ),
    'padding' => '15px 20px',
    'fullwidth' => true,
    'fullheight' => true,
);

// include LINOTYPE_plugin::$plugin['dir'] . 'admin/fields/composer/composer.php';
LINOTYPE::$FIELDS->display('linotype_field_composer', $field );

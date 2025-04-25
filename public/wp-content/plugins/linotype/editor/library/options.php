<?php

$field = array(
    'id' => 'options',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $LINOTYPE_EDITOR['data']['options'],
    'options' => array(
        'items' => LINOTYPE::$FIELDS->get(),
        'data' => array(),
        'min_height' => '500',
        'border' => true,
        'toolbar' => true,
        'actions' => array( 'edit' ,'sort' ,'delete' ,'add' ,'clone' ),
        'empty' => false,
    ),
    'padding' => '15px 20px',
    'fullwidth' => true,
);

include LINOTYPE_plugin::$plugin['dir'] . 'admin/fields/composer/composer.php';

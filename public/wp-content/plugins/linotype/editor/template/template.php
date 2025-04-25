<?php

$field = array(
    'id' => 'template',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $LINOTYPE_EDITOR['data']['template'],
    'options' => array(
        'type' => 'block',
        'items' => LINOTYPE::$BLOCKS->get(),
        'data' => array(),
        'min_height' => '500px',
        'border' => true,
        'toolbar' => true,
        'actions' => array( 'add', 'edit' ,'sort', 'clone', 'copy', 'delete', 'source', 'link' ),
        'empty' => false,
        'layout' => 'split',
       
    ),
    'padding' => '20px',
    'fullwidth' => true,
    'fullheight' => true,
);

LINOTYPE::$FIELDS->display('linotype_field_composer', $field );

?>
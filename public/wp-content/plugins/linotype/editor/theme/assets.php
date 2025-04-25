<?php

LINOTYPE::$FIELDS->display('linotype_field_filesmanager', array(
    'id' => 'options',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => '',
    'options' => array(
        'dir' => $LINOTYPE_EDITOR['data']['dir'],
        'url' => $LINOTYPE_EDITOR['data']['url'],
    ),
    'padding' => '0px',
    'fullwidth' => true,
    'fullheight' => true,
) );

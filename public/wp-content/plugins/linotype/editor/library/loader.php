<?php

if( ! $LINOTYPE_EDITOR['data']['scripts'] ) {

    $LINOTYPE_EDITOR['data']['scripts'] = '[
        {
            "id": "' . $LINOTYPE_EDITOR['id'] . '",
            "version": "1.0",
            "dependencies": [
                "jquery"
            ],
            "footer": "true",
            "full": "/script.js",
            "min": "/script.js",
            "cdn": ""
        }
    ]';

}
$field = array(
    'id' => 'scripts',
    'title' => 'Scripts loader',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $LINOTYPE_EDITOR['data']['scripts'],
    'options' => array(
        'height' => '40vh',
        'json' => true,
    ),
    'padding' => '20px',
    'fullwidth' => true,
    'fullheight' => true,
);

LINOTYPE::$FIELDS->display('linotype_field_textarea', $field );




if( ! $LINOTYPE_EDITOR['data']['styles'] ) {

    $LINOTYPE_EDITOR['data']['styles'] = '[
        {
            "id": "' . $LINOTYPE_EDITOR['id'] . '",
            "version": "1.0",
            "dependencies": [],
            "media": "screen",
            "full": "/style.css",
            "min": "/style.css",
            "cdn": ""
        }
    ]';

}

$field = array(
    'id' => 'styles',
    'title' => 'Styles loader',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $LINOTYPE_EDITOR['data']['styles'],
    'options' => array(
        'height' => '40vh',
        'json' => true,
    ),
    'padding' => '20px',
    'fullwidth' => true,
    'fullheight' => true,
);

LINOTYPE::$FIELDS->display('linotype_field_textarea', $field );

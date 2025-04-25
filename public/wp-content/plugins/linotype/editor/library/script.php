<?php

global $LINOTYPE_EDITOR;

$code = '';
if ( file_exists( $LINOTYPE_EDITOR['data']['dir'] . '/script.js' ) ){
    $code = file_get_contents( $LINOTYPE_EDITOR['data']['dir'] . '/script.js' );
    if ( $code ) $code = htmlspecialchars( $code );
}

LINOTYPE::$FIELDS->display('linotype_field_code', array(
    'id' => 'code-js',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $code,
    'options' => array(
        'type' => 'js',
    ),
    'padding' => '0px',
    'fullwidth' => true,
    'fullheight' => true,
    'fullscreen' => true,
));

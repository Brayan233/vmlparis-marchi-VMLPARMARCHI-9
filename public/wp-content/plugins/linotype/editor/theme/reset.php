<?php

global $LINOTYPE_EDITOR;

$code = '';
if ( file_exists( $LINOTYPE_EDITOR['data']['dir'] . '/reset.css' ) ){
    $code = file_get_contents( $LINOTYPE_EDITOR['data']['dir'] . '/reset.css' );
    if ( $code ) $code = htmlspecialchars( $code );
}

LINOTYPE::$FIELDS->display('linotype_field_code', array(
    'id' => 'code-reset',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $code,
    'options' => array(
        'type' => 'css',
    ),
    'padding' => '0px',
    'fullwidth' => true,
    'fullheight' => true,
    'fullscreen' => true,
));
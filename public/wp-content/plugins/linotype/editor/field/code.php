<?php

global $LINOTYPE_EDITOR;

$code = '';
if ( file_exists( $LINOTYPE_EDITOR['data']['dir'] . '/template.php' ) ){
    $code = file_get_contents( $LINOTYPE_EDITOR['data']['dir'] . '/template.php' );
    if ( $code ) $code = htmlspecialchars( $code );
}

LINOTYPE::$FIELDS->display('linotype_field_code', array(
    'id' => 'code-php',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $code,
    'options' => array(
        'type' => 'php',
    ),
    'padding' => '0px',
    'fullwidth' => true,
    'fullheight' => true,
    'fullscreen' => true,
));

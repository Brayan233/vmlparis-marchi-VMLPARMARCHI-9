<?php

global $LINOTYPE_EDITOR;

$php = '';
if ( file_exists( $LINOTYPE_EDITOR['data']['dir'] . '/admin/template.php' ) ){
    $php = file_get_contents( $LINOTYPE_EDITOR['data']['dir'] . '/admin/template.php' );
    if ( $php ) $php = htmlspecialchars( $php );
}
$css = '';
if ( file_exists( $LINOTYPE_EDITOR['data']['dir'] . '/admin/style.css' ) ){
    $css = file_get_contents( $LINOTYPE_EDITOR['data']['dir'] . '/admin/style.css' );
    if ( $css ) $css = htmlspecialchars( $css );
}
$js = '';
if ( file_exists( $LINOTYPE_EDITOR['data']['dir'] . '/admin/script.js' ) ){
    $js = file_get_contents( $LINOTYPE_EDITOR['data']['dir'] . '/admin/script.js' );
    if ( $js ) $js = htmlspecialchars( $js );
}

LINOTYPE::$FIELDS->display('linotype_field_code', array(
    'id' => 'admin-php',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $php,
    'options' => array(
        'type' => 'php',
        'height' => '33vh',
    ),
    'padding' => '0px',
    'fullwidth' => true,
    'fullheight' => false,
));

LINOTYPE::$FIELDS->display('linotype_field_code', array(
    'id' => 'admin-js',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $js,
    'options' => array(
        'type' => 'js',
        'height' => '33vh',
    ),
    'padding' => '0px',
    'fullwidth' => true,
    'fullheight' => false,
));

LINOTYPE::$FIELDS->display('linotype_field_code', array(
    'id' => 'admin-css',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $css,
    'options' => array(
        'type' => 'css',
        'height' => '33vh',
    ),
    'padding' => '0px',
    'fullwidth' => true,
    'fullheight' => false,
));
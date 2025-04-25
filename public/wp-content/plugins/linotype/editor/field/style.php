<?php

global $LINOTYPE_EDITOR;

$code = '';
if ( file_exists( $LINOTYPE_EDITOR['data']['dir'] . '/style.less' ) ){
    $code_processor = 'less';
    $code = file_get_contents( $LINOTYPE_EDITOR['data']['dir'] . '/style.less' );
    if ( $code ) $code = htmlspecialchars( $code );
} else if ( file_exists( $LINOTYPE_EDITOR['data']['dir'] . '/style.scss' ) ){
    $code_processor = 'scss';
    $code = file_get_contents( $LINOTYPE_EDITOR['data']['dir'] . '/style.scss' );
    if ( $code ) $code = htmlspecialchars( $code );
} else if ( file_exists( $LINOTYPE_EDITOR['data']['dir'] . '/style.css' ) ){
    $code_processor = 'css';
    $code = file_get_contents( $LINOTYPE_EDITOR['data']['dir'] . '/style.css' );
    if ( $code ) $code = htmlspecialchars( $code );
}

?>

<select class="css-processor" name="css-processor" style="width:100px">
    <option>css</option>
    <option <?php if ( $code_processor == 'scss' ) echo 'selected="selected"'; ?>>scss</option>
    <option <?php if ( $code_processor == 'less' ) echo 'selected="selected"'; ?>>less</option>
</select>

<?php 

LINOTYPE::$FIELDS->display('linotype_field_code', array(
    'id' => 'code-css',
    'title' => '',
    'info' => '',
    'desc' => '',
    'path' => '',
    'value' => $code,
    'options' => array(
        'type' => $code_processor,
    ),
    'padding' => '0px',
    'fullwidth' => true,
    'fullheight' => true,
    'fullscreen' => true,
));

<?php

function linotype_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSLVERSION,3); 
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function linotype_file_get_contents( $url ) {

    $filename = basename( $url );
  
    $fileorigin = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]" . "/";
  
    $file = $_SERVER['DOCUMENT_ROOT'] . '/' . str_replace( $fileorigin, '', $url );

    $args = array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false
        ),
        "http"=>array(
            'timeout' => 60, 
            'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/3.0.0.1'
        )
    );

    $file_part = explode('?', $file );
    $file = $file_part[0];
    
    if ( file_exists( $file ) ) {
        return file_get_contents( $file, false, stream_context_create( $args ) );
    } else {
        return "";
    }
    
}

function linotype_file_get_contents_dist( $url ) {

    if ( $url ) {
  
        $args = array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false
            ),
            "http"=>array(
                'timeout' => 60, 
                'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/3.0.0.1'
            )
        );
        
        return file_get_contents( $url, false, stream_context_create( $args ) );
        
    } else {

        return "";
    }

}

//TEMPS
function linotype_editor_update_file() {

    LINOTYPE_file::update_file( stripslashes( $_REQUEST['dir'] ), stripslashes( $_REQUEST['id'] ), stripslashes( $_REQUEST['path'] ), stripslashes( $_REQUEST['file'] ), stripslashes( $_REQUEST['content'] ) );

    die();

}

add_action( 'wp_ajax_nopriv_' . 'linotype_editor_update_file', 'linotype_editor_update_file' );
add_action( 'wp_ajax_' . 'linotype_editor_update_file', 'linotype_editor_update_file' );


function linotype_editor_theme_install() {

    $ID = $_REQUEST['theme_id'];

    if ( ! isset( $_REQUEST['theme_id'] ) || $_REQUEST['theme_id'] === '' ) json_encode( array( 'success' => false, 'data' => array( 'id' => $ID  ) ) );

    $THEME = LINOTYPE::$THEMES->get( $ID );

    $from = $THEME['dir'];
    $to = dirname( get_template_directory() ) . '/' . $ID;

    if ( ! file_exists( $to . '/index.php' ) ) {

        handypress_helper::copy_dir( $from, $to );

        $success = true;

    } else {

        $success = false;

    }

    switch_theme( $ID );

    update_option( 'linotype_theme', $ID );

    $output = json_encode( array( 'success' => $success, 'data' => array( 'id' => $ID, 'from' => $from, 'to' => $to  ) ) );

    die( $output );

}

add_action( 'wp_ajax_nopriv_' . 'linotype_editor_theme_install', 'linotype_editor_theme_install' );
add_action( 'wp_ajax_' . 'linotype_editor_theme_install', 'linotype_editor_theme_install' );



function linotype_editor_theme_import() {

    $TYPE = $_REQUEST['type'];
    $ID = $_REQUEST['id'];

    if ( ! isset( $_REQUEST['id'] ) || $_REQUEST['id'] === '' || ! isset( $_REQUEST['id'] ) || $_REQUEST['id'] === '' ) json_encode( array( 'success' => false, 'data' => array( 'id' => $ID  ) ) );

    switch ( $TYPE ) {
            
        case 'block':
            $import = LINOTYPE::$BLOCKS->import( $ID );
        break;

        case 'module':
            $import = LINOTYPE::$MODULES->import( $ID );
        break;

        case 'template':
            $import = LINOTYPE::$TEMPLATES->import( $ID );
        break;

        case 'theme':
            $import = LINOTYPE::$THEMES->import( $ID );
        break;

        case 'library':
            $import = LINOTYPE::$LIBRARIES->import( $ID );
        break;

        case 'field':
            $import = LINOTYPE::$FIELDS->import( $ID );
        break;

    }

    if ( isset( $import['status'] ) && $import['status'] == "success" ) {

        $output = json_encode( array( 'success' => true, 'data' => array( 'id' => $ID ) ) );

    } else {

        $output = json_encode( array( 'success' => false, 'data' => array( 'type' => $TYPE, 'id' => $ID ) ) );

    }

    die( $output );

}

add_action( 'wp_ajax_nopriv_' . 'linotype_editor_theme_import', 'linotype_editor_theme_import' );
add_action( 'wp_ajax_' . 'linotype_editor_theme_import', 'linotype_editor_theme_import' );

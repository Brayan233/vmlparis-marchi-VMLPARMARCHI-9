<?php
/**
 * blocks editor functions
 *
 * @package WordPress
 * @subpackage blocks
 * @since 1.0.0
 */

function linotype_editor_init() {
    
    global $LINOTYPE_EDITOR;

    $LINOTYPE_EDITOR = array();

    //check role
    if ( ! current_user_can( 'linotype_edit' ) ) {
    
        wp_die( '<h1>LINOTYPRE</h1>' . '<p>' . 'Error - editor:001' . '</p>', 403 );
    
    }
    
    $TYPE = 'index';
    $ID = null;
    $UPDATED = null;
    $MESSAGE = "";
    $MESSAGE = null;

    //check state & set args
    if ( isset ( $_POST['updated'] ) ) {

        $TYPE = $_POST['type'];
        $ID = null;
		if ( isset( $_POST['id'] ) && $_POST['id'] ) $ID = $_POST['id'];
        $UPDATED = $_POST['updated'];
        $MESSAGE = "";
		if ( isset( $_POST['message'] ) && $_POST['message'] ) $MESSAGE = $_POST['message'];

    } else if ( isset( $_GET['type'] ) && $_GET['type'] ) {

        $TYPE = $_GET['type'];
        $ID = null;
		if ( isset( $_GET['id'] ) && $_GET['id'] ) $ID = $_GET['id'];
        $UPDATED = null;
        $MESSAGE = "";
		if ( isset( $_GET['message'] ) && $_GET['message'] ) $MESSAGE = $_GET['message'];

    } 

    $LINOTYPE_EDITOR['message'] = "";
    if ( $MESSAGE ) $LINOTYPE_EDITOR['message'] = $MESSAGE;

    $LINOTYPE_EDITOR['id'] = $ID;

    $LINOTYPE_EDITOR['type'] = $TYPE;
    
    $LINOTYPE_EDITOR['frontend'] = get_bloginfo('url');
    $LINOTYPE_EDITOR['backend'] = get_admin_url();

    $LINOTYPE_EDITOR['close_url'] = get_bloginfo('url') . '/wp-admin';

    //get editor content 
    switch( $TYPE ) {
        
        case "index":
            
            $LINOTYPE_EDITOR['toolbar'] = array( 'close', 'tabs' );    
            $LINOTYPE_EDITOR['tabs'] = array(
                
                "dashboard" => array('title' => '<b>Dashboard</b>', 'active' => true, 'icon' => 'dashicons dashicons-dashboard' ),
                
                "themes" => array('title' => 'Themes', 'active' => false, 'icon' => 'dashicons dashicons-admin-appearance', 'count' => count( LINOTYPE::$THEMES->get() ) ),
                "templates" => array('title' => 'Templates', 'active' => false, 'icon' => 'dashicons dashicons-admin-page', 'count' => count( LINOTYPE::$TEMPLATES->get() )  ),
                "modules" => array('title' => 'Modules', 'active' => false, 'icon' => 'dashicons dashicons-welcome-widgets-menus dashicons-editor-table', 'count' => count( LINOTYPE::$MODULES->get() )  ),
                "blocks" => array('title' => 'Blocks', 'active' => false, 'icon' => 'dashicons dashicons-editor-code', 'count' => count( LINOTYPE::$BLOCKS->get() )  ),
                "fields" => array('title' => 'Fields', 'active' => false, 'icon' => 'dashicons dashicons-edit', 'count' => count( LINOTYPE::$FIELDS->get() )  ),
                "libraries" => array('title' => 'Libraries', 'active' => false, 'icon' => 'dashicons dashicons-category', 'count' => count( LINOTYPE::$LIBRARIES->get() )  ),
                
            );
            $LINOTYPE_EDITOR['tabs_right'] = array(
                //"market" => array('title' => '', 'active' => false, 'icon' => 'dashicons dashicons-cart' ),
                "sync" => array('title' => '', 'active' => false, 'icon' => 'dashicons dashicons-cloud' ),
                "settings" => array('title' => '', 'active' => false, 'icon' => 'dashicons dashicons-admin-settings' ),
            );
            $LINOTYPE_EDITOR['data'] = array( 'type' => 'index', 'current' => get_option( 'linotype_theme', 'linotype_theme_blockstarter' ), 'title' => 'Dashboard', 'id' => null, 'author' => null, 'sync_status' => null, 'update' => null, 'path' => null, 'sync_title' => null );
            $LINOTYPE_EDITOR['block'] = LINOTYPE::$BLOCKS->get();
			$LINOTYPE_EDITOR['template'] = LINOTYPE::$TEMPLATES->get();
			$LINOTYPE_EDITOR['theme'] = LINOTYPE::$THEMES->get();
            $LINOTYPE_EDITOR['return_url'] = get_bloginfo('url') . '/wp-admin';
        break;

        case "add":
            $LINOTYPE_EDITOR['toolbar'] = array( 'close', 'back', 'tabs', 'save' );
            $LINOTYPE_EDITOR['tabs'] = array(
                "add" => array('title' => 'Create', 'active' => true, 'icon' => 'dashicons dashicons-plus'),
            );
			$LINOTYPE_EDITOR['data'] = array( 'type' => 'add', 'title' => 'Add New', 'id' => null, 'author' => null, 'sync_status' => null, 'update' => null, 'path' => null, 'sync_title' => null );	
			$LINOTYPE_EDITOR['block'] = LINOTYPE::$BLOCKS->get();
			$LINOTYPE_EDITOR['template'] = LINOTYPE::$TEMPLATES->get();
			$LINOTYPE_EDITOR['theme'] = LINOTYPE::$THEMES->get();
			$LINOTYPE_EDITOR['return_url'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php';
        break;
        
        case "import":
            $LINOTYPE_EDITOR['toolbar'] = array( 'close', 'back', 'tabs', 'save' );
            $LINOTYPE_EDITOR['tabs'] = array(
                "import" => array('title' => 'Import', 'active' => true, 'icon' => 'dashicons dashicons-download'),
            );
			$LINOTYPE_EDITOR['data'] = array( 'type' => 'import', 'title' => 'import', 'id' => null, 'author' => null, 'sync_status' => null, 'update' => null, 'path' => null, 'sync_title' => null );	
			$LINOTYPE_EDITOR['block'] = LINOTYPE::$BLOCKS->get();
			$LINOTYPE_EDITOR['template'] = LINOTYPE::$TEMPLATES->get();
			$LINOTYPE_EDITOR['theme'] = LINOTYPE::$THEMES->get();
			$LINOTYPE_EDITOR['return_url'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php';
		break;

        case "block":
            $LINOTYPE_EDITOR['toolbar'] = array( 'close', 'back', 'tabs', 'sync', 'save' );
            $LINOTYPE_EDITOR['tabs'] = array(
                "preview" => array('title' => 'Preview', 'active' => false, 'icon' => 'dashicons dashicons-admin-site'),
                "code" => array('title' => 'Code', 'active' => true, 'icon' => 'dashicons dashicons-editor-code' ),
                "style" => array('title' => 'Style', 'active' => false, 'icon' => 'dashicons dashicons-admin-customizer' ),
                "script" => array('title' => 'Script', 'active' => false, 'icon' => 'dashicons dashicons-hammer' ),
                "functions" => array('title' => 'Functions', 'active' => false, 'icon' => 'dashicons dashicons-admin-plugins' ),
                "assets" => array('title' => 'Assets', 'active' => false, 'icon' => 'dashicons dashicons-category' ),
                "options" => array('title' => 'Options', 'active' => false, 'icon' => 'dashicons dashicons-image-filter'),
                "admin" => array('title' => 'Admin', 'active' => false, 'icon' => 'dashicons dashicons-admin-users' ),
                "settings" => array('title' => 'Settings', 'active' => false, 'icon' => 'dashicons dashicons-forms'),
            );
            $LINOTYPE_EDITOR['data'] = LINOTYPE::$BLOCKS->get($ID);
            $LINOTYPE_EDITOR['return_url'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php#blocks';
        break;

        case "module":
            $LINOTYPE_EDITOR['toolbar'] = array( 'close', 'back', 'tabs', 'sync', 'save' );    
            $LINOTYPE_EDITOR['tabs'] = array(
                "preview" => array('title' => 'Preview', 'active' => false, 'icon' => 'dashicons dashicons-admin-site'),
                "module" => array('title' => 'Module', 'active' => true, 'icon' => 'dashicons dashicons-welcome-widgets-menus' ),
                //"options" => array('title' => 'Options', 'active' => false ),
                "settings" => array('title' => 'Settings', 'active' => false, 'icon' => 'dashicons dashicons-forms'),
            );
            $LINOTYPE_EDITOR['data'] = LINOTYPE::$MODULES->get($ID);
            $LINOTYPE_EDITOR['return_url'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php#modules';
        break;

        case "template":
            $LINOTYPE_EDITOR['toolbar'] = array( 'close', 'back', 'tabs', 'sync', 'save' );    
            $LINOTYPE_EDITOR['tabs'] = array(
                "preview" => array('title' => 'Preview', 'active' => false, 'icon' => 'dashicons dashicons-admin-site'),
                "template" => array('title' => 'Template', 'active' => true, 'icon' => 'dashicons dashicons-admin-page' ),
                //"options" => array('title' => 'Options', 'active' => false ),
                "settings" => array('title' => 'Settings', 'active' => false, 'icon' => 'dashicons dashicons-forms' ),
            );
            $LINOTYPE_EDITOR['data'] = LINOTYPE::$TEMPLATES->get($ID);
            $LINOTYPE_EDITOR['return_url'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php#templates';
        break;

        case "theme":
            $LINOTYPE_EDITOR['toolbar'] = array( 'close', 'back', 'tabs', 'sync', 'save' );  
            $LINOTYPE_EDITOR['tabs'] = array(
                //"preview" => array('title' => 'Preview', 'active' => false, 'icon' => 'dashicons dashicons-admin-site'),
                "map" => array('title' => 'Map', 'active' => true, 'icon' => 'dashicons dashicons-networking' ),
                "customposts" => array('title' => 'Custom', 'active' => false, 'icon' => 'dashicons dashicons-pressthis' ),
                "globals" => array('title' => 'Globals', 'active' => false, 'icon' => 'dashicons dashicons-editor-ul' ),
                "header" => array('title' => 'Header', 'active' => false, 'icon' => 'dashicons dashicons-arrow-up-alt2' ),
                "footer" => array('title' => 'Footer', 'active' => false, 'icon' => 'dashicons dashicons-arrow-down-alt2' ),
                "reset" => array('title' => 'Reset', 'active' => false, 'icon' => 'dashicons dashicons-admin-appearance' ),
                "style" => array('title' => 'Style', 'active' => false, 'icon' => 'dashicons dashicons-admin-customizer' ),
                "assets" => array('title' => 'Assets', 'active' => false, 'icon' => 'dashicons dashicons-category' ),                
                "settings" => array('title' => 'Settings', 'active' => false, 'icon' => 'dashicons dashicons-forms'),
            );
            $LINOTYPE_EDITOR['data'] = LINOTYPE::$THEMES->get($ID);
            $LINOTYPE_EDITOR['return_url'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php#themes';
        break;

        case "library":
            $LINOTYPE_EDITOR['toolbar'] = array( 'close', 'back', 'tabs', 'sync', 'save' );  
            $LINOTYPE_EDITOR['tabs'] = array(
                "script" => array('title' => 'Script', 'active' => false, 'icon' => 'dashicons dashicons-hammer' ),
                "style" => array('title' => 'Style', 'active' => false, 'icon' => 'dashicons dashicons-admin-customizer' ),
                "loader" => array('title' => 'Loader', 'active' => false, 'icon' => 'dashicons dashicons-update-alt'),
                "assets" => array('title' => 'Assets', 'active' => false, 'icon' => 'dashicons dashicons-category' ),
                "settings" => array('title' => 'Settings', 'active' => false, 'icon' => 'dashicons dashicons-forms'),
            );
            $LINOTYPE_EDITOR['data'] = LINOTYPE::$LIBRARIES->get($ID);
            $LINOTYPE_EDITOR['return_url'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php#libraries';
        break;

        case "field":
            $LINOTYPE_EDITOR['toolbar'] = array( 'close', 'back', 'tabs', 'sync', 'save' );  
            $LINOTYPE_EDITOR['tabs'] = array(
                "preview" => array('title' => 'Preview', 'active' => false, 'icon' => 'dashicons dashicons-admin-site'),
                "code" => array('title' => 'Code', 'active' => true, 'icon' => 'dashicons dashicons-editor-code' ),
                "style" => array('title' => 'Style', 'active' => false, 'icon' => 'dashicons dashicons-admin-customizer' ),
                "script" => array('title' => 'Script', 'active' => false, 'icon' => 'dashicons dashicons-hammer' ),
                "functions" => array('title' => 'Functions', 'active' => false, 'icon' => 'dashicons dashicons-admin-plugins' ),
                "admin" => array('title' => 'Admin', 'active' => false, 'icon' => 'dashicons dashicons-admin-users' ),
                "assets" => array('title' => 'Assets', 'active' => false, 'icon' => 'dashicons dashicons-category' ),
                "options" => array('title' => 'Options', 'active' => false, 'icon' => 'dashicons dashicons-image-filter'),
                "settings" => array('title' => 'Settings', 'active' => false, 'icon' => 'dashicons dashicons-forms'),
            );
            $LINOTYPE_EDITOR['data'] = LINOTYPE::$FIELDS->get($ID);
            $LINOTYPE_EDITOR['return_url'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php#fields';
        break;
        
    }

}

function linotype_editor_update(){

    global $LINOTYPE_EDITOR;
    
    if ( isset( $_POST['type'] ) && isset( $_POST['action'] ) && $_POST['action'] == 'save' ) {
        
        if ( ! current_user_can( 'linotype_save' ) ) wp_die( '<h1>LINOTYPRE</h1>' . '<p>You can\'t save. contact administrator</p>', 403 );

        if ( isset( $_POST['type'] ) ) {

            switch ( $_POST['type'] ) {
            
                case 'block':
                    $update = LINOTYPE::$BLOCKS->update( $LINOTYPE_EDITOR['data']['id'], $LINOTYPE_EDITOR['data'], $_POST['sync'] );
                break;
            
                case 'module':
                    $update = LINOTYPE::$MODULES->update( $LINOTYPE_EDITOR['data']['id'], $LINOTYPE_EDITOR['data'], $_POST['sync'] );
                break;

                case 'template':
                    $update = LINOTYPE::$TEMPLATES->update( $LINOTYPE_EDITOR['data']['id'], $LINOTYPE_EDITOR['data'], $_POST['sync'] );
                break;
            
                case 'theme':
                    $update = LINOTYPE::$THEMES->update( $LINOTYPE_EDITOR['data']['id'], $LINOTYPE_EDITOR['data'], $_POST['sync'] );
                break;
                
                case 'library':
                    $update = LINOTYPE::$LIBRARIES->update( $LINOTYPE_EDITOR['data']['id'], $LINOTYPE_EDITOR['data'], $_POST['sync'] );
                break;
            
                case 'field':
                    $update = LINOTYPE::$FIELDS->update( $LINOTYPE_EDITOR['data']['id'], $LINOTYPE_EDITOR['data'], $_POST['sync'] );
                break;
            
            }

        }

        if ( $update['status'] == "success" ) {
        
            wp_redirect( add_query_arg( array(
                'type' => $LINOTYPE_EDITOR['data']['type'], 
                'id' => $LINOTYPE_EDITOR['data']['id'], 
                'updated' => true,
                'message' => $update['message'],
                'cache' => time(),
                ), 
                LINOTYPE_plugin::$plugin['url'] . 'editor/index.php'
            )); 
            exit;
        
        } else {

            wp_redirect( add_query_arg( array(
                'type' => $LINOTYPE_EDITOR['data']['type'], 
                'id' => $LINOTYPE_EDITOR['data']['id'], 
                'updated' => false,
                'message' => $update['message'],
                'cache' => time(), 
                ), 
                LINOTYPE_plugin::$plugin['url'] . 'editor/index.php' 
            ));
            exit;

        }
    
    }

}

function linotype_editor_delete(){

    global $LINOTYPE_EDITOR;
    
    $DATA = $_POST;
    
    if ( isset( $_POST['type'] ) && isset( $_POST['action'] ) && $_POST['action'] == 'save' && isset($DATA['delete']) && $DATA['delete'] == $DATA['id'] ) {

        if ( ! current_user_can( 'linotype_save' ) ) wp_die( '<h1>LINOTYPRE</h1>' . '<p>You can\'t delete. contact administrator</p>', 403 );

        if ( isset( $DATA['type'] ) ) {

            switch ( $DATA['type'] ) {
            
                case 'block':
                    $delete = LINOTYPE::$BLOCKS->delete( $DATA['delete'], $DATA['sync'] );
                break;
            
                case 'module':
                    $delete = LINOTYPE::$MODULES->delete( $DATA['delete'], $DATA['sync'] );
                break;

                case 'template':
                    $delete = LINOTYPE::$TEMPLATES->delete( $DATA['delete'], $DATA['sync'] );
                break;
            
                case 'theme':
                    $delete = LINOTYPE::$THEMES->delete( $DATA['delete'], $DATA['sync'] );
                break;
                
                case 'library':
                    $delete = LINOTYPE::$LIBRARIES->delete( $DATA['delete'], $DATA['sync'] );
                break;
            
                case 'field':
                    $delete = LINOTYPE::$FIELDS->delete( $DATA['delete'], $DATA['sync'] );
                break;
            
            }

        }

        wp_redirect( add_query_arg( array(
            'type' => 'index',
            'message' => $delete['message'],
            'cache' => time(),
            ), 
            LINOTYPE_plugin::$plugin['url'] . 'editor/index.php' 
        ));
        exit;
        
    }

}

function linotype_editor_add(){

    global $LINOTYPE_EDITOR;
    
    $DATA = $_POST;

    if ( isset( $DATA['type'] ) && $DATA['type'] == 'add' && isset( $DATA['action'] ) && $DATA['action'] == 'save' && isset( $DATA['target'] ) && $DATA['target'] && isset($DATA['id']) && $DATA['id'] && isset($DATA['author']) && $DATA['author'] && isset($DATA['title']) && $DATA['title']  ) {
        
        if ( ! current_user_can( 'linotype_save' ) ) wp_die( '<h1>LINOTYPRE</h1>' . '<p>You can\'t add. contact administrator</p>', 403 );

        switch ( $DATA['target'] ) {
        
            case 'block':
                $add = LINOTYPE::$BLOCKS->create( $DATA, $DATA['sync'] );
            break;
        
            case 'module':
                $add = LINOTYPE::$MODULES->create( $DATA, $DATA['sync'] );
            break;

            case 'template':
                $add = LINOTYPE::$TEMPLATES->create( $DATA, $DATA['sync'] );
            break;
        
            case 'theme':
                $add = LINOTYPE::$THEMES->create( $DATA, $DATA['sync'] );
            break;

            case 'library':
                $add = LINOTYPE::$LIBRARIES->create( $DATA, $DATA['sync'] );
            break;
        
            case 'field':
                $add = LINOTYPE::$FIELDS->create( $DATA, $DATA['sync'] );
            break;
        
        }

        if ( isset( $add['status'] ) && $add['status'] == "success" ) {
 
            wp_redirect( add_query_arg( array(
                'type' => $DATA['target'], 
                'id' => $add['id'],
                'message' => $add['message'],
                'cache' => time(), 
                ), 
                LINOTYPE_plugin::$plugin['url'] . 'editor/index.php' 
            ));
            exit;
        
        } else {
      
            wp_redirect( add_query_arg( array(
                'type' => $LINOTYPE_EDITOR['data']['type'], 
                'message' => $add['message'],
                'cache' => time(), 
                ), 
                LINOTYPE_plugin::$plugin['url'] . 'editor/index.php' 
            ));
            exit;

        }
    
    }

}

function linotype_editor_import(){

    global $LINOTYPE_EDITOR;
    
    $DATA = $_POST;

    if ( isset( $DATA['type'] ) && $DATA['type'] == 'import' && isset( $DATA['action'] ) && $DATA['action'] == 'save' && isset($DATA['target']) && $DATA['target'] && isset($DATA['import']) && $DATA['import'] ) {
    
        if ( ! current_user_can( 'linotype_save' ) ) wp_die( '<h1>LINOTYPRE</h1>' . '<p>You can\'t import. contact administrator</p>', 403 );

        switch ( $DATA['target'] ) {
        
            case 'block':
                $import = LINOTYPE::$BLOCKS->import( $DATA['import'] );
            break;
        
            case 'module':
                $import = LINOTYPE::$MODULES->import( $DATA['import'] );
            break;

            case 'template':
                $import = LINOTYPE::$TEMPLATES->import( $DATA['import'] );
            break;
        
            case 'theme':
                $import = LINOTYPE::$THEMES->import( $DATA['import'] );
            break;

            case 'library':
                $import = LINOTYPE::$LIBRARIES->import( $DATA['import'] );
            break;
        
            case 'field':
                $import = LINOTYPE::$FIELDS->import( $DATA['import'] );
            break;
        
        }

        if ( isset( $import['status'] ) && $import['status'] == "success" ) {
 
            wp_redirect( add_query_arg( array(
                'type' => $DATA['target'], 
                'id' => $import['id'],
                'message' => $import['message'],
                'cache' => time(), 
                ), 
                LINOTYPE_plugin::$plugin['url'] . 'editor/index.php' 
            ));
            exit;
        
        } else {
      
            wp_redirect( add_query_arg( array(
                'type' => $LINOTYPE_EDITOR['data']['type'],
                'message' => $import['message'],
                'cache' => time(), 
                ), 
                LINOTYPE_plugin::$plugin['url'] . 'editor/index.php' 
            ));
            exit;

        }
    
    }

}


function linotype_editor_fetch() {

    global $LINOTYPE_EDITOR;
    
    $DATA = $_GET;

    if ( isset( $DATA['type'] ) && $DATA['type'] == 'fetch' && isset( $DATA['target'] ) ) {

        if ( ! current_user_can( 'linotype_save' ) ) wp_die( '<h1>LINOTYPRE</h1>' . '<p>You can\'t fetch. contact administrator</p>', 403 );

        switch ( $DATA['target'] ) {
        
            case 'block':
                $SYNC_DATA = LINOTYPE::$SYNC->fetch( 'blocks' ,   LINOTYPE::$BLOCKS->get() );
            break;
        
            case 'module':
                $SYNC_DATA = LINOTYPE::$SYNC->fetch( 'modules', LINOTYPE::$MODULES->get() );
            break;

            case 'template':
                $SYNC_DATA = LINOTYPE::$SYNC->fetch( 'templates', LINOTYPE::$TEMPLATES->get() );
            break;
        
            case 'theme':
                $SYNC_DATA = LINOTYPE::$SYNC->fetch( 'themes',    LINOTYPE::$THEMES->get() );
            break;

            case 'library':
                $SYNC_DATA = LINOTYPE::$SYNC->fetch( 'libraries', LINOTYPE::$LIBRARIES->get() ); 
            break;
        
            case 'field':
                $SYNC_DATA = LINOTYPE::$SYNC->fetch( 'fields',    LINOTYPE::$FIELDS->get() );
            break;
        
        }
        
        wp_redirect( add_query_arg( array(
            'type' => 'index',
            'message' => 'Fetched (' . $DATA['target'] . ')',
            'cache' => time(), 
            ), 
            LINOTYPE_plugin::$plugin['url'] . 'editor/index.php'
        ));
        exit;
    
    }

}

add_action( 'linotype_editor_init', 'linotype_editor_init', 20 );
add_action( 'linotype_editor_init', 'linotype_editor_fetch', 30 );

add_action( 'linotype_editor_save', 'linotype_editor_delete', 40 );
add_action( 'linotype_editor_save', 'linotype_editor_add', 50 );
add_action( 'linotype_editor_save', 'linotype_editor_import', 60 );
add_action( 'linotype_editor_save', 'linotype_editor_update', 70 );


add_action( 'linotype_editor_save', function(){

    if ( current_user_can( 'linotype_save' ) && isset( $_GET['cache'] ) && $_GET['cache'] ) {
   
        header('Cache-Control: max-age=0');

        LINOTYPE_cache::clear_server_cache();
    
        //$build = new COMPOSER_build( array(
        //    "theme" => LINOTYPE::$THEME,
        //    "blocks" => LINOTYPE::$BLOCKS->get(),
        //));
    
    }

}, 100 );





?>
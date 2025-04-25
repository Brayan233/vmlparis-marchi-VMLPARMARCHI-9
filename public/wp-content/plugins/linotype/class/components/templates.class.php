<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 *
 * LINOTYPE_templates
 *
 */
class LINOTYPE_templates {

  public $SETTINGS;

  public $ITEMS;

  static $CONFIG = array(
    "type" => "template",
    "author" => "",
    "version" => "",
    "update" => "",
    "commit" => "",
    "sync" => "",
    "title" => "",
    "desc" => "",
    "icon" => "dashicons dashicons-admin-page",
    "color" => "",
    "category" => "",
    "supports" => array(),
    "tags" => array(),
    "target" => array()
  ); 

  /*
   *
   * __construct
   *
   */
  function __construct( $settings ) {
    
    //save settings
    $this->SETTINGS = $settings;

    //create directory
    if ( ! file_exists( $this->SETTINGS['dir'] ) ) wp_mkdir_p( $this->SETTINGS['dir'] );
    
    //get templates
    $this->ITEMS = $this->init();

  }

  /**
  *
  * init
  *
  **/
  public function init() {
    
    $CONFIG = array();

    $data_dir_list = handypress_helper::getFileList( $this->SETTINGS['dir'] );

    LINOTYPE::$SYNC->init( 'templates', $this->SETTINGS['dir'] . '/sync.json' );

    if ( $data_dir_list ){
      foreach ( $data_dir_list as $data_dir_list_key => $data ) {

        if ( $data['type'] == 'dir' ) $CONFIG[ $data['name'] ] = $this->get_config( $data['name'] );
        
      }
    }

    uasort( $CONFIG, function( $a, $b ) {
    
      return  strcmp($a["title"], $b["title"]);
    
    });

    return $CONFIG;

  }

  /*
   *
   * get_config
   *
   */
  public function get_config( $ID ){

    $CONFIG = self::$CONFIG;

    $DIR = $this->SETTINGS['dir'] . '/' . $ID;
    $URL = $this->SETTINGS['url'] . '/' . $ID;

    if ( file_exists( $DIR . '/config.json' ) ) {
      
      $CONFIG['id'] = $ID;
      $CONFIG['hash'] = hash('crc32', $ID );
      $CONFIG['path'] = 'templates/' . $ID;
      $CONFIG['dir'] = $DIR;
      $CONFIG['url'] = $URL;
      $CONFIG['preview'] = $URL . '/preview.png';
      $CONFIG['editor_link'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php?type=template&id=' . $ID;

      $config_data = file_get_contents( $DIR . '/config.json' );
    
      if ( $config_data ) {
        
        $config_data = json_decode( $config_data, true );
        $CONFIG = array_merge( $CONFIG, array_filter( $config_data ) );
      
      }

    } else {

      linolog( 'error', 'no config file for ' . $ID );

    }



    $CONFIG['config']['template'] = array();
    $CONFIG['template'] = array();

    if ( file_exists( $DIR . '/template.json' ) ) {

      $template_data = file_get_contents( $DIR . '/template.json' );
      
      if ( $template_data ) {
        
        $template_data = json_decode( $template_data, true );
        $CONFIG['template'] = $template_data;
      
      }

    }

    // if ( file_exists( $DIR . '/functions.php' ) ) {
    //   include $DIR . '/functions.php';
    // }

    $CONFIG['sync_status'] = LINOTYPE::$SYNC->get_status( 'templates', $ID );
    
    return $CONFIG;
    
  }

  
  /*
   *
   * format_config
   *
   */
  public function format_config( $DATA ) {

    $NEW_DATA = self::$CONFIG;
      
    foreach( self::$CONFIG as $DATA_key => $DATA_default ) {

      if ( isset( $DATA[ $DATA_key ] ) && $DATA[ $DATA_key ] ) {
        
        switch ( $DATA_key ) {

          case "tags":
            $NEW_DATA[ $DATA_key ] = explode( ',', $DATA[ $DATA_key ] );
          break;
          
          default:
            $NEW_DATA[ $DATA_key ] = $DATA[ $DATA_key ];
          break;
        
        }
          
      }

    }
    
    $date = new DateTime();
    $NEW_DATA[ 'update' ] = $date->format( DateTime::ATOM );
    
    return $NEW_DATA;

  }

  /*
   *
   * update
   *
   */
  public function update( $ID = null, $DATA = null, $SYNC = null  ) {
    
    $output = array("status" => "error", "message" => "template-update-error-not-save" );

    if ( $ID && $DATA ) {
      
      if ( isset( $this->ITEMS[$ID] ) ) {

        if ( is_writable( $DATA['dir'] ) ) {

          if ( $SYNC == 'fetch' ) {

            $this->fetch_config( $DATA['dir'], $ID, 'templates/' . $ID, 'config.json', $_POST, $SYNC );

          } else {
          
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'templates/' . $ID, 'template.json', stripslashes( $_POST['template'] ), $SYNC );
            
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'templates/' . $ID, 'preview.png', stripslashes( $_POST['screenshot'] ), $SYNC );
            
            LINOTYPE_file::update_config( $DATA['dir'], $ID, 'templates/' . $ID, 'config.json', $this->format_config( $_POST ), $SYNC );
            
            $this->ITEMS[$ID] = $this->get_config( $ID );
            LINOTYPE::$SYNC->fetch( 'templates', $this->ITEMS, $ID );

          }

          $output = array("status" => "success", "message" => false );

        } else {

          $output = array("status" => "error", "message" => "template-update-error-not-writable" );
            
        }
        
      
      } else {

        $output = array("status" => "error", "message" => "template-update-error-not-exist" );

      }

    }

    return $output;

  }

  public function create( $DATA = null, $SYNC = null ) {
    
    $output = array("status" => "error", "message" => "template-create-error-1" );
    
    $ID = $DATA['author'] . '_template_' . $DATA['id'];

    if ( ! isset( $this->ITEMS[ $ID ] ) ) {
      
      $DIR = $this->SETTINGS['dir'] . '/' . $ID;
      
      if ( ! is_dir( $DIR ) && wp_mkdir_p( $DIR ) ) {

        if ( isset( $DATA['duplicate'] ) && $DATA['duplicate'] ) {

          $DUPLICATE_PATH = explode( '/', $DATA['duplicate'] );
          $DUPLICATE_DIR = $this->SETTINGS['dir'] . '/' .$DUPLICATE_PATH[1];

          if ( file_exists( $DUPLICATE_DIR .'/template.json' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'templates/' . $ID, 'template.json', file_get_contents( $DUPLICATE_DIR .'/template.json' ), '' );
            
          if ( file_exists( $DUPLICATE_DIR .'/preview.png' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'templates/' . $ID, 'preview.png', file_get_contents( $DUPLICATE_DIR .'/preview.png' ), '' );
            
          $DUPLICATE_CONFIG = array();
          $DUPLICATE_CONFIG_FILE = file_get_contents( $DUPLICATE_DIR .'/config.json' );
          if ( $DUPLICATE_CONFIG_FILE ) $DUPLICATE_CONFIG = json_decode( $DUPLICATE_CONFIG_FILE, true );
          
          $CONFIG = $this->format_config( $DUPLICATE_CONFIG );
          $CONFIG[ 'author' ] = $DATA[ 'author' ];
          $CONFIG[ 'title' ] = $DATA[ 'title' ];
          $CONFIG[ 'type' ] = 'template';
          $CONFIG[ 'version' ] = "1.0";

        } else {

          $CONFIG = $this->format_config( $DATA );
          $CONFIG[ 'type' ] = 'template';
          $CONFIG[ 'version' ] = "1.0";

        }

        LINOTYPE_file::update_config( $DIR, $ID, 'templates/' . $ID, 'config.json', $CONFIG, $SYNC );

        $this->ITEMS[$ID] = $this->get_config( $ID );
        LINOTYPE::$SYNC->fetch( 'templates', $this->ITEMS, $ID );

        $output = array("status" => "success", "id" => $ID, "message" => false );

      } else {

        $output = array("status" => "error", "message" => "template-create-error-3" );
          
      }
      
    
    } else {

      $output = array("status" => "error", "message" => "template-create-error-2" );

    }

    return $output;

  }
  
  /*
   *
   * import
   *
   */
  public function import( $ID = null ) {
    
    $output = array("status" => "error", "message" => "template-create-error-1" );
 
    $DIR = $this->SETTINGS['dir'] . '/' . $ID;
    
    if ( ! is_dir( $DIR ) ) wp_mkdir_p( $DIR );

    if ( is_dir( $DIR ) ) {

      LINOTYPE_file::update_file( $DIR, $ID, 'templates/' . $ID, 'template.json', '', 'pull' );
      
      LINOTYPE_file::update_file( $DIR, $ID, 'templates/' . $ID, 'preview.png', '', 'pull' );
      
      LINOTYPE_file::update_config( $DIR, $ID, 'templates/' . $ID, 'config.json', '', 'pull' );
      
      $this->ITEMS[$ID] = $this->get_config( $ID );
      LINOTYPE::$SYNC->fetch( 'templates', $this->ITEMS, $ID );

      $output = array("status" => "success", "id" => $ID, "message" => false );

    } else {

      $output = array("status" => "error", "message" => "template-create-error-3" );
        
    }

    return $output;

  }
  
  /*
   *
   * delete
   *
   */
  public function delete( $ID, $SYNC ) {
    
    $output = array("status" => "error", "message" => "template-delete-error-1" );
 
    if ( $ID ) {
      
      $dir = $this->SETTINGS['dir'] . '/' . $ID;
      
      if ( is_dir( $dir ) ) {

        if ( $SYNC == 'push' ) LINOTYPE::$SYNC->delete( 'templates/' . $ID );

        LINOTYPE_helpers::rrmdir( $dir );
        
        $output = array("status" => "success", "id" => $ID, "message" => "delete" );

      } else {

        $output = array("status" => "error", "message" => "template-delete-error-3" );
          
      }
      
    } else {

      $output = array("status" => "error", "message" => "template-delete-error-2" );

    }

    return $output;

  }

  public function fetch_config( $DIR, $ID, $PATH, $FILE, $DATA, $SYNC ) {

    $exist = LINOTYPE::$SYNC->exist( $PATH . '/' . $FILE ); 
    if ( $exist ) LINOTYPE::$SYNC->fetch( 'templates', $this->ITEMS, $ID );

  }

  /*
   *
   * get
   *
   */
  public function get( $id = null ) {
    
    if ( $id ) {
      
      if ( isset( $this->ITEMS[$id] ) ) {

        return $this->ITEMS[$id];
      
      } else {

        return null;

      }

    } else {

      return $this->ITEMS;

    }

  }

  /*
   *
   * get_select_data
   *
   */
  public function get_select_data() {
    
    $data = array();

    if ( $this->ITEMS ) {
      foreach ( $this->ITEMS as $ITEM_key => $ITEM ) {
        array_push( $data, array( 'title' => $ITEM['title'], 'value' => $ITEM_key ) );
      }
    }

    return $data;
        
  }


  /**
  *
  * load
  *
  **/
  public function load( $libraries ) {
    
  }


}

<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 *
 * LINOTYPE_blocks
 *
 */
class LINOTYPE_blocks {

  public $SETTINGS;

  public $ITEMS;

  static $CONFIG = array(
    "type" => "block",
    "author" => "",
    "version" => "",
    "update" => "",
    "commit" => "",
    "title" => "",
    "desc" => "",
    "icon" => "dashicons dashicons-admin-generic",
    "color" => "",
    "collapse" => false,
    "category" => "",
    "tags" => array(),
    "target" => array(),
    "parent" => array(),
    "accept" => array(),
    "libraries" => array()
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
    
    //get blocks
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
    
    LINOTYPE::$SYNC->init( 'blocks', $this->SETTINGS['dir'] . '/sync.json' );

    if ( $data_dir_list ){
      foreach ( $data_dir_list as $data_dir_list_key => $data ) {
        
        if ( $data['type'] == 'dir' ) $CONFIG[ $data['name'] ] = $this->get_config( $data['name'] );

        //check childrens
        if ( file_exists( $this->SETTINGS['dir'] . '/' . $data['name'] . '/childrens' ) ) {

          $data_dir_list_children = handypress_helper::getFileList( $this->SETTINGS['dir'] . '/' . $data['name'] . '/childrens' );
      
          if ( $data_dir_list_children ){
            foreach ( $data_dir_list_children as $data_dir_list_children_key => $children ) {
              
              if ( $children['type'] == 'dir' ) $CONFIG[ $children['name'] ] = $this->get_config( $children['name'], $data['name'] );
              
            }
          }

        }
        
      }
    }
    
    uasort( $CONFIG, function( $a, $b ) {
    
      return  strcmp($a["title"], $b["title"]);
    
    });

    // _HANDYLOG( 'CONFIG', $CONFIG );

    return $CONFIG;

  }

  /**
  *
  * add
  *
  **/
  public function add( $type, $items ) {

    if ( $items ){
      foreach ( $items as $item_key => $item ) {
        
        $item['options'] = $this->get_module_options( $item['module'] );

        //linolog('$overwrite', $item );

        $this->ITEMS[ $item_key ] = $item;
        
      }
    }
    
    // _HANDYLOG( 'add:' . $type, $items );

  }

  public function get_module_options( $template, $options = array() ) {

     if ( $template ){
       foreach ( $template as $item_key => $item ) {

        if ( isset( $item['options']['_overwrite'] ) && $item['options']['_overwrite'] ) {

          foreach ( $item['options']['_overwrite'] as $overwrite_key => $overwrite ) {

            if ( $overwrite[ 'overwrite_target'] === 'template' ) {

              if ( ! $overwrite['tab'] ) $overwrite['tab'] = 'Module';

              $options[ $overwrite['meta_id'] ] = array(
                "title" => $overwrite['title'],
                "info" => ( isset( $overwrite['info'] ) ? $overwrite['info'] : "" ),
                "type" => $overwrite['type'],
                "options" => $overwrite,
                "col" => $overwrite['col'],
                "desc" => $overwrite['desc'],
                "fullwidth" => true,
                "help" => true,
                "padding" => "20px 20px 0px 20px",
                "tab" => $overwrite['tab']
              );

            }

          }

        }
        
        if ( isset( $item['contents'] ) && $item['contents'] ) {
          
          $options = $this->get_module_options( $item['contents'], $options );

        }
        
      }
    }

    return $options;

  }


  /*
   *
   * get_config
   *
   */
  public function get_config( $ID, $parent_id = false ){

    $CONFIG = self::$CONFIG;
    
    $children_path = '';
    if ( $parent_id ) $children_path = $parent_id . '/childrens/';

    $DIR = $this->SETTINGS['dir'] . '/' . $children_path . $ID;
    $URL = $this->SETTINGS['url'] . '/' . $children_path . $ID;

    if ( file_exists( $DIR . '/config.json' ) ) {
      
      $CONFIG['id'] = $ID;
      $CONFIG['hash'] = hash('crc32', $ID );
      $CONFIG['path'] = 'blocks/' . $ID;
      $CONFIG['dir'] = $DIR;
      $CONFIG['url'] = $URL;
      $CONFIG['preview'] = $URL . '/preview.png';
      $CONFIG['editor_link'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php?type=block&id=' . $ID;

      $config_data = file_get_contents( $DIR . '/config.json' );
    
      if ( $config_data ) {
        
        $config_data = json_decode( $config_data, true );
        $CONFIG = array_merge( $CONFIG, array_filter( $config_data ) );
      
      }

    }

    $CONFIG['config']['options'] = array();
    $CONFIG['options'] = array();

    if ( file_exists( $DIR . '/options.json' ) ) {

      $options_data = file_get_contents( $DIR . '/options.json' );
      
      if ( $options_data ) {
        
        $options_data = json_decode( $options_data, true );
        $CONFIG['config']['options'] = $options_data;
        $CONFIG['options'] = $options_data;
      
      }

    }
    
    $CONFIG['sync_status'] = LINOTYPE::$SYNC->get_status( 'blocks', $ID );
    
    return $CONFIG;
    
  }

  /*
   *
   * format_config
   *
   */
  public function format_config( $DATA ){

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
  public function update( $ID = null, $DATA = null, $SYNC = null ) {
    
    //return array("status" => "error", "message" => json_encode( $_POST ) );

    $output = array("status" => "error", "message" => "block-update-error-not-save" );

    if ( $ID && $DATA ) {
      
      if ( isset( $this->ITEMS[$ID] ) ) {

        if ( is_writable( $DATA['dir'] ) ) {

          if ( $SYNC == 'fetch' ) {

            $this->fetch_config( $DATA['dir'], $ID, 'blocks/' . $ID, 'config.json', $_POST, $SYNC );

          } else {
          
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'blocks/' . $ID, 'template.php', stripslashes( $_POST['code-php'] ), $SYNC );

            if ( isset( $_POST['css-processor'] ) && $_POST['css-processor'] == 'scss' ) {

              LINOTYPE_file::update_file( $DATA['dir'], $ID, 'blocks/' . $ID, 'style.scss', stripslashes( $_POST['code-css'] ), $SYNC );

              $_POST['code-css'] = LINOTYPE_helpers::preprocessor_css( 'scss', stripslashes( $_POST['code-css'] ) );

            } else if ( isset( $_POST['css-processor'] ) && $_POST['css-processor'] == 'less' ) {

              LINOTYPE_file::update_file( $DATA['dir'], $ID, 'blocks/' . $ID, 'style.less', stripslashes( $_POST['code-css'] ), $SYNC );

              $_POST['code-css'] = LINOTYPE_helpers::preprocessor_css( 'less', stripslashes( $_POST['code-css'] ) );

            }

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'blocks/' . $ID, 'style.css', stripslashes( $_POST['code-css'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'blocks/' . $ID, 'script.js', stripslashes( $_POST['code-js'] ), $SYNC );
            
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'blocks/' . $ID, 'functions.php', stripslashes( $_POST['code-functions'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'] . '/admin', $ID, 'blocks/' . $ID . '/admin', 'template.php', stripslashes( $_POST['admin-php'] ), $SYNC );
            LINOTYPE_file::update_file( $DATA['dir'] . '/admin', $ID, 'blocks/' . $ID . '/admin', 'style.css', stripslashes( $_POST['admin-css'] ), $SYNC );
            LINOTYPE_file::update_file( $DATA['dir'] . '/admin', $ID, 'blocks/' . $ID . '/admin', 'script.js', stripslashes( $_POST['admin-js'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'blocks/' . $ID, 'preview.png', stripslashes( $_POST['screenshot'] ), $SYNC );
            
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'blocks/' . $ID, 'options.json', stripslashes( $_POST['options'] ), $SYNC );

            LINOTYPE_file::update_config( $DATA['dir'], $ID, 'blocks/' . $ID, 'config.json', $this->format_config( $_POST ), $SYNC );
            
            $this->ITEMS[$ID] = $this->get_config( $ID );
            LINOTYPE::$SYNC->fetch( 'blocks', $this->ITEMS, $ID );

          }

          $output = array("status" => "success", "message" => false );

        } else {

          $output = array("status" => "error", "message" => "block-update-error-not-writable" );
            
        }
        
      
      } else {

        $output = array("status" => "error", "message" => "block-update-error-not-exist" );

      }

    }

    return $output;

  }


  /*
   *
   * create
   *
   */
  public function create( $DATA = null, $SYNC = null ) {
    
    $output = array("status" => "error", "message" => "block-create-error-1" );
    
    $ID = $DATA['author'] . '_block_' . $DATA['id'];

    if ( ! isset( $this->ITEMS[ $ID ] ) ) {
      
      $DIR = $this->SETTINGS['dir'] . '/' . $ID;
      
      if ( ! is_dir( $DIR ) && wp_mkdir_p( $DIR ) ) {

        if ( isset( $DATA['duplicate'] ) && $DATA['duplicate'] ) {

          $DUPLICATE_PATH = explode( '/', $DATA['duplicate'] );
          $DUPLICATE_DIR = $this->SETTINGS['dir'] . '/' .$DUPLICATE_PATH[1];

          if ( file_exists( $DUPLICATE_DIR .'/template.php' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'template.php', file_get_contents( $DUPLICATE_DIR .'/template.php' ), '' );
          if ( file_exists( $DUPLICATE_DIR .'/style.css' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'style.css', file_get_contents( $DUPLICATE_DIR .'/style.css' ), '' );
          if ( file_exists( $DUPLICATE_DIR .'/script.js' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'script.js', file_get_contents( $DUPLICATE_DIR .'/script.js' ), '' );
          
          if ( file_exists( $DUPLICATE_DIR .'/functions.php' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'functions.php', file_get_contents( $DUPLICATE_DIR .'/functions.php' ), '' );

          if ( file_exists( $DUPLICATE_DIR .'/admin/template.php' ) ) LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'blocks/' . $ID . '/admin', 'template.php', file_get_contents( $DUPLICATE_DIR .'/admin/template.php' ), '' );
          if ( file_exists( $DUPLICATE_DIR .'/admin/style.css' ) ) LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'blocks/' . $ID . '/admin', 'style.css', file_get_contents( $DUPLICATE_DIR .'/admin/style.css' ), '' );
          if ( file_exists( $DUPLICATE_DIR .'/admin/script.js' ) ) LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'blocks/' . $ID . '/admin', 'script.js', file_get_contents( $DUPLICATE_DIR .'/admin/script.js' ), '' );

          if ( file_exists( $DUPLICATE_DIR .'/preview.png' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'preview.png', file_get_contents( $DUPLICATE_DIR .'/preview.png' ), '' );
          
          if ( file_exists( $DUPLICATE_DIR .'/options.json' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'options.json', file_get_contents( $DUPLICATE_DIR .'/options.json' ), '' );

          $DUPLICATE_CONFIG = array();
          $DUPLICATE_CONFIG_FILE = file_get_contents( $DUPLICATE_DIR .'/config.json' );
          if ( $DUPLICATE_CONFIG_FILE ) $DUPLICATE_CONFIG = json_decode( $DUPLICATE_CONFIG_FILE, true );
          
          $CONFIG = $this->format_config( $DUPLICATE_CONFIG );
          $CONFIG[ 'author' ] = $DATA[ 'author' ];
          $CONFIG[ 'title' ] = $DATA[ 'title' ];
          $CONFIG[ 'type' ] = 'block';
          $CONFIG[ 'version' ] = "1.0";

        } else {

          $CONFIG = $this->format_config( $DATA );
          $CONFIG[ 'type' ] = 'block';
          $CONFIG[ 'version' ] = "1.0";

        }

        LINOTYPE_file::update_config( $DIR, $ID, 'blocks/' . $ID, 'config.json', $CONFIG, '' );

        $this->ITEMS[$ID] = $this->get_config( $ID );
        LINOTYPE::$SYNC->fetch( 'blocks', $this->ITEMS, $ID );

        $output = array("status" => "success", "id" => $ID, "message" => false );

      } else {

        $output = array("status" => "error", "message" => "block-create-error-3" );
          
      }
      
    
    } else {

      $output = array("status" => "error", "message" => "block-create-error-2" );

    }

    return $output;

  }


  /*
   *
   * import
   *
   */
  public function import( $ID = null ) {
    
    $output = array("status" => "error", "message" => "block-create-error-1" );
 
    $DIR = $this->SETTINGS['dir'] . '/' . $ID;
    
    if ( ! is_dir( $DIR ) ) wp_mkdir_p( $DIR );

    if ( is_dir( $DIR ) ) {

      LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'template.php', '', 'pull' );
      LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'style.css', '', 'pull' );
      LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'script.js', '', 'pull' );
      
      //LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'style.map', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'functions.php', '', 'pull' );

      LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'blocks/' . $ID . '/admin', 'template.php', '', 'pull' );
      LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'blocks/' . $ID . '/admin', 'style.css', '', 'pull' );
      LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'blocks/' . $ID . '/admin', 'script.js', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'preview.png', '', 'pull' );
      
      LINOTYPE_file::update_file( $DIR, $ID, 'blocks/' . $ID, 'options.json', '', 'pull' );

      LINOTYPE_file::update_config( $DIR, $ID, 'blocks/' . $ID, 'config.json', '', 'pull' );
      
      $this->ITEMS[$ID] = $this->get_config( $ID );
      LINOTYPE::$SYNC->fetch( 'blocks', $this->ITEMS, $ID );

      $output = array("status" => "success", "id" => $ID, "message" => false );

    } else {

      $output = array("status" => "error", "message" => "block-create-error-3" );
        
    }

    return $output;

  }


  /*
   *
   * delete
   *
   */
  public function delete( $ID, $SYNC ) {
    
    $output = array("status" => "error", "message" => "block-delete-error-1" );
 
    if ( $ID ) {
      
      $dir = $this->SETTINGS['dir'] . '/' . $ID;
      
      if ( is_dir( $dir ) ) {

        if ( $SYNC == 'push' ) LINOTYPE::$SYNC->delete( 'blocks/' . $ID );

        LINOTYPE_helpers::rrmdir( $dir );
        
        $output = array("status" => "success", "id" => $ID, "message" => "delete" );

      } else {

        $output = array("status" => "error", "message" => "block-delete-error-3" );
          
      }
      
    } else {

      $output = array("status" => "error", "message" => "block-delete-error-2" );

    }

    return $output;

  }



  public function fetch_config( $DIR, $ID, $PATH, $FILE, $DATA, $SYNC ) {

    $exist = LINOTYPE::$SYNC->exist( $PATH . '/' . $FILE ); 
    if ( $exist ) LINOTYPE::$SYNC->fetch( 'blocks', $this->ITEMS, $ID );

  }

  
  /*
   *
   * get
   *
   */
  public function get( $params = null ) {
    
    if ( $params && is_array( $params ) ) {

      $ids = array();

      foreach( $params as $id ){

        if ( isset( $this->ITEMS[$id] ) ) $ids[$id] = $this->ITEMS[$id];

      }

      return $ids;

    } else if ( $params ) {
      
      if ( isset( $this->ITEMS[$params] ) ) {

        return $this->ITEMS[$params];
      
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
    
    $this->LIBRARIES = $libraries;

    $composer_elements_options = LINOTYPE::$FIELDS->get();

    if ( $this->ITEMS ) {
      foreach ( $this->ITEMS as $ITEM_key => $ITEM ) {

        if ( $ITEM['type'] == 'module' ) {

          $options = array();
          if ( isset( $ITEM['options'] ) && $ITEM['options'] ) {
            foreach ( $ITEM['options'] as $field_key => $field ) {

                $options_field = array();

                if ( isset( $field['options']['options'] ) && $field['options']['options'] ) {
                  $options_field = $field['options']['options'];
                }

                $options[ $field_key ] = array_merge( array(
                  "title"=>'',
                  "info"=>'',
                  "type"=> $field['type'],
                  "options" => $options_field,
                  "default"=>"",
                  "fullwidth" => true,
                  "help" => false,
                  "col" => "",
                  "disabled" => "",
                  "desc" => "",
                  "padding" => "20px 20px 0px 20px",
                  "tab" => "General"
                ), $field['options'] );

          
            }
          }

        } else {
            
          //create custom element options field
          $options = array();
          if ( isset( $ITEM['options'] ) && $ITEM['options'] ) {
            foreach ( $ITEM['options'] as $field_key => $field ) {

              if ( isset( $field['options']['id'] ) && $field['options']['id'] ) {

                $options_field = array();

                if ( isset( $field['options']['options'] ) && $field['options']['options'] ) {
                  $options_field = $field['options']['options'];
                }

                $options[ $field['options']['id'] ] = array_merge( array(
                  "title"=>'',
                  "info"=>'',
                  "type"=> $field['type'],
                  "options" => $options_field,
                  "default"=>"",
                  "fullwidth" => true,
                  "help" => false,
                  "col" => "",
                  "disabled" => "",
                  "desc" => "",
                  "padding" => "20px 20px 0px 20px",
                  "tab" => "General"
                ), $field['options'] );

              }

            }
          }

          if ( isset( $_REQUEST['composer_overwrite'] ) && $_REQUEST['composer_overwrite'] == "true"  ) $options = $this->add_block_wrapper_options( $options );

        }

        if ( isset( $_REQUEST['composer_overwrite'] ) && $_REQUEST['composer_overwrite'] == "true"  ) $options = $this->add_block_overwrite_options( $options );

        //get editor element
        $editor_callback = null;
        
        if ( isset( $ITEM['dir'] ) && file_exists( $ITEM['dir'] . '/admin/template.php' ) ){

          $editor_callback = function( $item, $type, $title, $icon, $options, $contents, $elements, $editor, $preview ) {
            
            if ( file_exists( $elements[ $item['type'] ]['dir'] . '/admin/style.css' ) ) {
                wp_enqueue_style( 'composer-' . $item['type'] . '-admin', $elements[ $item['type'] ]['url'] . '/admin/style.css', false, false, 'screen' );
                
              }

            if ( file_exists( $elements[ $item['type'] ]['dir'] . '/admin/script.js' ) ) {
                wp_enqueue_script('composer-' . $item['type'] . '-admin', $elements[ $item['type'] ]['url'] . '/admin/script.js', array('jquery'), '1.0', true );
            }

            if ( file_exists( $elements[ $item['type'] ]['dir'] . '/admin/template.php' ) ) {

              $settings = array(
                "type" => $type,
                "title" => $title,
                "icon" => $icon,
                "options" => $options,
                "contents" => $contents,
                "item" => $item,
                "elements" => $elements,
                "editor" => $editor
              );

              //_HANDYLOG($item['type'], $elements[ $item['type'] ]['options'] );

              include $elements[ $item['type'] ]['dir'] . '/admin/template.php';
            }

          };

        }

        //get frontend element
        $frontend_callback = null;

        $frontend_callback = function( $item, $type, $title, $icon, $options, $contents, $elements, $editor, $preview ) {
        
            // if ( LINOTYPE::$SETTINGS['cache']['enable'] == false ) {

              if ( isset( $elements[ $item['type'] ]['libraries'] ) && $elements[ $item['type'] ]['libraries'] ){
                foreach( $elements[ $item['type'] ]['libraries'] as $library_key => $library ) {

                  if( isset( $this->LIBRARIES[ $library ]['styles'] ) && $this->LIBRARIES[ $library ]['styles'] ){
                    foreach( $this->LIBRARIES[ $library ]['styles'] as $style_key => $style ) {
                      
                      $style = array_merge( array(
                        'full' => '',
                        'id' => '',
                        'dependencies' => array(),
                        'media' => '',
                      ), $style );

                      $style['full'] = $this->LIBRARIES[ $library ]['url'] . $style['full'];

                      if ( LINOTYPE::$SETTINGS['cache']['minify_css'] == false ) {
                        
                        //libraries : add styles
                        //wp_enqueue_style( 'LINOTYPE-' . $style['id'], $style['full'], $style['dependencies'], time(), $style['media'] );
                        LINOTYPE::$BLOCKS_STYLES[ 'LINOTYPE-' . $style['id'] ] = array( 'LINOTYPE-' . $style['id'], $style['full'], $style['dependencies'], time(), $style['media'] );

                      }

                    }
                  }

                  if( isset( $this->LIBRARIES[ $library ]['scripts'] ) && $this->LIBRARIES[ $library ]['scripts'] ){
                    foreach( $this->LIBRARIES[ $library ]['scripts'] as $script_key => $script ) {
                      
                      $script = array_merge( array(
                        'full' => '',
                        'id' => '',
                        'dependencies' => array(),
                        'footer' => '',
                      ), $script );

                      $script['full'] = $this->LIBRARIES[ $library ]['url'] . $script['full'];

                      if ( LINOTYPE::$SETTINGS['cache']['minify_js'] == false ) {

                        //libraries : add scripts
                        //wp_enqueue_script( 'LINOTYPE-' . $script['id'], $script['full'], $script['dependencies'], time(), $script['footer'] );
                        LINOTYPE::$BLOCKS_SCRIPTS[ 'LINOTYPE-' . $script['id'] ] = array( 'LINOTYPE-' . $script['id'], $script['full'], $script['dependencies'], time(), $script['footer'] );

                      }

                    }
                  }

                }
              }

            // }
            
            if ( LINOTYPE::$SETTINGS['cache']['minify_css'] == false && file_exists( $elements[ $item['type'] ]['dir'] . '/style.css' ) ) {
              
              if ( file_exists( $elements[ $item['type'] ]['dir'] . '/style.css' ) ) {

                //block : add style
                //wp_enqueue_style( 'LINOTYPE-' . $item['type'], $elements[ $item['type'] ]['url'] . '/style.css', false, time(), 'screen' );
                LINOTYPE::$BLOCKS_STYLES[ 'LINOTYPE-' . $item['type'] ] = array( 'LINOTYPE-' . $item['type'], $elements[ $item['type'] ]['url'] . '/style.css', false, time(), 'screen' );

              }

            }

            if ( LINOTYPE::$SETTINGS['cache']['minify_js'] == false && file_exists( $elements[ $item['type'] ]['dir'] . '/script.js' ) ) {
              
              if ( file_exists( $elements[ $item['type'] ]['dir'] . '/script.js' ) ) {

                //block : add script
                //wp_enqueue_script('LINOTYPE-' . $item['type'], $elements[ $item['type'] ]['url'] . '/script.js', array('jquery'), time(), true );
                LINOTYPE::$BLOCKS_SCRIPTS[ 'LINOTYPE-' . $item['type'] ] = array( 'LINOTYPE-' . $item['type'], $elements[ $item['type'] ]['url'] . '/script.js', array('jquery'), time(), true );

              }

            }

            //overwrite option if enable

            $item['editable'] = false;

            if ( isset( $options['_overwrite'] ) ) {

              $object_id = get_the_ID();
              
              if ( $options['_overwrite'] ) {
                foreach ( $options['_overwrite'] as $option_key => $option ) {
                  
                  if ( ! isset( $option['meta_id'] ) || ! $option['meta_id'] && $option['id'] !== '_composer_contents' ) $option['meta_id'] = $option['id'];

                  $pre_meta_id = '_overwrite_';
                  if ( isset( $option['meta_id_strict'] ) && $option['meta_id_strict'] === 'yes' ) $pre_meta_id = '';

                  if ( isset( $option['overwrite_target'] ) ) {
                      
                    switch ( $option['overwrite_target'] ) {

                      case "both":

                        $new_value = linoption( $pre_meta_id . $option['meta_id'] );
                        $new_value_check_post = get_post_meta( $object_id, $pre_meta_id . $option['meta_id'], true  );
                        if ( $new_value_check_post ) $new_value = $new_value_check_post;

                      break;

                      case "meta":
                        
                        $new_value = get_post_meta( $object_id, $pre_meta_id . $option['meta_id'], true  );

                        if ( ! $new_value ) {
                          
                          //if current page is term, get term meta
                          $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                          if ( $term ) $new_value = get_term_meta( $term->term_id, $option['meta_id'], true );
                        
                        }
                                                
                      break;

                      case "post_title":

                        $new_value = get_the_title( $object_id );

                      break;

                      case "post_content":
                        
                        if ( have_posts() ) {
                          while ( have_posts() ) {

                            the_post(); 
                            $new_value =  get_the_content();
                          
                          }
                        }

                      break;

                      case "post_link":

                        $new_value = get_the_permalink( $object_id );

                      break;

                      case "post_image":

                        $new_value = get_post_thumbnail_id( $object_id );

                      break;

                      case "current_term":

                        $new_value = get_queried_object_id();

                      break;

                      case "object_id":

                        $new_value = get_queried_object_id();

                      break;

                      default:

                        $new_value = linoption( $pre_meta_id . $option['meta_id'] );

                      break;

                    }

                  } else {

                    $new_value = linoption( $pre_meta_id . $option['meta_id'] );

                  }


                  if ( $new_value && LINOTYPE_helpers::is_json( $new_value ) ) $new_value = json_decode( $new_value, true );

                  if ( $new_value !== "" ) {
                    
                    $options[ $option['id'] ] = $new_value;
                    
                  }

                  if ( $option['id'] == '_composer_contents') {

                    $contents = $new_value;
                      
                  }

                  $item['editable'] = true;

                }
              }

            }

            //add blocks option to js
            LINOTYPE::$BLOCKS_OPTIONS_JS[ 'block_' . $item['id'] ] = array(
              'type' => $item['type'],
              'options' => $options,
            );
            
            global $COMPOSER_element;

            $settings = array(
              "type" => $type,
              "title" => $title,
              "icon" => $icon,
              "options" => $options,
              "contents" => $contents,
              "item" => $item,
              "elements" => $elements,
              "editor" => $editor,
              "preview" => $preview,
            );

            $COMPOSER_element = array( 'item' => $item, 'options' => $options, 'contents' => $contents, 'elements' => $elements, 'editor' => $editor );

            if ( file_exists( $elements[ $item['type'] ]['dir'] . '/template.php' ) ) {
              
              ob_start();

                include $elements[ $item['type'] ]['dir'] . '/template.php';
                
                $template_content = ob_get_contents();

              ob_end_clean();

              $item_id = isset( $item['id'] ) ? $item['id'] : "";
              $item_type = isset( $item['type'] ) ? $item['type'] : "";

              $template_content = str_replace( array(
                "{{linotype:item:id}}",
                "{{linotype:item:class}}",
              ), array(
                $item_id,
                $item_type,
              ), $template_content );

              echo $template_content;

            } else {
              
              $module_data = LINOTYPE::$MODULES->get( $item['type'] );
              
              LINOTYPE_composer::render( $module_data['module'], $elements, false, $settings['preview'] );

            }

          
          
        };

        $this->ITEMS[ $ITEM_key ]['infos'] = '';
        $this->ITEMS[ $ITEM_key ]['background'] = '';
        
        $this->ITEMS[ $ITEM_key ]['editor'] = $editor_callback;
        $this->ITEMS[ $ITEM_key ]['render'] = $frontend_callback;
        $this->ITEMS[ $ITEM_key ]['options'] = $options;

      }
    }

  }

  public function add_block_wrapper_options( $options ){

    $options['_block_custom_class'] = array(
      "title"=>'Custom class',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-12",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_width'] = array(
      "title"=>'Width',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-6",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_height'] = array(
      "title"=>'Height',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-6",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_display'] = array(
      "title"=>'Display',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-6",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_position'] = array(
      "title"=>'Position',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-6",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_index'] = array(
      "title"=>'Index',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-12",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_opacity'] = array(
      "title"=>'Opacity',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-12",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_margin'] = array(
      "title"=>'Margin',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-6",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );
    $options['_block_default_padding'] = array(
      "title"=>'Padding',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-6",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_font_size'] = array(
      "title"=>'Font Size',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-3",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_line_height'] = array(
      "title"=>'Line height',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-3",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_font_family'] = array(
      "title"=>'Font Family',
      "info"=>'',
      "type"=> 'linotype_field_googlefonts',
      "options" => array(),
      "default"=>"",
      "col" => "col-3",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_font_weight'] = array(
      "title"=>'Font Weight',
      "info"=>'',
      "type"=> 'linotype_field_text',
      "options" => array(),
      "default"=>"",
      "col" => "col-3",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_bg_color'] = array(
      "title"=>'Background Color',
      "info"=>'',
      "type"=> 'linotype_field_color',
      "options" => array(),
      "default"=>"",
      "col" => "col-6",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_color'] = array(
      "title"=>'Font Color',
      "info"=>'',
      "type"=> 'linotype_field_color',
      "options" => array(),
      "default"=>"",
      "col" => "col-6",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    $options['_block_default_hide'] = array(
      "title"=>'Hide if',
      "info"=>'',
      "type"=> 'linotype_field_select',
      "options" => array(
        "data" => array(
          array( "title" => "Hidden on all",  "value" => "d-none" ),
          array( "title" => "Visible on all", "value" => "d-block" ), 
          array( "title" => "Hidden on xs",   "value" => "d-xs-none" ), 
          array( "title" => "Hidden on sm",   "value" => "d-sm-none" ), 
          array( "title" => "Hidden on md",   "value" => "d-md-none" ), 
          array( "title" => "Hidden on lg",   "value" => "d-lg-none" ), 
          array( "title" => "Hidden on xl",   "value" => "d-xl-none" ), 
          array( "title" => "Visible on xs",  "value" => "d-xs-block" ), 
          array( "title" => "Visible on sm",  "value" => "d-sm-block" ), 
          array( "title" => "Visible on md",  "value" => "d-md-block" ), 
          array( "title" => "Visible on lg",  "value" => "d-lg-block" ), 
          array( "title" => "Visible on xl",  "value" => "d-xl-block" ),
        )
      ),
      "default"=>"",
      "col" => "col-12",
      "desc" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "Defaults"
    );

    // $options['_block_custom_css'] = array(
    //   "title"=>'Custom Css',
    //   "info"=>'',
    //   "type"=> 'linotype_field_textarea',
    //   "options" => array(),
    //   "default"=>"",
    //   "col" => "col-12",
    //   "desc" => "",
    //   "disabled" => "",
    //   "fullwidth" => true,
    //   "help" => false,
    //   "padding" => "20px 20px 0px 20px",
    //   "tab" => "Custom"
    // );

     return $options;

  }

  public function add_block_overwrite_options( $options ) {

    //$all_options = implode( '<br/>', array_keys( $options ) );
    
    $options_select = array();
    if ( isset( $options ) && $options ) {
      foreach ( $options as $option_id => $option ) {
        $options_select[] =  array( 'value' => $option_id, 'title' => $option_id . ' => ' . $option['title'] );
      }
    }

    $options_select[] =  array( 'value' => '_composer_contents', 'title' => "contents" );

    $field_select = array( array( 'title' => "origin", 'value' => "" ) );

    foreach( LINOTYPE::$FIELDS->get() as $FIELD_key => $FIELD ){
      
     array_push( $field_select, array( 'title' => $FIELD['title'], 'value' => $FIELD_key ) );

    }
    
    $options[ '_overwrite' ] = array(
      "title"=>'',
      "info"=>'',
      "type"=> 'linotype_field_data',
      "options" => array(
        "collapsed" => true,
        'data'=> array(
          
          array(
            'id' => 'id',
            'title' => 'Overide option: ',
            'desc' => '',
            'type' => 'select',
            'options' => $options_select,
            'width' => '24%',
          ),
          array(
            'id' => 'overwrite_target',
            'title' => 'Overide by: ',
            'desc' => '',
            'type' => 'select',
            'options' => function() {

              $overwrite_target = array(
                array('value' => 'meta', 'title' => 'Meta' ),
                array('value' => 'post_title', 'title' => 'Post title' ),
                array('value' => 'post_content', 'title' => 'Post content' ),
                array('value' => 'post_link', 'title' => 'Post link' ),
                array('value' => 'post_image', 'title' => 'Post image' ),
                array('value' => 'option', 'title' => 'Option' ),
                array('value' => 'both', 'title' => 'Meta & Options' ),
                array('value' => 'current_term', 'title' => 'Current Term' ),
                array('value' => 'object_id', 'title' => 'Object ID' ),
                array('value' => 'template', 'title' => 'Template' ),
              );
              // foreach ( get_post_types( '', 'names' ) as $post_type ) {
              //   array_push( $overwrite_target, array( 'value' => 'meta-' . $post_type, 'title' => 'Meta (' . $post_type . ')' ) );
              // }
               
              return $overwrite_target;

            },
            'width' => '24%',
          ),
          array(
            'id' => 'meta_id_strict',
            'title' => 'Strict id',
            'options' => array(
              array('value' => '', 'title' => '_overide_' ),
              array('value' => 'yes', 'title' => 'yes' ),
            ),
            'desc' => '',
            'type' => 'select',
            'width' => '24%',
          ),
          array(
            'id' => 'meta_id',
            'title' => 'Meta ID: ',
            'desc' => '',
            'type' => 'text',
            'width' => '24%',
          ),
          
          array(
            'id' => 'type',
            'title' => 'Meta Type: ',
            'desc' => '',
            'type' => 'select',
            'options' => $field_select,
          ),
          array(
            'id' => 'options',
            'title' => 'Meta Option: ',
            'desc' => '',
            'type' => 'json',
          ),
          array(
            'id' => 'title',
            'title' => 'Meta Title: ',
            'desc' => '',
            'type' => 'text',
          ),
          array(
            'id' => 'desc',
            'title' => 'Meta Desc',
            'desc' => '',
            'type' => 'text',
          ),
          array(
            'id' => 'col',
            'title' => 'Column',
            'desc' => '',
            'type' => 'select',
            'options' => array(
              array('value' => 'col-12', 'title' => 'Col 12' ),
              array('value' => 'col-11', 'title' => 'Col 11' ),
              array('value' => 'col-10', 'title' => 'Col 10' ),
              array('value' => 'col-9', 'title' => 'Col 9' ),
              array('value' => 'col-8', 'title' => 'Col 8' ),
              array('value' => 'col-7', 'title' => 'Col 7' ),
              array('value' => 'col-6', 'title' => 'Col 6' ),
              array('value' => 'col-5', 'title' => 'Col 5' ),
              array('value' => 'col-4', 'title' => 'Col 4' ),
              array('value' => 'col-3', 'title' => 'Col 3' ),
              array('value' => 'col-2', 'title' => 'Col 2' ),
              array('value' => 'col-1', 'title' => 'Col 1' ),
            )
          ),
          array(
            'id' => 'padding',
            'title' => 'Padding',
            'desc' => '',
            'type' => 'text',
          ),
          array(
            'id' => 'tab',
            'title' => 'Tab',
            'desc' => '',
            'type' => 'text',
          ),
          array(
            'id' => 'group',
            'title' => 'Group',
            'desc' => '',
            'type' => 'text',
          ),
          // array(
          //   'id' => 'pos',
          //   'title' => 'Position',
          //   'desc' => '',
          //   'type' => 'text',
          // )
        ),
        'height'=>'500px'
      ),
      "default"=>"",
      "col" => "",
      "disabled" => "",
      "fullwidth" => true,
      "help" => false,
      "padding" => "0px;margin-top:-1px",
      "tab" => "Overwrite"
    );

    return $options;

  }

}

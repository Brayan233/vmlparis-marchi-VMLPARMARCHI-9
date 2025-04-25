<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 *
 * LINOTYPE_fields
 *
 */
class LINOTYPE_fields {

  public $SETTINGS;

  public $ITEMS;

  static $CONFIG = array(
    "type" => "field",
    "author" => "",
    "version" => "",
    "update" => "",
    "sync" => "",
    "title" => "",
    "desc" => "",
    "icon" => "dashicons dashicons-edit",
    "color" => "",
    "category" => "",
    "tags" => array(),
    "target" => array(),
    "parent" => array(),
    "accept" => array(),
    "libraries" => array(),
    "field_id" => "",
    "field_default" => "",
    "field_dummy" => "",
    "field_title" => "",
    "field_info" => "",
    "field_desc" => "",
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
    
    //get fields
    $this->ITEMS = $this->init();

  }

  /**
  *
  * init
  *
  **/
  public function init() {
    
    $CONFIG = array();

    LINOTYPE::$SYNC->init( 'fields', $this->SETTINGS['dir'] . '/sync.json' );

    $defaults_dir_list = handypress_helper::getFileList( LINOTYPE::$SETTINGS['plugin_dir'] . 'defaults/fields' );
    
    if ( $defaults_dir_list ){
      foreach ( $defaults_dir_list as $data ) {

        if ( $data['type'] == 'dir' ) {
          $config = $this->init_config( $data, 'default' );
          if ( $config ) $CONFIG[ $data['name'] ] = $config;
        }

      }
    }

    $customs_dir_list = handypress_helper::getFileList( $this->SETTINGS['dir'] );

    if ( $customs_dir_list ){
      foreach ( $customs_dir_list as $data ) {

        if ( $data['type'] == 'dir' ) {
          $config = $this->init_config( $data, 'custom' );
          if ( $config ) $CONFIG[ $data['name'] ] = $config;
        }

      }
    }

    uasort( $CONFIG, function( $a, $b ) {
    
      return  strcmp($a["title"], $b["title"]);
    
    });

    return $CONFIG;

  }

  /*
   *
   * set_config
   *
   */
  public function init_config( $DATA, $SOURCE = 'custom' ) {

    $CONFIG = self::$CONFIG;

    $ID = $DATA['name'];

    $DIR = $DATA['path'];
    $URL = str_replace( LINOTYPE::$SETTINGS['base_dir'], LINOTYPE::$SETTINGS['base_url'], $DATA['path'] );

    if ( file_exists( $DIR . '/config.json' ) ) {
      
      $CONFIG['source'] = $SOURCE;
      $CONFIG['id'] = $ID;
      $CONFIG['hash'] = hash('crc32', $ID );
      $CONFIG['path'] = 'fields/' . $ID;
      $CONFIG['dir'] = $DIR;
      $CONFIG['url'] = $URL;
      $CONFIG['preview'] = $URL . '/preview.png';
      $CONFIG['editor_link'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php?type=field&id=' . $ID;

      $config_data = file_get_contents( $DIR . '/config.json' );
    
      if ( $config_data ) {
        
        $config_data = json_decode( $config_data, true );
        $CONFIG = array_merge( $CONFIG, array_filter( $config_data ) );
      
      }

      if ( file_exists( $DIR . '/options.json' ) ) {

        $options = file_get_contents( $DIR . '/options.json' );
        
        if ( $options ) {
          
          $CONFIG[ 'field_options' ] = json_decode( $options, true );
        
        }
      
      }

      $CONFIG['sync_status'] = LINOTYPE::$SYNC->get_status( 'fields', $ID );

      return $CONFIG;

    } else {

      return false;

    }
    
  }

  /*
   *
   * get_config
   *
   */
  public function get_config( $ID ) {

    if ( isset( $this->ITEMS[ $ID ] ) && $this->ITEMS[ $ID ] ) {

      return $this->ITEMS[ $ID ];
    
    } else {

      return false;

    }

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
          case "target":
          case "parent":
          case "accept":
          case "libraries":
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
    
    if( $DATA['source'] == 'default' ) wp_die("This is a default field, you can't edit.");

    $output = array("status" => "error", "message" => "field-update-error-not-save" );

    if ( $ID && $DATA ) {
      
      if ( isset( $this->ITEMS[$ID] ) ) {

        if ( is_writable( $DATA['dir'] ) ) {

          if ( $SYNC == 'fetch' ) {

            $this->fetch_config( $DATA['dir'], $ID, 'fields/' . $ID, 'config.json', $_POST, $SYNC );

          } else {
          
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'fields/' . $ID, 'template.php', stripslashes( $_POST['code-php'] ), $SYNC );
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'fields/' . $ID, 'style.css', stripslashes( $_POST['code-css'] ), $SYNC );
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'fields/' . $ID, 'script.js', stripslashes( $_POST['code-js'] ), $SYNC );
            
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'fields/' . $ID, 'functions.php', stripslashes( $_POST['code-functions'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'] . '/admin', $ID, 'fields/' . $ID . '/admin', 'template.php', stripslashes( $_POST['admin-php'] ), $SYNC );
            LINOTYPE_file::update_file( $DATA['dir'] . '/admin', $ID, 'fields/' . $ID . '/admin', 'style.css', stripslashes( $_POST['admin-css'] ), $SYNC );
            LINOTYPE_file::update_file( $DATA['dir'] . '/admin', $ID, 'fields/' . $ID . '/admin', 'script.js', stripslashes( $_POST['admin-js'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'fields/' . $ID, 'preview.png', stripslashes( $_POST['screenshot'] ), $SYNC );
            
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'fields/' . $ID, 'options.json', stripslashes( $_POST['field_options'] ), $SYNC );

            LINOTYPE_file::update_config( $DATA['dir'], $ID, 'fields/' . $ID, 'config.json', $this->format_config( $_POST ), $SYNC );
            
            $this->ITEMS[$ID] = $this->get_config( $ID );
            LINOTYPE::$SYNC->fetch( 'fields', $this->ITEMS, $ID );

          }

          $output = array("status" => "success", "message" => false );

        } else {

          $output = array("status" => "error", "message" => "field-update-error-not-writable" );
            
        }
        
      
      } else {

        $output = array("status" => "error", "message" => "field-update-error-not-exist" );

      }

    }

    return $output;

  }
  

  /*
   *
   * update
   *
   */
  public function create( $DATA = null, $SYNC = null ) {
    
    $output = array("status" => "error", "message" => "field-create-error-1" );
    
    $ID = $DATA['author'] . '_field_' . $DATA['id'];

    if ( ! isset( $this->ITEMS[ $ID ] ) ) {
      
      $DIR = $this->SETTINGS['dir'] . '/' . $ID;
      
      if ( ! is_dir( $DIR ) && wp_mkdir_p( $DIR ) ) {
        
        if ( isset( $DATA['duplicate'] ) && $DATA['duplicate'] ) {

          $DUPLICATE_PATH = explode( '/', $DATA['duplicate'] );
          $DUPLICATE_DIR = $this->SETTINGS['dir'] . '/' .$DUPLICATE_PATH[1];

          if ( file_exists( $DUPLICATE_DIR .'/template.php' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'template.php', file_get_contents( $DUPLICATE_DIR .'/template.php' ), '' );
          if ( file_exists( $DUPLICATE_DIR .'/style.css' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'style.css', file_get_contents( $DUPLICATE_DIR .'/style.css' ), '' );
          if ( file_exists( $DUPLICATE_DIR .'/script.js' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'script.js', file_get_contents( $DUPLICATE_DIR .'/script.js' ), '' );
          
          if ( file_exists( $DUPLICATE_DIR .'/functions.php' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'functions.php', file_get_contents( $DUPLICATE_DIR .'/functions.php' ), '' );
          
          if ( file_exists( $DUPLICATE_DIR .'/admin/template.php' ) ) LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'fields/' . $ID . '/admin', 'template.php', file_get_contents( $DUPLICATE_DIR .'/admin/template.php' ), '' );
          if ( file_exists( $DUPLICATE_DIR .'/admin/style.css' ) ) LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'fields/' . $ID . '/admin', 'style.css', file_get_contents( $DUPLICATE_DIR .'/admin/style.css' ), '' );
          if ( file_exists( $DUPLICATE_DIR .'/admin/script.js' ) ) LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'fields/' . $ID . '/admin', 'script.js', file_get_contents( $DUPLICATE_DIR .'/admin/script.js' ), '' );

          if ( file_exists( $DUPLICATE_DIR .'/preview.png' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'preview.png', file_get_contents( $DUPLICATE_DIR .'/preview.png' ), '' );
          
          if ( file_exists( $DUPLICATE_DIR .'/options.json' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'options.json', file_get_contents( $DUPLICATE_DIR .'/options.json' ), '' );
          
          $DUPLICATE_CONFIG = array();
          $DUPLICATE_CONFIG_FILE = file_get_contents( $DUPLICATE_DIR .'/config.json' );
          if ( $DUPLICATE_CONFIG_FILE ) $DUPLICATE_CONFIG = json_decode( $DUPLICATE_CONFIG_FILE, true );
          
          $CONFIG = $this->format_config( $DUPLICATE_CONFIG );
          $CONFIG[ 'author' ] = $DATA[ 'author' ];
          $CONFIG[ 'title' ] = $DATA[ 'title' ];
          $CONFIG[ 'type' ] = 'field';
          $CONFIG[ 'version' ] = "1.0";

        } else {

          $CONFIG = $this->format_config( $DATA );
          $CONFIG[ 'type' ] = 'field';
          $CONFIG[ 'version' ] = "1.0";

        }

        LINOTYPE_file::update_config( $$DIR, $ID, 'fields/' . $ID, 'config.json', $CONFIG, $SYNC );

        $this->ITEMS[$ID] = $this->get_config( $ID );
        LINOTYPE::$SYNC->fetch( 'fields', $this->ITEMS, $ID );

        $output = array("status" => "success", "id" => $ID, "message" => false );

      } else {

        $output = array("status" => "error", "message" => "field-create-error-3" );
          
      }
      
    
    } else {

      $output = array("status" => "error", "message" => "field-create-error-2" );

    }

    return $output;

  }

  /*
   *
   * import
   *
   */
  public function import( $ID = null ) {
    
    $output = array("status" => "error", "message" => "field-create-error-1" );
 
    $DIR = $this->SETTINGS['dir'] . '/' . $ID;
    
    if ( ! is_dir( $DIR ) ) wp_mkdir_p( $DIR );

    if ( is_dir( $DIR ) ) {

      LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'template.php', '', 'pull' );
      LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'style.css', '', 'pull' );
      LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'script.js', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'functions.php', '', 'pull' );
      
      LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'fields/' . $ID . '/admin', 'template.php', '', 'pull' );
      LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'fields/' . $ID . '/admin', 'style.css', '', 'pull' );
      LINOTYPE_file::update_file( $DIR . '/admin', $ID, 'fields/' . $ID . '/admin', 'script.js', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'preview.png', '', 'pull' );
      
      LINOTYPE_file::update_file( $DIR, $ID, 'fields/' . $ID, 'options.json', '', 'pull' );

      LINOTYPE_file::update_config( $DIR, $ID, 'fields/' . $ID, 'config.json', '', 'pull' );
      
      $this->ITEMS[$ID] = $this->get_config( $ID );
      LINOTYPE::$SYNC->fetch( 'fields', $this->ITEMS, $ID );

      $output = array("status" => "success", "id" => $ID, "message" => false );

    } else {

      $output = array("status" => "error", "message" => "field-create-error-3" );
        
    }

    return $output;

  }
  
  /*
   *
   * delete
   *
   */
  public function delete( $ID, $SYNC ) {
    
    $output = array("status" => "error", "message" => "field-delete-error-1" );
 
    if ( $ID ) {
      
      $dir = $this->SETTINGS['dir'] . '/' . $ID;
      
      if ( is_dir( $dir ) ) {

        if ( $SYNC == 'push' ) LINOTYPE::$SYNC->delete( 'fields/' . $ID );

        LINOTYPE_helpers::rrmdir( $dir );
        
        $output = array("status" => "success", "id" => $ID, "message" => "delete" );

      } else {

        $output = array("status" => "error", "message" => "field-delete-error-3" );
          
      }
      
    } else {

      $output = array("status" => "error", "message" => "field-delete-error-2" );

    }

    return $output;

  }



  public function fetch_config( $DIR, $ID, $PATH, $FILE, $DATA, $SYNC ) {

    $exist = LINOTYPE::$SYNC->exist( $PATH . '/' . $FILE ); 
    if ( $exist ) LINOTYPE::$SYNC->fetch( 'fields', $this->ITEMS, $ID );

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
  * display
  *
  **/
  public function display( $id, $field ) {

    if( isset( $this->ITEMS[ $id ]['dir'] ) && file_exists(  $this->ITEMS[ $id ]['dir'] . '/template.php' ) ) {

      include $this->ITEMS[ $id ]['dir'] . '/template.php';

    }

  }

  /**
  *
  * preview
  *
  **/
  public function preview( $id, $field ) {

    if( isset( $this->ITEMS[ $id ]['dir'] ) && file_exists(  $this->ITEMS[ $id ]['dir'] . '/preview.php' ) ) {

      include $this->ITEMS[ $id ]['dir'] . '/preview.php';

    } else {

      if ( isset( $field['value'] ) ) echo $field['value'];

    }

  }

  public function load( $libraries ) {
    
    $this->LIBRARIES = $libraries;

    $composer_elements_options = LINOTYPE_helpers::get_element_options();
    
    if ( $this->ITEMS ) {
      foreach ( $this->ITEMS as $ITEM_key => $ITEM ) {

        //get editor element
        $editor_callback = null;
        
        if ( file_exists( $ITEM['dir'] . '/admin/template.php' ) ){

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

        } else {

          $editor_callback = function( $item, $type, $title, $icon, $options, $contents, $elements, $editor, $preview ) {
          
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

            block_composer('header', $settings );

              //echo "ID:" . $options['id'];

            block_composer('footer', $settings );
            

          };

        }
        
        // //get frontend element
        $frontend_callback = null;

        if ( file_exists( $ITEM['dir'] . '/template.php' ) ){

          $frontend_callback = function( $item, $type, $title, $icon, $options, $contents, $elements, $editor, $preview ) {
          
            // _HANDYLOG('options', array( $item, $type, $title, $icon, $options, $contents, $elements, $editor, $preview ) );

            // if ( isset( LINOTYPE::$SETTINGS['cache']['enable'] ) && LINOTYPE::$SETTINGS['cache']['enable'] == false ) {

              if ( isset( $elements[ $item['type'] ]['libraries'] ) && $elements[ $item['type'] ]['libraries'] ){
                foreach( $elements[ $item['type'] ]['libraries'] as $library_key => $library ) {

                  if( isset( $this->LIBRARIES[ $library ]['styles'] ) && $this->LIBRARIES[ $library ]['styles'] ){
                    foreach( $this->LIBRARIES[ $library ]['styles'] as $style_key => $style ) {
      
                      wp_enqueue_style( $style['id'], $style['full'], $style['dependencies'], $style['version'], $style['media'] );
                      
                    }
                  }

                  if( isset( $this->LIBRARIES[ $library ]['scripts'] ) && $this->LIBRARIES[ $library ]['scripts'] ){
                    foreach( $this->LIBRARIES[ $library ]['scripts'] as $script_key => $script ) {
      
                      wp_enqueue_script( $script['id'], $script['full'], $script['dependencies'], $script['version'], $script['footer'] );
                    
                    }
                  }

                }
              }

            // }

            if (  file_exists( $elements[ $item['type'] ]['dir'] . '/style.css' ) ) {

              wp_enqueue_style( 'composer-' . $item['type'], $elements[ $item['type'] ]['url'] . '/style.css', false, $elements[ $item['type'] ]['version'], 'screen' );
            
            }

            if (  file_exists( $elements[ $item['type'] ]['dir'] . '/script.js' ) ) {
            
              wp_enqueue_script('composer-' . $item['type'], $elements[ $item['type'] ]['url'] . '/script.js', array('jquery'), $elements[ $item['type'] ]['version'], true );
            
            }

            // if ( file_exists( $elements[ $item['type'] ]['dir'] . '/functions.php' ) ) {
            //     include $elements[ $item['type'] ]['dir'] . '/functions.php';
            // }

            
            //overide option if enable
            if ( isset( $options['overide_by_post_meta'] ) && get_queried_object_id() ) {

              foreach ( $options as $option_key => $option ) {

                if ( $option_key != 'overide_by_post_meta' ){

                  $overide_data = get_post_meta( get_queried_object_id(), '_composer_custom_' . $options['overide_by_post_meta'] . '_' . $option_key, true  );

                  if ( $overide_data ){

                    $options[$option_key] = $overide_data;

                  }

                }

              }

            }
            
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

            //_HANDYLOG( $settings );

            $COMPOSER_element = array( 'item' => $item, 'options' => $options, 'contents' => $contents, 'elements' => $elements, 'editor' => $editor );

              $composer_template_autoload = true;

              if ( $composer_template_autoload && file_exists( $elements[ $item['type'] ]['dir'] . '/template.php' ) ) {
                
                // global $COMPOSER_settings;
                // $COMPOSER_settings = $settings;

                include $elements[ $item['type'] ]['dir'] . '/template.php';
              
              }

          };

        }
        
        $options = array();

        $options = $this->add_field_wrapper_options( $options );
        
        $field_options = array();

        if ( isset( $this->ITEMS[ $ITEM_key ]['field_options'] ) && $this->ITEMS[ $ITEM_key ]['field_options'] ) $field_options = json_encode( $this->ITEMS[ $ITEM_key ]['field_options'], JSON_PRETTY_PRINT );

        $options[ 'options' ] = array(
            "title"=> 'Options',
            "desc"=>'',
            'disabled' => false,
            "type"=>'linotype_field_json',
            "info"=>'',
            "options" => array(
              "data" => $field_options,
            ),
            "fullwidth" => true,
            "col" => 'col-12',
            "help" => false,
            "padding" => "20px 20px 0px 20px;",
            "tab" => "Params"

        );

        

        $this->ITEMS[ $ITEM_key ]['infos'] = '';
        $this->ITEMS[ $ITEM_key ]['background'] = '';
        
        $this->ITEMS[ $ITEM_key ]['editor'] = $editor_callback;
        $this->ITEMS[ $ITEM_key ]['render'] = $frontend_callback;
        $this->ITEMS[ $ITEM_key ]['options'] = $options;

      }
    }

  }

  public function add_field_wrapper_options( $options = array() ){


    $options["id"] = array(
        "title"=>'Option ID',
        "desc"=>'Define unique id to get value in your block template with $options[\'myID\']',
        'disabled' => false,
        "type"=>'linotype_field_text',
        "info"=>'',
        "options" => array(
          "placeholder" => "",
        ),
        "default"=>"",
        "fullwidth" => true,
        "col" => 'col-12',
        "help" => false,
        "padding" => "20px 20px 0px 20px;background-color:#F2F2F2;",
        "tab" => "General"
    );

    $options["default"] = array(
      "title"=>'Option default value',
      "info"=>'',
      "desc"=>'Define default value if option empty',
      "col"=>'col-12',
      'disabled' => false,
      "type"=>'linotype_field_text',
      "options" => array(
        "placeholder" => "",
      ),
      "default"=>"",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px;background-color:#F2F2F2;",
      "tab" => "General"
    );

    $options["dummy"] = array(
      "title"=>'Option dummy value',
      "info"=>'',
      "desc"=>'Define dummy value for the preview',
      "col"=>'col-12',
      'disabled' => false,
      "type"=>'linotype_field_textarea',
      "options" => array(
        "style" => "min-height:32px;height:32px;",
      ),
      "default"=>"",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 25px 20px;background-color:#F2F2F2;border-bottom:1px solid #E5E5E5;",
      "tab" => "General"
    );

    $options["title"] = array(
      "title"=>'Field Title',
      "info"=>'',
      "desc"=>'',
      "col"=>'col-12',
      'disabled' => false,
      "type"=>'linotype_field_text',
      "options" => array(
        "placeholder" => "",
      ),
      "default"=>"",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "General"
    );

    $options["info"] = array(
      "title"=>'Field Info',
      "info"=>'',
      "desc"=>'',
      "col"=>'col-12',
      'disabled' => false,
      "type"=>'linotype_field_text',
      "options" => array(
        "placeholder" => "",
      ),
      "default"=>"",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "General"
    );

    $options["desc"] = array(
      "title"=>'Field Description',
      "info"=>'',
      "desc"=>'',
      "col"=>'col-12',
      'disabled' => false,
      "type"=>'linotype_field_textarea',
      "options" => array(
        "placeholder" => "",
      ),
      "default"=>"",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "General"
    );

    $options["padding"] = array(
      "title"=>'Field Padding',
      "info"=>'',
      "desc"=>'',
      "col"=>'col-12',
      'disabled' => false,
      "type"=>'linotype_field_text',
      "options" => array(
        "placeholder" => "20px",
      ),
      "default"=>"",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "General"
    );

    $options["col"] = array(
      "title"=>'Field Column',
      "info"=>'',
      "desc"=>'',
      "col"=>'col-12',
      'disabled' => false,
      "type"=>'linotype_field_text',
      "options" => array(
        "placeholder" => "",
      ),
      "default"=>"",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "General"
    );

    $options["tab"] = array(
      "title"=>'Field Group',
      "info"=>'',
      "desc"=>'',
      "col"=>'col-12',
      'disabled' => false,
      "type"=>'linotype_field_text',
      "options" => array(
        "placeholder" => "",
      ),
      "default"=>"",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 0px 20px",
      "tab" => "General"
    );
    
    // $options['_block_default_margin'] = array(
    //   "title"=>'Margin',
    //   "info"=>'',
    //   "type"=> 'text',
    //   "options" => array(),
    //   "default"=>"",
    //   "col" => "",
    //   "desc" => "",
    //   "disabled" => "",
    //   "fullwidth" => true,
    //   "help" => false,
    //   "padding" => "20px 20px 0px 20px",
    //   "tab" => "Settings"
    // );
    // $options['_block_default_padding'] = array(
    //   "title"=>'Padding',
    //   "info"=>'',
    //   "type"=> 'text',
    //   "options" => array(),
    //   "default"=>"",
    //   "col" => "",
    //   "desc" => "",
    //   "disabled" => "",
    //   "fullwidth" => true,
    //   "help" => false,
    //   "padding" => "20px 20px 0px 20px",
    //   "tab" => "Settings"
    // );

    // $options['_block_default_bg_color'] = array(
    //   "title"=>'Background Color',
    //   "info"=>'',
    //   "type"=> 'color',
    //   "options" => array(),
    //   "default"=>"",
    //   "col" => "",
    //   "desc" => "",
    //   "disabled" => "",
    //   "fullwidth" => true,
    //   "help" => false,
    //   "padding" => "20px 20px 0px 20px",
    //   "tab" => "Settings"
    // );

    // $options['_block_default_bg_img'] = array(
    //   "title"=>'Background Image',
    //   "info"=>'',
    //   "type"=> 'image',
    //   "options" => array('output' => 'id'),
    //   "default"=>"",
    //   "col" => "",
    //   "desc" => "",
    //   "disabled" => "",
    //   "fullwidth" => true,
    //   "help" => false,
    //   "padding" => "20px 20px 0px 20px",
    //   "tab" => "Settings"
    // );

    return $options;

  }

}

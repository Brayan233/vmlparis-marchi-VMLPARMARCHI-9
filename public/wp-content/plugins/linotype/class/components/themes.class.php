<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 *
 * LINOTYPE_themes
 *
 */
class LINOTYPE_themes {

  public $SETTINGS;

  public $ITEMS;

  static $CONFIG = array(
    "type" => "theme",
    "author" => "",
    "version" => "",
    "update" => "",
    "commit" => "",
    "sync" => "",
    "title" => "",
    "icon" => "dashicons dashicons-admin-appearance",
    "uri" => "",
    "author_uri" => "",
    "desc" => "",
    "color" => "",
    "category" => "",
    "tags" => array(),
    "target" => array(),
    "map" => array(),
    "customposts" => array(),
    "globals" => array(),
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
    
    //get themes
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
    
    if ( $data_dir_list == null ) {

      wp_mkdir_p( $this->SETTINGS['dir'] . '/linotype_theme_starter' );
      handypress_helper::copy_dir( LINOTYPE::$SETTINGS['plugin_dir'] . 'defaults/themes/linotype_theme_starter', $this->SETTINGS['dir'] . '/linotype_theme_starter' );

      $data_dir_list = handypress_helper::getFileList( $this->SETTINGS['dir'] );

    }
    
    LINOTYPE::$SYNC->init( 'themes', $this->SETTINGS['dir'] . '/sync.json' );

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
      $CONFIG['path'] = 'themes/' . $ID;
      $CONFIG['dir'] = $DIR;
      $CONFIG['url'] = $URL;
      $CONFIG['preview'] = $URL . '/preview.png';
      $CONFIG['editor_link'] = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php?type=theme&id=' . $ID;

      $config_data = file_get_contents( $DIR . '/config.json' );
    
      if ( $config_data ) {
        
        $config_data = json_decode( $config_data, true );
        if ( $config_data ) $CONFIG = array_merge( $CONFIG, array_filter( $config_data ) );
        
      }

    }

    $CONFIG[ 'prod' ] = filter_var( get_option( 'linotype_theme_production', false ), FILTER_VALIDATE_BOOLEAN );
    
    if ( file_exists( $DIR . '/map.json' ) ) {

      $map = file_get_contents( $DIR . '/map.json' );
      
      if ( $map ) {
        
        $CONFIG[ 'map' ] = json_decode( $map, true );
      
      }
    
    }

    if ( file_exists( $DIR . '/customposts.json' ) ) {

      $customposts = file_get_contents( $DIR . '/customposts.json' );
      
      if ( $customposts ) {
        
        $CONFIG[ 'customposts' ] = json_decode( $customposts, true );
      
      }
    
    }

    if ( file_exists( $DIR . '/globals.json' ) ) {

      $globals = file_get_contents( $DIR . '/globals.json' );
      
      if ( $globals ) {
        
        $CONFIG[ 'globals' ] = json_decode( $globals, true );
      
      }
    
    }

    if ( file_exists( $DIR . '/options.json' ) ) {

      $options_data = file_get_contents( $DIR . '/options.json' );
      
      if ( $options_data ) {
        
        $options_data = json_decode( $options_data, true );
        $CONFIG['config']['options'] = $options_data;
        $CONFIG['options'] = $options_data;
      
      }

    }
    
    // wp_mkdir_p( $DIR . '/assets/js' );
    // wp_mkdir_p( $DIR . '/assets/css' );

    // wp_mkdir_p( $DIR . '/cache/public/desktop' );
    // wp_mkdir_p( $DIR . '/cache/public/mobile' );

    $CONFIG['sync_status'] = LINOTYPE::$SYNC->get_status( 'themes', $ID );
    
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
  public function update( $ID = null, $DATA = null, $SYNC = null  ) {
    
    $output = array("status" => "error", "message" => "theme-update-error-not-save" );

    if ( $ID && $DATA ) {
      
      if ( isset( $this->ITEMS[$ID] ) ) {

        if ( is_writable( $DATA['dir'] ) ) {

          if ( $SYNC == 'fetch' ) {

            $this->fetch_config( $DATA['dir'], $ID, 'themes/' . $ID, 'config.json', $_POST, $SYNC );

          } else {
          
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'themes/' . $ID, 'map.json', stripslashes( $_POST['map'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'themes/' . $ID, 'customposts.json', stripslashes( $_POST['customposts'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'themes/' . $ID, 'globals.json', stripslashes( $_POST['globals'] ), $SYNC );
            
            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'themes/' . $ID, 'header.php', stripslashes( $_POST['code-header'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'themes/' . $ID, 'index.php', '', $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'themes/' . $ID, 'footer.php', stripslashes( $_POST['code-footer'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'themes/' . $ID, 'reset.css', stripslashes( $_POST['code-reset'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'themes/' . $ID, 'style.css', stripslashes( $_POST['code-css'] ), $SYNC );

            LINOTYPE_file::update_file( $DATA['dir'], $ID, 'themes/' . $ID, 'preview.png', stripslashes( $_POST['screenshot'] ), $SYNC );
            
            LINOTYPE_file::update_config( $DATA['dir'], $ID, 'themes/' . $ID, 'config.json', $this->format_config( $_POST ), $SYNC );
            
            $this->ITEMS[$ID] = $this->get_config( $ID );
            LINOTYPE::$SYNC->fetch( 'themes', $this->ITEMS, $ID );

          }

          $output = array("status" => "success", "message" => false );

        } else {

          $output = array("status" => "error", "message" => "theme-update-error-not-writable" );
            
        }
        
      
      } else {

        $output = array("status" => "error", "message" => "theme-update-error-not-exist" );

      }

    }

    return $output;

  }

  public function create( $DATA = null, $SYNC = null ) {
    
    $output = array("status" => "error", "message" => "theme-create-error-1" );
    
    $ID = $DATA['author'] . '_theme_' . $DATA['id'];

    if ( ! isset( $this->ITEMS[ $ID ] ) ) {
      
      $DIR = $this->SETTINGS['dir'] . '/' . $ID;
      
      if ( ! is_dir( $DIR ) && wp_mkdir_p( $DIR ) ) {

        if ( isset( $DATA['duplicate'] ) && $DATA['duplicate'] ) {

          $DUPLICATE_PATH = explode( '/', $DATA['duplicate'] );
          $DUPLICATE_DIR = $this->SETTINGS['dir'] . '/' .$DUPLICATE_PATH[1];

          if ( file_exists( $DUPLICATE_DIR .'/map.json' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'map.json', file_get_contents( $DUPLICATE_DIR .'/map.json' ), '' );

          if ( file_exists( $DUPLICATE_DIR .'/customposts.json' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'customposts.json', file_get_contents( $DUPLICATE_DIR .'/customposts.json' ), '' );

          if ( file_exists( $DUPLICATE_DIR .'/globals.json' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'globals.json', file_get_contents( $DUPLICATE_DIR .'/globals.json' ), '' );
            
          if ( file_exists( $DUPLICATE_DIR .'/header.php' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'header.php', file_get_contents( $DUPLICATE_DIR .'/header.php' ), '' );

          if ( file_exists( $DUPLICATE_DIR .'/index.php' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'index.php', file_get_contents( $DUPLICATE_DIR .'/index.php' ), '' );

          if ( file_exists( $DUPLICATE_DIR .'/footer.php' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'footer.php', file_get_contents( $DUPLICATE_DIR .'/footer.php' ), '' );

          if ( file_exists( $DUPLICATE_DIR .'/preview.png' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'preview.png', file_get_contents( $DUPLICATE_DIR .'/preview.png' ), '' );

          if ( file_exists( $DUPLICATE_DIR .'/reset.css' ) ) LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'reset.css', file_get_contents( $DUPLICATE_DIR .'/reset.css' ), '' );
          
          $DUPLICATE_CONFIG = array();
          $DUPLICATE_CONFIG_FILE = file_get_contents( $DUPLICATE_DIR .'/config.json' );
          if ( $DUPLICATE_CONFIG_FILE ) $DUPLICATE_CONFIG = json_decode( $DUPLICATE_CONFIG_FILE, true );
          
          $CONFIG = $this->format_config( $DUPLICATE_CONFIG );
          $CONFIG[ 'author' ] = $DATA[ 'author' ];
          $CONFIG[ 'title' ] = $DATA[ 'title' ];
          $CONFIG[ 'type' ] = 'theme';
          $CONFIG[ 'version' ] = "1.0";

        } else {

          $CONFIG = $this->format_config( $DATA );
          $CONFIG[ 'type' ] = 'theme';
          $CONFIG[ 'version' ] = "1.0";

        }
        
        $STYLE = "/*" . PHP_EOL;
        $STYLE .= "Theme Name:  " . $CONFIG[ 'title' ] . PHP_EOL;
        $STYLE .= "Theme URI:   " . $CONFIG[ 'uri' ] . PHP_EOL;
        $STYLE .= "Author:      " . $CONFIG[ 'author' ] . PHP_EOL;
        $STYLE .= "Author URI:  " . $CONFIG[ 'author_uri' ] . PHP_EOL;
        $STYLE .= "Description: " . $CONFIG[ 'desc' ] . PHP_EOL;
        $STYLE .= "Version:     " . $CONFIG[ 'version' ] . PHP_EOL;
        $STYLE .= "*/" . PHP_EOL  . PHP_EOL;
        
        LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'style.css', $STYLE, $SYNC );

        LINOTYPE_file::update_config( $DIR, $ID, 'themes/' . $ID, 'config.json', $CONFIG, $SYNC );

        $this->ITEMS[$ID] = $this->get_config( $ID );
        LINOTYPE::$SYNC->fetch( 'themes', $this->ITEMS, $ID );

        $output = array("status" => "success", "id" => $ID, "message" => false );

      } else {

        $output = array("status" => "error", "message" => "theme-create-error-3" );
          
      }
      
    
    } else {

      $output = array("status" => "error", "message" => "theme-create-error-2" );

    }

    return $output;

  }
  
  /*
   *
   * import
   *
   */
  public function import( $ID = null ) {
    
    $output = array("status" => "error", "message" => "theme-create-error-1" );
 
    $DIR = $this->SETTINGS['dir'] . '/' . $ID;
    
    if ( ! is_dir( $DIR ) ) wp_mkdir_p( $DIR );

    if ( is_dir( $DIR ) ) {

      LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'map.json', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'customposts.json', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'globals.json', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'header.php', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'index.php', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'footer.php', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'reset.css', '', 'pull' );

      LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'style.css', '', 'pull' );
      
      LINOTYPE_file::update_file( $DIR, $ID, 'themes/' . $ID, 'preview.png', '', 'pull' );
      
      LINOTYPE_file::update_config( $DIR, $ID, 'themes/' . $ID, 'config.json', '', 'pull' );
      
      $this->ITEMS[$ID] = $this->get_config( $ID );
      LINOTYPE::$SYNC->fetch( 'themes', $this->ITEMS, $ID );

      $output = array("status" => "success", "id" => $ID, "message" => false );

    } else {

      $output = array("status" => "error", "message" => "theme-create-error-3" );
        
    }

    return $output;

  }
  
  /*
   *
   * delete
   *
   */
  public function delete( $ID, $SYNC ) {
    
    $output = array("status" => "error", "message" => "theme-delete-error-1" );
 
    if ( $ID ) {
      
      $dir = $this->SETTINGS['dir'] . '/' . $ID;
      
      if ( is_dir( $dir ) ) {

        if ( $SYNC == 'push' ) LINOTYPE::$SYNC->delete( 'themes/' . $ID );

        LINOTYPE_helpers::rrmdir( $dir );
        
        $output = array("status" => "success", "id" => $ID, "message" => "delete" );

      } else {

        $output = array("status" => "error", "message" => "theme-delete-error-3" );
          
      }
      
    } else {

      $output = array("status" => "error", "message" => "theme-delete-error-2" );

    }

    return $output;

  }

  public function fetch_config( $DIR, $ID, $PATH, $FILE, $DATA, $SYNC ) {

    $exist = LINOTYPE::$SYNC->exist( $PATH . '/' . $FILE ); 
    if ( $exist ) LINOTYPE::$SYNC->fetch( 'themes', $this->ITEMS, $ID );

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

  static function get_map(){

    $map = array();

    //add post type
    $post_types = get_post_types( array( 'public'   => true ), 'objects' );

    if ( $post_types ) {
      foreach ( $post_types as $key => $post_type ) {

        $POSTS = array();

        $METAS = array();

        $args = array(
          'post_type' => $post_type->name,
          'post_status' => 'publish',
          'posts_per_page' => -1,
        );

        $query = new WP_Query( $args );

        $rows = $query->get_posts();

        if ( $rows ) {
          foreach ( $rows as $post ) {

            $POSTS[ $post->ID ] = array(
                "ID" => $post->ID,
                "title" => $post->post_title ,
                //'metas' => get_post_meta( $post->ID ),
                //"link" => get_permalink( $post->ID ),
                //"post" => $post,
            );

          }
        }

        $map[ $post_type->name ]['infos']["title"] = $post_type->labels->name;
        $map[ $post_type->name ]['infos']["slug"] = $post_type->name;
        $map[ $post_type->name ]['infos']["posts"] = $POSTS;
        // $map[ $post_type->name ]['infos']['metas'] = $METAS;

        $map[ $post_type->name ]['types']['single'] = array( "title" => __("Single"), "template" => "", "templates" => array(), "target" => $POSTS, "rules" => array() );

        if ( get_post_type_object( $post_type->name )->has_archive OR $post_type->name == 'post' OR $post_type->name == 'product' ) $map[ $post_type->name ]['types']['archive'] = array( "title" => __("Archive"), "template" => "", "templates" => array(), "target" => array(), "rules" => array() );

      }
    }

    //add builtin
    $map['author']['infos'] = array( "title" => __('Author'), "slug" => 'author' );
    $map['author']['types'] = array(
      'single' => array( "title" => __("Single"), "template" => "", "templates" => array(), "target" => array(), "rules" => array() ),
    );
    $map['error']['infos'] = array( "title" => __('Error'), "slug" => 'error' );
    $map['error']['types'] = array(
      'single' => array( "title" => __("Single"), "template" => "", "templates" => array(), "target" => array(), "rules" => array() ),
    );
    $map['search']['infos'] = array( "title" => __('Search'), "slug" => 'search' );
    $map['search']['types'] = array(
      'single' => array( "title" => __("Single"), "template" => "", "templates" => array(), "target" => array(), "rules" => array() ),
    );


    //add taxonomy
    $taxonomies = get_taxonomies( array( "public" => true ) );

    if ( $taxonomies ){

      foreach ( $taxonomies as $tax_key => $tax ) {

        $TERMS = array();

        $taxonomy = get_taxonomy( $tax );

        $terms = get_terms( $tax );

        if ( $terms ) {
          foreach ( $terms as $term_key => $term ) {

            $TERMS[ $term->term_id ] = array(
                "ID" => $term->term_id,
                "title" => $term->name,
                "taxonomy" => $tax,
                //"link" => get_term_link( $term->term_id ),
                //"post" => $post,
            );

          }
        }

        if ( $taxonomy->object_type ) {
          foreach ( $taxonomy->object_type as $key => $target ) {

            if ( isset( $map[ $target ] ) ) {

              $map[ $target ]['infos']['taxonomies'][ $taxonomy->name ] = $TERMS;

              $map[ $target ]['types'][ $taxonomy->name ] = array( "title" => $taxonomy->label, "template" => "", "templates" => array(), "target" => $TERMS, "rules" => array() );

            }

          }
        }

      }

    }

    //add templates
    $templates = LINOTYPE::$TEMPLATES->get();

    $templates_map = array();

    if ( $templates ) {
      foreach ( $templates as $template_key => $template ) {
        
        

        if ( isset( $template['target'] ) && is_array( $template['target'] ) ) {
          foreach ( $template['target'] as $template_target_key => $template_target ) {
            
            if ( $template_target ) {
            
              $template_target = explode('_', $template_target );
              if ( count( $template_target ) == 1 ) array_push( $template_target, 'single' );

              if ( $template_target[1] == 'archive' || $template_target[1] == 'taxonomy' || $template_target[1] == 'category' || $template_target[1] == 'tag' ) {

                $map[ $template_target[0] ]['types'][ 'product_cat' ]['templates'][ $template['id'] ] = array(
                  "ID" => $template['id'],
                  "title" => $template['title'],
                  //"link" => 'admin.php?page=composer_templates&action=edit&post='. $template['ID']
                );
                $map[ $template_target[0] ]['types'][ 'product_tag' ]['templates'][ $template['id'] ] = array(
                  "ID" => $template['id'],
                  "title" => $template['title'],
                  //"link" => 'admin.php?page=composer_templates&action=edit&post='. $template['id']
                );
      
              } 
              
              if ( isset( $map[ $template_target[0] ]['types'][ $template_target[1] ] ) ) {
              
                $map[ $template_target[0] ]['types'][ $template_target[1] ]['templates'][ $template['id'] ] = array(
                  "ID" => $template['id'],
                  "title" => $template['title'],
                  //"link" => 'admin.php?page=composer_templates&action=edit&post='. $template['id']
                );
              
              }

            
            }

          }
        }

      }
    }

    // _HANDYLOG('map', $map);

    return $map;

  }


}

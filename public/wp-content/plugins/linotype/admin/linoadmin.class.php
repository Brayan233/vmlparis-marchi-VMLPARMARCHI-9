<?php

/**
 * LINOADMIN FRAMEWORK
 *
 * Wordpress administration framework
 *
 * @package WordPress
 * @subpackage LINOADMIN
 * @version 1.0.0
 * @author Yannick Armspach
 * @link http://www.handypress.io
 *
 */

include 'class/linoadmin.location.class.php';
include 'class/linoadmin.metabox.class.php';
include 'class/linoadmin.meta.class.php';
include 'class/linoadmin.customdata.class.php';
include 'class/linoadmin.custompost.class.php';

include 'class/handypress.helper.class.php';
include 'class/handypress.table.class.php';
include 'class/handypress.db.class.php';
include 'class/handypress.system_config.class.php';
include 'class/handypress.exec_time.class.php';
include 'class/handypress.shell_executer.class.php';
include 'class/handypress.papersize.class.php';
include 'class/handypress.plugin.class.php';
include 'class/handypress.notices.class.php';
include 'class/handypress.google_cloudprint.class.php';
include 'class/handypress.shortcodes.class.php';
include 'class/handypress.file.class.php';

class LINOADMIN {

  static $ADMIN;

  public $LINOADMIN_ID = array();

  public $LINOADMIN = array();

  public $parentID_location = "";
  public $parentID_metabox = "";

  public $parentID_location_last = "";
  public $parentID_metabox_last = "";

  static $post_id;

  static $post_type;

  /**
   *
   * construct
   *
   */
  function __construct( $options = array() ) {

    self::$ADMIN['file'] = __FILE__;
    self::$ADMIN['dir'] = dirname( self::$ADMIN['file'] );
    self::$ADMIN['url'] = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( self::$ADMIN['file'] ) );
    self::$ADMIN['field_dir'] = dirname( self::$ADMIN['file'] ) . '/fields';
    self::$ADMIN['field_url'] = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( self::$ADMIN['file'] ) ) . '/fields';
    self::$ADMIN['version'] = '1.0.0';

    //init
    $this->parentID_location_last = "";
    $this->parentID_metabox_last = "";

    //get post ID
    if ( isset( $_GET['post'] ) && $_GET['post'] ) self::$post_id = $_GET['post'];
    if ( isset( $_REQUEST['post_ID'] ) && $_REQUEST['post_ID'] && ! self::$post_id ) self::$post_id = $_REQUEST['post_ID'];

    //get post type
    if ( self::$post_id ) self::$post_type = get_post_type( self::$post_id );
    if ( ! self::$post_type && isset( $_REQUEST['post_type'] ) ) self::$post_type = $_REQUEST['post_type'];

    if ( is_admin() && isset( $_REQUEST['postbox_flush_pos'] ) && $_REQUEST['postbox_flush_pos'] ) $this->postbox_flush_pos();

  }

  /**
   *
   * init
   *
   */
  public function init() {


    foreach ( $this->LINOADMIN as $LOCATION_ID => $LOCATION ) {

      if ( current_user_can( $LOCATION['capability'] ) && $LOCATION['enable'] !== false ) {

        $this->LINOADMIN[ $LOCATION_ID ]['linoadmin_location'] = new LINOADMIN_LOCATION( $LOCATION_ID, $LOCATION, $this->LINOADMIN );

      }

    }

    

  }

  /**
   *
   * @addLocation
   *
   * Init the creation of the admin page
   *
   *
   */
   public function addLocation( $id, $params ){

    $params_default = array(
      "type" => "option",
      "capability" => "administrator",
      "name" => "General",
      "subname" => "General",
      "title" => null,
      "title_icon" => null,
      "desc" => null,
      "layout" => null,
      "column" => 2,
      "submenu" => null,
      "hidden" => false,
      "icon" => null,
      "order" => null,
      "wrap_style" => null,
      "remove_supports" => array(),
      "remove_metabox" => array(),
      "bt_save" => true,
      "bt_save_title" => 'Save',
      "bt_reset" => false,
      "enable" => true,
      "customdata" => false,
      "custompost" => false,
      "postlist" => false,
      "postlist_custom" => false,
      "message" => false,
      "add_header" => false,
      "add_footer" => false,
      "force_parent_menu_id" => false,
      "admin_css" => array(),
      "remove_extra_padding" => false,
      "nowrap" => false,
      "screen_options" => true,
    );
    //$params = array_filter( $params, function( $val ) { return $val != ''; });
    $params = array_merge( $params_default, $params );

    //add params in the master array settings

    if ( ! isset( $this->LINOADMIN[$id] ) ) {

      foreach ($params as $key => $param) {
        $this->LINOADMIN[$id][$key] = $param;
      }

    }

    $this->parentID_location_last = $id;

  }


  /**
   *
   * @addMetabox
   *
   * Add Metabox to the settings array
   *
   *
   */
  public function addMetabox( $id, $params ){

    $params_default = array(
      "name" => "Options",
      "type" => "sortable",
      "context" => "normal",
      "priority" => "default",
      "force_state" => null,
      "hide_box_style" => false,
      "hide_handle" => false,
      "disable_switch" => false,
      "disable_sortable" => false,
      "remove_padding" => false,
      "tabs_style"=>"h2",
      "tabs_hide_if_one" => true,
      "tabs_contents" => array(),
      "bt_save" => false,
      "bt_reset" => false,
      "enable" => true,
      "parentID_location" => "",
    );
    $params = array_merge( $params_default, $params );

    //get metabox id
    $metabox_id = $id;

    //get metabox parentID_location
    $parentID_location = $params['parentID_location'];

    //if no parentID_location set General
    if ( empty( $parentID_location ) && $this->parentID_location_last ) $parentID_location =  $this->parentID_location_last;
    if ( empty( $parentID_location ) ) $parentID_location =  'General';

    //if General not exist create General
    if ( empty( $this->LINOADMIN[$parentID_location] ) ) {

      $this->LINOADMIN[$parentID_location] = array(
        "name"=>'General',
        "subname"=>'General',
        "title"=>'General',
        "hidden"=>false,
        "enable"=>true,
      );

    }

    //add params in the master array settings
    if ( ! isset( $this->LINOADMIN[$parentID_location]['metabox'][$metabox_id] ) ) {

      foreach ($params as $key => $param) {
        $this->LINOADMIN[$parentID_location]['metabox'][$metabox_id][$key] = $param;
      }
    
    }
    
    //create general tab if no tab define
    if ( empty( $params['tabs'] ) ) $params['tabs'] = array();

    //create tabs
    if ( $params['tabs'] ) {
      foreach ( $params['tabs'] as $tab_key => $tab ) {

        //add params in the master array settings
        if ( ! isset( $this->LINOADMIN[$parentID_location]['metabox'][$metabox_id]['tabs_contents'][$tab_key] ) ) {

          foreach ($tab as $key => $param) {
            $this->LINOADMIN[$parentID_location]['metabox'][$metabox_id]['tabs_contents'][$tab_key][$key] = $param;
          }

        }

      }
    }

    $this->parentID_metabox_last = $metabox_id;

  }

  /**
   *
   * @addMeta
   *
   * Add Meta to the settings array
   *
   *
   */
  public function addMeta( $id, $params ){

    $params_default = array(
      "title" => "",
      "info" => "",
      "desc" => "",
      "name" => "",
      "type" => "text",
      "col" => "col-12",
      "options" => array(),
      "default" => null,
      "value_filter" => false,
      "padding" => "",
      "fullwidth" => false,
      'fullheight' => false,
      "disabled" => false,
      "hide" => false,
      "bt_save" => false,
      "bt_reset" => false,
      "enable" => true,
      "parentID_location" => "",
      "parentID_metabox" => "",
      "id_multiple" => null,
      "path" => $id,
    );
    $params = array_merge( $params_default, $params );

    //get metabox parentID_location
    $parentID_location = $params['parentID_location'];

    //if no parentID_location set General
    if ( empty( $parentID_location ) && $this->parentID_location_last ) $parentID_location =  $this->parentID_location_last;
    if ( empty( $parentID_location ) ) $parentID_location =  'General';

    //if General not exist create General
    if ( empty( $this->LINOADMIN[$parentID_location] ) ) {

      $this->LINOADMIN[$parentID_location] = array(
        "name"=>'General',
        "subname"=>'General',
        "title"=>'General',
        "bt_save" => true,
        "bt_reset" => false,
        "enable" => true,
      );

    }

    //get metabox id
    $parentID_metabox = $params['parentID_metabox'];

    //if no parentID_location set General
    if ( empty( $parentID_metabox ) && $this->parentID_metabox_last ) $parentID_metabox =  $this->parentID_metabox_last;
    if ( empty( $parentID_metabox ) ) $parentID_metabox =  '__options__';

    //if General not exist create General
    if ( empty( $this->LINOADMIN[$parentID_location]['metabox'][$parentID_metabox] ) ) {

      $this->LINOADMIN[$parentID_location]['metabox'][$parentID_metabox] = array(
        "name" => "Options",
        "type" => "sortable",
        "context" => "normal",
        "priority" => "default",
        "force_state" => null,
        "hide_box_style" => false,
        "hide_handle" => false,
        "disable_switch" => false,
        "disable_sortable" => false,
        "remove_padding" => false,
        "tabs_style"=>"h2",
        "tabs_hide_if_one" => true,
        "tabs_contents" => array(),
        "bt_save" => false,
        "bt_reset" => false,
        "enable" => true,
        "parentID_location" => $parentID_location,
      );

    }

    //get meta id
    $meta_id = $id;

    //temp array
    $tab_array = $this->LINOADMIN[$parentID_location]['metabox'][$parentID_metabox]['tabs_contents'];

    //if metabox tabs empty
    if ( empty( $params['tab'] ) ) {

      $params['tab'] = 'General';

      if ( empty( $tab_array['General'] ) ) {

        $tab_array["General"]['label'] = $params['tab'];
        $tab_array["General"]['title'] = $params['tab'];

      }

    } else {

      if ( empty( $tab_array[$params['tab']] ) ) {

        $tab_array[$params['tab']]['label'] = $params['tab'];
        $tab_array[$params['tab']]['title'] = $params['tab'];

      }

    }

    global $LINOADMIN;

    $LINOADMIN[ $parentID_location . '/' . $parentID_metabox . '/' . $meta_id ] = $params;
    $params['path'] = $parentID_location . '/' . $parentID_metabox . '/' . $meta_id;

    //add params in the master array settings
    foreach ($params as $key => $param) {
      $tab_array[$params['tab']]['meta'][$meta_id][$key] = $param;
    }

    //return to the global array
    $this->LINOADMIN[$parentID_location]['metabox'][$parentID_metabox]['tabs_contents'] = $tab_array;

  }


  /**
   *
   * @get_settings
   *
   * get settings array
   *
   * @return  array settings
   *
  */
  public function get_settings(){

    //return master settings aray
    return $this->LINOADMIN;

  }

  /**
   *
   * @get_settings
   *
   * get settings array
   *
   * @return  array settings
   *
  */
  public function get( $location = null ){

    if ( $location ) {
      return $this->LINOADMIN[$location]['linoadmin_location'];
    } else {
      return $this->LINOADMIN;
    }

  }

  /**
   *
   * @push_settings
   *
   * push settings array is you want force new settings
   *
   * @param  array  $new  a new settings array
   *
   */
  public function push_settings( $new ){

    //replace the current setting array
    $this->LINOADMIN = $new;

  }


  /**
   *
   * @clone_settings
   *
   * close existing settings array for a new location;
   *
   * @param  array  $new  a new settings array
   *
   */
  public function clone_settings( $source_path, $target_path, $with_childrens = true ) {

    //explode source path
    $source = explode( '/', $source_path );

    // explode traget path
    $target = explode( '/', $target_path );

    //check if source level exist
    if ( count( $source ) === 1 ) {

      //clone
      if( isset( $this->LINOADMIN[ $source[0] ] ) ) $this->LINOADMIN[ $target[0] ] = $this->LINOADMIN[ $source[0] ];

       //remove children
      if ( $with_childrens == false ) $this->LINOADMIN[ $target[0] ]['metabox'] = array();

    } else if ( count( $source ) === 2 ) {

      //set content
      $source_content = $this->LINOADMIN[ $source[0] ]['metabox'][ $source[1] ];

      //remove children
      if ( $with_childrens == false ) $source_content['tabs_contents'] = array();

      //clone
      $this->LINOADMIN[ $target[0] ]['metabox'][ $target[1] ] = $source_content;

    } else if ( count( $source ) === 3 ){

      // set content
      $source_content = $this->LINOADMIN[ $source[0] ]['metabox'][ $source[1] ]['tabs_contents'][ $source[2] ];

      //clone meta
      $this->LINOADMIN[ $target[0] ]['metabox'][ $target[1] ]['tabs_contents'][ $target[2] ] = $source_content;

    } else {

      return;

    }

  }


  /**
   *
   * @do_meta_zone
   *
   * Create the column responsive layout and load the metabox
   *
   * @see LINOADMIN_LOCATION::create_meta_zone()
   *
   * @param  array    $column {
   *  @type  string   id   the metabox zone id (use custom or classic like 'normal', 'side', 'column3', 'column4' ) don't use special charactere
   * }
   * @param  string   $type    define if static of sortable metabox
   *
  */
  public function do_meta_zone( $column = array( 'normal', null, null, null ), $type = 'static' ){

    LINOADMIN_LOCATION::create_meta_zone( $column, $type );

  }




  /**
   *
   * @postbox_flush_pos
   *
   * quick user metabox position reset
   *
   * @see LINOADMIN_LOCATION::postbox_flush_pos()
   *
   */
  public function postbox_flush_pos(){

    LINOADMIN_LOCATION::postbox_flush_pos();

  }

  /**
   *
   * @get_admin_color
   *
   * get the admin color
   *
   * @see
   *
   */
  public function set_admin_color_class(){

    //get current admin color
    global $_wp_admin_css_colors;
    global $admin_colors;

    $current_admin_color = get_user_option( 'admin_color' );
    //$theme_color = $_wp_admin_css_colors[ $current_admin_color ]->colors[3];

    return $admin_colors;

  }

  /**
   *
   * @run
   *
   * run linoadmin creation
   *
   */
  public function run(){

    $this->init();

  }

  


}


?>

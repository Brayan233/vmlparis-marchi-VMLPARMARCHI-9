<?php

/**
 * LINOADMIN_LOCATION
 *
 * Load all linoadmin location
 *
 * @package WordPress
 * @subpackage LINOADMIN
 * @version 1.0.0
 * @author Yannick Armspach
 * @link http://www.handypress.io
 *
 */
class LINOADMIN_LOCATION extends LINOADMIN {

  public $LOCATION_ID;
  public $LOCATION;
  public $LINOADMIN;

  /**
   *
   *  construct
   *
   */
  function __construct( $ID, $LOCATION, $LINOADMIN ) {

    $this->LOCATION_ID = $ID;
    $this->LOCATION = $LOCATION;
    $this->LINOADMIN = $LINOADMIN;
    
    //get current page
    $this->LOCATION['current_location_ID'] = $this->get_current_location_id();

    //require custom functions for current page
    add_action('admin_init', array( $this, 'load_fields_functions' ) );

    //add script and style
    add_action( 'admin_init', array( $this, 'script_n_style' ) , 999999 );
    add_action( 'admin_head', array( $this, 'custom_script_n_style' ) , 999999 );

    if ( $this->LOCATION['current_location_ID'] == $this->LOCATION_ID ) {
      
      
      //generate the custom header
      if ( $this->LOCATION['add_header'] ) $this->LOCATION['add_header'] = $this->generate_custom_header( $this->LOCATION['add_header'] );
      
      //generate the custom footer
      if ( $this->LOCATION['add_footer'] ) $this->LOCATION['add_footer'] = $this->generate_custom_footer( $this->LOCATION['add_footer'] );

      //convert icon if file
      $this->LOCATION['icon'] = $this->create_icon( $this->LOCATION['icon']  );

      //create custom post if requested
      if ( $this->LOCATION['customdata'] == true ) $this->LOCATION['linoadmin_customdata'] = new LINOADMIN_CUSTOMDATA( $this->LOCATION );

      //create custom post if requested
      if ( $this->LOCATION['custompost'] == true || is_array( $this->LOCATION['custompost'] ) ) $custompost = new LINOADMIN_CUSTOMPOST( $this->LOCATION_ID, $this->LOCATION, $this->LINOADMIN );

      //remove screen options
      if ( $this->LOCATION['screen_options'] == false ) add_filter('screen_options_show_screen', '__return_false');

    }

    //INIT ADMIN
    switch ( $this->LOCATION['type'] ) {

      case 'option':

        add_action('admin_menu', array( $this, 'init_option' ) );

        $this->save_option();

        $this->call_action();

      break;

      case 'dashboard':

        add_action('admin_init', array( $this, 'init_dashboard' ) );

        add_action( 'admin_init', array( $this, 'remove_metabox' ) , 999999 );

        $this->save_dashboard();

      break;

      case 'post':

        add_action( 'add_meta_boxes', array( $this, 'post_metaboxes_add' ) );

        add_action( 'save_post', array( $this, 'save_post' ), 10, 1 );

        add_action( 'admin_init', array( $this, 'post_supports' ) , 999999 );

        add_action( 'add_meta_boxes', array( $this, 'remove_metabox' ) , 999999 );

      break;

      case 'attachment':

        add_action( 'add_meta_boxes', array( $this, 'post_metaboxes_add' ) );

        add_action( 'edit_attachment', array( $this, 'save_post' ), 10, 1 );

        add_action( 'admin_init', array( $this, 'post_supports' ) , 999999 );

        add_action( 'add_meta_boxes', array( $this, 'remove_metabox' ) , 999999 );

      break;

    }

  }

  /**
   *
   *  get_current_location_id
   *
   */
  public function get_current_location_id(){
    
    global $pagenow;

    $current_location_ID = '';

    if ( $pagenow ) $current_location_ID = str_replace( '.php', '', $pagenow );

    if ( ( $pagenow == 'edit.php' || $pagenow == 'post-new.php' ) ) $current_location_ID = 'post';

    if ( ( $pagenow == 'edit.php' || $pagenow == 'post-new.php' ) && ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'page' ) ) $current_location_ID = 'page';

    if ( ( $pagenow == 'edit.php' || $pagenow == 'post-new.php' ) && ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'page' ) ) $current_location_ID = $_GET['post_type'];

    if ( ( $pagenow == 'post.php' ) && isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) ) $current_location_ID = get_post_type( $_GET['post'] );

    if ( $pagenow == 'upload.php' || $pagenow == 'media-new.php' ) $current_location_ID = 'attachment';

    if ( isset( $_GET['page'] ) ) $current_location_ID = $_GET['page'];

    return $current_location_ID;

  }
  
  /**
   *
   *  load_fields_functions
   *
   */
  public function load_fields_functions(){
    
    if ( isset( $this->LOCATION["metabox"] ) ) {
      foreach ( $this->LOCATION["metabox"] as $METABOX_ID => $METABOX ) {
  
        if ( isset( $METABOX['tabs_contents'] ) ) {
          foreach ( $METABOX['tabs_contents'] as $TAB_ID => $TAB ) {
  
            if ( isset( $TAB['meta'] ) ) {
              foreach ( $TAB['meta'] as $META_ID => $META ) {
  
                //check if custom field
                if ( is_dir( $META['type'] ) ) {
  
                  if ( file_exists( $META['type'] . '/functions.php' ) ) {
  
                    require_once( $META['type'] . '/functions.php' );
  
                  }
  
                } else {
  
                 if ( file_exists( LINOADMIN::$ADMIN['dir'] . '/fields/'.$META['type'].'/functions.php' ) ) {
  
                  require_once( LINOADMIN::$ADMIN['dir'] . '/fields/' . $META['type'] . '/functions.php' );
  
                 }
  
               }
  
              }
            }
  
          }
        }
  
      }
    }

  }

  /**
   *
   * @script_n_style
   *
   * load scrip and style for the admin
   *
  */
  public function script_n_style() {
   
    wp_enqueue_style( 'linoadmin-font-awesome', LINOADMIN::$ADMIN['url'] . '/css/font-awesome/css/font-awesome.min.css', false, false, 'screen' );

    wp_enqueue_style( 'linoadmin', LINOADMIN::$ADMIN['url'] . '/css/linoadmin.css', false, false, 'screen' );

    wp_enqueue_script('linoadmin', LINOADMIN::$ADMIN['url'] . '/js/linoadmin.js', array('jquery'), '1.0', true );

  }

  /**
   *
   * @custom_script_n_style
   *
   * print custom scrip and style for the admin
   *
  */
  public function custom_script_n_style() {

    if ( $this->LOCATION['admin_css'] ) {

      echo '<style id="' . $this->LOCATION_ID . '">';

        foreach ( $this->LOCATION['admin_css'] as $com => $css ) {

          echo '
          /* ' . $com . ' */
          ' . $css . '
          ';

        }

      echo '</style>';

    }

  }

  
  /**
   *
   * @init_option
   *
   * create the menu or submenu location and add script if page have sortable metabox
   *
  */
  public function init_option(){


    //if page have admin menu item
    if ( $this->LOCATION['hidden'] ){

      //add hiden admin page
      $this->LOCATION['slug'] = add_submenu_page( null, $this->LOCATION_ID, $this->LOCATION['name'], $this->LOCATION['capability'], $this->LOCATION_ID, array($this,'option_create_page'),10 );

    } else {

      //if page parent
      if ( $this->LOCATION['submenu'] ){

        //_HANDYLOG('option:submenu');

        //add as submenu
        $this->LOCATION['slug'] = add_submenu_page( $this->LOCATION['submenu'], $this->LOCATION_ID, $this->LOCATION['name'], $this->LOCATION['capability'], $this->LOCATION_ID, array($this,'option_create_page'),10 );

        add_filter( 'custom_menu_order', array( $this, 'submenu_order' ) );

      } else {

        //add as menu
        $this->LOCATION['slug'] = add_menu_page( $this->LOCATION_ID, $this->LOCATION['name'], $this->LOCATION['capability'], $this->LOCATION_ID, array($this,'option_create_page') , $this->LOCATION['icon'], $this->LOCATION['order'],10 );
        $this->LOCATION['slug'] = add_submenu_page( $this->LOCATION_ID, $this->LOCATION_ID, $this->LOCATION['subname'], $this->LOCATION['capability'], $this->LOCATION_ID, array($this,'option_create_page'),10 );

      }

    }

    if ( $this->LOCATION['current_location_ID'] == $this->LOCATION_ID ) {
        
      //load the metabox core
      add_action( 'load-' . $this->LOCATION['slug'],  array( $this,'option_metaboxes_core' ), 9 );

      //load the metabox script init
      add_action( 'admin_footer-' . $this->LOCATION['slug'], array($this,'option_metaboxes_scripts' ) );

      //add the metabox
      add_action( 'add_meta_boxes', array( $this, 'option_metaboxes_add' ) );
    
    }

  }


  /**
   *
   * @submenu_order
   *
   * reorder the submenu
   *
  */
  public function submenu_order( $menu_ord ) {

    global $submenu;

    if ( $this->LOCATION['order'] ) {

      if ( $submenu[ $this->LOCATION['submenu'] ] ) {
       foreach ( $submenu[ $this->LOCATION['submenu'] ] as $submenu_key => $submenu_content ) {

        if ( $submenu_content[2] == $this->LOCATION_ID ) {

           $this_submenu_content = $submenu_content;
           $this_submenu_key = $submenu_key;

           unset( $submenu[ $this->LOCATION['submenu'] ][ $submenu_key ] );

        }

       }
      }

      if ( isset( $submenu[ $this->LOCATION['submenu'] ] ) ) array_splice( $submenu[ $this->LOCATION['submenu'] ], $this->LOCATION['order'] -1, 0, array( $this_submenu_content ) );

    }

    return $menu_ord;

  }

  /**
   *
   * @init_dashboard
   *
   * init the dashboard to enable metabox
   *
  */
  public function init_dashboard(){

    add_action( 'wp_dashboard_setup', array( $this, 'dashboard_metaboxes_add' ) , 999999 );

  }

  /**
   *
   * @option_page_metaboxes_core
   *
   * load the metabox core
   *
  */
  public function option_metaboxes_core(){

    do_action('add_meta_boxes_'.$this->LOCATION['slug'], null);
    do_action('add_meta_boxes', $this->LOCATION['slug'], null);

    wp_enqueue_script('postbox');

    //add_screen_option('layout_columns', array( 'max' => 4, 'default' => 3) );
    //add_screen_option( 'per_page', array( 'label' => 'Reset Metabox to default position', 'default' => 0, 'option' => 'linoadmin_reset_metabox_order' ) );

  }


  /**
   *
   * @option_page_metaboxes_add
   *
   * init the metabox script
   *
   */
  public function option_metaboxes_scripts(){

    echo '<script>postboxes.add_postbox_toggles(pagenow);</script>';

  }


  /**
   *
   * @option_page_metaboxes_add
   *
   * add metaboxes to option page
   *
   */
  public function option_metaboxes_add(){

    //_HANDYLOG('option_metaboxes_add');

    if ( current_user_can( $this->LOCATION['capability'] ) && isset( $_GET['page'] ) && $_GET['page'] == $this->LOCATION_ID ) {

      global $wp_meta_boxes;

      //reset option metabox to prevent post metabox load on options page
      if ( $wp_meta_boxes ) {
        foreach ( $wp_meta_boxes as $box_key => $box ) {
          if ( strpos( $box_key, $this->LOCATION_ID ) !== false ) $wp_meta_boxes[ $box_key ] = array();
        }
      }

      if ( isset( $this->LOCATION['metabox'] ) ) {

        foreach ( $this->LOCATION['metabox'] as $METABOX_KEY => $METABOX ) {

          if ( $METABOX['enable'] ) {

            //check metabox capability
            if ( ! isset( $METABOX['capability'] ) ) $METABOX['capability'] = $this->LOCATION['capability'];

            //check if rules
            if ( current_user_can( $METABOX['capability'] ) && empty( $METABOX['if'] ) || $this->compare_meta( $METABOX['if'] ) ) {

              if ( empty( $METABOX['context'] ) ) $METABOX['context'] = 'normal';
              if ( empty( $METABOX['priority'] ) ) $METABOX['priority'] = 'default';

              //remove over effet if option
              if ( $METABOX['priority'] == 'over' ) $METABOX['priority'] = 'high';

              //create the metabox
              add_meta_box( $METABOX_KEY, $METABOX['name'], array( $this, 'create_metabox_content' ),  $this->LOCATION['slug'], $METABOX['context'], $METABOX['priority'], array( 'METABOX_KEY'=>$METABOX_KEY ) );

              //if over move the postbox
              if ( $METABOX['priority'] == 'over' ) add_filter( 'postbox_classes_'. $this->LOCATION['slug'] .'_' . $METABOX_KEY , array( $this, 'postbox_move_over' ) );

              //force the default open/close state
              if ( $METABOX['force_state'] == 'close' ) add_filter( 'postbox_classes_'. $this->LOCATION['slug'] .'_' . $METABOX_KEY , array( $this, 'postbox_force_close' ) );
              if ( $METABOX['force_state'] == 'open'  ) add_filter( 'postbox_classes_'. $this->LOCATION['slug'] .'_' . $METABOX_KEY , array( $this, 'postbox_force_open' ) );

              //hide box style
              if ( $METABOX['hide_box_style'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION['slug'] .'_' . $METABOX_KEY , array( $this, 'postbox_hide_box_style' ) );

              //hide handle
              if ( $METABOX['hide_handle'] == true    ) add_filter( 'postbox_classes_'. $this->LOCATION['slug'] .'_' . $METABOX_KEY , array( $this, 'postbox_hide_handle' ) );

              //disable switch
              if ( $METABOX['disable_switch'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION['slug'] .'_' . $METABOX_KEY , array( $this, 'postbox_disable_switch' ) );

              //disable sortable
              if ( $METABOX['disable_sortable'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION['slug'] .'_' . $METABOX_KEY , array( $this, 'postbox_disable_sortable' ) );

              //remove the padding of the postbox inner
              if ( $METABOX['remove_padding'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION['slug'] .'_' . $METABOX_KEY , array( $this, 'postbox_remove_padding' ) );


            }

          }

        }

      }

    }

  }


  /**
   *
   *  @post_metaboxes_add
   *
   * add metaboxes to post
   *
   */
  public function post_metaboxes_add(){

    //_HANDYLOG('post_metaboxes_add');

    global $post_id;

    if ( $this->LOCATION['metabox'] ) {

      foreach ( $this->LOCATION['metabox'] as $METABOX_KEY => $METABOX ) {

        if ( $METABOX['enable'] ) {

          //check metabox capability
          if ( ! isset( $METABOX['capability'] ) ) $METABOX['capability'] = $this->LOCATION['capability'];

          //check if rules
          if ( current_user_can( $METABOX['capability'] ) && empty( $METABOX['if'] ) || $this->compare_meta( $METABOX['if'] ) ) {

            if ( empty( $METABOX['context'] ) ) $METABOX['context'] = 'normal';
            if ( empty( $METABOX['priority'] ) ) $METABOX['priority'] = 'default';

            //set postbox over all postbox
            $priority = $METABOX['priority'];
            if ( $priority == 'over' ) $priority = 'high';

            //create the metabox
            add_meta_box( $METABOX_KEY, $METABOX['name'], array( $this, 'create_metabox_content' ),  $this->LOCATION_ID, $METABOX['context'], $priority, array( 'METABOX_KEY'=>$METABOX_KEY ) );

            //if over move the postbox
            if ( $METABOX['priority'] == 'over' ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_move_over' ) );

            //force the default open/close state
            if ( $METABOX['force_state'] == 'close' ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_force_close' ) );
            if ( $METABOX['force_state'] == 'open'  ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_force_open' ) );

            //hide box style
            if ( $METABOX['hide_box_style'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_hide_box_style' ) );

            //hide handle
            if ( $METABOX['hide_handle'] == true    ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_hide_handle' ) );

            //disable switch
            if ( $METABOX['disable_switch'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_disable_switch' ) );

            //disable sortable
            if ( $METABOX['disable_sortable'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_disable_sortable' ) );

            //remove the padding of the postbox inner
            if ( $METABOX['remove_padding'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_remove_padding' ) );

          }

        }

      }

    }

  }


  /**
   *
   *  @dashboard_metaboxes_add
   *
   * add metaboxes to post
   *
   */
  public function dashboard_metaboxes_add(){

    //_HANDYLOG('dashboard_metaboxes_add');

    if ( $this->LOCATION['metabox'] ) {

      foreach ( $this->LOCATION['metabox'] as $METABOX_KEY => $METABOX ) {

        if ( $METABOX['enable'] ) {

          //check metabox capability
          if ( ! isset( $METABOX['capability'] ) ) $METABOX['capability'] = $this->LOCATION['capability'];

          //check if rules
          if ( current_user_can( $METABOX['capability'] ) && empty( $METABOX['if'] ) || $this->compare_meta( $METABOX['if'] ) ) {

            if ( empty( $METABOX['context'] ) ) $METABOX['context'] = 'normal';
            if ( empty( $METABOX['priority'] ) ) $METABOX['priority'] = 'default';

            //remove over effet if dashboard
            if ( $METABOX['priority'] == 'over' ) $METABOX['priority'] = 'high';

            //create the metabox
            add_meta_box( $METABOX_KEY, $METABOX['name'], array( $this, 'create_metabox_content' ),  $this->LOCATION_ID, $METABOX['context'], $METABOX['priority'], array( 'METABOX_KEY'=>$METABOX_KEY ) );
            //wp_add_dashboard_widget( $METABOX_KEY, $METABOX['name'], array( $this, 'create_metabox_content' ), null, array( 'METABOX_KEY'=>$METABOX_KEY ) );

            //if over move the postbox
            if ( $METABOX['priority'] == 'over' ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_move_over' ) );

            //force the default open/close state
            if ( $METABOX['force_state'] == 'close' ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_force_close' ) );
            if ( $METABOX['force_state'] == 'open'  ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_force_open' ) );

            //hide box style
            if ( $METABOX['hide_box_style'] == true       ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_hide_box_style' ) );

            //hide handle
            if ( $METABOX['hide_handle'] == true    ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_hide_handle' ) );

            //disable switch
            if ( $METABOX['disable_switch'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_disable_switch' ) );

            //disable sortable
            if ( $METABOX['disable_sortable'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_disable_sortable' ) );

            //remove the padding of the postbox inner
            if ( $METABOX['remove_padding'] == true ) add_filter( 'postbox_classes_'. $this->LOCATION_ID .'_' . $METABOX_KEY , array( $this, 'postbox_remove_padding' ) );


          }

        }

      }

    }

  }


  /**
   *
   * @compare_meta
   *
   * compare meta with operator;
   *
   * @param  array  $if       an array with meta_id, operator and value
   * @return bool   $result   true if operator success or false
   *
   */
  public function compare_meta( $if ) {

    global $post_id;

    if ( $post_id ) {

      //get value if post
      $value = get_post_meta( $post_id, $if[0], true );

    } else {

      //get value if option
      $value = get_option( $if[0] );

    }

    //default false
    $result = false;

    //exe comparator
    switch ( $if[1] ) {

      case '==':  if ( $value == $if[2] )   $result = true; break;
      case '===': if ( $value === $if[2] )  $result = true; break;
      case '!=':  if ( $value != $if[2] )   $result = true; break;
      case '<>':  if ( $value <> $if[2] )   $result = true; break;
      case '!==': if ( $value !== $if[2] )  $result = true; break;
      case '<':   if ( $value < $if[2] )    $result = true; break;
      case '>':   if ( $value > $if[2] )    $result = true; break;
      case '<=':  if ( $value <= $if[2] )   $result = true; break;
      case '>=':  if ( $value >= $if[2] )   $result = true; break;

    }

    return $result;

  }

  /**
   *
   *  @postbox_move_over
   *
   * //add class for the jquery tips
   *
   */
  public function postbox_move_over( $classes=array() ) {

    $pos = array_search( 'move-over', $classes );

    if ( $pos === false ) $classes[] = 'move-over';

    return $classes;

  }

  /**
   *
   *  @postbox_force_open
   *
   * //force the postbox open state
   *
   */
  public function postbox_force_open( $classes=array() ) {

    $pos = array_search( 'closed', $classes );

    if ( $pos !== false ) unset( $classes[ $pos ] );

    return $classes;

  }

  /**
   *
   *  @postbox_force_close
   *
   * //force the postbox close state
   *
   */
  public function postbox_force_close( $classes=array() ) {

    $pos = array_search( 'closed', $classes );

    if ( $pos === false ) $classes[] = 'closed';

    return $classes;

  }


  /**
   *
   *  @postbox_hide_box
   *
   * //remove the box layout
   *
   */
  public function postbox_hide_box_style( $classes=array() ) {

    $pos = array_search( 'hide-box-style', $classes );

    if ( $pos === false ) $classes[] = 'hide-box-style';

    return $classes;

  }


  /**
   *
   *  @postbox_hide_handle
   *
   * //remove the handle
   *
   */
  public function postbox_hide_handle( $classes=array() ) {

    $pos = array_search( 'hide-handle', $classes );

    if ( $pos === false ) $classes[] = 'hide-handle';

    return $classes;

  }


  /**
   *
   *  @postbox_disable_switch
   *
   * //disable switch state button
   *
   */
  public function postbox_disable_switch( $classes=array() ) {

    $pos = array_search( 'disable-switch', $classes );

    if ( $pos === false ) $classes[] = 'disable-switch';

    return $classes;

  }


  /**
   *
   *  @postbox_disable_sortable
   *
   * //disable switch state button
   *
   */
  public function postbox_disable_sortable( $classes=array() ) {

    $pos = array_search( 'disable-sortable', $classes );

    if ( $pos === false ) $classes[] = 'disable-sortable';

    return $classes;

  }


  /**
   *
   *  @postbox_remove_padding
   *
   * //add somme classe in the postbox master
   *
   */
  public function postbox_remove_padding( $classes=array() ) {

    $pos = array_search( 'remove-padding', $classes );

    if ( $pos === false ) $classes[] = 'remove-padding';

    return $classes;

  }

  /**
   *
   *  @postbox_flush_pos
   *
   * reset the postbox position
   *
   */
  public function postbox_flush_pos(){

    //reset metabox position
    global $wpdb;
    $table = $wpdb->prefix . 'usermeta';
    $wpdb->query("DELETE FROM $table WHERE meta_key LIKE 'meta-box-order%'"); //for all

    $wpdb->query("DELETE FROM $table WHERE meta_key LIKE 'manageedit-%columnshidden'"); //for all



  }


  /**
   *
   *  @create_metabox_content
   *
   * create the metabox content
   *
   */
  public function create_metabox_content( $post, $metabox ){

    $metabox = new LINOADMIN_METABOX(  $this->LOCATION, $this->LOCATION['type'], $this->LOCATION['metabox'][ $metabox['args']['METABOX_KEY'] ], $this->LINOADMIN );

  }

  /**
   *
   *  @option_create_page
   *
   * create the option page
   *
   */
  public function option_create_page() {

    //load the layout
    if ( $this->LOCATION['layout'] ) {

      if ( is_file( $this->LOCATION['layout'] ) ) {

        //load custom layout
        include $this->LOCATION['layout'];

      } else {

        //check if custom field
        if ( is_dir( $this->LOCATION['layout'] ) ) {

          $layout_name = explode('/', $this->LOCATION['layout'] );

          $layout_name = end( $layout_name );

          if ( file_exists( $this->LOCATION['layout'] . '/' . $layout_name . '.php' ) ) {

            include $this->LOCATION['layout'] . '/' . $layout_name . '.php';

          }

        } else {

          //load default layout
          $default_layout = LINOADMIN::$ADMIN['dir'] . '/views/layouts/' . $this->LOCATION['layout'] . '/' . $this->LOCATION['layout'] . '.php';
          if ( is_file( $default_layout ) ) include $default_layout;

        }

      }

    } else if ( $this->LOCATION['customdata'] == true ) {

      $this->LOCATION['linoadmin_customdata']->display();

    } else {

       echo $this->get_option_page_header();

        LINOADMIN::do_meta_zone( array( 'normal' ) );

        echo $this->get_option_page_footer();

    }

  }
  public function get_option_page_header() {

    $header = '';

    //if remove_extra_padding
    if ( $this->LOCATION['remove_extra_padding'] ) $header .= '<style>.postbox-container { float: none;} #wpbody-content .metabox-holder { padding-top: 0px; }</style>';
    if ( $this->LOCATION['remove_extra_padding'] ) $header .= '<style>#wpbody-content{padding-bottom:0px;}#wpfooter{display:none;}</style>';

    $nowrap = '';
    if ( $this->LOCATION['nowrap'] ) $nowrap .= ' nowrap';

    $header .= '<div class="wrap' . $nowrap . '">';
      $header .= '<form id="wpbody-form" method="post">';

      if ( $this->LOCATION['add_header'] ) $header .= $this->LOCATION['add_header'];

      //title
      if ( $this->LOCATION["title"] ) $header .= '<h1 class="wp-heading-inline">';
      if ( $this->LOCATION["title_icon"] ) $header .= '<span style="width: 32px;height: 32px;font-size: 32px;" class="dashicons ' . $this->LOCATION["title_icon"] . '"></span> ';
      if ( $this->LOCATION["title"] ) $header .= $this->LOCATION["title"];
      if ( $this->LOCATION["title"] ) $header .= '</h1>'; //style="color:'.$this->theme_color.'"
      if ( $this->LOCATION["bt_save"] === 'header' ) $header .= '<input class="button-primary" style="vertical-align: super;" name="save" type="submit" value="' . $this->LOCATION["bt_save_title"] . '" /> ';
      if ( $this->LOCATION["bt_save"] === 'header_right' ) $header .= '<input class="button-primary" style="position: absolute;top: 20px;right: 20px;" name="save" type="submit" value="' . $this->LOCATION["bt_save_title"] . '" /> ';
      if ( $this->LOCATION["title"] ) $header .= '<hr class="wp-header-end">'; 
      //message
      if( $this->LOCATION["message"] && isset( $_REQUEST['message'] ) ) {

        if ( 'success' == $_REQUEST['message'] ) $header .= '<div id="message" class="updated notice notice-success is-dismissible below-h2"><p><strong>Saved.</strong></p><button type="button" class="notice-dismiss"></button></div>';
        if ( 'error'   == $_REQUEST['message'] )  $header .= '<div id="message" class="error is-dismissible"><p><strong>Not saved.</strong></p></div>';

      }

    return $header;

  }

  public function get_option_page_footer() {

    $footer = '';

    //if save
    if ( $this->LOCATION["bt_save"] !== 'header' && $this->LOCATION["bt_save"] !== 'header_right'  && ( $this->LOCATION["bt_save"] || $this->LOCATION["bt_save"] == 'footer' ) ) {

        $footer .= '<p class="submit"><input class="button-primary force-align-middle" name="save" type="submit" value="' . $this->LOCATION["bt_save_title"] . '" /></p>';
        
    }

    if ( $this->LOCATION["bt_save"] ) $footer .= '<input type="hidden" name="action" value="save" />';

    //metabox nonce
    $footer .= wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
    $footer .= wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );

    if ( $this->LOCATION['add_footer'] ) $footer .= $this->LOCATION['add_footer'];

    $footer .= '</form>';
    $footer .= '</div>';

    return $footer;

  }


  /**
   *
   * @create_meta_zone
   *
   * Create the column responsive layout and load the metabox
   *
   * @see option_create_page() or /views/layouts
   *
   * @param  array    $column {
   *  @type  string   id   the metabox zone id (use custom or classic like 'normal', 'side', 'column3', 'column4' ) don't use special charactere
   * }
   * @param  string   $type    define if static of sortable metabox
   * @return  echo
  */
  public function create_meta_zone( $column = array( 'normal', null, null, null ), $type = 'static' ){

    $this->postbox_container_count = 0;

    if ( count( $column ) > 1  ) {
      echo '<div id="dashboard-widgets-wrap">';
      echo '<div id="dashboard-widgets" class="metabox-holder columns-' . count( $column ) . '">';
    } else {
      echo '<div class="metabox-holder columns-' . count( $column ) . '">';
    }

        if ( isset( $column[0] ) ) {
          $this->postbox_container_count++;
          echo '<div id="postbox-container-'.$this->postbox_container_count.'" class="postbox-container postbox-container-column-1">';
            $this->do_meta_boxes( '', $column[0], null, $type );
          echo '</div>';
        }

        if ( isset( $column[1] ) ) {
          $this->postbox_container_count++;
          echo '<div id="postbox-container-'.$this->postbox_container_count.'" class="postbox-container postbox-container-column-2">';
            $this->do_meta_boxes( '', $column[1], null, $type );
          echo '</div>';
        }

        if ( isset( $column[2] ) ) {
          $this->postbox_container_count++;
          echo '<div id="postbox-container-'.$this->postbox_container_count.'" class="postbox-container postbox-container-column-3">';
            $this->do_meta_boxes( '', $column[2], null, $type );
          echo '</div>';
        }

        if ( isset( $column[3] ) ) {
          $this->postbox_container_count++;
          echo '<div id="postbox-container-'.$this->postbox_container_count.'" class="postbox-container postbox-container-column-4">';
            $this->do_meta_boxes( '', $column[3], null, $type );
          echo '</div>';
        }

    if ( count( $column ) > 1  ) {
      echo '</div>';
      echo '</div>';
    } else {
      echo '</div>';
    }

  }

  /**
   *
   * @do_meta_boxes
   *
   * Overwrite the default do_meta_boxes to enable static metabox
   *
   * @see txt
   *
   * @param  string  $screen    where the metabox loaded (let empty because execute already in current option page )
   * @param  string  $context   where the metabox display in the layout
   * @param  string  $object    null
   * @param  string  $type      static or sortable metabox
   * @return numeric
  */
  public function do_meta_boxes( $screen, $context, $object, $type = 'static' ) {

    global $wp_meta_boxes;
    static $already_sorted = false;

    if ( empty( $screen ) )
      $screen = get_current_screen();
    elseif ( is_string( $screen ) )
      $screen = convert_to_screen( $screen );

    $page = $screen->id;

    $hidden = get_hidden_meta_boxes( $screen );

    if ( $type == 'sortable' ) printf('<div id="%s-sortables" class="meta-box-sortables">', htmlspecialchars($context));
    if ( $type == 'static' ) printf('<div id="%s-sortables" class="meta-box-static">', htmlspecialchars($context));

      // Grab the ones the user has manually sorted. Pull them out of their previous context/priority and into the one the user chose
      if ( ! $already_sorted && $sorted = get_user_option( "meta-box-order_$page" ) ) {
        foreach ( $sorted as $box_context => $ids ) {
          foreach ( explode( ',', $ids ) as $id ) {
            if ( $id && 'dashboard_browser_nag' !== $id ) {
              add_meta_box( $id, null, null, $screen, $box_context, 'sorted' );
            }
          }
        }
      }

      $already_sorted = true;

      $i = 0;

      if ( isset( $wp_meta_boxes[ $page ][ $context ] ) ) {
        foreach ( array( 'high', 'sorted', 'core', 'default', 'low' ) as $priority ) {
          if ( isset( $wp_meta_boxes[ $page ][ $context ][ $priority ]) ) {
            foreach ( (array) $wp_meta_boxes[ $page ][ $context ][ $priority ] as $box ) {
              if ( false == $box || ! $box['title'] )
                continue;
              $i++;
              $hidden_class = in_array($box['id'], $hidden) ? ' hide-if-js' : '';
              echo '<div id="' . $box['id'] . '" class="postbox ' . postbox_classes($box['id'], $page) . $hidden_class . '" ' . '>' . "\n";
              if ( 'dashboard_browser_nag' != $box['id'] )
                echo '<div class="handlediv" title="' . esc_attr__('Click to toggle') . '"><br /></div>';

              if ( $type == 'sortable' ) echo "<h2 class='hndle'><span>{$box['title']}</span></h2>\n";
              if ( $type == 'static' ) echo "<h2 class='hndle' style='cursor: pointer;'><span>{$box['title']}</span></h2>\n";

              echo '<div class="inside">' . "\n";
              call_user_func($box['callback'], $object, $box);
              echo "</div>\n";
              echo "</div>\n";
            }
          }
        }
      }

    echo "</div>";

    return $i;

  }

  /**
   *
   *  @call_action
   *
   * action to save current page options
   *
   */
  public function call_action(){

    global $post_id;

    if ( $this->LOCATION['type'] == 'option' && isset( $_GET['page'] ) && $_GET['page'] == $this->LOCATION_ID ) {

      if ( isset( $this->LOCATION["metabox"] ) ) {
      foreach ( $this->LOCATION["metabox"] as $METABOX_ID => $METABOX ) {

        if ( isset( $METABOX['tabs_contents'] ) ) {
        foreach ( $METABOX['tabs_contents'] as $TAB_ID => $TAB ) {

          if ( isset( $TAB['meta'] ) ) {
          foreach ( $TAB['meta'] as $META_ID => $META ) {

            if( isset( $META['options'] ) && isset( $META['options']['action'] ) && $META['options']['action'] && isset( $_GET[ $META_ID . '_action'] ) && $_GET[ $META_ID . '_action'] == "exec" ) {

              $action = $META['options']['action']();

              $header_location = $_SERVER['REQUEST_URI'];
              $header_location = remove_query_arg( $META_ID . '_action', $header_location );
              $header_location = add_query_arg( array( 'message' => 'success' ) , $header_location );

              wp_redirect( $header_location );

              die;

            }

          }
          }

        }
        }

      }
      }

    }

  }


  /**
  *
  * generate_custom_header
  *
  * @desc
  *
  **/
  public function generate_custom_header( $params ) {
    
    $header = '';

    if ( $this->LOCATION['current_location_ID'] == $this->LOCATION_ID ) {

      if ( is_callable( $params ) ) {

        $header = $params();

      } else if ( is_file( $params ) ) {
        
        ob_start();
          
          include $params;

          $header = ob_get_contents();

        ob_end_clean();

      } else if ( is_array( $params ) ) {

        $header .= '<div class="clear"></div>';

        $header .= '<div class="wrap about-wrap" style="max-width:inherit;margin-top:0px;">';

          $header .= '<h1>' . $params['title'] . '</h1>';

          $header .= '<div class="about-text">' . $params['desc'] . '</div>';

          $header .= '<div class="wp-badge" style="' . $this->create_icon( $params['icon'], 'css' ) . '!important;background-color:transparent;-webkit-box-shadow:none;box-shadow:none;color:#999!important;">' . $params['version'] . '</div>';

        $header .= '</div>';

        $header .= '<div class="clear"></div>';

      }  else {

        $header .= stripcslashes($params);

      }

    }

    return $header;

  }

  /**
  *
  * generate_custom_header
  *
  * @desc
  *
  **/
  public function generate_custom_footer( $params ) {

    $footer = '';

    if ( $this->LOCATION['current_location_ID'] == $this->LOCATION_ID ) {

      if ( is_callable( $params ) ) {

        $footer = $params();

      } else if ( is_callable( $params ) ) {

        $footer .= '<div class="clear"></div>';

        $footer .= '<div style="padding-left:25px;' . $this->create_icon( $params['icon'], 'css' ) . '!important;background-repeat: no-repeat;">';

          $footer .= '<p>' . '<b>' . $params['title'] . ' ' . $params['version'] . '</b> ' . $params['desc'] . '</p>';

        $footer .= '</div>';

        $footer .= '<div class="clear"></div>';

      } else {

        $footer = $params;
        if ( $footer ) $footer = stripslashes( $footer );

      }

    }

    return $footer;

  }

  public function create_icon( $file, $type = 'base64' ) {

    if ( file_exists( $file ) ) {

      $file_type = pathinfo( $file, PATHINFO_EXTENSION );

      if ( $file_type == "svg" ) {

        if ( get_option( 'LINOADMIN_icon_svg_generator_' . $this->LOCATION_ID . '_' . $type ) ) {

          return get_option( 'LINOADMIN_icon_svg_generator_' . $this->LOCATION_ID . '_' . $type );

        } else {

          $file_data = fread( fopen( $file, "r" ), filesize( $file ) );
          $encoded = base64_encode( $file_data );

          $base64 = 'data:image/svg+xml;base64,' . $encoded;

          if ( $type == "base64" ) {

            $icon = $base64;

          } else if ( $type == "css" ) {

            $icon = 'background-image:url(' . $base64 . ')';

          }

          update_option( 'LINOADMIN_icon_svg_generator_' . $this->LOCATION_ID . '_' . $type, $icon );

        }

        return $icon;

      } else {

        return $file;

      }

    } else {

      return $file;

    }

  }

  
  
  /**
   *
   *  @save_dashboard
   *
   * action to save current page options
   *
   */
  public function save_dashboard(){

    global $pagenow;

    if ( isset( $_REQUEST['action'] ) && 'save' == $_REQUEST['action'] ) {

      if ( $this->LOCATION['type'] == 'dashboard' && $pagenow === 'index.php' ) {

        if ( isset( $this->LOCATION["metabox"] ) ) {
        foreach ( $this->LOCATION["metabox"] as $METABOX_ID => $METABOX ) {

          if ( isset( $METABOX['tabs_contents'] ) ) {
          foreach ( $METABOX['tabs_contents'] as $TAB_ID => $TAB ) {

            if ( isset( $TAB['meta'] ) ) {
            foreach ( $TAB['meta'] as $META_ID => $META ) {

              $value = "";
              if ( isset( $_POST[$META_ID] ) ) $value = $_POST[$META_ID];

              if ( isset( $META['override_save'] ) ) {

                $message = $META['override_save']( $META_ID, $META, $post_id, $value );

              } else {

                update_option( $META_ID, $value );

              }

            }
            }

          }
          }

        }
        }

        $header_location = '';

        if ( isset( $this->LOCATION['redirect'] ) ) {

          $header_location .= $this->LOCATION['redirect'];

        } else {

          $header_location .= 'index.php' . '?message=success';

        }

        wp_redirect( $header_location );

        die;

      }

    }

  }

  /**
   *
   *  @save_option
   *
   * action to save current page options
   *
   */
  public function save_option(){



    global $post_id;

    if (  isset( $_REQUEST['action'] ) && 'save' == $_REQUEST['action'] ) {

      if ( $this->LOCATION['type'] == 'option' && isset( $_GET['page'] ) && $_GET['page'] == $this->LOCATION_ID ) {

        $db_data = array();

        if ( isset( $this->LOCATION["metabox"] ) ) {
        foreach ( $this->LOCATION["metabox"] as $METABOX_ID => $METABOX ) {

          if ( isset( $METABOX['tabs_contents'] ) ) {
          foreach ( $METABOX['tabs_contents'] as $TAB_ID => $TAB ) {

            ///////serialize_data_start

            $serialize_data = array();

            //loop all meta
            if ( isset( $TAB['meta'] ) ) {
              foreach ( $TAB['meta'] as $META_ID => $META ) {

                $is_serialize = false;
                $serialize_path = explode( '@', $META_ID );
                if ( count( $serialize_path ) > 1 ) $is_serialize = true; //$serialized_id = $serialize_path[0]; unset( $serialize_path[0] );

                if ( $is_serialize ) {

                  $serialize_value = "";

                  if ( isset( $_POST[ str_replace( '@', '__', $META_ID ) ] ) ) $serialize_value = $_POST[ str_replace( '@', '__', $META_ID ) ];

                  $serialize_data[ $serialize_path[0] ][ $serialize_path[1] ] = $serialize_value;

                  unset( $TAB['meta'][$META_ID] );

                }

              }
            }

            if ( $serialize_data ) {
              foreach ( $serialize_data as $key => $value) {

                if ( $this->LOCATION['customdata'] ) {

                  $db_data[$key] = $value;

                } else {

                  update_option( $key, $value );

                }

              }
            }

            ///////serialize_data_end

            if ( isset( $TAB['meta'] ) ) {
            foreach ( $TAB['meta'] as $META_ID => $META ) {

              $value = "";
              if ( isset( $_POST[$META_ID] ) ) $value = $_POST[$META_ID];

              //convert json to array if needed
              //$value_arr = json_decode( $value );
              //if ( is_array( $value_arr ) ) $value = $value_arr;

              if ( isset( $META['override_save'] ) ) {

                $message = $META['override_save']( $META_ID, $META, $post_id, $value );

              } else {

                if ( $this->LOCATION['customdata'] ) {

                  $db_data[$META_ID] = $value;

                } else {

                  update_option( $META_ID, $value );

                }

              }

            }
            }

          }
          }

        }
        }

        // var_dump($_POST); die;

        //save customdata if set
        if ( $this->LOCATION['customdata'] ) {

          if ( isset ( $_REQUEST['post'] ) && $_REQUEST['post'] ) {
            
            $update = $this->LOCATION['linoadmin_customdata']->db()->update_row( $_REQUEST['post'], $db_data );

          } else {

            $insert = $this->LOCATION['linoadmin_customdata']->db()->insert_row( $db_data );

          }

        }

        //reload
        $header_location = '';

        if ( isset( $this->LOCATION['redirect'] ) ) {

          $header_location .= $this->LOCATION['redirect'];

        } else {

          if ( isset( $this->LOCATION['submenu'] ) && substr( $this->LOCATION['submenu'], -3) == '.php' ) {

            if ( $this->LOCATION['customdata'] && ( $_REQUEST['post'] || isset( $insert ) ) ) {

              $id = $_REQUEST['post'];
              if ( isset( $insert ) ) $id = $insert;

              $header_location .= $this->LOCATION['submenu'] . '?page=' . $this->LOCATION_ID . '&action=edit&post=' . $id .'&message=success';

            } else {

              $header_location .= $this->LOCATION['submenu'] . '?page=' . $this->LOCATION_ID . '&message=success';

            }

          } else {

            if ( $this->LOCATION['customdata'] && ( isset( $_REQUEST['post'] ) || isset( $insert ) ) ) {

              if ( isset( $_REQUEST['post'] ) ) $id = $_REQUEST['post'];
              if ( isset( $insert ) ) $id = $insert;

              $header_location .= 'admin.php' . '?page=' . $this->LOCATION_ID . '&action=edit&post=' . $id . '&message=success';

            } else {

              $header_location .= 'admin.php' . '?page=' . $this->LOCATION_ID . '&message=success';

            }

          }

        }

        wp_redirect( $header_location );

        die;

      }

    }

  }


  /**
   *
   *  @save_option
   *
   * action to save current post meta
   *
   */
  public function save_post(){

    global $pagenow;
    global $post;

    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if ( isset( $this->LOCATION["metabox"] ) ) {
    foreach ( $this->LOCATION["metabox"] as $METABOX_ID => $METABOX ) {

      if ( isset( $METABOX['tabs_contents'] ) ) {
      foreach ( $METABOX['tabs_contents'] as $TAB_ID => $TAB ) {

        ///////serialize_data_start

        $serialize_data = array();

        //loop all meta
        if ( isset( $TAB['meta'] ) ) {
          foreach ( $TAB['meta'] as $META_ID => $META ) {

            $is_serialize = false;
            $serialize_path = explode( '@', $META_ID );
            if ( count( $serialize_path ) > 1 ) $is_serialize = true; //$serialized_id = $serialize_path[0]; unset( $serialize_path[0] );

            if ( $is_serialize ) {

              $serialize_value = "";

              if ( isset( $_POST[ str_replace( '@', '__', $META_ID ) ] ) ) $serialize_value = $_POST[ str_replace( '@', '__', $META_ID ) ];

              $serialize_data[ $serialize_path[0] ][ $serialize_path[1] ] = $serialize_value;

              unset( $TAB['meta'][$META_ID] );

            }

          }
        }

        if ( $serialize_data ) {
          foreach ( $serialize_data as $key => $value ) {

            update_post_meta( $post->ID, $key, $value );

          }
        }

        ///////serialize_data_end

        if ( isset( $TAB['meta'] ) ) {
        foreach ( $TAB['meta'] as $META_ID => $META ) {

          $value = "";
          if ( isset( $_POST[$META_ID] ) ) $value = $_POST[$META_ID];

          if ( isset( $META['override_save'] ) ) {

            $message = $META['override_save']( $META_ID, $META, $post_id, $value );

          } else {

            switch ( $META_ID ) {

              case 'the_title':

                remove_action( 'save_post', array( $this, 'save_post' ) );
                wp_update_post( array( 'ID' => $post->ID, 'post_title' => $value ) );
                add_action( 'save_post', array( $this, 'save_post' ) );
                
              break;

              case 'post_content':

                if ( isset( $post->ID ) ) {
                  remove_action( 'save_post', array( $this, 'save_post' ) );
                  remove_filter('content_save_pre', 'wp_filter_post_kses');
                  remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
                  wp_update_post( array( 'ID' => $post->ID, 'post_content_filtered' => $value ) );
                  add_action( 'save_post', array( $this, 'save_post' ) );
                  add_filter('content_save_pre', 'wp_filter_post_kses');
                  add_filter('content_filtered_save_pre', 'wp_filter_post_kses');
                }

              break;

              case 'excerpt':

                remove_action( 'save_post', array( $this, 'save_post' ) );
                wp_update_post( array( 'ID' => $post->ID, 'post_excerpt' => $value ) );
                add_action( 'save_post', array( $this, 'save_post' ) );
                
              break;

              default:

                //update post meta
                if ( $post ) update_post_meta( $post->ID, $META_ID, $value );

              break;

            }

          }

        }
        }

      }
      }

    }
    }

  }


  /**
   *
   *  @post_supports
   *
   * remove post support if needed
   *
   */
  public function post_supports(){

    if ( $this->LOCATION['remove_supports'] ) {

      foreach ( $this->LOCATION['remove_supports'] as $support_key => $support ) {

        remove_post_type_support( $this->LOCATION_ID, $support );

      }

      //remove margin wrap if title and editor disable
      if ( in_array('title', $this->LOCATION['remove_supports'] ) && in_array('editor', $this->LOCATION['remove_supports'] ) ) {

        if ( ( isset($_GET['post']) && get_post_type($_GET['post']) == $this->LOCATION_ID ) || ( isset($_GET['post_type']) && $_GET['post_type'] == $this->LOCATION_ID ) ) {

          $this->LOCATION['admin_css']['remove_support_padding'] = '
            #post-body-content, .edit-form-section {
              margin-bottom: 0px;
            }
            .postbox.hide-box-style h2.LINOADMIN-tab-nav.nav-tab-wrapper{
              padding-top: 0px!important;
            }';

        }

      }

    }

  }


  /**
   *
   *  @remove_meta_box
   *
   * remove post metabox if needed
   *
   */
  public function remove_metabox(){

    if ( $this->LOCATION['remove_metabox'] && ( $this->LOCATION['current_location_ID'] == $this->LOCATION_ID ) ) {

      foreach ( $this->LOCATION['remove_metabox'] as $metabox_key => $metabox_id ) {

        if ( handypress_helper::str_start( '#', $metabox_id ) || handypress_helper::str_start( '.', $metabox_id ) ) {

          $this->LOCATION['admin_css']['remove_metabox_' . $metabox_key ] = $metabox_id . '{ display:none }';

        } else {

          remove_meta_box( $metabox_id, $this->LOCATION_ID, 'normal' );
          remove_meta_box( $metabox_id, $this->LOCATION_ID, 'side' );

        }

      }

    }

  }


}

/**
 *
 *  UNUSE ??????
 *
 */

// public function customdata(){
  
//   return $this->LOCATION['linoadmin_customdata'];

// }

<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 *
 * BLOCKS
 *
 */
class LINOTYPE {

  static $LINOTYPE;

  static $SETTINGS;

  static $CURRENT;

  static $THEME;

  static $BLOCKS;

  static $BLOCKS_OPTIONS_JS;

  static $TEMPLATES;

  static $MODULES;

  static $THEMES;

  static $BLOCKS_STYLES;

  static $BLOCKS_SCRIPTS;

  static $LIBRERIES_STYLES;

  static $LIBRERIES_SCRIPTS;

  static $STYLES;

  static $SCRIPTS;

  static $ASSETS;

  static $FIELDS;

  static $LIBRARIES;

  static $SYNC;

  public $TEMPLATE_DEFAULT;

  static $QUERY;
  /*
   *
   * __construct
   *
   */
  function __construct() {

    //get_settings
    $SETTINGS = new LINOTYPE_settings();
    LINOTYPE::$SETTINGS = $SETTINGS->get();

    //set_settings
    add_action( 'plugins_loaded', array( $this, 'set_settings' ), 0 );

    $this->cache = new LINOTYPE_cache();

    //init builder
    add_action( 'init', array( $this, 'init' ), 9999 );

    //load customposts
    add_action( 'init', array( $this, 'load_customposts' ), 99999 );

    //load functions
    add_action( 'init', array( $this, 'load_functions' ), 999999 );

    //content composer
    add_filter( 'the_content', array( $this, 'load_content' ) );

    //add support
    add_action( 'init', array( $this, 'add_support' ) );

  }

  /*
   *
   * init
   *
   */
  public function init() {

    //redirect_templates
    add_filter( 'template_include', array( $this, 'load_template' ) );

    //SYNC
    LINOTYPE::$SYNC = new LINOTYPE_sync( get_option('linotype_sync_github_key'), get_option('linotype_sync_github_user'), get_option('linotype_sync_github_repo'), array(
      'dir' => LINOTYPE::$SETTINGS['dir'],
      'url' => LINOTYPE::$SETTINGS['url'],
    ));

    //LIBRARIES
    LINOTYPE::$LIBRARIES = new LINOTYPE_libraries(array(
      'dir' => LINOTYPE::$SETTINGS['dir'] . '/libraries',
      'url' => LINOTYPE::$SETTINGS['url'] . '/libraries',
    ));

    //FIELDS
    LINOTYPE::$FIELDS = new LINOTYPE_fields(array(
      'dir' => LINOTYPE::$SETTINGS['dir'] . '/fields',
      'url' => LINOTYPE::$SETTINGS['url'] . '/fields',
    ));
    LINOTYPE::$FIELDS->load( LINOTYPE::$LIBRARIES->get() );

    //THEMES
    LINOTYPE::$THEMES = new LINOTYPE_themes(array(
      'dir' => LINOTYPE::$SETTINGS['dir'] . '/themes',
      'url' => LINOTYPE::$SETTINGS['url'] . '/themes',
    ));

    //TEMPLATES
    LINOTYPE::$TEMPLATES = new LINOTYPE_templates(array(
      'dir' => LINOTYPE::$SETTINGS['dir'] . '/templates',
      'url' => LINOTYPE::$SETTINGS['url'] . '/templates',
    ));

    //MODULES
    LINOTYPE::$MODULES = new LINOTYPE_modules(array(
      'dir' => LINOTYPE::$SETTINGS['dir'] . '/modules',
      'url' => LINOTYPE::$SETTINGS['url'] . '/modules',
    ));

    //BLOCKS
    LINOTYPE::$BLOCKS = new LINOTYPE_blocks(array(
      'dir' => LINOTYPE::$SETTINGS['dir'] . '/blocks',
      'url' => LINOTYPE::$SETTINGS['url'] . '/blocks',
    ));

    //MODULES AS BLOCKS
    LINOTYPE::$BLOCKS->add( 'modules', LINOTYPE::$MODULES->get() );

    //LOAD BLOCKS
    LINOTYPE::$BLOCKS->load( LINOTYPE::$LIBRARIES->get() );

    //CURRENT
    LINOTYPE::$CURRENT = new LINOTYPE_current( basename( get_template_directory() ) );

    //THEME
    LINOTYPE::$THEME = LINOTYPE::$THEMES->get( get_option( 'linotype_theme', 'linotype_theme_blockstarter' ) );

    if ( LINOTYPE_INFOS ) linolog('LIBRARY', function(){ return array( 'LIBRARY' => array(
      'THEMES' => LINOTYPE::$THEMES->get(),
      'TEMPLATES' => LINOTYPE::$TEMPLATES->get(),
      'MODULES' => LINOTYPE::$MODULES->get(),
      'BLOCKS' => LINOTYPE::$BLOCKS->get(),
      'FIELDS' => LINOTYPE::$FIELDS->get(),
      'LIBRARIES' => LINOTYPE::$LIBRARIES->get(),
    ) ); } );

  }

  /*
   *
   * load_customposts
   *
   */
  public function load_customposts() {

    //if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '/editor/index.php' ) === false && strpos( $_SERVER['REQUEST_URI'], 'wp-admin/admin.php?page=themes' ) === false ) {

      if( isset( LINOTYPE::$CURRENT->get()['customposts'] ) ){
        foreach( LINOTYPE::$CURRENT->get()['customposts'] as $custompost ) {

          new LINOTYPE_custompost( $custompost );

        }
      }

    //}

  }

  /*
   *
   * load_functions
   *
   */
  public function load_functions() {

    if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], 'wp-admin/admin.php?page=fields' ) === false ) {

      foreach( LINOTYPE::$FIELDS->get() as $field_id => $field ) {

        if ( file_exists( $field['dir'] . '/functions.php' ) ) {

          include $field['dir'] . '/functions.php';

        }

      }

    }

    if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '/editor/index.php' ) === false && strpos( $_SERVER['REQUEST_URI'], 'wp-admin/admin.php?page=blocks' ) === false ) {

      foreach( LINOTYPE::$BLOCKS->get() as $block_id => $block ) {

        if ( isset( $block['dir'] ) && file_exists( $block['dir'] . '/functions.php' ) ) {

          include $block['dir'] . '/functions.php';

        }

      }

    }

  }

  /*
   *
   * template_disable
   *
   */
  public function template_disable( $template ){

    return false;

  }

  /*
   *
   * load_template
   *
   */
  public function load_template( $template ) {

    LINOTYPE::$QUERY = get_queried_object();

    if ( isset( $_GET['linotype-preview'] ) && $_GET['linotype-preview']  ) {

      $this->load_template_preview( $template );

    } else {

      $this->load_template_live( $template );

    }

  }

  /*
   *
   * load_template_live
   *
   */
  public function load_template_live( $template ) {

    $this->cache->init_cache();

    if ( is_admin() ) return false;

    if ( empty( LINOTYPE::$THEME['dir'] ) ) wp_die('<h3><b>LINOTYPE</b></h3>No theme enabled<br/><br/><a class="button" href="' . LINOTYPE::$SETTINGS['editor_link'] . '">EDIT</a>', 'LINOTYPE');

    $this->TEMPLATE_DEFAULT = '';

    if ( $template !== get_template_directory() . '/index.php' ) {

      $this->TEMPLATE_DEFAULT = $template;

    }

    $this->location_id = get_queried_object_id();

    $this->location_type = $this->get_location();

    $template_data = null;
    $template_content = "";

    $template_id = $this->get_template_id( $this->location_id, $this->location_type );

    if ( $template_id ) {
      $template_data = LINOTYPE::$TEMPLATES->get( $template_id );
    } else if ( get_option( 'linotype_template_default') ) {
      $template_data = LINOTYPE::$TEMPLATES->get( get_option( 'linotype_template_default') );
    }

    $template_assets = LINOTYPE_helpers::get_template_assets( $template_data['template'] );

    $this->load_styles_assets( $template_assets['styles'] );

    wp_deregister_script( 'jquery' );
    $this->load_scripts_assets( $template_assets['scripts'] );

    get_header();

    if ( $template_data ) {

        LINOTYPE_composer::render( $template_data['template'], LINOTYPE::$BLOCKS->get() );

    } else {

        if ( have_posts() ) :

            while ( have_posts() ) : the_post();

            the_content();

            endwhile;

        endif;

    }

    $this->load_custom_styles();


    $this->load_custom_scripts();

    get_footer();

    $this->cache->end_cache();

  }

  public function load_template_preview(){

    ob_start();

      // include LINOTYPE_plugin::$plugin['dir'] . 'admin/fields/composer/composer.class.php';

      switch ( $_GET['linotype-preview'] ) {

        case "template":
          $data = LINOTYPE::$TEMPLATES->get( $_GET['id'] );
          LINOTYPE_composer::render( $data['template'], LINOTYPE::$BLOCKS->get(), false, true );
        break;

        case "module":
          $data = LINOTYPE::$MODULES->get( $_GET['id'] );
          LINOTYPE_composer::render( $data['module'], LINOTYPE::$BLOCKS->get(), false, true );
        break;

        case "block":
          $data = array( array( "type" => $_GET['id'] ) );
          if ( $data ) LINOTYPE_composer::render( $data, LINOTYPE::$BLOCKS->get(), false, true );
        break;

        case "field":
          $data = array( array( "type" => $_GET['id'] ) );
          if ( $data ) LINOTYPE_composer::render( $data, LINOTYPE::$FIELDS->get(), false, true );
        break;

      }

      $template_content = ob_get_contents();

      ob_end_clean();

      wp_enqueue_style( 'linotype-preview', LINOTYPE_plugin::$plugin['url'] . 'editor/frontend.css', false, false, 'screen' );

      do_action( 'get_header' );
      if ( LINOTYPE::$THEME['dir'] ) include LINOTYPE::$THEME['dir'] . '/header.php';

      echo '<div id="linotype---preview">';

        echo '<div id="linotype---preview-ruler-top"></div>';
        echo '<div id="linotype---preview-ruler-right"></div>';
        echo '<div id="linotype---preview-ruler-bottom"></div>';
        echo '<div id="linotype---preview-ruler-left"></div>';

          echo $template_content;

        echo '</div>';

      do_action( 'get_footer' );
      if ( LINOTYPE::$THEME['dir'] ) include LINOTYPE::$THEME['dir'] . '/footer.php';

  }


  public function get_location() {

    global $wp_query;

    $type = array();

    if ( is_front_page() && is_home() ) {

    	$type = array( 'post', 'archive' );

    } elseif ( is_front_page() ){

    	$type = array( 'page', 'single' );

    } elseif ( is_home() ) {

    	$type = array( 'post', 'archive' );

    } else if ( $wp_query->is_page ) {

        $type = array( 'page', 'single' );

    } elseif ( $wp_query->is_single ) {

        $type = array( 'post', 'single' );

        if ( $wp_query->is_attachment ) $type = array( 'attachment', 'single'  );

        if ( isset( $wp_query->query['post_type'] ) ) $type = array( $wp_query->query['post_type'], 'single' );

    } elseif ( $wp_query->is_category ) {

        $type = array( 'post', 'category' );

    } elseif ( $wp_query->is_tag ) {

        $type = array( 'post', 'post_tag' );

    } elseif ( $wp_query->is_tax ) {

        global $wp_taxonomies;

        $tax = $wp_taxonomies[ $wp_query->query_vars['taxonomy'] ];

        $type = array( $tax->object_type[0], $wp_query->query_vars['taxonomy'], $wp_query->query_vars['term'] );

    } elseif ( $wp_query->is_archive ) {

        if ( $wp_query->is_day ) {

            $type = array( 'post', 'archive', 'day' );

        } elseif ( $wp_query->is_month ) {

            $type = array( 'post', 'archive', 'month' );

        } elseif ( $wp_query->is_year ) {

            $type = array( 'post', 'archive', 'year' );

        } elseif ( $wp_query->is_author ) {

            $type = array( 'author', 'single' );

        } else {

            $type = array( 'post', 'archive' );

            if ( $wp_query->query['post_type'] ) $type = array( $wp_query->query['post_type'], 'archive' );

        }

    } elseif ( $wp_query->is_search ) {

        $type = array( 'search', 'single' );

    } elseif ( $wp_query->is_404 ) {

        $type = array( 'error', 'single' );

    } elseif ( $wp_query->is_404 ) {

      $type = array( 'default', 'page' );

  }

    if ( class_exists( 'WooCommerce' ) ) {

      if( is_woocommerce() ) $type = array( 'product', 'archive' );
      if( is_shop() ) $type = array( 'product', 'archive' );
      if( is_product_taxonomy() ) $type = array( 'product', 'taxonomy' );
      if( is_product_category() ) $type = array( 'product', 'product_cat' );
      if( is_product_tag() ) $type = array( 'product', 'product_tag' );
      if( is_product() ) $type = array( 'product', 'single' );

      // function is_subcategory($cat_id = null) {
        if (is_tax('product_cat')) {

            if (empty($cat_id)){
                $cat_id = get_queried_object_id();
            }

            $cat = get_term(get_queried_object_id(), 'product_cat');
            if ( empty($cat->parent) ){
            //  $type = array( 'product', 'product_cat' );
            }else{
              $type = array( 'product', 'archive' );
            }
        }
        // return false;
    // }

      // if( is_cart() ) $type = array( 'woocommerce', 'cart' );
      // if( is_checkout() ) $type = array( 'woocommerce', 'checkout' );
      // if( is_checkout_pay_page() ) $type = array( 'woocommerce', 'checkout_pay_page' );
      // if( is_wc_endpoint_url() ) $type = array( 'woocommerce', 'endpoint' );
      // if( is_account_page() ) $type = array( 'woocommerce', 'account' );
      // if( is_view_order_page() ) $type = array( 'woocommerce', 'order' );
      // if( is_edit_account_page() ) $type = array( 'woocommerce', 'account' );
      // if( is_order_received_page() ) $type = array( 'woocommerce', 'account' );
      // if( is_add_payment_method_page() ) $type = array( 'woocommerce', 'account' );
      // if( is_lost_password_page() ) $type = array( 'woocommerce', 'password' );

    }

    return $type;

  }


  /**
  *
  * get_template_id
  *
  **/
  public function get_template_id( $id, $type ) {

    $template_id = false;

    //if themes engine enable select template from map
    if ( LINOTYPE::$SETTINGS['has_themes'] ) {

      if ( isset( LINOTYPE::$THEME['map'][ $type[0] ]['types'][ $type[1] ]['template'] ) ) {

        //get default
        $template_id = LINOTYPE::$THEME['map'][ $type[0] ]['types'][ $type[1] ]['template'];

        //check template rules
        if ( LINOTYPE::$THEME['map'][ $type[0] ]['types'][ $type[1] ]['rules'] ){

          foreach ( LINOTYPE::$THEME['map'][ $type[0] ]['types'][ $type[1] ]['rules'] as $rule_key => $rule ) {

            if ( $rule['template'] ) {

              switch ( $rule['if'] ) {

                case 'post':

                  if ( $rule['is'] == $id ) {

                    $template_id = $rule['template'];

                  }

                break;

                case 'taxonomy':

                  $is = explode( '=', $rule['is'] );

                  $queried_object_terms = wp_get_object_terms(  $id, $is[0], array('fields' => 'ids') );

                  if ( in_array( $is[1], $queried_object_terms ) ) {

                    $template_id = $rule['template'];

                  }

                break;

                case 'archive':

                  $is = explode( '=', $rule['is'] );

                  $queried_object = get_queried_object();

                  if ( isset( $queried_object->taxonomy ) && $queried_object->taxonomy  == $is[0] && isset( $queried_object->term_id ) && $queried_object->term_id  == $is[1] ) {

                    $template_id = $rule['template'];

                  }

                break;

                case 'archive_last':

                  $term_id = get_queried_object_id();
                  $taxonomy_name = get_queried_object()->taxonomy;

                  if ( $taxonomy_name ) {

                    $terms = get_term_children( $term_id, $taxonomy_name );

                    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

                      //not last

                    } else {

                      $template_id = $rule['template'];

                    }

                  }

                break;

                case 'meta':

                  $is = explode( '=', $rule['is'] );

                  $queried_object_meta = get_post_meta( $id, $is[0], true );

                  if ( $queried_object_meta &&  $queried_object_meta == $is[1] ) {

                    $template_id = $rule['template'];

                  }

                break;

              }

            }

          }

        }


      }

    }

    //if template engine enable select template from post
    if ( LINOTYPE::$SETTINGS['has_templates'] && $type[1] == "single"  ) {

      //check manual
      $manual_id = get_post_meta( $id, '_linotype_template', true );
      if ( $manual_id ) $template_id = $manual_id;

    }

    LINOTYPE::$THEME['current'] = array(
      "type" => $type,
      "template" => LINOTYPE::$TEMPLATES->get( $template_id ),
    );

    return $template_id;

  }


  /**
  *
  * get_template
  *
  **/
  public function get_template( $template_data = null ) {

    $content = '';

    if ( $template_data ) {

      ob_start();

        LINOTYPE_composer::render( $template_data, LINOTYPE::$BLOCKS->get() );

        $content = ob_get_contents();

      ob_end_clean();

    } else {

      ob_start();

        if ( have_posts() ) :

            while ( have_posts() ) : the_post();

            the_content();

            endwhile;

        endif;

        $content = ob_get_contents();

      ob_end_clean();

    }

    return $content;

  }


  /**
  *
  * load_content
  *
  * @desc replace the_content() by the composer version
  *
  **/
  public function load_content( $content ) {

    $linotype_content = '';

    global $post;

    if ( isset( $post->ID ) && $post->ID ) {

      $composer_enable = get_post_meta( $post->ID, '_linotype_content', true );
      $composer_data = get_post_meta( $post->ID, '_linotype_data', true );

      if ( $composer_enable ) {

        if ( $composer_data ) {

          $composer_data = json_decode(  $composer_data , true );

            ob_start();

              // include LINOTYPE_plugin::$plugin['dir'] . 'admin/fields/composer/composer.class.php';

              LINOTYPE_composer::render( $composer_data, LINOTYPE::$BLOCKS->get() );

              $linotype_content = ob_get_contents();

            ob_end_clean();

        }

      }

    }

    if ( isset( $this->TEMPLATE_DEFAULT ) && $this->TEMPLATE_DEFAULT ) {

      ob_start();

        require_once( $this->TEMPLATE_DEFAULT );

        echo $this->TEMPLATE_DEFAULT;

      $template_content = ob_get_contents();

      ob_end_clean();

      return $template_content;

    }

    if ( $linotype_content ) $content = $linotype_content;

    return $content;

  }





  /**
  *
  * load_styles_assets
  *
  *
  **/
  public function load_styles_assets( $template_styles ) {

    $version = false;

    if ( LINOTYPE::$SETTINGS['cache']['minify_css'] ) {

      wp_deregister_style('dashicons');

      // wp_enqueue_style( 'LINOTYPE-global-reset', LINOTYPE::$THEME['url'] . '/reset.css', false, $version, 'screen' );

      // wp_enqueue_style( 'LINOTYPE-global-style-libraries', LINOTYPE::$SETTINGS['url_cache'] . '/public/assets/css/libraries_' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.css', false, $version, 'screen' );

      // wp_enqueue_style( 'LINOTYPE-global-style-theme', LINOTYPE::$THEME['url'] . '/style.css', false, $version, 'screen' );

      // wp_enqueue_style( 'LINOTYPE-global-style-blocks', LINOTYPE::$SETTINGS['url_cache'] . '/public/assets/css/blocks_' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.css', false, $version, 'screen' );

      wp_enqueue_style( 'LINOTYPE-app-style', LINOTYPE::$SETTINGS['url_cache'] . '/public/assets/css/bundle.' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.css', false, $version, 'screen' );

    } else {

      if( LINOTYPE::$SETTINGS['cache']['minify_plugins'] ) wp_enqueue_style( 'LINOTYPE-global-style-plugins', LINOTYPE::$SETTINGS['url_cache'] . '/public/assets/css/plugins_' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.css', false, $version, 'screen' );

      wp_enqueue_style( 'LINOTYPE-global-reset', LINOTYPE::$THEME['url'] . '/reset.css', false, $version, 'screen' );

      foreach ( $template_styles['libraries'] as $library_asset_id => $library_asset_url ) {

        wp_enqueue_style( 'LINOTYPE-style-' . $library_asset_id, $library_asset_url, false, $version, 'screen' );

      }

      wp_enqueue_style( 'LINOTYPE-global-style-theme', LINOTYPE::$THEME['url'] . '/style.css', false, $version, 'screen' );

      foreach ( $template_styles['blocks'] as $block_asset_id => $block_asset_url ) {

        wp_enqueue_style( 'LINOTYPE-style-' . $block_asset_id, $block_asset_url, false, $version, 'screen' );

      }

    }

  }

  /**
  *
  * load_scripts_assets
  *
  *
  **/
  public function load_scripts_assets( $template_scripts ) {

    $version = false;

    wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.js' ), false, NULL, true );
    wp_enqueue_script( 'jquery' );

    if ( LINOTYPE::$SETTINGS['cache']['minify_js'] ) {

      wp_enqueue_script('LINOTYPE-app-script', LINOTYPE::$SETTINGS['url_cache'] . '/public/assets/js/bundle.' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.js', array(), false, true );

    } else {

      if( LINOTYPE::$SETTINGS['cache']['minify_plugins'] ) wp_enqueue_script('LINOTYPE-global-script-plugins', LINOTYPE::$SETTINGS['url_cache'] . '/public/assets/js/plugins_' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.js', array(), $version );

      foreach ( $template_scripts['libraries'] as $library_asset_id => $library_asset_url ) {

        wp_enqueue_script('LINOTYPE-script-' . $library_asset_id, $library_asset_url, array(), false, true );

      }

      foreach ( $template_scripts['blocks'] as $block_asset_id => $block_asset_url ) {

        wp_enqueue_script('LINOTYPE-script-' . $block_asset_id, $block_asset_url, array(), false, true );

      }

      if ( LINOTYPE::$SETTINGS['cache']['lazyload'] ) wp_enqueue_script('LINOTYPE-lazyload', LINOTYPE_plugin::$plugin['url'] . '/lib/lazyload/lazyload.min.js', array(), false, true );

    }


  }

  /**
  *
  * locate_custom_styles
  *
  *
  **/
  public function locate_custom_styles() {

    echo '<style id="LINOTYPE-custom-styles" type="text/css">{{LINOTYPE::$STYLES}}</style>';

  }

  /**
  *
  * load_custom_styles
  *
  *
  **/
  public function load_custom_styles() {

    if ( LINOTYPE::$STYLES ) echo '<style id="LINOTYPE-custom-styles" type="text/css">' . LINOTYPE::$STYLES . '</style>';

  }

  public function reorder_styles() {

    global $wp_styles;

    // linolog( $wp_styles->queue );

    $current_styles = array_values( $wp_styles->queue );

    $style_plugins = array();
    $style_blocks = array();
    $style_libraries = array();

    foreach( $current_styles as $style ) {

      if ( strpos( $style, '_block_' ) !== false ) {
        $style_blocks[] = $style;
      } else if ( strpos( $style, '_library_' ) !== false ) {
        $style_libraries[] = $style;
      } else if ( ! in_array( $style, array('LINOTYPE','LINOTYPE-global-reset','LINOTYPE-global-style') ) ) {
        $style_plugins[] = $style;
      }

    }

    $wp_style_ordered = array();
    $wp_style_ordered = array_merge( $wp_style_ordered, $style_plugins );
    $wp_style_ordered = array_merge( $wp_style_ordered, array('LINOTYPE-global-reset') );
    if ( LINOTYPE::$SETTINGS['cache']['minify_css'] ) {
      $wp_style_ordered = array_merge( $wp_style_ordered, array_values( 'LINOTYPE-global-style-libraries' ) );
    } else {
      $wp_style_ordered = array_merge( $wp_style_ordered, array_values( $style_libraries ) );
    }
    $wp_style_ordered = array_merge( $wp_style_ordered, array('LINOTYPE-global-style') );
    if ( LINOTYPE::$SETTINGS['cache']['minify_css'] ) {
      $wp_style_ordered = array_merge( $wp_style_ordered, array_values( 'LINOTYPE-global-style-blocks' ) );
    } else {
      $wp_style_ordered = array_merge( $wp_style_ordered, array_values( $style_blocks ) );
    }
    $wp_style_ordered = array_merge( $wp_style_ordered, array('LINOTYPE') );

    $wp_styles->queue = $wp_style_ordered;

    // linolog( $wp_styles->queue );

    return $wp_styles;

  }

  public function load_custom_scripts(){

    // echo '<script type="text/javascript">' . PHP_EOL;
    //   echo '/* <![CDATA[ */'. PHP_EOL;
    //   echo 'var LINOTYPE = ' . json_encode( LINOTYPE::$BLOCKS_OPTIONS_JS ) . PHP_EOL;
    //   echo 'var LINOTYPE_ajax = "\/wp-admin\/admin-ajax.php";'. PHP_EOL;
    //   echo '/* ]]> */'. PHP_EOL;
    // echo '</script>';

    if ( get_option('linotype_theme_js') ) echo ' <script id="LINOTYPE-custom-scripts" type="text/javascript">(function($) {' . PHP_EOL . get_option('linotype_theme_js') . PHP_EOL . '}(jQuery));</script>';

    if ( LINOTYPE::$SCRIPTS ) echo ' <script id="LINOTYPE-custom-scripts" type="text/javascript">(function($) {' . PHP_EOL . 'window.linotype = [];' . LINOTYPE::$SCRIPTS . PHP_EOL . '}(jQuery));</script>';

  }

  /**
  *
  * add_support
  *
  * @desc add to enable custom menu in wordpress admin
  *
  **/
  public function add_support() {

    //add_theme_support( 'automatic-feed-links' );

    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

    add_theme_support( 'title-tag' );

    add_theme_support( 'woocommerce' );

    add_filter('use_block_editor_for_post_type', '__return_false', 10);

    add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );

    function remove_block_css() {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-block-style' );
        wp_dequeue_style( 'storefront-gutenberg-blocks' );
    }

    add_filter( 'woocommerce_enqueue_styles', 'woocommerce_dequeue_styles' );

    function woocommerce_dequeue_styles( $enqueue_styles ) {
       unset( $enqueue_styles['woocommerce-general'] );
       unset( $enqueue_styles['woocommerce-layout'] );
       unset( $enqueue_styles['woocommerce-smallscreen'] );
       return $enqueue_styles;
    }

    add_theme_support( 'post-thumbnails' );

    add_theme_support( 'menus' );

    //add_theme_support('widgets');

    //load_theme_textdomain( 'text_domain', get_template_directory() . '/language' );

    // Allow SVG

    add_filter( 'facetwp_assets', function( $assets ) {

      if ( ! is_admin() && LINOTYPE::$SETTINGS['cache']['minify_plugins'] ) {

        global $wp_scripts;

        if ( $assets ) {
          foreach( $assets as $handle => $src ) {

            if ( $src && strpos( $handle, '.css' ) !== false ) LINOTYPE::$SETTINGS['cache']['all_assets']['styles'][$handle] = array( 'src' => $src, 'handle' => $handle );
            if ( $src && strpos( $handle, '.js' ) !== false ) LINOTYPE::$SETTINGS['cache']['all_assets']['scripts'][$handle] = array( 'src' => $src, 'handle' => $handle );

          }
        }

        update_option('linotype_all_assets', LINOTYPE::$SETTINGS['cache']['all_assets'] );

        $assets = array();

      }

      return $assets;

    });


  }



  public function set_settings() {

    LINOTYPE::$SETTINGS['linotype_content_post_types'] = get_post_types( array('public'=>true), 'names' );
    $linotype_content_post_types = array_filter( explode( ',', get_option( 'linotype_content_post_types'  ) ) );
    if ( $linotype_content_post_types ) LINOTYPE::$SETTINGS['linotype_content_post_types'] = $linotype_content_post_types;

    if ( LINOTYPE_INFOS ) linolog('SETTINGS', function(){ return array( 'SETTINGS' => LINOTYPE::$SETTINGS ); } );

  }

}

?>

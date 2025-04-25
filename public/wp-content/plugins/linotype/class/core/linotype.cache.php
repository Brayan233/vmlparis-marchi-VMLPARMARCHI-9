<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*
* LINOTYPE_cache
*
*
**/
class LINOTYPE_cache {

  function __construct(){

    //load cache
    $boot_pos = 0;
    if ( file_exists( get_template_directory() . '/boot.php' ) ) $boot_pos = 99999;
    add_action( 'plugins_loaded', array( $this, 'load_cache' ), $boot_pos );

    if( LINOTYPE::$SETTINGS['cache']['minify_plugins'] )   add_filter( 'script_loader_tag', array( $this, 'dequeue_assets_scripts' ), 10, 2 );
    if( LINOTYPE::$SETTINGS['cache']['minify_plugins'] )   add_filter( 'style_loader_tag', array( $this, 'dequeue_assets_styles' ), 10, 2 );

    if( LINOTYPE::$SETTINGS['cache']['remove_css_js_version'] ) add_filter( 'style_loader_src', array( $this, 'remove_css_js_ver' ), 10, 2 );
    if( LINOTYPE::$SETTINGS['cache']['remove_css_js_version'] ) add_filter( 'script_loader_src', array( $this, 'remove_css_js_ver' ), 10, 2 );
    if( LINOTYPE::$SETTINGS['cache']['add_defer_attribute'] ) add_filter('script_loader_tag', array( $this, 'add_defer_attribute' ), 10, 2);
    if( LINOTYPE::$SETTINGS['cache']['dequeue_jquery_migrate'] ) add_filter( 'wp_default_scripts', array( $this, 'dequeue_jquery_migrate' ) );

    //init builder
    add_action( 'init', array( $this, 'built_theme' ), 99999 );

    add_action( 'shutdown', array( $this, 'list_plugins_assets' ), 999999999999 );

  }

  public function load_cache() {

    if ( defined( 'WP_CLI' ) && WP_CLI ) {

      //silent

    } else {

      if ( file_exists( get_template_directory() . '/boot.php' ) ) include get_template_directory() . '/boot.php';

    }

    $this->set_cachefile();

    if ( ! empty( LINOTYPE::$SETTINGS['cache']['exception'] ) ) {

      $exceptions = preg_split( '#(\n|\r)#', LINOTYPE::$SETTINGS['cache']['exception'] );

      foreach ( $exceptions as $exception ) {

        $regex = false;

        if ( preg_match( '#^[\s]*$#', $exception ) ) {
          continue;
        }

        $exception = trim( $exception );

        if ( ! preg_match( '#^/#', $exception ) ) {

          $url = rtrim( 'http' . ( isset( $_SERVER['HTTPS'] ) ? 's' : '' ) . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}", '/' );

          if ( $regex ) {
            if ( preg_match( '#^' . $exception . '$#', $url ) ) {
              // Exception match!
              LINOTYPE::$SETTINGS['cache']['enable'] = false;
            }
          } elseif ( preg_match( '#\*$#', $exception ) ) {
            $filtered_exception = str_replace( '*', '', $exception );

            if ( preg_match( '#^' . $filtered_exception . '#', $url ) ) {
              // Exception match!
              LINOTYPE::$SETTINGS['cache']['enable'] = false;
            }
          } else {
            $exception = rtrim( $exception, '/' );

            if ( strtolower( $exception ) === strtolower( $url ) ) {
              // Exception match!
              LINOTYPE::$SETTINGS['cache']['enable'] = false;
            }
          }
        } else {
          $path = $_SERVER['REQUEST_URI'];

          if ( $regex ) {
            if ( preg_match( '#^' . $exception . '$#', $path ) ) {
              // Exception match!
              LINOTYPE::$SETTINGS['cache']['enable'] = false;
            }
          } elseif ( preg_match( '#\*$#', $exception ) ) {
            $filtered_exception = preg_replace( '#/?\*#', '', $exception );

            if ( preg_match( '#^' . $filtered_exception . '#i', $path ) ) {
              // Exception match!
              LINOTYPE::$SETTINGS['cache']['enable'] = false;
            }
          } else {
            if ( '/' !== $path ) {
              $path = rtrim( $path, '/' );
            }

            if ( '/' !== $exception ) {
              $exception = rtrim( $exception, '/' );
            }

            if ( strtolower( $exception ) === strtolower( $path ) ) {
              // Exception match!
              LINOTYPE::$SETTINGS['cache']['enable'] = false;
            }
          }
        }

      }

    }

    if ( in_array(
      basename( $_SERVER['SCRIPT_FILENAME'] ),
      array(
        'wp-app.php',
        'xmlrpc.php',
        'wp-cron.php',
        'wp-ajax.php',
      )
    ) ) {
      //_HANDYLOG('nocache-3');
      LINOTYPE::$SETTINGS['cache']['enable'] = false;
    }

    	// Never batcache WP javascript generators
    if ( strstr( $_SERVER['SCRIPT_FILENAME'], 'wp-includes/js' ) ) {
      //_HANDYLOG('nocache-4');
      LINOTYPE::$SETTINGS['cache']['enable'] = false;
    }

    	// Never batcache a POST request.
    if ( ! empty( $GLOBALS['HTTP_RAW_POST_DATA'] ) || ! empty( $_POST )
    || ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] )
    ) {
      //_HANDYLOG('nocache-5');
      LINOTYPE::$SETTINGS['cache']['enable'] = false;
    }

    if( current_user_can( 'linotype_admin' ) ) LINOTYPE::$SETTINGS['cache']['enable'] = false;

    if (defined('DOING_AJAX') && DOING_AJAX) LINOTYPE::$SETTINGS['cache']['enable'] = false;

    if ( ! is_admin() && LINOTYPE::$SETTINGS['cache']['enable'] ) {

      header('Etag: "' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '"');
      
      if ( file_exists( $this->cachefile ) ) {

        $cache_limit_in_mins = 60 * intval( LINOTYPE::$SETTINGS['cache']['max_age'] );
        $diff_in_secs = ( time() - ( 60 * $cache_limit_in_mins ) ) - filemtime( $this->cachefile );

        if( $diff_in_secs < 0 ) {

          header( 'Cache-Control: max-age=' . $cache_limit_in_mins );

          header('Last-Modified: ' . gmdate('D, d M Y H:i:s', LINOTYPE::$SETTINGS['cache']['timestamp'] ) . ' GMT' );

          echo file_get_contents( $this->cachefile );

          die();

        }

      }

    } else if ( ! is_admin() ) {

      header("Expires: on, 01 Jan 1970 00:00:00 GMT");
      header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
      header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");

    }

  }

  public function set_cachefile() {

    if ( is_admin() && current_user_can( 'linotype_admin' ) && isset( $_GET['page'] ) && $_GET['page'] == 'linotype_cache' && isset( $_GET['cache_delete'] ) && $_GET['cache_delete'] ) {

      if ( file_exists( LINOTYPE::$SETTINGS['dir_cache'] . '/' . $_GET['cache_delete'] ) ) unlink( LINOTYPE::$SETTINGS['dir_cache'] . '/' . $_GET['cache_delete'] );

    }

    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $id = LINOTYPE::$SETTINGS['cache']['timestamp'] . '-' . base64_encode( $url );

    $dir = 'public/desktop/';
    if ( wp_is_mobile() ) $dir = 'public/mobile/';

    $this->cachefile = LINOTYPE::$SETTINGS['dir_cache'] . '/' . $dir . $id;

  }


  static function cache_html_empty() {

    if ( current_user_can( 'linotype_admin' ) && is_admin() ) {

      $files = glob( LINOTYPE::$SETTINGS['dir_cache'] . '/public/assets/css/*');

      foreach( $files as $file ) {

        if( is_file( $file ) ) unlink( $file );

      }

      $files = glob( LINOTYPE::$SETTINGS['dir_cache'] . '/public/assets/js/*');

      foreach( $files as $file ) {

        if( is_file( $file ) ) unlink( $file );

      }

      $files = glob( LINOTYPE::$SETTINGS['dir_cache'] . '/public/desktop/*');

      foreach( $files as $file ) {

        if( is_file( $file ) ) unlink( $file );

      }

      $files = glob( LINOTYPE::$SETTINGS['dir_cache'] . '/public/mobile/*');

      foreach( $files as $file ) {

        if( is_file( $file ) ) unlink( $file );

      }

      update_option( 'linotype_cahe_timestamp', time() );

      LINOTYPE_cache::clear_server_cache();

    }

  }

  static function clear_server_cache(){

    //opcache_reset();

    if ( defined( 'KINSTAMU_VERSION' ) ) {

      $url1 = get_bloginfo('url') . '/kinsta-clear-cache-all/';
      $curl1 = curl_init();
      curl_setopt($curl1, CURLOPT_URL, $url1);
      curl_setopt($curl1, CURLOPT_RETURNTRANSFER, TRUE);
      curl_exec($curl1);
      //echo curl_getinfo($curl1, CURLINFO_SIZE_DOWNLOAD );

    }

  }

  static function cache_plugins_index_reset() {

    if ( current_user_can( 'linotype_admin' ) && is_admin() ) {

      update_option('linotype_all_assets', '' );
      update_option('linotype_minify_plugins_styles', '' );
      update_option('linotype_minify_plugins_scripts', '' );

    }

  }

  static function cache_plugins_create() {

    if ( current_user_can( 'linotype_admin' ) && is_admin() ) {



    }

  }

  static function get_cache_files() {

    if ( current_user_can( 'linotype_admin' ) && is_admin() ) {

      $files = array(
        'public' => array(
          'desktop' => glob( LINOTYPE::$SETTINGS['dir_cache'] . '/public/desktop/*' ),
          'mobile'  => glob( LINOTYPE::$SETTINGS['dir_cache'] . '/public/mobile/*' ),
        )
      );

      return $files;

    }

  }

  public function init_cache() {

    //if ( ! isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) || ( empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') )  {

      ob_start();

    //}

  }

  public function end_cache() {

    //if ( ! isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) || ( empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') )  {

      $PAGECACHE = ob_get_contents();

      //ob_end_flush();
      ob_end_clean();

      if ( ! is_admin() ) {

        if ( LINOTYPE::$SETTINGS['cache']['lazyload'] ) {

          //$PAGECACHE = preg_replace("/<(img|source)(.*?lazyload.*?)( )(src=)(.*?)>/i", '<$1$2 data-$4$5>', $PAGECACHE );
          //$PAGECACHE = preg_replace("/<(img|source)(.*?lazyload.*?)( )(srcset=)(.*?)>/i", '<$1$2 data-$4$5>', $PAGECACHE );

        }

        if ( LINOTYPE::$SETTINGS['cache']['minify_html'] ) {

          $PAGECACHE = preg_replace(array(
              '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
              '/[^\S ]+\</s',     // strip whitespaces before tags, except space
              '/(\s)+/s',         // shorten multiple whitespace sequences
              '/<!--(.|\s)*?-->/' // Remove HTML comments
          ), array(
            '>',
            '<',
            '\\1',
            ''
          ), $PAGECACHE );

        }

        if ( LINOTYPE::$SETTINGS['cache']['enable'] ) {

          file_put_contents( $this->cachefile, $PAGECACHE );

        }

      }

      echo $PAGECACHE;

    //}

  }

  /*
   *
   * built_theme
   *
   */
  public function built_theme() {

    if ( current_user_can( 'linotype_admin' ) && is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'linotype_cache' ) {

      $build = new COMPOSER_build( array(
        "theme" => LINOTYPE::$THEME,
        "blocks" => LINOTYPE::$BLOCKS->get(),
      ));

    }

  }


  /*
   *
   * dequeue_assets_scripts
   *
   */
  public function dequeue_assets_scripts( $tag, $handle ) {

    if ( ! is_admin() && in_array( $handle, LINOTYPE::$SETTINGS['cache']['scripts_selected'] ) ) {

      $tag = '';

    }

    return $tag;

  }


  /*
   *
   * dequeue_assets_styles
   *
   */
  public function dequeue_assets_styles( $tag, $handle ) {

    if ( ! is_admin() && in_array( $handle, LINOTYPE::$SETTINGS['cache']['styles_selected'] ) ) {

      $tag = '';

    }

    return $tag;

  }


  /*
   *
   * remove_css_js_ver
   *
   */
  public function remove_css_js_ver( $src ) {

    if( strpos( $src, '?ver=' ) ) $src = remove_query_arg( 'ver', $src );

    return $src;

  }


  /*
   *
   * add_defer_attribute
   *
   */
  public function add_defer_attribute( $tag, $handle ) {

    if ( ! is_admin() ) {

     if ( LINOTYPE::$SETTINGS['cache']['add_jquery_defer_attribute'] && $handle === 'jquery-core' ) {

        return str_replace( ' src', ' defer src', $tag );

      } else if ( $handle !== 'jquery-core' ) {

        return str_replace( ' src', ' defer src', $tag );

      }

    }

    return $tag;

  }


  /*
   *
   * dequeue_jquery_migrate
   *
   */
  public function dequeue_jquery_migrate( $scripts ) {

    if ( ! is_admin() ) {

      $scripts->remove( 'jquery');
      $scripts->add( 'jquery', false, array( 'jquery-core' ), false, false );

    }

  }


  /*
  *
  * list_plugins_assets
  *
  */
  public function list_plugins_assets() {

    if ( ! is_admin() && LINOTYPE::$SETTINGS['cache']['minify_plugins'] ) {

      global $wp_scripts;

      foreach( $wp_scripts->done as $script ) :
        if ( substr( $wp_scripts->registered[$script]->handle, 0, 9 ) !== "LINOTYPE-" ) {

            $handle = $wp_scripts->registered[$script]->handle;

            $src = $wp_scripts->registered[$script]->src;
            if ( $src && substr( $src, 0, 4 ) !== "http" ) $src = get_bloginfo('url') . $src;

            if ( $src ) LINOTYPE::$SETTINGS['cache']['all_assets']['scripts'][$handle] = array( 'src' => $src, 'handle' => $handle );

        }
      endforeach;

      global $wp_styles;

      foreach( $wp_styles->done as $style ) :
        if ( substr( $wp_styles->registered[$style]->handle, 0, 9 ) !== "LINOTYPE-" ) {

          $handle = $wp_styles->registered[$style]->handle;

          $src = $wp_styles->registered[$style]->src;
          if ( $src && substr( $src, 0, 4 ) !== "http" ) $src = get_bloginfo('url') . $src;

          if ( $src ) LINOTYPE::$SETTINGS['cache']['all_assets']['styles'][$handle] = array( 'src' => $src, 'handle' => $handle );

        }
      endforeach;

      update_option('linotype_all_assets', LINOTYPE::$SETTINGS['cache']['all_assets'] );


    }



  }

}

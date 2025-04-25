<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*
* LINOTYPE_wp
*
*
**/
class LINOTYPE_wp {

  function __construct(){

    add_action( 'init', function() { 

      if( LINOTYPE::$SETTINGS['cache']['disable_emojis'] ) $this->disable_emojis();
      if( LINOTYPE::$SETTINGS['cache']['disable_embeds_code'] ) $this->disable_embeds_code();
      if( LINOTYPE::$SETTINGS['disable_wp_updates'] ) $this->disable_wp_updates();
      if( LINOTYPE::$SETTINGS['disable_wp_adminbar'] ) $this->disable_wp_adminbar();

      $this->disable_gutengerg();

    });

    //$this->frontend_login();

    //$this->force_login();

    add_action( 'admin_init', array( $this, 'disable_autosave' ) );
    
    add_filter( 'wp_revisions_to_keep', array( $this, 'control_revisions' ), 10, 2 );

  }

  
  
  public function disable_gutengerg() {

    add_action( 'wp_print_styles', function(){
      wp_dequeue_style( 'wp-block-library' );
    }, 100 );

  }


  public function control_revisions($num, $post) {

    $num = 5;
    return $num;

  }

  public function disable_autosave() {
    if ( ! defined( 'AUTOSAVE_INTERVAL' ) ) define('AUTOSAVE_INTERVAL', 86400);
    wp_deregister_script( 'autosave' );
  }

  public function force_login() {
    
    add_filter( 'init', function ( ) {
      
      if ( ! is_user_logged_in() && ! is_admin() && ! in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')) ) {
      
        auth_redirect();

        if  (current_user_can('activate_plugins')) wp_redirect('/wp-admin/index.php', 302);

      
      }

    });
  
  }

  public function frontend_login() {

    

    add_filter('login_url', 'your_login_url', 10, 2 );
    add_action('login_init', 'your_login_page');
    add_action('wp_login', 'your_login_redirect', 10, 2);

    add_filter( 'init', function ( ) {
      flush_rewrite_rules();
    } );


    add_filter( 'generate_rewrite_rules', function ( $wp_rewrite ) {
      $wp_rewrite->rules = array_merge(
          ['login/?$' => 'index.php?custom-login=1'],
          $wp_rewrite->rules
      );
    } );
    add_filter( 'query_vars', function( $query_vars ) {
        $query_vars[] = 'custom-login';
        return $query_vars;
    } );
    add_action( 'template_redirect', function() {
        $custom = intval( get_query_var( 'custom-login' ) );
        if ( $custom ) {
            //include ABSPATH . 'wp-login-custom.php';
            echo 'login';
            die;
        }
    } );
    
    // assuming that your new front end login url is "/login", use these:
    function your_login_url($login_url, $redirect) {
        // return home_url('/login/');
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 ); 
        exit();
    }

    function your_login_page() {
        // wp_redirect( home_url('/login/'), 302 );
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 ); 
        exit();
    }

    // if admin send them to the dashboard, otherwise leave them on the frontend
    function your_login_redirect($user_login, $user) {

      if  (current_user_can('activate_plugins')) {
              wp_redirect('/wp-admin/index.php', 302);
          
      }
      return; 
    }

  }



  /*
   *
   * disable_emojis
   *
   */
  public function disable_emojis() {


    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', function( $plugins ) {
      if ( is_array( $plugins ) ) {
      return array_diff( $plugins, array( 'wpemoji' ) );
      } else {
      return array();
      }
      } );
    add_filter( 'wp_resource_hints', function( $urls, $relation_type ) {
      if ( 'dns-prefetch' == $relation_type ) {
      /** This filter is documented in wp-includes/formatting.php */
      $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
      
      $urls = array_diff( $urls, array( $emoji_svg_url ) );
      }
      
      return $urls;
    
      }, 10, 2 );

  
 }


  /*
  *
  * disable_embeds_code
  *
  */
  public function disable_embeds_code() {
 


      // Remove the REST API endpoint.
      remove_action( 'rest_api_init', 'wp_oembed_register_route' );

      // Turn off oEmbed auto discovery.
      add_filter( 'embed_oembed_discover', '__return_false' );

      // Don't filter oEmbed results.
      remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

      // Remove oEmbed discovery links.
      remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

      // Remove oEmbed-specific JavaScript from the front-end and back-end.
      remove_action( 'wp_head', 'wp_oembed_add_host_js' );
      add_filter( 'tiny_mce_plugins', function($plugins) {
        return array_diff($plugins, array('wpembed'));
      } );

      // Remove all embeds rewrite rules.
      add_filter( 'rewrite_rules_array', function($rules) {
        foreach($rules as $rule => $rewrite) {
          if(false !== strpos($rewrite, 'embed=true')) {
            unset($rules[$rule]);
          }
        }
        return $rules;
      } );
    
      // Remove filter of the oEmbed result before any HTTP requests are made.
      remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
    

     
 }


 /*
  *
  * disable_wp_adminbar
  *
  */
  public function disable_wp_adminbar() {
    
    

      if( LINOTYPE::$SETTINGS['disable_wp_adminbar'] ) add_filter('show_admin_bar', '__return_false');
    
      
    
  }

 /*
  *
  * disable_wp_updates
  *
  */
  public function disable_wp_updates() {
  
    remove_action( 'init', 'wp_version_check' );

    function remove_core_updates() {
    
      add_filter('pre_option_update_core','__return_null');
      add_filter('pre_site_transient_update_core','__return_null');
    
    }

    add_action('after_setup_theme','remove_core_updates');

    remove_action('load-update-core.php','wp_update_plugins');
    add_filter('pre_site_transient_update_plugins','__return_null');

    remove_action('load-update-core.php','wp_update_themes');
    add_filter('pre_site_transient_update_themes','__return_null');

  }

}

$LINOTYPE_wp = new LINOTYPE_wp();

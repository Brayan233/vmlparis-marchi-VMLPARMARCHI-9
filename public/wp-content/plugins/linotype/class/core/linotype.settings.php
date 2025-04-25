<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*
* LINOTYPE_settings
*
*
**/
class LINOTYPE_settings {

  public function get() {
    
    $licence = get_option( 'linotype_licence', false );

    $device = new Mobile_Detect;
    $device_is = 'desktop';
    if ( $device->isMobile() ) $device_is = 'mobile';
    if ( $device->isTablet() ) $device_is = 'tablet';

    $SETTINGS = array(
      "theme" => array(
        "id" => get_option( 'linotype_theme', 'linotype_theme_blockstarter' ),
      ),
      'base_dir' => ABSPATH,
      'base_url' => get_bloginfo('url') . '/',
      'plugin_dir' => LINOTYPE_plugin::$plugin['dir'],
      'plugin_url' => LINOTYPE_plugin::$plugin['url'],
      "licence" => $licence,
      "engine" => get_option( 'linotype_engine', 'full' ),
      "cache" => array(
        'timestamp' => get_option( 'linotype_cahe_timestamp', false ),
        'enable' => filter_var( get_option( 'linotype_cache', false ), FILTER_VALIDATE_BOOLEAN ),
        'max_age' => 720,
        'exception' => get_option( 'linotype_cache_exception', false ),
        'lazyload' => filter_var( get_option( 'linotype_lazyload', false ), FILTER_VALIDATE_BOOLEAN ),
        'minify_html' => filter_var( get_option( 'linotype_minify_html', false ), FILTER_VALIDATE_BOOLEAN ),
        'minify_css' => filter_var( get_option( 'linotype_minify_css', false ), FILTER_VALIDATE_BOOLEAN ),
        'minify_js' => filter_var( get_option( 'linotype_minify_js', false ), FILTER_VALIDATE_BOOLEAN ),
        'minify_plugins' => filter_var( get_option( 'linotype_minify_plugins', false ), FILTER_VALIDATE_BOOLEAN ),
        'remove_css_js_version' => filter_var( get_option( 'linotype_remove_css_js_version', false ), FILTER_VALIDATE_BOOLEAN ),
        'add_jquery_defer_attribute' => filter_var( get_option( 'linotype_add_jquery_defer_attribute', false ), FILTER_VALIDATE_BOOLEAN ),
        'add_defer_attribute' => filter_var( get_option( 'linotype_add_defer_attribute', false ), FILTER_VALIDATE_BOOLEAN ),
        'dequeue_jquery_migrate' => filter_var( get_option( 'linotype_dequeue_jquery_migrate', false ), FILTER_VALIDATE_BOOLEAN ),
        'disable_emojis' => filter_var( get_option( 'linotype_disable_emojis', false ), FILTER_VALIDATE_BOOLEAN ),
        'disable_embeds_code' => filter_var( get_option( 'linotype_disable_embeds_code', false ), FILTER_VALIDATE_BOOLEAN ),
        'all_assets' => array( 'scripts' => array(), 'styles' => array() ),
        'scripts_selected' => array(),
        'styles_selected' => array(),
      ),
      'device' => $device_is,
      'editor_link' => LINOTYPE_plugin::$plugin['url'] . 'editor/index.php',
      "has_libraries" => false,
      "has_fields" => false,
      "has_templates" => false,
      "has_modules" => false,
      "has_themes" => false,
      "linotype_content_by_default" => get_option( 'linotype_content_by_default', false ),
      "linotype_content_post_types" => array(),
      'disable_wp_updates' => filter_var( get_option( 'linotype_disable_wp_updates', false ), FILTER_VALIDATE_BOOLEAN ),
      'disable_wp_adminbar' => filter_var( get_option( 'linotype_disable_wp_adminbar', false ), FILTER_VALIDATE_BOOLEAN ),
    ); 
    
    //init cache timestamp
    if ( $SETTINGS['cache']['timestamp'] == false ) {
      
      $SETTINGS['cache']['timestamp'] = time();
      update_option( 'linotype_cahe_timestamp', $SETTINGS['cache']['timestamp'] );
    
    }
    
    if ( get_option('linotype_cache_max_age') ) $SETTINGS['cache']['max_age'] = get_option('linotype_cache_max_age');

    //add existing registred asset
    if ( get_option('linotype_all_assets', false ) ) $SETTINGS['cache']['all_assets'] = get_option('linotype_all_assets', false );

    if ( get_option( 'linotype_minify_plugins_scripts', false ) ) {
      $SETTINGS['cache']['scripts_selected'] = get_option('linotype_minify_plugins_scripts', false );
      if ( $SETTINGS['cache']['scripts_selected'] ) $SETTINGS['cache']['scripts_selected'] = json_decode( stripslashes( $SETTINGS['cache']['scripts_selected'] ), true );
    }

    if ( get_option( 'linotype_minify_plugins_styles', false ) ) {
      $SETTINGS['cache']['styles_selected'] = get_option('linotype_minify_plugins_styles', false );
      if ( $SETTINGS['cache']['styles_selected'] ) $SETTINGS['cache']['styles_selected'] = json_decode( stripslashes( $SETTINGS['cache']['styles_selected'] ), true );
    }

    // check licence
    if ( $SETTINGS['licence'] ) {

      //enable libraries
      $SETTINGS['has_libraries'] = true;

      //enable fields
      $SETTINGS['has_fields'] = true;

      //check engine type to enable templates
      if ( $SETTINGS['engine'] == 'full' || $SETTINGS['engine'] == 'templates' ) {

        $SETTINGS['has_templates'] = true;
        $SETTINGS['has_modules']   = true;
      
      }

      //check engine type to enable themes
      if ( $SETTINGS['engine'] == 'full' ) {
      
        $SETTINGS['has_themes'] = true;
      
      }

    }

    //get where block content load by default
    $linotype_content_by_default = get_option( 'linotype_content_by_default', false );
    if ( isset( $linotype_content_post_types ) && $linotype_content_post_types ) $SETTINGS['linotype_content_post_types'] = $linotype_content_post_types;

    //get blocks location
    $GET_UPLOAD_DIR = wp_upload_dir( null, false );
    $UPLOAD_DIR['path'] = $GET_UPLOAD_DIR['basedir']; 
    $UPLOAD_DIR['url'] = $GET_UPLOAD_DIR['baseurl']; 

    $SETTINGS['url'] = WP_CONTENT_URL . '/linotype';
    $SETTINGS['dir'] = WP_CONTENT_DIR . '/linotype';
    $SETTINGS['url_cache'] = $UPLOAD_DIR['url'] . '/linotype-cache';
    $SETTINGS['dir_cache'] = $UPLOAD_DIR['path'] . '/linotype-cache';

    //create directory
    if ( ! file_exists( $SETTINGS['dir_cache'] ) ) wp_mkdir_p( $SETTINGS['dir_cache'] );
    if ( ! file_exists( $SETTINGS['dir_cache'] . '/public/desktop' ) ) wp_mkdir_p( $SETTINGS['dir_cache'] . '/public/desktop' );
    if ( ! file_exists( $SETTINGS['dir_cache'] . '/public/mobile' ) ) wp_mkdir_p( $SETTINGS['dir_cache'] . '/public/mobile' );
    if ( ! file_exists( $SETTINGS['dir_cache'] . '/public/api' ) ) wp_mkdir_p( $SETTINGS['dir_cache'] . '/public/api' );
    if ( ! file_exists( $SETTINGS['dir_cache'] . '/public/assets/js' ) ) wp_mkdir_p( $SETTINGS['dir_cache'] . '/public/assets/js' );
    if ( ! file_exists( $SETTINGS['dir_cache'] . '/public/assets/css' ) ) wp_mkdir_p( $SETTINGS['dir_cache'] . '/public/assets/css' );

    if ( ! file_exists( $SETTINGS['dir'] ) ) wp_mkdir_p( $SETTINGS['dir'] );

    return $SETTINGS;

  }

  

}

<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
Plugin Name: LINOTYPE 
domain: linotype
Plugin URI: https://linotype.io
Description: THE SHAPEABLE BUILDER WILL FIT YOUR WORKFLOW
Version: 1.5.8
Author: Yannick Armspach
Author URI: https://handypress.io
Author Email: Yannick Armspach <ask@linotype.io>
*/

if ( ! class_exists( 'LINOTYPE' ) ) {

global $LINOTYPE;

define('LINOTYPE_INFOS', false );
define('LINOTYPE_DEBUG', true );

include 'vendor/autoload.php';

include 'class/core/linotype.settings.php';

include 'class/core/linotype.tools.php';

include 'lib/lessphp/lessc.inc.php';
include 'lib/scssphp/scss.inc.php';

include 'admin/linoadmin.class.php';

include 'class/linotype.class.php';

include 'class/core/linotype.wp.php';
include 'class/core/linotype.custompost.php';
include 'class/core/linotype.admin.php';
include 'class/core/linotype.backend.php';
include 'class/core/linotype.caps.php';
include 'class/core/linotype.file.php';
include 'class/core/linotype.helpers.php';
include 'class/core/linotype.builder.php';
include 'class/core/linotype.fields.php';
include 'class/core/linotype.api.php';
include 'class/core/linotype.cache.php';
include 'class/core/linotype.sync.php';
include 'class/core/linotype.current.php';

include 'class/components/blocks.class.php';
include 'class/components/templates.class.php';
include 'class/components/modules.class.php';
include 'class/components/themes.class.php';
include 'class/components/fields.class.php';
include 'class/components/libraries.class.php';

include 'class/functions/globals.php';
include 'class/functions/helpers.php';

/*
*
* LINOTYPE_plugin
*
*/
class LINOTYPE_plugin  {

  static $CLASS;

  static $admin;

  static $plugin;

  function __construct() {
  
    //init admin
    self::$admin = new LINOADMIN();

    //init plugin
    $plugin = new handypress_plugin( array(
      'name'    => 'blocks',
      'version'   => '1.0.0',
      'basename'  => plugin_basename( __FILE__ ),
      'dir'     => plugin_dir_path( __FILE__ ),
      'url'     => plugin_dir_url( __FILE__ ),
      'domain'  => 'blocks',
      'activate'  => null,
      'update'  => null,
      'links'   => array( 
        array('title' => __('Settings','blocks'), 'url' => '/wp-admin/admin.php?page=linotype_settings' ),
      ),
    ) );

    //check require config
    $check_system_config = new handypress_system_config();
    $this->check_sys = $check_system_config->check( array(
      "php" => "5.3",
      "max_execution_time" => "30",
      "memory_limit" => "128",
    ) );

    //execute if right config
    handypress_notices::add( array(
      "type" => 'error', 
      "message" => '<strong>LINOTYPE</strong> - Check the server requirement : upload_max_filesize=100M memory_limit=512M max_execution_time=30',
      // "button" => array(
      //     array( 'Check config', admin_url( '/admin.php?page=blocks' ) )
      //   ),
      "force_hide" => false,
      "if" => array( $this->check_sys['status'], "!==", true ),
    ));

    //get plugin params
    self::$plugin = $plugin->get();
    
    //load plugin
    LINOTYPE_plugin::$CLASS = new LINOTYPE();

    //load admin
    add_action( 'init', array( $this, 'load_admin' ), 9999 );

  }

  public function load_admin(){

    if ( is_admin() ) {

      wp_enqueue_style( 'linotype-font', self::$plugin['url'] . '/assets/fonts/blockicons/style.css', false, false, 'screen' );
      
      $LINOTYPE_admin = new LINOTYPE_admin();

      include self::$plugin['dir'] . 'settings/admin.linotype.php';
     
      include self::$plugin['dir'] . 'settings/admin.backend.php';

      include self::$plugin['dir'] . 'settings/admin.settings.php';

      include self::$plugin['dir'] . 'settings/admin.licence.php';

      include self::$plugin['dir'] . 'settings/templates.selector.php';
      
      include self::$plugin['dir'] . 'settings/metas.overwrite.php';

      include self::$plugin['dir'] . 'settings/options.overwrite.php';

      include self::$plugin['dir'] . 'settings/options.traductions.php';

      self::$admin->run();

    }

  }

}

$LINOTYPE_plugin = new LINOTYPE_plugin();

}
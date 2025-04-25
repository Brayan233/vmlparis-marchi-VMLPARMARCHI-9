<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*
* LINOTYPE_helpers
*
* define('LINOTYPE_DEBUG', true );
*
**/

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class LINODUMP
{
    private static $handler;

    public static function dump($var)
    {
        if (null === self::$handler) {
            $cloner = new VarCloner();

            if (isset($_SERVER['VAR_DUMPER_FORMAT'])) {
                $dumper = 'html' === $_SERVER['VAR_DUMPER_FORMAT'] ? new HtmlDumper() : new CliDumper();
            } else {
                $dumper = \in_array(\PHP_SAPI, ['cli', 'phpdbg']) ? new CliDumper() : new HtmlDumper();
            }

			      $dumper->setTheme('light');

            self::$handler = function ($var) use ($cloner, $dumper) {
                //$dumper->dump($cloner->cloneVar($var));
				$dumper->dump(
					$cloner->cloneVar($var),
					function ($line, $depth) use (&$output) {
						// A negative depth means "end of dump"
						if ($depth >= 0) {
							// Adds a two spaces indentation to the line
							echo str_repeat('  ', $depth).$line."<br/>";
						}
					}
				);
            };
        }

        return (self::$handler)($var);
    }

    public static function setHandler(callable $callable = null)
    {
        $prevHandler = self::$handler;
        self::$handler = $callable;

        return $prevHandler;
    }
}

class LINOTYPE_dump {

  public $count = 1;

  public $kill = false;

  public $panel = "";
  public $panel_top = "";
  public $panel_bottom = "";

  function __construct(){

    if ( LINOTYPE_dump::saveIsAdmin() ) {

      // add_action('wp', array( $this, 'create_log_meta' ), 9999999999999999999999999999 );

      add_action('admin_enqueue_scripts', array( $this, 'get_panel_asset') );
      add_action('wp_head', array( $this, 'get_panel_asset') );

      add_action('wp_head', array( $this, 'get_panel') );
      add_action('admin_head', array( $this, 'get_panel') );

      add_action('wp_head', array( $this, 'get_panel_bottom') );
      add_action('admin_head', array( $this, 'get_panel_bottom') );

      add_action('wp_head', array( $this, 'get_panel_top') );
      add_action('admin_head', array( $this, 'get_panel_top') );

      add_action('wp_footer', array( $this, 'print_panel'), 9999999999999999999999999999 );
      add_action('admin_footer', array( $this, 'print_panel'), 9999999999999999999999999999 );

      $this->check_server_cache();
      
    }

  }

  public function get_panel_asset(){

    if ( get_option( 'linotype_helper', false ) && current_user_can( 'linotype_admin' ) ) {
      wp_enqueue_style( 'dashicons' );
      wp_enqueue_style( 'LINOTYPE', LINOTYPE_plugin::$plugin['url'] . 'assets/css/linotype.css', false, false, 'screen' );
      wp_enqueue_script('LINOTYPE', LINOTYPE_plugin::$plugin['url'] . 'assets/js/linotype.js', array('jquery'), false );
    }

  }

  public function log( $id, $data, $state ){

    if ( $id == 'kill' ) $this->kill = true;

    $this->state = $state;

    $this->log[] = array( '#' . $this->count . ' <i> ' . $id . '</i>', $data );

    $this->count++;

  }

  public function logPrint(){

    if ( $this->kill == false && isset($this->log) ) {

      echo '<h4>linodump()</h4>';

      foreach ($this->log as $key => $log) {

        echo '<div class="dump-item">';

          echo '<h5>'. $log[0].'</h5>';
              
          LINODUMP::dump($log[1]);
          
        echo '</div>';
      
      }

    }

  }

  public function create_log_meta(){

    global $post;

    $data = get_post_meta( $post->ID,'',true );
    $data['_wp_page_template'] = get_post_meta( $post->ID, '_wp_page_template', true );

    linolog( '#' . $this->count . ' <i> post_meta id: '. $post->ID . '</i>', $data );
    
    $this->count++;

    
  }

  public function check_server_cache() {

    //disable cache
    // header("Cache-Control: max-age=1");
    
    // if ( is_callable( 'opcache_reset' ) ) {

    //   opcache_reset();

    // }

  }

  /**
  *
  * get_panel
  *
  **/
  public function print_panel() {

    if ( get_option( 'linotype_helper', false ) && current_user_can( 'linotype_admin' ) ) {

      echo '<div class="linotype-panel">';

        echo '<div class="linotype-panel-inner">';

          echo $this->panel_top;

          echo '<div class="linotype-panel-scroll"><div class="linotype-panel-scroll-content">';

            echo $this->panel;

            echo $this->logPrint();

          echo '</div></div>';

          echo $this->panel_bottom;

        echo '</div>';

      echo '</div>';

    }

  }

  public function get_panel_top() {

    $html = "";

    $html .= '<div class="linotype-panel-actions top"><ul>';

      
      $html .= '<li class="linotype-panel-action-fix"><span class="dashicons dashicons-editor-code"></span></li>';
       
      $html .= '<li class="linotype-panel-action-ccc"><a class="panel-button linotype-panel-dashboard" href="' . LINOTYPE::$SETTINGS['editor_link'] . '">LINOTYPE</a></li>';
        
      if ( is_admin() ) {
  
      } else {
        
        $html .= '<li class="linotype-panel-action-ccc"><a class="panel-button" href="' . get_edit_post_link() . '">Edit content</a></li>';

        if ( LINOTYPE::$THEME['current']['template'] ) $html .= '<li class="linotype-panel-action-ccc"><a class="panel-button" href="' . LINOTYPE::$THEME['current']['template']['editor_link'] . '">Edit Template</a></li>';
      
      }

    $html .= '</ul></div>';
    
    $this->panel_top = $html;

  }

  static function saveIsAdmin() {

    if( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return false;

    if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'LINOTYPE_composer_edit' ) return false;

    return true;

  }

  public function get_panel_bottom() {
    
    $html = "";

    $html .= '<div class="linotype-panel-actions bottom"><ul>';

      if ( is_admin() ) {
        
      } else {

        $html .= '<li class="linotype-panel-action-debug">debug</li>';

      }

    $html .= '</ul></div>';
    
    $this->panel_bottom = $html;

  }
//if ( get_option( 'linotype_helper' ) && current_user_can( 'linotype_admin' ) ) {
  public function get_panel() {
    
    $html = "";

    if ( is_admin() ) {
          

    } else {

      if ( isset( LINOTYPE::$THEME['current'] ) ) {

        $html .= '<h5>Location:</h5>';
        $html .= '<code>' . LINOTYPE::$THEME['current']['type'][0] . ' > ' . LINOTYPE::$THEME['current']['type'][1] . '</code>';

        if ( LINOTYPE::$THEME['current']['template'] ) {

          $html .= '<h5>Template Name:</h5>';
          $html .= '<code>' . LINOTYPE::$THEME['current']['template']['title'] . '</code>';

          if ( get_the_ID() && get_post_meta( get_the_ID(), '_linotype_template', true ) ) {

            $html .= '<h5>Template ID:</h5>';
            $html .= '<code>' . get_post_meta( get_the_ID(), '_linotype_template', true ) . ' <span>post overwrite<span></code>';

          } else {

            $html .= '<h5>Template ID:</h5>';
            $html .= '<code>' . LINOTYPE::$THEME['current']['template']['id'] . ' <span>theme map<span></code>';

          }

        } else {

          $html .= '<h5>Template ID:</h5>';
          $html .= '<p>No Template ( Blank )</p>';

        }

      }

    }

    $html .= '<br/><br/>';

    $this->panel = $html;

  }


}

function linolog( $id = null, $data = null, $state = "open" ){

  if (LINOTYPE_dump::saveIsAdmin()) {
       
      
    if ( defined( 'LINOTYPE_DEBUG' ) && LINOTYPE_DEBUG == true  ) {

      global $LINOTYPE_dump;

      if ( $id && $data === null ) {
        
        $data = $id;
        $id = '';

      } else {

        $id = '' . $id;

      }

      if ( $data && is_callable( $data ) ) $data = $data();

      $LINOTYPE_dump->log( $id, $data, $state );

      //$LINOTYPE_dump_count++;

    }

  }

}

function linodump( $data = null ) {

  if (LINOTYPE_dump::saveIsAdmin()) {
       

    if ( defined( 'LINOTYPE_DEBUG' ) && LINOTYPE_DEBUG == true  ) {

      LINODUMP::dump( $data );

    }
  }

}

global $LINOTYPE_dump;

$LINOTYPE_dump = new LINOTYPE_dump();

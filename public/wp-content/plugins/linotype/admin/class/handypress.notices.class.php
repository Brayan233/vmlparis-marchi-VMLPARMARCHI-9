<?php

/**
*
* HANDYPRESS - admin_notices
*
* 

handypress_notices::add( array(
  "type" => 'default', 
  "message" => 'notice message',
  "button" => array( 
      array( 'link1', 'http://', 'button-primary' ), 
      array( 'link2', 'http://', 'button-secondary' ), 
      array( 'google.com', 'http://google.com', 'button-tiny', '_blank' ), 
   ),
   // "if" => array( "value1", "!==", "value1" ),
   // "dismiss" => true, 
   "force_hide" => false,
));

**/

/**
 * handypress_notices
 */
if ( ! class_exists('handypress_notices') ) {
  
class handypress_notices {

  public $id;

  public static $admin_notices = array();

  function __construct() {

    //add notice
    add_action( 'admin_notices', array( $this, "create" ) );
      
  }

  public static function add( $notices, $type = 'info' ) {

    if ( is_array( $notices ) ) {
      
      handypress_notices::$admin_notices[] = $notices;

    } else {
      
      handypress_notices::$admin_notices[] = array( "message" => $notices, "type" => $type );

    }

  }

  public function create() {


    $html = '';

    if ( handypress_notices::$admin_notices ) { 

      foreach ( handypress_notices::$admin_notices as $notice_key => $notice ) {

        $display_notice = true;
        if ( isset( $notice['if'] ) && $this->compare_meta( $notice['if'] ) !== true ) $display_notice = false;

        if ( $display_notice === true ) {

          switch ( $notice['type'] ) {

            case 'success':
            case 'updated':
            case 'update':
              $notice['type'] = 'notice notice-success';
            break;

            case 'error':
              $notice['type'] = 'notice notice-error';
            break;
            
            case 'alert':
            case 'warning':
              $notice['type'] = 'notice notice-warning';
            break;

            case 'info':
            case 'message':
            case 'default':
            default:
              $notice['type'] = 'notice notice-info';
            break;

          }

          $html .= '<div id="message" class="'. $notice['type'] . '">';

            $html .= '<p>'. $notice['message'] . '</p>';

            if ( isset( $notice['dismiss'] ) && $notice['dismiss'] ) $html .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Ne pas tenir compte de ce message.</span></button>';
            
            if ( isset( $notice['button'] ) ) {

              $html .= '<p>';

                foreach ( $notice['button'] as $button_key => $button ) {

                  if ( ! isset( $button[2] ) ) $button[2] = 'button-secondary';

                  if ( ! isset( $button[3] ) ) $button[3] = '';

                  $html .= '<a href="'.$button[1].'" class="button '.$button[2].'" target="'.$button[3].'">'.$button[0].'</a> ';
                  
                }

              $html .= '</p>';

            }

          $html .= '</div>';

        }

      }

    }

    echo $html;

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
  public function compare_meta( $if, $as_string = false ) {

    if ( $as_string ) {
      $if[0] = '' . $if[0];
      $if[2] = '' . $if[2];
    }

    //default false
    $result = false;

    if ( $if[2] ) {

      //exe comparator
      switch ( $if[1] ) {
        
        case '==':  if ( $if[0] == $if[2] )   $result = true; break;
        case '===': if ( $if[0] === $if[2] )  $result = true; break;
        case '!=':  if ( $if[0] != $if[2] )   $result = true; break;
        case '<>':  if ( $if[0] <> $if[2] )   $result = true; break;
        case '!==': if ( $if[0] !== $if[2] )  $result = true; break;
        case '<':   if ( $if[0] < $if[2] )    $result = true; break;
        case '>':   if ( $if[0] > $if[2] )    $result = true; break;
        case '<=':  if ( $if[0] <= $if[2] )   $result = true; break;
        case '>=':  if ( $if[0] >= $if[2] )   $result = true; break;
       
      }

    } else {

      if ( $if[1] ) $result = true; 

    }

    return $result;

  }

}

$handypress_notices = new handypress_notices();
}

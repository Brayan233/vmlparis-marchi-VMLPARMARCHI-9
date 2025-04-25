<?php

/**
*
* handypress_wp_admin_notices
*
**/
if ( ! class_exists('handypress_system_config') ) {
  
class handypress_system_config {

  static $DOMAIN = 'HANDYPRESS_CHECK_SYSTEM_CONFIG';

  static function check( $check_this ) {
  
    $output = array( "status" => true, "infos" => array() );

    if ( $check_this ){
      foreach ( $check_this as $check_type => $check_value ) {
        
        $check = null;

        switch ( $check_type ) {

          case 'php':
            
            $check = self::php( $check_value );

            if ( $check['success'] == false ) {

              $output['infos']['php'] = array( "title" => "PHP", "check" => false, "message" => __('<code>PHP '.$check['current'].'</code> – Require <code>' . $check['requiere'] . '</code> minimum.', self::$DOMAIN ) ); 

              $output['status'] = false;

            } else {

              $output['infos']['php'] = array( "title" => "PHP", "check" => true, "message" => __('<code>PHP '.$check['current'].'</code>', self::$DOMAIN ) ); 

            }

          break;

          case 'max_execution_time':
            
            $check = self::php_ini('max_execution_time', $check_value );

            if ( $check['success'] == false ) {

              $output['infos']['max_execution_time'] = array( "title" => "Max Execution Time", "check" => false, "message" => __('<code>'.$check['ini'].' = '.$check['current'].';</code> – Require <code>' . $check['requiere'] . ';</code> minimum.', self::$DOMAIN ) ); 

              $output['status'] = false;

            } else {

              $output['infos']['max_execution_time'] = array( "title" => "Max Execution Time", "check" => true, "message" => __('<code>'.$check['ini'].' = '.$check['current'].';</code>', self::$DOMAIN ) ); 

            }

          break;

          case 'memory_limit':
            
            $check = self::php_ini('memory_limit', $check_value );

            if ( $check['success'] == false ) {

              $output['infos']['memory_limit'] = array( "title" => "Memory Limit", "check" => false, "message" => __('<code>'.$check['ini'].' = '.$check['current'].';</code> – Require <code>' . $check['requiere'] . ';</code> minimum.', self::$DOMAIN ) ); 

              $output['status'] = false;

            } else {

              $output['infos']['memory_limit'] = array( "title" => "Memory Limit", "check" => true, "message" => __('<code>'.$check['ini'].' = '.$check['current'].';</code>', self::$DOMAIN ) ); 

            }

          break;

          case 'ghostscript':
            
            $check = self::ghostscript( $check_value );

            if ( $check['success'] == false ) {

              $output['infos']['ghostscript'] = array( "title" => "Ghostscript", "check" => false, "message" => __('<code>GhostScript '.$check['current'].'</code> – Require <code>' . $check['requiere'] . '</code> minimum.', self::$DOMAIN ) ); 

              $output['status'] = false;

            } else {

              $output['infos']['ghostscript'] = array( "title" => "Ghostscript", "check" => true, "message" => __('<code>GhostScript '.$check['current'].'</code>', self::$DOMAIN ) ); 

            }

          break;

          case 'xpdf':
            
            $check = self::xpdf( $check_value );

            if ( $check['success'] == false ) {

              $output['infos']['xpdf'] = array( "title" => "xPDF", "check" => false, "message" => __('<code>xPDF '.$check['current'].'</code> – Require <code>' . $check['requiere'] . '</code> minimum.', self::$DOMAIN ) ); 

              $output['status'] = false;

            } else {

              $output['infos']['xpdf'] = array( "title" => "xPDF", "check" => true, "message" => __('<code>xPDF '.$check['current'].'</code>', self::$DOMAIN ) ); 

            }

          break;

          case 'pdftk':
            
            $check = self::pdftk( $check_value );

            if ( $check['success'] == false ) {

              $output['infos']['pdftk'] = array( "title" => "PDFtk", "check" => false, "message" => __('<code>PDFtk '.$check['current'].'</code> – Require <code>' . $check['requiere'] . '</code> minimum.', self::$DOMAIN ) ); 

              $output['status'] = false;

            } else {

              $output['infos']['pdftk'] = array( "title" => "PDFtk", "check" => true, "message" => __('<code>PDFtk '.$check['current'].'</code>', self::$DOMAIN ) ); 

            }

          break;

          case 'imagemagick':
            
            $check = self::imagemagick( $check_value );

            if ( $check['success'] == false ) {

              $output['infos']['imagemagick'] = array( "title" => "ImageMagick", "check" => false, "message" => __('<code>ImageMagick '.$check['current'].'</code> – Require <code>' . $check['requiere'] . '</code> minimum.', self::$DOMAIN ) ); 

              $output['status'] = false;

            } else {

              $output['infos']['imagemagick'] = array( "title" => "ImageMagick", "check" => true, "message" => __('<code>ImageMagick '.$check['current'].'</code>', self::$DOMAIN ) ); 

            }

          break;

          case 'curl':
            
            $check = self::curl( $check_value );

            if ( $check['success'] == false ) {

              $output['infos']['curl'] = array( "title" => "cURL", "check" => false, "message" => __('<code>cURL '.$check['current'].'</code> – Require <code>' . $check['requiere'] . '</code> minimum.', self::$DOMAIN ) ); 

              $output['status'] = false;

            } else {

              $output['infos']['curl'] = array( "title" => "cURL", "check" => true, "message" => __('<code>cURL '.$check['current'].'</code>', self::$DOMAIN ) ); 

            }

          break;
        
        }

      }
    }

    return $output;

  }

  /**
   * Check php version
   * @param  string $requiere version number in string
   * @return array            return an array with a success boolean, current value and requiere value
   */
  static function php( $requiere ){

    $check = array();
    
    preg_match( '/([0-9]+\.[0-9]+\.[0-9]+)/', PHP_VERSION, $current );

    $check['current'] = $current[1];
    $check['requiere'] = $requiere;

    if( $check['current'] == $check['requiere'] || version_compare( $check['current'], $check['requiere'] ) >= 0 ) {
      
      $check['success'] = true;

    } else {

      $check['success'] = false;

    }

    return $check;

  }

  /**
   * Check php ini value of php
   * @param  string $requiere version number in string
   * @return array            return an array with a success boolean, current value and requiere value
   */
  static function php_ini( $ini, $requiere ){

    $check = array();

    $check['ini'] = $ini;
    $check['current'] = ini_get( $ini );

    if ( strpos( $check['current'], 'G' ) !== false ) $check['current'] = intval( $check['current'] ) * 1000;
    
    $check['requiere'] = $requiere;

    if( intval( $check['current'] ) == -1 || intval( $check['current'] ) >= intval( $check['requiere'] ) ) {
      
      $check['success'] = true;

    } else {

      $check['success'] = false;

    }

    return $check;

  }

  /**
   * Check ghostscript version
   * @param  string $requiere version number in string
   * @return array            return an array with a success boolean, current value and requiere value
   */
  static function ghostscript( $requiere ){

    $check = array();

    $check['current'] = shell_exec("gs --version");
    $check['requiere'] = $requiere;

    if( $check['current'] == $check['requiere'] || version_compare( $check['current'], $check['requiere'] ) >= 0 ) {
      
      $check['success'] = true;

    } else {

      $check['success'] = false;

    }

    return $check;

  }

  /**
   * Check xpdf version
   * @param  string $requiere version number in string
   * @return array            return an array with a success boolean, current value and requiere value
   */
  static function xpdf( $requiere ){

    $check = array();

    exec("which xpdf", $out );
    
    $check['requiere'] = $requiere;

    if( isset( $out[0] ) && $out[0] != "" ) {
      
      $check['current'] = $check['requiere'];

      if( $check['current'] == $check['requiere'] || version_compare( $check['current'], $check['requiere'] ) >= 0 ) {
        
        $check['success'] = true;

      } else {

        $check['success'] = false;

      }

    } else {

      $check['current'] = 'not found';
      $check['success'] = false;

    }

    return $check;

  }

  /**
   * Check pdftk version
   * @param  string $requiere version number in string
   * @return array            return an array with a success boolean, current value and requiere value
   */
  static function pdftk( $requiere ){

    $check = array();

    exec("which pdftk", $out );
    
    $check['requiere'] = $requiere;

    if( isset( $out[0] ) && $out[0] != "" ) {
      
      $check['current'] = $check['requiere'];

      if( $check['current'] == $check['requiere'] || version_compare( $check['current'], $check['requiere'] ) >= 0 ) {
        
        $check['success'] = true;

      } else {

        $check['success'] = false;

      }

    } else {

      $check['current'] = 'not found';
      $check['success'] = false;

    }

    return $check;

  }

  /**
   * Check imagemagick version
   * @param  string $requiere version number in string
   * @return array            return an array with a success boolean, current value and requiere value
   */
  static function imagemagick( $requiere ){

    $check = array();

    exec("convert -version", $out, $rcode);

    $check['requiere'] = $requiere;

    if( $out ) {
      
      $out = implode( ' ', $out );
      preg_match('/([0-9]+\.[0-9]+\.[0-9]+)/', $out, $current);

      if ( isset( $current[1] ) ){
        $check['current'] = $current[1];
      } else {
        $check['current'] = null;
      }

      if( $check['current'] == $check['requiere'] || version_compare( $check['current'], $check['requiere'] ) >= 0 ) {
        
        $check['success'] = true;

      } else {

        $check['success'] = false;

      }

    } else {

      $check['current'] = 'not found';
      $check['success'] = false;

    }

    return $check;

  }

  /**
   * Check curl version
   * @param  string $requiere version number in string
   * @return array            return an array with a success boolean, current value and requiere value
   */
  static function curl( $requiere ){

    $check = array();
    
    if ( function_exists('curl_version') ) {

      $current = curl_version();

      $check['current'] = $current['version'];
      $check['requiere'] = $requiere;

      if( $check['current'] == $check['requiere'] || version_compare( $check['current'], $check['requiere'] ) >= 0 ) {
        
        $check['success'] = true;

      } else {

        $check['success'] = false;

      }

    } else {

      $check['success'] = false;

    }

    return $check;

  }

}

}

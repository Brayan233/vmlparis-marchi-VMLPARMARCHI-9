<?php

/**
*
* handypress_file
*
**/
if ( ! class_exists('handypress_file') ) {
  
class handypress_file {


  public $dir;

  function __construct( $dir ) {

    $this->dir = $dir;

  }

  public function put( $filename, $compression = false, $content = fasle ) {

    $file = $this->dir . $filename;

    if ( $content ) {

      switch ( $compression ) {
        
        case 'gz':
          $content = gzdeflate( $content, 9 );
        break;

        case 'gz64':
          $content = base64_encode( gzdeflate( $content, 9 ) );
        break;
        
        case 'base64':
          $content = base64_encode( $content );
        break;

        case 'json':
          $content = json_encode( $content );
        break;

        case 'json_pretty':
          $content = json_encode( $content, JSON_PRETTY_PRINT );
        break;
      
      }

      file_put_contents( $file, $content );
      
      return true;

    } else {

      return false;

    }

  }

  public function get( $filename, $compression = false ) {

    $file = $this->dir . $filename;

    if ( file_exists( $file ) ) {

      $content = file_get_contents( $file );

      switch ( $compression ) {

        case 'gz':
          $content = gzinflate( $content );
        break;

        case 'gz64':
          $content = gzinflate( base64_decode(  $content ) );
        break;

        case 'base64':
          $content = base64_decode( $content );
        break;

        case 'json':
        case 'json_pretty':
          $content = json_decode( $content, true );
        break;
      
      }

      return $content;     
    
    } else {

      return false; 

    }

  }

  public function delete( $filename ) {

    $file = $this->dir . $filename;

    if ( file_exists( $file ) ) {

      unlink( $file );

      return true;
    
    } else {

      return false; 

    }

  }


}
}

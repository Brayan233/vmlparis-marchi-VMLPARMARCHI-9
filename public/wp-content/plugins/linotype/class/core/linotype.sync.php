<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
/*
 *
 * LINOTYPE_sync
 *
 */
class LINOTYPE_sync {

  static $ENABLE;

  static $DATA;

  /*
   *
   * __construct
   *
   */
  function __construct( $AUTH = null, $AUTHOR = null, $REPO = null, $SETTINGS = null ) {

    $this->AUTH = $AUTH;
    $this->AUTHOR = $AUTHOR;
    $this->REPO   = $REPO;
    $this->SETTINGS   = $SETTINGS;

    self::$ENABLE = false;

    try {

      $this->GITHUB = new \Github\Client();
      $this->GITHUB->authenticate($this->AUTH, null, \Github\Client::AUTH_HTTP_TOKEN );
      
      $this->ROOT = '';
      $this->BRANCH = 'refs/heads/master';

      $this->LIST = $this->GITHUB->api('repo')->contents()->show($this->AUTHOR, $this->REPO, $this->ROOT, $this->BRANCH );
      
      //_HANDYLOG( $this->LIST );

      self::$ENABLE = true;

    } catch (Exception $e) {

      self::$ENABLE = false;

    }

    if ( $this->AUTH && self::$ENABLE == false ) {

      handypress_notices::add( array(
        "type" => 'error', 
        "message" => __('<strong>Github</strong> - Sync Auth - ' .  $e->getMessage() ),
        //"button" => array( array( 'Check settings', admin_url( '/admin.php?page=linotype_settings' ) ) ),
        "force_hide" => false,
      ));

    }

  }

  public function list_repo( $PATH = '' ) {
    
    try {

      $REPO = $this->GITHUB->api('repo')->contents()->show($this->AUTHOR, $this->REPO, $PATH, $this->BRANCH );
    
    } catch (Exception $e) {

      $REPO = array();

    }
    
    return $REPO;

  }

  public function init( $TYPE, $FILE = null ) {
    
    if ( self::$ENABLE && file_exists( $FILE ) ) {

      $content = file_get_contents( $FILE );
      if ( $content ) self::$DATA[ $TYPE ] = json_decode( $content, true );

    }

    //_HANDYLOG( self::$DATA );

  }

  static function get_status( $TYPE, $ID ) {
    
    if ( self::$ENABLE && isset( self::$DATA[ $TYPE ][ $ID ][ 'status' ] ) && self::$DATA[ $TYPE ][ $ID ][ 'status' ] ) {
      
      return self::$DATA[ $TYPE ][ $ID ][ 'status' ];

    } else {

      return 'init';

    }

  }

  static function data( $TYPE = null, $ID = null, $DATA_ID = null ) {
    
    $return = array();

    if ( $TYPE && $ID && $DATA_ID && isset( self::$DATA[ $TYPE ][ $ID ][ $DATA_ID ] ) ) {
      
      $return = self::$DATA[ $TYPE ][ $ID ][ $DATA_ID ];

    } else if ( $TYPE && $ID && isset( self::$DATA[ $TYPE ][ $ID ] ) ) {
    
      $return = self::$DATA[ $TYPE ][ $ID ];
    
    } else if ( $TYPE && isset( self::$DATA[ $TYPE ] ) ) {
    
      $return = self::$DATA[ $TYPE ];
    
    } else {

      $return = self::$DATA;

    }

    return $return;

  }

  
  public function get_commits( $PATH ) {
    
    $COMMITS = array();

    try {

      $DATA = $this->GITHUB->api('repo')->commits()->all( $this->AUTHOR, $this->REPO, array('sha' => 'master', 'path' => $PATH ) );
      
      if ( $DATA ) {
        foreach ( $DATA as $commit_key => $commit ) {

          $COMMITS[ $commit_key ] = array( 
            'id' => $commit_key,
            'date' => $commit['commit']['author']['date'],
            'path' => $PATH,
          );

        }
      }

    } catch ( Exception $e ) {

      //wp_die( 'Error', 'get_commits', $e );

    }

    return $COMMITS;

  }

  public function fetch( $TYPE = null, $ITEMS = null, $ID = null, $UPDATE = true ) {
    
    try {
      
      if ( $ID !== null ) {

        $fileExists = $this->GITHUB->api('repo')->contents()->exists( $this->AUTHOR, $this->REPO, $ITEMS[ $ID ]['path'] . '/config.json', 'master' );
          
          if ( $fileExists ) {

            $commits = $this->GITHUB->api('repo')->commits()->all( $this->AUTHOR, $this->REPO, array('sha' => 'master', 'path' => $ITEMS[ $ID ]['path'] ) );
              
            $statut = self::get_sync_status( $ITEMS[ $ID ]['update'], $commits[0]['commit']['author']['date'] );
            
            self::$DATA[ $TYPE ][ $ID ] = array( 
              'status' => $statut, 
              'date' => $commits[0]['commit']['author']['date'],
              'commit' => $commits[0]['sha'],
            );
            
          } else {

            self::$DATA[ $TYPE ][ $ID ] = array( 
              'status' => 'init', 
              'date' => null,
              'commit' => null,
            );

          }

      } else {

        foreach ( $ITEMS as $ITEM_KEY => $ITEM ) {
          
          $fileExists = $this->GITHUB->api('repo')->contents()->exists( $this->AUTHOR, $this->REPO, $ITEM['path'] . '/config.json', 'master' );
          
          if ( $fileExists ) {

            $commits = $this->GITHUB->api('repo')->commits()->all( $this->AUTHOR, $this->REPO, array('sha' => 'master', 'path' => $ITEM['path'] ) );
            
            $statut = self::get_sync_status( $ITEM['update'], $commits[0]['commit']['author']['date'] );
            
            self::$DATA[ $TYPE ][ $ITEM['id'] ] = array( 
              'status' => $statut, 
              'date' => $commits[0]['commit']['author']['date'], 
              'commit' => $commits[0]['sha']
            );
          
          } else {

            self::$DATA[ $TYPE ][ $ITEM['id'] ] = array( 
              'status' => 'init', 
              'date' => null,
              'commit' => null
            );

          }

        }

      }
      
     if ( get_option('linotype_sync_github_key') ) LINOTYPE_helpers::file_save( $this->SETTINGS['dir'] . '/' . $TYPE . '/sync.json', json_encode( self::$DATA[ $TYPE ] ) );
    
    } catch (Exception $e) {

      die('fetch error');

    }

    

  }

  
  public function exist( $PATH ) {

    try {

      $fileExists = $this->GITHUB->api('repo')->contents()->exists( $this->AUTHOR, $this->REPO, $PATH, 'master' );

      if ( $fileExists ) {

        return $fileExists;

      } else {
        
        return $fileExists;

      }

    } catch (Exception $e) {

      handypress_notices::add( array(
        "type" => 'error', 
        "message" => __('<strong>' . $TYPE . '/ ' . $ID . '</strong> - Push Error - ' .  $e->getMessage() ),
      ));

    }

  }

  public function push_update( $PATH, $CONTENT ) {

    try {

      $oldFile = $this->GITHUB->api('repo')->contents()->show( $this->AUTHOR, $this->REPO, $PATH, $this->BRANCH );
      $fileInfo = $this->GITHUB->api('repo')->contents()->update( $this->AUTHOR, $this->REPO, $PATH, $CONTENT, 'update from wp-blocks', $oldFile['sha'], $this->BRANCH, array('name' => $this->AUTHOR, 'email' => 'sync@wp-blocks.com') );
    
    } catch (Exception $e) {

      handypress_notices::add( array(
        "type" => 'error', 
        "message" => __('<strong>' . $TYPE . '/ ' . $ID . '</strong> - Push Error - ' .  $e->getMessage() ),
      ));

    }

  }

  public function push_create( $PATH, $CONTENT ) {

    try {

      $fileInfo = $this->GITHUB->api('repo')->contents()->create( $this->AUTHOR, $this->REPO, $PATH, $CONTENT, 'update from wp-blocks', $this->BRANCH, array('name' => $this->AUTHOR, 'email' => 'sync@wp-blocks.com') );
      
    } catch (Exception $e) {

      handypress_notices::add( array(
        "type" => 'error', 
        "message" => __('<strong>' . $TYPE . '/ ' . $ID . '</strong> - Push Error - ' .  $e->getMessage() ),
      ));

    }

  }

  public function delete( $PATH ) {

    try {

      $oldFile = $this->GITHUB->api('repo')->contents()->show( $this->AUTHOR, $this->REPO, $PATH, $this->BRANCH );
      $fileInfo = $this->GITHUB->api('repo')->contents()->rm( $this->AUTHOR, $this->REPO, $PATH, 'remove from wp-blocks', $oldFile['sha'], $this->BRANCH, array('name' => $this->AUTHOR, 'email' => 'sync@wp-blocks.com') );

    } catch (Exception $e) {

      handypress_notices::add( array(
        "type" => 'error', 
        "message" => __('<strong>' . $PATH . '</strong> - Delete Error - ' .  $e->getMessage() ),
      ));

    }

  }

 



  public function pull( $PATH ) {

    $CONTENT = "";

    try {

      $newFile = $this->GITHUB->api('repo')->contents()->exists( $this->AUTHOR, $this->REPO, $PATH, $this->BRANCH);
      if ( $newFile ) $CONTENT = $this->GITHUB->api('repo')->contents()->download( $this->AUTHOR, $this->REPO, $PATH, $this->BRANCH );
      
    } catch (Exception $e) {

      handypress_notices::add( array(
        "type" => 'error', 
        "message" => __('<strong>' . $TYPE . '/ ' . $ID . '</strong> - Push Error - ' .  $e->getMessage() ),
      ));

    }

    return $CONTENT;

  }

  static function get_sync_status( $local_date_string = null, $commit_date_string = null ) {
    
    $status = 'init';

    if ( $commit_date_string ) {

      $local_date  = new DateTime( $local_date_string );
      $commit_date = new DateTime( $commit_date_string );
      $diff  = $local_date->diff( $commit_date );

      if ( $diff->y === 0 && $diff->m === 0 && $diff->d === 0 && $diff->h === 0 && $diff->i === 0 && $diff->s === 1 ) {
        
        $local_date = $commit_date;
      
      }

      if ( $local_date_string && $local_date == $commit_date ) {

        $status = 'update';

      } else if ( $local_date_string && $local_date > $commit_date ) {

        $status = 'push';

      } else if ( $local_date_string && $local_date < $commit_date ) {

        $status = 'pull';

      } 

    }

    return $status;

  }







}

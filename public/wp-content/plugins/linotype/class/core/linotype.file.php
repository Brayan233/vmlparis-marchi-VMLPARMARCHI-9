<?php

class LINOTYPE_file {


  static function update_file( $DIR, $ID, $PATH, $FILE, $DATA, $SYNC = 'local' ) {
    
    if ( ! file_exists( $DIR ) ) wp_mkdir_p( $DIR ); 
    
    switch ( $SYNC ) {
      
      case "init":
      case "push":

        if ( $DATA ) {

          if ( LINOTYPE::$SYNC->exist( $PATH . '/' . $FILE ) ) {

            LINOTYPE::$SYNC->push_update( $PATH . '/' . $FILE, $DATA );
          
          } else {
          
            LINOTYPE::$SYNC->push_create( $PATH . '/' . $FILE, $DATA );
          
          }

          LINOTYPE_helpers::file_save( $DIR . '/' . $FILE, $DATA, true );
        
        } else {

          if ( $SYNC == 'push' ) LINOTYPE::$SYNC->delete( $PATH . '/' . $FILE );

        }

      break;
      
      case "pull":

        $DATA = LINOTYPE::$SYNC->pull( $PATH . '/' . $FILE );
        
        LINOTYPE_helpers::file_save( $DIR . '/' . $FILE, $DATA, true );
      
      break;

      case "rebase":

        $DATA = LINOTYPE::$SYNC->pull( $PATH . '/' . $FILE );
        
        LINOTYPE_helpers::file_save( $DIR . '/' . $FILE, $DATA, true );

      break;
      
      default:
      
        LINOTYPE_helpers::file_save( $DIR . '/' . $FILE, $DATA, true );

      break;

    }
    
  }

  static function update_config( $DIR, $ID, $PATH, $FILE, $DATA, $SYNC ) {

    if ( ! file_exists( $DIR ) ) wp_mkdir_p( $DIR ); 

    if ( ! get_option('linotype_sync_github_key') ) $DATA['update'] = "";
    
    switch ( $SYNC ) {
      
      case "init":

        $CONFIG = $DATA;
        
        $CONFIG = json_encode( $CONFIG, JSON_PRETTY_PRINT );

        LINOTYPE::$SYNC->push_create( $PATH . '/' . $FILE, $CONFIG );

        LINOTYPE_helpers::file_save( $DIR . '/' . $FILE, $CONFIG );

      break;
      
      case "push":
        
        $CONFIG = $DATA;

        $CONFIG = json_encode( $CONFIG, JSON_PRETTY_PRINT );

        LINOTYPE::$SYNC->push_update( $PATH . '/' . $FILE, $CONFIG );

        LINOTYPE_helpers::file_save( $DIR . '/' . $FILE, $CONFIG );

      break;
      
      case "pull":

        $CONFIG_PULL = LINOTYPE::$SYNC->pull( $PATH . '/' . $FILE );

        $CONFIG = json_decode( stripslashes( $CONFIG_PULL ), true );

        $COMMITS = LINOTYPE::$SYNC->get_commits( $PATH );

        $CONFIG['update'] = $COMMITS[0]['date'];

        $CONFIG_JSON = json_encode( $CONFIG, JSON_PRETTY_PRINT );

        LINOTYPE_helpers::file_save( $DIR . '/' . $FILE, $CONFIG_JSON );

      break;

      case "rebase":

        $CONFIG_PULL = LINOTYPE::$SYNC->pull( $PATH . '/' . $FILE );

        $CONFIG = json_decode( stripslashes( $CONFIG_PULL ), true );

        $COMMITS = LINOTYPE::$SYNC->get_commits( $PATH );

        $CONFIG['update'] = $COMMITS[0]['date'];

        $CONFIG_JSON = json_encode( $CONFIG, JSON_PRETTY_PRINT );

        LINOTYPE_helpers::file_save( $DIR . '/' . $FILE, $CONFIG_JSON );

      break;
      
      default:

        $CONFIG = $DATA;

        $CONFIG = json_encode( $CONFIG, JSON_PRETTY_PRINT );

        LINOTYPE_helpers::file_save( $DIR . '/' . $FILE, $CONFIG );

      break;

    }

  }



}

?>
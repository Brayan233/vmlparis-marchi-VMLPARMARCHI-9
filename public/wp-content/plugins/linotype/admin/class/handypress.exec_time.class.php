<?php

/**
*
* handypress_exec_time
*
**/
if ( ! class_exists('handypress_exec_time') ) {
  
class handypress_exec_time {

  /**
   * global exec time array
   * @var array
   */
  public $exectime = array();

  /**
   * exec time count
   * @var array
   */
  public $exectime_counter = 1;

  /**
   * return right order 
   * @var array
   */
  public $return = array();

  /**
   * Starting the timer
   * @param  string $id the timer unique id
   */
  public function start( $id = null ){
    
    //stop if null
    if ( $id == null ) return;

    //save starting time
    $this->exectime[$id] = microtime(true);

  }

  /**
   * Ending the timer
   * @param  string $id the timer unique id
   * @param  string $msg replace return with string if exist
   */
  public function end( $id = null, $msg = null ){

    //stop if null
    if ( $id == null ) return;

    //get microtime
    $microtime = microtime(true) - $this->exectime[$id];
    
    //date
    $micro = explode( '.', number_format($microtime, 2) - number_format($microtime, 0) );
    $date = date('H:i:s', $microtime );

    //set result
    $this->exectime[$id] = array(
      "microtime" => $microtime,
      "h:m:s" => substr($date . $micro[1] , 0, 11),
      "m:s" => substr($date . $micro[1] , 3, 8),
      "s" => number_format($microtime, 2),
    );

    if ( $msg != null ) {
      
      $pattern = array( '%microtime%', '%h:m:s%', '%m:s%', '%s%' );

      $msg = str_replace($pattern, $this->exectime[$id], $msg );

      $this->exectime[$id] = $msg;

    }

    $this->return[ sprintf('%02d', $this->exectime_counter ) . '_' . $id] = $this->exectime[$id];

    $this->exectime_counter++;

  }

  /**
   * return the exec time array
   * @param  string $id the timer unique id
   * @return array      return the global or specific id of exec time
   */
  public function get( $id = null ){

    if ( $id == null ) {

      return $this->return;

    } else {

      return $this->exectime[$id];
    
    }


  }

}
}
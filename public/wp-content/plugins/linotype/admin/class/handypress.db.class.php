<?php
/**
*
* handypress_wp_admin_notices
*
**/
if ( ! class_exists('handypress_db') ) {

class handypress_db {


  public $wpdb;

  public $params;

  public $table_ID;

  public $column_formats;


  function __construct( $params = array() ) {

    global $wpdb;

    $this->wpdb = $wpdb;

    $this->params = $params;

    $this->table_ID = $this->wpdb->prefix . $this->params['id'];

    $this->column_formats = $this->all_columns();

    $this->create_table();

  }

  public function create_table(){

    global $charset_collate;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

    $columns_content = '';
    if( $this->params['columns'] ) {
      foreach ( $this->params['columns'] as $key => $column ) {

        if ( $key !== 'ID' && $key !== 'user_id' && $key !== 'create_date' && $key !== 'update_date' ) {

          $columns_content .= $column['row'] . ', ';

        }

      }
    }

    $sql_create_table = "CREATE TABLE $this->table_ID (
      ID bigint(20) unsigned NOT NULL auto_increment,
      {$columns_content}
      user_id bigint(20) unsigned NOT NULL default '0',
      update_date datetime NOT NULL default '0000-00-00 00:00:00',
      create_date datetime NOT NULL default '0000-00-00 00:00:00',
      PRIMARY KEY  (ID),
      KEY abc (user_id)
      ) $charset_collate; ";

    dbDelta( $sql_create_table );


    ////////////////// Create column if needed
    global $wpdb;

    if( $this->params['columns'] ) {
      foreach ( $this->params['columns'] as $key => $column ) {

        if ( $key !== 'ID' && $key !== 'user_id' && $key !== 'create_date' && $key !== 'update_date' ) {

          $test_row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$this->table_ID' AND column_name = '$key'"  );

          if ( empty( $test_row ) ) {
            $wpdb->query("ALTER TABLE $this->table_ID ADD $key varchar(255) NOT NULL default ''");
              //_HANDYLOG( $this->table_ID . ' : new row created', $key );
          }

           //_HANDYLOG( $this->table_ID . ' : row exist', $key );

        }

      }
    }
    //////////////////

  }

  public function all_columns(){

      $columns_start = array(
          'ID'  => '%d',
      );

      $columns_content = array();
      if( $this->params['columns'] ) {
        foreach ( $this->params['columns'] as $key => $column ) {

          if ( $key !== 'ID' && $key !== 'user_id' && $key !== 'create_date' && $key !== 'update_date' ) {

            $columns_content[$key] = $column['format'];

          }

        }
      }

      $columns_end = array(
          'user_id'     => '%d',
          'update_date' => '%s',
          'create_date' => '%s',
      );

      $columns = array_merge( $columns_start, $columns_content, $columns_end );

      return $columns;

  }

  public function format_data( $data = array() ) {

    foreach ( $data as $key => $value ) {

      if ( ! $value ) $value = "";

      //not for default
      if ( $key !== 'ID' && $key !== 'user_id' && $key !== 'create_date' && $key !== 'update_date' ) {

        //sanitize
        $data[$key] = sanitize_option( $key, $value );

        //serialize
        //$data[$key] = maybe_serialize( $value );
        if ( $value && is_array( $value ) ) $data[$key] = json_encode( $value );

      }

    }

    //Force fields to lower case
    $data = array_change_key_case ( $data );

    //get column
    $column_formats = $this->column_formats;

    //White list columns
    $data = array_intersect_key( $data, $column_formats );

    //Reorder column_formats to match the order of columns given in $data
    $data_keys = array_keys( $data );
    $column_formats = array_merge( array_flip( $data_keys ), $column_formats );

    return array( "data" => $data, "column_formats" => $column_formats );

  }

  public function insert_row( $data = array() ){

    global $wpdb;

    //add create data
    $data['user_id'] = get_current_user_id();
    $date = current_time('timestamp');
    $data['create_date'] = date_i18n( 'Y-m-d H:i:s', $date, true );
    $data['update_date'] = date_i18n( 'Y-m-d H:i:s', $date, true );

    //create insert data
    $insert = $this->format_data( $data );

    //insert db
    $insert_post = $this->wpdb->insert( $this->table_ID, wp_unslash( $insert['data'] ), $insert['column_formats'] );

    //return id
    return $wpdb->insert_id;

  }

  public function update_row( $ID, $data = array() ){

    global $wpdb;

    $ID = absint( $ID );

    if( empty( $ID ) ) return false;

    //add create data
    $date = current_time('timestamp');
    $data['update_date'] = date_i18n( 'Y-m-d H:i:s', $date, true );

    //create update data
    $update = $this->format_data( $data );

    //update
    if ( false === $wpdb->update( $this->table_ID, wp_unslash( $update['data'] ), array( 'ID' => $ID ), $update['column_formats'] ) ) return false;

    return true;

  }

  public function get( $ID, $field = '*' ){

    global $wpdb;

    $data = $wpdb->get_row( "SELECT $field FROM $this->table_ID WHERE ID = $ID", ARRAY_A );

    if ( $data ) {
      foreach ( $data as $key => $value ) {

        //not for default
        if ( $key !== 'ID' && $key !== 'user_id' && $key !== 'create_date' && $key !== 'update_date' ) {

          //sanitize
          $data[$key] = sanitize_option( $key, $value );

          //serialize
          $data[$key] = maybe_unserialize( $value );
          if ( $value && ! is_array( $value ) && handypress_helper::is_json( $value ) ) $data[$key] = json_decode( $value, true );

        }

      }


      if ( $field == '*' ) {

        return $data;

      } else {

        return $data[$field];

      }

    } else {

      return "";

    }

  }

  public function delete( $ID ){

    global $wpdb;

    $ID = absint( $ID );

    if( empty( $ID ) ) return false;

    $sql = $wpdb->prepare( "DELETE from $this->table_ID WHERE ID = %d", $ID );

    if( ! $wpdb->query( $sql ) ) {

      return false;

    } else {

      return true;

    }

  }

  /**
   * Retrieves activity logs from the database matching $query.
   * $query is an array which can contain the following keys:
   *
   * 'fields' - an array of columns to include in returned roles. Or 'count' to count rows. Default: empty (all fields).
   * 'orderby' - datetime, user_id or ID. Default: datetime.
   * 'order' - asc or desc
   * 'user_id' - user ID to match, or an array of user IDs
   * 'since' - timestamp. Return only activities after this date. Default false, no restriction.
   * 'until' - timestamp. Return only activities up to this date. Default false, no restriction.
   *
   *@param $query Query array
   *@return array Array of matching logs. False on error.
  */
  public static function get_all_data( $table ) {

    global $wpdb;

    $data = array();

    $table_ID = $wpdb->prefix . $table;

    $all_data = $wpdb->get_results( "SELECT * FROM $table_ID WHERE 1=1", ARRAY_A );

    foreach ( $all_data as $data_key => $data ) {

      foreach ( $data as $key => $value ) {

        //not for default
        //if ( $key !== 'ID' && $key !== 'user_id' && $key !== 'create_date' && $key !== 'update_date' ) {

          //sanitize
          $all_data[$data_key][$key] = sanitize_option( $key, $value );

          //serialize
          $all_data[$data_key][$key] = maybe_unserialize( $value );
          if ( $value && ! is_array( $value ) && handypress_helper::is_json( $value ) ) $all_data[$data_key][$key] = json_decode( $value, true );

        //}

      }

    }

    return $all_data;

  }
  public static function get_row_data( $table, $ID, $field = '*' ){

    global $wpdb;

    $table_ID = $wpdb->prefix . $table;

    $data = $wpdb->get_row( "SELECT $field FROM $table_ID WHERE ID = $ID", ARRAY_A );

    if ( isset( $data ) && $data ) {
      foreach ( $data as $key => $value ) {

        //not for default
        if ( $key !== 'ID' && $key !== 'user_id' && $key !== 'create_date' && $key !== 'update_date' ) {

          //sanitize
          $data[$key] = sanitize_option( $key, $value );

          //serialize
          $data[$key] = maybe_unserialize( $value );
          if ( $value && ! is_array( $value ) && handypress_helper::is_json( $value ) ) $data[$key] = json_decode( $value, true );

        }

      }
    }

    if ( $field == '*' ) {

      return $data;

    } else {

      return $data[$field];

    }

  }
  public static function update_row_data( $table, $ID, $new_data = array() ){

    global $wpdb;

    $table_ID = $wpdb->prefix . $table;

    $ID = absint( $ID );

    if( empty( $ID ) ) return false;

    //add create data
    $date = current_time('timestamp');
    $data['update_date'] = date_i18n( 'Y-m-d H:i:s', $date, true );

    $data = array_merge( $new_data, $data );

    $wpdb->update( $table_ID, $data, array( 'ID' => $ID ), array( '%s', '%s' ), array( '%d' ) );

    //update
  //  if ( false === $wpdb->update( $table_ID, wp_unslash( $update['data'] ), array( 'ID' => $ID ), $update['column_formats'] ) ) return false;

    return true;

  }


  public function get_data( $query = array() ){

       global $wpdb;

       /* Parse defaults */
       $defaults = array(
         'fields'=>array(),'orderby'=>'datetime','order'=>'desc', 'user_id'=>false,
         'since'=>false,'until'=>false,'number'=>100,'offset'=>0
       );
      $query = wp_parse_args($query, $defaults);

      /* Form a cache key from the query */
      $cache_key = $this->params['id'] . ':'.md5( serialize($query));
      $cache = wp_cache_get( $cache_key );
      if ( false !== $cache ) {
              $cache = apply_filters('get_data', $cache, $query);
              return $cache;
      }
       extract($query);

      /* SQL Select */
      //Whitelist of allowed fields
      $allowed_fields = $this->all_columns();



      if( is_array($fields) ){
          //Convert fields to lowercase (as our column names are all lower case - see part 1)
          $fields = array_map('strtolower',$fields);
          //Sanitize by white listing
          $fields = array_intersect($fields, $allowed_fields);
      }else{
          $fields = strtolower($fields);
      }
      //Return only selected fields. Empty is interpreted as all
      if( empty($fields) ){
          $select_sql = "SELECT* FROM {$this->table_ID}";
      }elseif( 'count' == $fields ) {
          $select_sql = "SELECT COUNT(*) FROM {$this->table_ID}";
      }else{
          $select_sql = "SELECT ".implode(',',$fields)." FROM {$this->table_ID}";
      }

       /*SQL Join */
       //We don't need this, but we'll allow it be filtered (see $this->params['id'] . '_clauses' )
       $join_sql='';
      /* SQL Where */
      //Initialise WHERE
      $where_sql = 'WHERE 1=1';
      if( !empty($ID) )
         $where_sql .=  $wpdb->prepare(' AND ID=%d', $ID);
      if( !empty($user_id) ){
         //Force $user_id to be an array
         if( !is_array( $user_id) )
             $user_id = array($user_id);
         $user_id = array_map('absint',$user_id); //Cast as positive integers
         $user_id__in = implode(',',$user_id);
         $where_sql .=  " AND user_id IN($user_id__in)";
      }
      $since = absint($since);
      $until = absint($until);
      if( !empty($since) )
         $where_sql .=  $wpdb->prepare(' AND create_date >= %s', date_i18n( 'Y-m-d H:i:s', $since,true));
      if( !empty($until) )
         $where_sql .=  $wpdb->prepare(' AND create_date <= %s', date_i18n( 'Y-m-d H:i:s', $until,true));
      /* SQL Order */
      //Whitelist order
      $order = strtoupper($order);
      $order = ( 'ASC' == $order ? 'ASC' : 'DESC' );
      switch( $orderby ){
         case 'ID':
              $order_sql = "ORDER BY ID $order";
         break;
         case 'user_id':
              $order_sql = "ORDER BY user_id $order";
         break;
         case 'datetime':
               $order_sql = "ORDER BY create_date $order";
         default:
         break;
      }
      /* SQL Limit */
      $offset = absint($offset); //Positive integer
      if( $number == -1 ){
           $limit_sql = "";
      }else{
           $number = absint($number); //Positive integer
           $limit_sql = "LIMIT $offset, $number";
      }
      /* Filter SQL */
      $pieces = array( 'select_sql', 'join_sql', 'where_sql', 'order_sql', 'limit_sql' );
      $clauses = apply_filters( $this->params['id'] . '_clauses', compact( $pieces ), $query );
      foreach ( $pieces as $piece )
            $$piece = isset( $clauses[ $piece ] ) ? $clauses[ $piece ] : '';
      /* Form SQL statement */
      $sql = "$select_sql $where_sql $order_sql $limit_sql";
      if( 'count' == $fields ){
          return $wpdb->get_var($sql);
      }
      /* Perform query */
      $logs = $wpdb->get_results($sql, ARRAY_A);
      /* Add to cache and filter */
      wp_cache_add( $cache_key, $logs, 24*60*60 );
      $logs = apply_filters('get_data', $logs, $query);
      return $logs;
   }
  /**
   * Deletes an activity log from the database
   *
   *@param $ID int ID of the activity log to be deleted
   *@return bool Whether the log was successfully deleted.
  */
  public function wptuts_delete_log( $ID ){
      global $wpdb;
      //Log ID must be positive integer
      $ID = absint($ID);
      if( empty($ID) )
           return false;
      do_action('wptuts_delete_log',$ID);
      $sql = $wpdb->prepare("DELETE from {$this->table_ID} WHERE ID = %d", $ID);
      if( !$wpdb->query( $sql ) )
           return false;
      do_action('wptuts_deleted_log',$ID);
      return true;
  }

}
}

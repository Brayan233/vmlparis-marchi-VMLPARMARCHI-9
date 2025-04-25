<?php

/**
 * handypress_helper
 *
 * Helper functions
 *
 * @package WordPress
 * @subpackage LINOADMIN
 * @version 1.0.0
 * @author Yannick Armspach
 * @link http://www.handypress.io
 *
 */
if ( ! class_exists('handypress_helper') ) {
  
class handypress_helper {


  /**
  *
  * user_roles_by_id
  *
  * @desc
  *
  **/
  static function user_roles_by_id( $id ) {

      $user = new WP_User( $id );

      if ( isset( $user->roles[0] ) ) {

        $out = $user->roles[0];

      } else {

        $out = false;

      }

      // if ( empty ( $user->roles ) or ! is_array( $user->roles ) ) return array ();

      // $wp_roles = new WP_Roles;
      // $names    = $wp_roles->get_names();
      // $out      = array ();

      // foreach ( $user->roles as $role ) {

      //     if ( isset ( $names[ $role ] ) ) {
      //      $out = array( 'id' => $role, 'name' => $names[$role] );
      //  } else {
      //    $out = array( 'id' => $role, 'name' => $role );
      //  }

      // }

      return $out;

  }

  static function is_json( $string ) {

   json_decode( $string );

   return ( json_last_error() == JSON_ERROR_NONE );

  }

  /**
  *
  * get_all_roles
  *
  * @desc
  *
  **/
  static function get_all_roles( $unsets = array() ) {

      global $wp_roles;

      $all_roles = $wp_roles->roles;
      $editable_roles = apply_filters('editable_roles', $all_roles);

      $roles = array();

      if ( $editable_roles ){
        foreach ( $editable_roles as $role_key => $role ) {
          $roles[$role_key] = array( "title" => $role['name'] . ' ('.$role_key.')', "value" => $role_key, "capabilities" => $role['capabilities'] );
        }
      }

      if ( $unsets ){
        foreach ( $unsets as $role_key ) {
          if ( isset( $roles[$role_key] ) ) unset ($roles[$role_key] );
        }
      }

      return $roles;

  }

  /**
  *
  * get_all_capabilities
  *
  * @desc
  *
  **/
  static function get_all_capabilities() {

    $all_capabilities = array();

    $capslist = get_role( 'administrator' )->capabilities;

    ksort( $capslist );

    foreach( $capslist as $cap_key => $cap ) {

      if ( ! in_array( $cap_key, array('level_0','level_1','level_2','level_3','level_4','level_5','level_6','level_7','level_8','level_9','level_10') ) ) {
        $all_capabilities[$cap_key] = array( "title" => ucfirst( str_replace( '_', ' ',  $cap_key ) ), "value" => $cap_key );
      }

    }

    return $all_capabilities;

  }

  /**
   * get_posttype
   * @param  boolean $public  only public post types
   * @return array            return an array with a success boolean, current value and requiere value
   */
  static function all_post_types( $public = true ) {

    $POST_TYPE = array();

    $custom_post_types = get_post_types( array( 'public'   => $public ), 'objects' );
    if ( $custom_post_types ) {
      foreach ( $custom_post_types as $post_type ) {
        array_push( $POST_TYPE, array( "title" => $post_type->labels->name , "value" => $post_type->name, "info" => $post_type->name, "data" => $post_type ) );
      }
    }

    return $POST_TYPE;

  }

  /**
   * get_posts
   * @param  boolean $public  only public post types
   * @return array            return an array with a success boolean, current value and requiere value
   */
  static function get_posts( $post_type = 'post', $post_status = 'publish' ) {

    $POSTS = array();

    $args = array(
      'post_type' => $post_type,
      'post_status' => $post_status,
      'posts_per_page' => -1,
    );

    $query = new WP_Query( $args );

    $rows = $query->get_posts();

    if ( $rows ) {
      foreach ( $rows as $post ) {
        array_push( $POSTS, array( "title" => $post->post_title , "value" => $post->ID, "info" => $post_type, "data" => $post  ) );
      }
    }

    return $POSTS;

  }

  /**
   * get_menus
   * @param  boolean $public  only public post types
   * @return array            return an array with a success boolean, current value and requiere value
   */
  static function get_menus( $type = 'linoadmin' ) {

    $MENUS = array();

    $nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );


    //loop items
    if ( $nav_menus ) {
      foreach ( $nav_menus as $nav_menu ) {

        switch ( $type ) {

          case 'visualcomposer':
            $MENUS[ $nav_menu->name ] = $nav_menu->term_id;
          break;

          default:
          case 'linoadmin':
            array_push( $MENUS, array( "title" => $nav_menu->name , "value" => $nav_menu->term_id, "info" => $nav_menu->term_id, "data" => $nav_menu ) );
          break;

        }

      }
    }

    wp_reset_postdata();

    return $MENUS;

  }

  /**
   * get_terms
   * @return array   return all existing terms
   */
  static function all_terms( $hierarchical = true, $public = true, $tax_separator = ' > ' ) {

    $TERMS = array();

    $taxonomies = get_taxonomies( array( "public" => $public ) );

    if ( $taxonomies ){

      foreach ( $taxonomies as $tax_key => $tax ) {

        $all_terms = array();

        $taxonomy = get_taxonomy( $tax );

        $terms = get_terms( $tax );

        if ( $terms ) {
          foreach ( $terms as $term_key => $term ) {

            if ( $hierarchical ) {
              array_push( $all_terms, array( "title" => $term->name . ' (' . $term->count . ')', "value" => $term->term_id, "info" => 'id:' . $term->term_id, "data" => array( "taxonomy" => $taxonomy, "term" => $term ) ) );
            } else {
              $TERMS[] = array( "title" => $taxonomy->label . $tax_separator . $term->name . ' (' . $term->count . ')', "value" => $term->taxonomy . $tax_separator . $term->term_id, "info" => 'id:' . $term->term_id, "optgroup" => $taxonomy->label );
            }

          }
        }

        if ( $hierarchical ) array_push( $TERMS, array( "optgrouplabel" => $taxonomy->label, "options" => $all_terms ) );

      }

    }

    return $TERMS;

  }

  /**
   *
   * is_multi_array
   *
   */
  static function is_multi_array( $arr ) {

    rsort( $arr );

    return isset( $arr[0] ) && is_array( $arr[0] );

  }

  /**
   *
   * parse_args
   *
   */
  static function parse_args( $b, &$a ) {

    $a = (array) $a;
    $b = (array) $b;
    $result = $b;

    foreach ( $a as $k => &$v ) {

      if ( is_array( $v ) && isset( $result[ $k ] ) ) {

        $result[ $k ] = handypress_helper::parse_args( $result[ $k ], $v );

      } else {

        if ( isset( $result[ $k ] ) && ! $result[ $k ] ) {
          $result[ $k ] = $v;
        }

      }

    }

    return $result;

  }

  static function array_merge(array & $array1, array & $array2)
  {
      $merged = $array1;

      foreach ($array2 as $key => & $value)
      {
          if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
          {
              $merged[$key] = self::array_merge($merged[$key], $value);
          } else if (is_numeric($key))
          {
               if (!in_array($value, $merged))
                  $merged[] = $value;
          } else
              $merged[$key] = $value;
      }

      return $merged;
  }


  /**
   *
   * recursive_array_search
   *
   */
  static function recursive_array_search( $needle, $haystack ) {

    foreach( $haystack as $key => $value ) {

        $current_key = $key;

        if( $needle === $value OR ( is_array( $value ) && recursive_array_search( $needle, $value ) !== false ) ) {

            return $current_key;

        }

    }

    return false;

  }

  static function copy_dir( $src, $dst ) {

    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                self::copy_dir($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }

    closedir($dir);

  }

  static function dirtree($dir, $type = '', $list = '' ){

    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

    // prevent empty ordered elements
    if (count($ffs) < 1)
        return;

    $list .= '<ul class="tree-list">';

    $files = '';

    foreach($ffs as $ff){

      if( ! is_dir($dir.'/'.$ff) ) {

        $file_parts = pathinfo($dir.'/'.$ff);

        if( isset( $file_parts['extension'] ) && $file_parts['extension'] == $type ) {

          $files .= '<li class="tree-file" data-file="' . $ff . '" data-path="' . $dir.'/'.$ff . '" ><label><input type="checkbox"/> <i class="fa fa-file-code-o"></i> ' . $ff . '</label>';

            //$files .= '<input type="text" style="" class="tree-file-id" value="' . $ff . '">';

          $files .= '</li>';

        }

      } else {

        $list_next = self::dirtree($dir.'/'.$ff, $type );

        if( $list_next != '<ul class="tree-list"></ul>' ) {

          $list .= '<li class="tree-folder close">';

            $list .= '<div class="tree-folder-title"><i class="fa fa-plus"></i> <i class="fa fa-folder icon-folder icon-folder"></i> '.$ff . '</div>';

            $list .= $list_next;

          $list .= '</li>';

        }

      }

    }

    $list .= $files;

    $list .= '</ul>';

    return $list;

}

  /**
   *
   * getFileList
   *
   */
  static function getFileList( $dir ) {

    $retval = array();

    if( substr( $dir, -1 ) != "/") $dir .= "/";

    $d = @dir($dir) or die( "getFileList: Failed opening directory $dir for reading" );

    while( false !== ( $entry = $d->read() ) ) {

      if( $entry[0] == "." ) continue;

      if( is_dir( "$dir$entry" ) ) {

        $retval[] = array(

          //base
          "path" => "$dir$entry/",
          "name" => basename("$dir$entry"),
          "type" => filetype("$dir$entry"),
          "size" => 0,
          "lastmod" => filemtime("$dir$entry"),

          //field
          "title" => basename("$dir$entry"),
          "value" => "$dir$entry/",
          "info" => filetype("$dir$entry"),

        );

      } elseif( is_readable( "$dir$entry" ) ) {

        $retval[] = array(
          "path" => "$dir$entry",
          "name" => basename("$dir$entry", ".php"),
          "type" => mime_content_type("$dir$entry"),
          "size" => filesize("$dir$entry"),
          "lastmod" => filemtime("$dir$entry"),

          //field
          "title" => basename("$dir$entry", ".php"),
          "value" => "$dir$entry",
          "info" => mime_content_type("$dir$entry"),

        );

      }

    }

    $d->close();

    return $retval;

  }

  /**
   *
   * aasort - sort array by key
   *
   */
  static function aasort( &$array, $key ) {

      $sorter = array();
      $ret = array();

      reset( $array );

      foreach ( $array as $ii => $va ) {

        $sorter[$ii] = $va[$key];

      }

      asort( $sorter );

      foreach ( $sorter as $ii => $va ) {

          $ret[$ii] = $array[$ii];

      }

      $array = $ret;

  }

  static function formatBytes( $bytes ) {

          if ($bytes >= 1073741824)
          {
              $bytes = number_format($bytes / 1073741824, 2) . 'GB';
          }
          elseif ($bytes >= 1048576)
          {
              $bytes = number_format($bytes / 1048576, 2) . 'MB';
          }
          elseif ($bytes >= 1024)
          {
              $bytes = number_format($bytes / 1024, 2) . 'KB';
          }
          elseif ($bytes > 1)
          {
              $bytes = $bytes . 'B';
          }
          elseif ($bytes == 1)
          {
              $bytes = $bytes . 'B';
          }
          else
          {
              $bytes = '0B';
          }

          return $bytes;

  }

  /**
   *
   * ArrayisAssoc : check if array is associative (is array with keys)
   *
   */
  static function isArrayAssoc( $arr ) {

    return array_keys($arr) !== range(0, count($arr) - 1);

  }

  static function getUniqueID() {

    return md5( self::getIP() . rand( 0, time() ) );

  }

  static function getIP() {

    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {

      $ip = $_SERVER['HTTP_CLIENT_IP'];

    } else if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {

      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

    } else {

      $ip = $_SERVER['REMOTE_ADDR'];

    }

    return $ip;

  }

  static function str_start( $str, $content ) {

       $length = strlen( $str );

       return ( substr( $content, 0, $length ) === $str );

  }

  static function str_end( $str, $content ) {

      $length = strlen( $str );

      if ( $length == 0 ) return true;

      return ( substr( $content, -$length ) === $str );

  }

  /**
   *
   * seo_by_yoast__remove_columns
   *
   */
  static function seo_by_yoast__remove_columns(){

    add_action ('wp_loaded','seo_by_yoast__remove_columns');

    function remove_columns( $columns ) {

      //unset( $columns['wpseo-score'] );
      unset( $columns['wpseo-title'] );
      unset( $columns['wpseo-metadesc'] );
      unset( $columns['wpseo-focuskw'] );

      return $columns;

    }

    $post_types = get_post_types( array(), 'names', 'and' );

    foreach ( $post_types  as $post_type ) {

      add_filter ( 'manage_edit-' . $post_type . '_columns', 'remove_columns' );

    }

  }

  static function hex2rgb( $hex, $inline = false, $alpha = 1, $wrap = false ) {

     $hex = str_replace("#", "", $hex);

     if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
     } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
     }

     $rgb = array($r, $g, $b );

     if ( $alpha ) $rgb = array($r, $g, $b, $alpha );

     if ( $inline ) {

       $rgb_inline = '';

       if ( $wrap ) $rgb_inline .= 'rgba(';
                    $rgb_inline .= implode( ",", $rgb );
       if ( $wrap ) $rgb_inline .= ')';

       return $rgb_inline;

     } else {


      return $rgb;

     }



  }

  /*
  public function get_PRESETS_old__for custompost(){

    $PRESETS = array();

    // WP_Query arguments
    $args = array (
      'post_type'              => array( 'wp_pre_flight_preset' ),
      'post_status'            => array( 'publish' ),
      'posts_per_page'         => '-1',
    );

    // The Query
    $presets_query = new WP_Query( $args );

    // The Loop
    if ( $presets_query->have_posts() ) {
      while ( $presets_query->have_posts() ) {
        $presets_query->the_post();

        array_push( $PRESETS, array( "title" => get_the_title(), "info" => "ID: " . get_the_ID() , "value" => get_the_ID() ) );


      }
    }

    // Restore original Post Data
    wp_reset_postdata();

    return $PRESETS;

  }
  */



}
}

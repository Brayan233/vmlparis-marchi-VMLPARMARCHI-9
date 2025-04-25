<?php

/**
 * LINOADMIN
 *
 * handy fields builder
 *
 * @package WordPress
 * @subpackage LINOADMIN
 * @version 1.0.0
 * @author Yannick Armspach
 * @link http://www.handypress.io
 *
 */

/**
 * Class to create the meta content.
 *
 * @since 1.0.0
 *
 */
class LINOADMIN_META extends LINOADMIN {

  /**
   * __construct
   *
   * @since 1.0
   *
   * @param array  $META  the meta params
   *
   */
  function __construct( $PAGE, $TYPE, $META = null, $LINOADMIN ) {

    $this->LINOADMIN = $LINOADMIN;

    $this->PAGE = $PAGE;

    $META = apply_filters('linoadmin_meta', $META );

    if ( $META ) {

      foreach ( $META as $id => $meta) {

        //check if serialize
        $is_serialize = false;
        $serialized = explode( '@', $id );
        if ( count( $serialized ) > 1 ) $is_serialize = true; $serialized_id = $serialized[0]; unset( $serialized[0] );
        $id = str_replace( '@', '__', $id );

        //check if rules
        if ( $meta['enable'] && ( empty( $meta['if'] ) || $this->compare_meta( $meta['if'] ) ) ) {

          $field = $meta;
          $field['id'] = $id;
          $field['value'] =  "";

          global $pagenow;
          global $post;

          switch ( $TYPE ) {

            case 'option':
            case 'dashboard':

              $field['saved_methode'] = 'option';

              if ( $this->PAGE['customdata'] ) {

                if ( isset( $_REQUEST['post'] ) ) {

                  if ( $is_serialize ){

                    if ( isset($this->PAGE['customdata']['sql_columns'][$serialized_id]) ) {
                      $serialize_value = $this->PAGE['linoadmin_customdata']->db()->get( $_REQUEST['post'], $serialized_id );
                    }

                    if ( $serialize_value ) {

                      $field['value'] = $serialize_value;

                      foreach ( array_values( $serialized ) as $deep_key => $deep_id ) {

                        if ( isset( $field['value'][ $deep_id ] ) ) {
                          $field['value'] = $field['value'][ $deep_id ];
                        } else {
                          $field['value'] = '';
                        }

                      }

                    }

                  } else {

                    if ( isset($this->PAGE['customdata']['sql_columns'][$id]) ) {
                      $field['value'] = $this->PAGE['linoadmin_customdata']->db()->get( $_REQUEST['post'], $id );
                    }

                  }

                }

              } else {

                if ( $is_serialize ){

                  $serialize_value = get_option( $serialized_id );

                  if ( $serialize_value ) {

                    $field['value'] = $serialize_value;

                    foreach ( array_values( $serialized ) as $deep_key => $deep_id ) {

                      if ( isset( $field['value'][ $deep_id ] ) ) {
                        $field['value'] = $field['value'][ $deep_id ];
                      } else {
                        $field['value'] = "";
                      }

                    }

                  }

                } else {

                  $field['value'] = get_option( $field['id'] );

                  if ( $field['value'] && handypress_helper::is_json( $field['value'] ) ) $field['value'] = json_decode( $field['value'], true );
                  
                }

              }


            break;

            case 'post':
            case 'attachment':

              $field['saved_methode'] = 'post';

              switch ( $field['id'] ) {

                case 'the_title':

                  $field['value'] = get_the_title( $post->ID );

                break;

                case 'post_name':

                  $field['value'] = get_post_field( 'post_name', $post->ID );

                break;

                case 'post_content':

                  $content_post = get_post( $post->ID );

                  if ( isset( $content_post->post_content ) && $content_post->post_content ) {
                    $field['value'] =   addslashes( str_replace(array("\r", "\n"), array('',''),  $content_post->post_content ) );
                  } else {
                    $field['value'] = "";
                  }

                break;

                case 'excerpt':

                  $field['value'] = get_the_excerpt( $post->ID );

                break;

                default:

                  if ( $is_serialize ){

                    $serialize_value = get_post_meta( $post->ID, $serialized_id, true );

                    if ( $serialize_value ) {

                      $field['value'] = $serialize_value;

                      foreach ( array_values( $serialized ) as $deep_key => $deep_id ) {

                        $field['value'] = $field['value'][ $deep_id ];

                      }

                    }

                  } else {

                    $field['value'] = get_post_meta( $post->ID, $field['id'] , true );

                    if ( $field['value'] && handypress_helper::is_json( $field['value'] ) ) $field['value'] = json_decode( $field['value'], true );

                  }

                break;

              }

            break;

          }

          $field = apply_filters('linoadmin_field', $field );

          if ( $field['value'] == "" && $field['default'] != "" ) {

            $field['value'] =  $field['default'];

          }

          if ( $field['value_filter'] && is_callable( $field['value_filter'] ) ) {

            $field = $field['value_filter']( $field );

          }

          //check if custom field
          if ( is_file( $field['type'] ) ) {


            if ( file_exists( $field['type'] ) ) {

              if ( ! $field['col'] ) $field['col'] = "col-12";

              echo '<div class="linoadmin-'. $field['col'] .'">';

                include $field['type'];

              echo '</div>';

            }

          } else {

            if ( file_exists( LINOADMIN::$ADMIN['dir'] . '/fields/'.$field['type'].'/'.$field['type'].'.php' ) ) {

              if ( ! $field['col'] ) $field['col'] = "col-12";
              
              echo '<div class="linoadmin-'. $field['col'] .'">';

                //load template field
                include LINOADMIN::$ADMIN['dir'] . '/fields/' . $field['type'] . '/' . $field['type'] . '.php';

                if ( isset( $field['help'] ) && $field['help'] ) {
                  echo '<pre>'; var_dump( $field ); echo '</pre>';
                }

                //display save button
                if ( $field["bt_save"] == true ) {

                  $bt_title = $field["bt_save"]['title'];
                  if ( ! $bt_title ) $bt_title = __("Save");

                  echo '<li class="wp-field" style="display: block;padding:' . $field['padding'] . '">';

                    echo '<input class="button-primary force-align-middle" name="save" type="submit" value="' . $bt_title . '" /> ';
                    echo '<input type="hidden" name="action" value="save" />';

                  echo '</li>';

                }

                //display reset button
                if ( $field["bt_reset"] == true ) {

                  $bt_title = $field["bt_reset"]['title'];
                  if ( ! $bt_title ) $bt_title = __("reset");

                  echo '<li class="wp-field" style="display: block;padding:' . $field['padding'] . '">';

                    echo '<input class="button button-small force-align-middle" name="reset" type="submit" value="' . $bt_title . '" />';

                  echo '</li>';

                }

              echo '</div>';


            }

          }

        }

      }

    }

  }

}

<?php

/* save temp settings */

function ajax_handypress_list_temp_settings() {

  update_option( 'handypress_list_temp_settings', $_REQUEST["settings"] );

  die( json_encode( array( 'type' => 'success') ) );

}

add_action( 'wp_ajax_handypress_list_temp_settings', 'ajax_handypress_list_temp_settings' );



/* edit */

function ajax_handypress_list_edit() {



  include dirname( __FILE__ ) . '/list-header.php';

  echo '<div class="list-modal-toolbar-top">';

      echo '<div class="list-modal-toolbar-left">';

        echo '<span class="list-modal-toolbar-title">Title</span>';

      echo '</div>';

      echo '<div class="list-modal-toolbar-right">';

        //echo '<span class="list-modal-close list-bt dashicons dashicons-no"></span>';

      echo '</div>';

  echo '</div>';

  $data = json_decode( stripslashes( get_option( 'handypress_list_temp_settings' ) ), true );

  $items = json_decode( get_option( $_REQUEST["list_id"] . '_items' ), true );



  $tabs = array();

  //order by tab
  if( isset( $items[ $data['type'] ]['settings'] ) ){
    foreach ( $items[ $data['type'] ]['settings'] as $field_id => $field ) {

      if ( ! isset( $field['tab'] ) ){
        $tabs[ 'General' ][ $field_id ] = $field;
      } else {
        $tabs[ $field['tab'] ][ $field_id ] = $field;
      }

    }
  }



  //print
  echo '<div class="LINOADMIN-tab" data-tab-style="nav">';

    $tab_id = 0;

    if( isset( $tabs ) && count( $tabs ) > 1 ){

      echo '<ul class="LINOADMIN-tab-nav">';

        foreach ( $tabs as $tab_title => $tab ) {

          $state = '';
          if ( $tab_id == 0 ) $state = ' active';

          echo '<li class="LINOADMIN-tab-nav-item' . $state . '" data-target="#content_' . $tab_id . '-LINOADMIN-tab-content-id"><span>' . $tab_title . '</span></li>';

          $tab_id++;

        }

        echo '</ul>';

    }

    $content_id = 0;

    if( isset( $tabs ) ){

      echo '<div class="LINOADMIN-tab-content">';

        foreach ( $tabs as $tab_title => $tab ) {

          $state = '';
          if ( $content_id == 0 ) $state .= ' content-tab-active';

          echo '<div id="content_' . $content_id . '-LINOADMIN-tab-content-id" class="LINOADMIN-tab-content-item' . $state . '">';

            echo '<div class="linoadmin-container-fullwidth">';
            echo '<div class="linoadmin-row no-gutters">';

              if( isset( $tab ) ){
                foreach ( $tab as $field_id => $field ) {

                  $field['id'] = $field_id;

                  $field['value'] = $data[ $field['id'] ];

                  if ( $field['value'] == "" && $field['default'] != "" ) {

                    $field['value'] =  $field['default'];

                  }

                  if ( file_exists( dirname( dirname( __FILE__ ) ) . '/' . $field['type'] . '/' . $field['type'] . '.php' ) ) {

                    if ( ! $field['col'] ) $field['col'] = "col-12";

                    echo '<div class="linoadmin-'. $field['col'] .'">';

                      include dirname( dirname( __FILE__ ) ) . '/' . $field['type'] . '/' . $field['type'] . '.php';

                    echo '</div>';

                  }

                  if ( isset( $field['help'] ) && $field['help'] === true ) { echo '<pre>'; var_dump($field); echo '</pre>'; }

                }
              }

            echo '</div>';
            echo '</div>';

            $content_id++;

          echo '</div>';

        }

      echo '</div>';

    }

  echo '</div>';

  echo '<div class="list-modal-toolbar-inner"><div class="list-modal-toolbar-inner-left"></div><div class="list-modal-toolbar-inner-right"><div class="list-modal-close button button-large button-error">Cancel</div> <div class="list-item-save button button-large button-success">OK</div></div></div>';

  include dirname( __FILE__ ) . '/list-footer.php';


  die();

}

add_action( 'wp_ajax_handypress_list_edit', 'ajax_handypress_list_edit' );


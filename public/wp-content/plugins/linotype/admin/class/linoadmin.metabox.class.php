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
 * Class to create the metaboxes content.
 *
 * @since 1.0.0
 *
 */
class LINOADMIN_METABOX extends LINOADMIN {

  public $TYPE;
  public $METABOX;
  public $TAB;
  public $TAB_COUNT = 0;
  public $tabs_style;

  /**
   * __construct
   *
   * @since 1.0
   *
   * @param array  $METABOX  the metabox params
   *
   */
  function __construct( $PAGE, $TYPE = 'option', $METABOX = null, $LINOADMIN ) {

    //$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    //$tab_hash = parse_url( $actual_link, PHP_URL_FRAGMENT);

    $this->LINOADMIN = $LINOADMIN;

    $this->PAGE = $PAGE;

    $this->TYPE = $TYPE;

    $TAB = $METABOX['tabs_contents'];

    $tabs_load_hidden = false;

    $tabs_style = $METABOX['tabs_style'];

    if ( ! $tabs_style ) $tabs_style = "h2";

    //remove tab if alone
    if ( $METABOX['tabs_hide_if_one'] !== false && count( $TAB ) <= 1 ) {

        $tabs_style = null;
        $single_tab = true;

    } else {

      $tabs_load_hidden = true;
      $single_tab = false;

    }


    //add individual form to enable metabox save on dashboard
    if ( $this->TYPE == 'dashboard' ) echo '<form id="wpbody-form" method="post">';

    echo '<div class="LINOADMIN">';

    //create the tab
    switch ( $tabs_style ) {

      case 'h2':
      case 'h3':
      case 'sub':
      case 'nav':

        $TAB_ID = 'LINOADMIN-tab-id-' . $this->TAB_COUNT;

        echo '<div id="' . $TAB_ID . '" class="LINOADMIN-tab" data-tab-style="' . $tabs_style . '">';

        if ( $tabs_style == "h2" ) echo '<h2 class="LINOADMIN-tab-nav nav-tab-wrapper">';
        if ( $tabs_style == "h3" ) echo '<h3 class="LINOADMIN-tab-nav nav-tab-wrapper">';
        if ( $tabs_style == "sub" ) echo '<ul class="LINOADMIN-tab-nav subsubsub">';
        if ( $tabs_style == "nav" ) echo '<ul class="LINOADMIN-tab-nav">';

        $nav_count = 0;

        // _HANDYLOG( $TAB );

        if ( $TAB ) {
          foreach ( $TAB as $id => $tab) {

            if ( ( isset( $id ) && $id != "" ) && ( ! isset( $tab['enable'] ) || $tab['enable'] !== false ) ) {

              $id = '' . sanitize_key( $id );// . $this->TAB_COUNT;

              if ( $nav_count == 0 ){
                $state = ' nav-tab-active';
                if ( $tabs_style == "sub" || $tabs_style == "nav" ) $state = ' active current';
              } else{
                $state = '';
              };

              $separator = '';
              if ( $nav_count != count( $TAB ) -1 ) $separator = ' | ';

              if ( ! $tab['label'] ) $tab['label'] = $tab['title'];
              if ( isset( $tab['icon'] ) && $tab['icon'] ) $tab['label'] = '<span class="' . $tab['icon'] . '"></span> ' . $tab['label'];

              if ( $tabs_style == "h2" || $tabs_style == "h3" ) echo '<a id="' . $id . '-LINOADMIN-tab-nav-id" class="LINOADMIN-tab-nav-item nav-tab' . $state . '" href="#' . $id . '" >' . $tab['label'] . '</a>';
              if ( $tabs_style == "sub" ) echo '<li id="tab-' . $id . '" class="' . $state . '" ><a id="' . $id . '-LINOADMIN-tab-nav-id" class="LINOADMIN-tab-nav-item ' . $state . '" href="#' . $id . '" >' . $tab['label'] . '</a></li>'.$separator;
              if ( $tabs_style == "nav" ) echo '<li class="LINOADMIN-tab-nav-item' . $state . '" data-target="#' . $id . '-LINOADMIN-tab-content-id"><span>' . $tab['label'] . '</span></li>';

              $nav_count++;

            }

          }
        }

        if ( $tabs_style == "h2" ) echo '</h2>';
        if ( $tabs_style == "h3" ) echo '</h3>';
        if ( $tabs_style == "sub" ) echo '</ul>';
        if ( $tabs_style == "nav" ) echo '</ul>';

        echo '<div class="LINOADMIN-tab-content">';

          $content_count = 0;

          if ( $TAB ) {
            foreach ( $TAB as $id => $tab ){

              if ( ( isset( $id ) && $id != "" ) && ( ! isset( $tab['enable'] ) || $tab['enable'] !== false ) ) {

                $id = '' . sanitize_key( $id );// . $this->TAB_COUNT;

                $state = '';
                if ( $content_count == 0 ) $state .= ' content-tab-active';

                $style = '';
                if( $tabs_load_hidden ) $style .= 'height:0px;overflow:hidden;';

                echo '<div id="' . $id . '-LINOADMIN-tab-content-id" class="LINOADMIN-tab-content-item' . $state . '" style="' . $style . '">';

                  echo '<div class="linoadmin-container-fullwidth">';
                  echo '<div class="linoadmin-row linoadmin-no-gutters">';

                    if ( isset( $tab['meta'] ) ) $meta = new LINOADMIN_META( $this->PAGE, $this->TYPE, $tab['meta'], $this->LINOADMIN );

                  echo '</div>';
                  echo '</div>';

                echo '</div>';

                $content_count++;

              }

            }
          }

        echo '</div>';

      echo '</div>';

      $this->TAB_COUNT++;

    break;



    case 'side':

      $TAB_ID = 'LINOADMIN-TABID-' . $this->TAB_COUNT;

      //echo '<style>#'.$METABOX_KEY.'.postbox .inside { padding: 0px; margin: 0px; }</style>';

      echo '<div style="display:block;" id="contextual-help-wrap" class="hidden" tabindex="-1" aria-label="' . $TAB_ID . '">';

        echo '<div id="contextual-help-back" style="background:#f2f2f2;right:0px;border-right:none;"></div>';

        echo '<div id="contextual-help-columns">';

          echo '<div class="contextual-help-tabs">';

            echo '<ul style="margin-top:0px;">';

              $nav_count = 0;

              if ( $TAB ) {
                foreach ( $TAB as $id => $tab){

                  if ( ( isset( $id ) && $id != "" ) && ( ! isset( $tab['enable'] ) || $tab['enable'] !== false ) ) {

                    $id = 'linoadmin-' . $id;// . $this->TAB_COUNT;

                    if ( $nav_count == 0 ){
                      $state = 'active';
                      $nofirsttopborder = 'border-top:none;';
                    } else{
                      $state = '';
                      $nofirsttopborder = '';
                    };

                    if ( ! $tab['label'] ) $tab['label'] = $tab['title'];

                    echo '<li id="tab-link-'.$id.'" class="' . $state . '" style="background:#f2f2f2;border-left:none;">';
                      echo '<a href="#tab-panel-'.$id.'" aria-controls="tab-panel-'.$id.'" style="box-shadow:none;'.$nofirsttopborder.'">' . $tab['label'] . '</a>';
                    echo '</li>';

                    $nav_count++;

                  }

                }
              }

            echo '</ul>';
          echo '</div>';

          //echo '<div class="contextual-help-sidebar"><p><strong>For more information:</strong></p><p><a href="https://codex.wordpress.org/Dashboard_Screen" target="_blank">Documentation on Dashboard</a></p><p><a href="https://wordpress.org/support/" target="_blank">Support Forums</a></p></div>';

          echo '<div class="contextual-help-tabs-wrap">';

            $nav_count = 0;

            if ( $TAB ) {
              foreach ($TAB as $id => $tab){

                if ( ( isset( $id ) && $id != "" ) && ( ! isset( $tab['enable'] ) || $tab['enable'] !== false ) ) {

                  $id = 'linoadmin-' . $id;// . $this->TAB_COUNT;

                  if ( $nav_count == 0 ){
                    $state = ' active';
                  } else{
                    $state = '';
                  };

                  echo '<div id="tab-panel-'.$id.'" class="help-tab-content' . $state . '" style="margin: 10px 0px 10px 0px;">';

                    echo '<div class="linoadmin-container-fullwidth">';
                    echo '<div class="linoadmin-row linoadmin-no-gutters">';

                      $meta = new LINOADMIN_META( $this->PAGE, $this->TYPE, $tab['meta'], $this->LINOADMIN );

                    echo '</div>';
                    echo '</div>';

                  echo '</div>';

                  $nav_count++;

                }

              }
            }

          echo '</div>';

        echo '</div>';

      echo '</div>';

      $this->TAB_COUNT++;

    break;



    default:

      echo '<div class="linoadmin-container-fullwidth">';
      echo '<div class="linoadmin-row linoadmin-no-gutters">';

        if ( isset( $TAB[ key( $TAB ) ]['meta'] ) ) $meta = new LINOADMIN_META( $this->PAGE, $this->TYPE, $TAB[ key( $TAB ) ]['meta'], $this->LINOADMIN );

      echo '</div>';
      echo '</div>';

    break;



    }

    //if save
      if ( !empty( $METABOX["bt_save"] ) || !empty( $METABOX["bt_reset"] ) ) {

        echo '<div id="submitdiv">';

        echo '<div class="submitbox" id="submitpost">';

          echo '<div id="major-publishing-actions">';

            //if reset
            if ( !empty( $METABOX["bt_reset"] ) || $METABOX["bt_reset"] === true ) {

              echo '<div id="delete-action">';

                //echo '<input class="button button-small force-align-middle" name="reset" type="submit" value="' . __("reset") . '" />';
                echo '<a class="submitdelete deletion" href="#">Reset</a>';
                //echo '<input type="hidden" name="reset" value="all" />';

              echo '</div>';

            }

            // if ( ! empty( $METABOX["bt_delete"] ) || $METABOX["bt_delete"] === true ) {
            //
            //   echo '<div id="delete-action">';
            //     echo '<a class="submitdelete deletion" href="' . get_delete_post_link($post->ID) . '">' . __("Trash") . '</a>';
            //   echo '</div>';
            //
            // }

            if ( ! empty( $METABOX["bt_save"] ) ) {

              echo '<div id="publishing-action">';

                if ( $this->TYPE == 'option' || $this->TYPE == 'dashboard'  ) {

                  $bt_save_title = __("Save");
                  if ( ! is_bool( $METABOX["bt_save"] ) ) $bt_save_title = $METABOX["bt_save"];

                  //echo '<span class="spinner"></span>';
                  echo '<input class="button-primary force-align-middle" name="save" type="submit" value="' . $bt_save_title . '" /> ';
                  echo '<input type="hidden" name="action" value="save" />';

                } else if ( $this->TYPE == 'post' || $this->TYPE == 'attachment' ) {

                  echo '<span class="spinner"></span>';

                  global $post;

                  $bt_save_title = __("Save");
                  if ( ! is_bool( $METABOX["bt_save"] ) ) $bt_save_title = $METABOX["bt_save"];

                  if ( $post && $post->post_status == 'publish' ){

                    echo '<input name="original_publish" id="original_publish" value="Update" type="hidden">';
                    echo '<input name="save" class="button button-primary button-large force-align-middle" id="publish" value="'. $bt_save_title . '" type="submit">';

                  } else {

                    echo '<input name="original_publish" id="original_publish" value="Publish" type="hidden">';
                    echo '<input name="publish" id="publish" class="button button-primary button-large force-align-middle" value="'. $bt_save_title . '" type="submit">';

                  }

                }

              echo '</div>';

            }

          echo '<div class="clear"></div>';

          echo '</div>';

        echo '</div>';

        echo '</div>';

      }

    echo '</div>';

    if ( $this->TYPE == 'dashboard' ) echo '</form>';

  }

}

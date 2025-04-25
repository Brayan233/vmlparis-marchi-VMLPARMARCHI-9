<?php

global $LINOTYPE_EDITOR;

define('WP_DEBUG_DISPLAY', false );

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-admin/admin.php' );

if ( ! isset( $_REQUEST['id'] ) && ! $_REQUEST['id'] ) {
  wp_die( '<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1>' . '<p>' . 'blocks:error:composer:001' . '</p>', 403 );  
}

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

wp_user_settings();
_wp_admin_html_begin();

wp_enqueue_style( 'colors' );
wp_enqueue_style( 'ie' );
wp_enqueue_script('utils');
wp_enqueue_script( 'svg-painter' );
// wp_enqueue_style( 'composer-iframe', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/composer-iframe.css', false, false, 'screen' );

$body_class = 'wp-core-ui js linotype linotype-editor ' . get_option('linotype_editor_dark','blocks-editor-light' );

if ( wp_is_mobile() ) :
	$body_class .= ' mobile';
	?><meta name="viewport" id="viewport-meta" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=1.2" /><?php
endif;

if ( is_rtl() ) {
	$body_class .= ' rtl';
}

$body_class .= ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_user_locale() ) ) );

$admin_title = '';

?><title><?php echo $admin_title; ?></title>

<script type="text/javascript">
var ajaxurl = <?php echo wp_json_encode( admin_url( 'admin-ajax.php', 'relative' ) ); ?>;
</script>

<style>
html.wp-toolbar {
    padding-top: 0px!important;
    box-sizing: border-box;
}
html,body {
  background-color: #f5f5f5!important;
  background: #f5f5f5!important;
  overflow:hidden;
}
html.wp-toolbar {
  padding-top: 0px;
}
.media-modal {
position: fixed!important;
top: 0px!important;
left: 0px!important;
right: 0px!important;
bottom: 0px!important;
z-index: 160000!important;
}

#wpcontent{
margin:0px 0px 70px 0px!important;
padding:0px!important;
}
#wpbody {
max-width: 100%!important;
margin-left: auto!important;
margin-right: auto!important;
}
#wpbody-content{
margin:0px!important;
padding:0px!important;
}
.wp-filter{
  margin: -1px 0 0px!important;
    border-left: none!important;
    border-right: none!important;
    box-shadow: none!important;
    background-color: #f5f5f5!important;
}

.composer-modal-container {
top: 0px!important;
bottom: 0px!important;
left: 0px!important;
right: 0px!important;
}

.composer-modal-bg {
background: rgba(255, 255, 255, 0.6)!important;
}

.composer-modal-toolbar-inner {
/* margin: 20px 0px 0px 0px;
padding: 20px 20px 20px 20px;
border-top: 1px solid #DDD;
position: fixed;
z-index: 100;
bottom: 0px;
left: 0px;
right: 0px;
background: #FFF;
height: 30px; */
margin: 12px 15px;
position: fixed;
z-index: 100;
top: 0px;
width: 160px;
right: 0px;

height: 30px;
}
.composer-modal-toolbar-inner-left {

}
.composer-modal-toolbar-inner-right {
  position: absolute;
  right: 20px;
}

#wp-auth-check-wrap{
  display:none!important;
}


.list-modal-container {
top: 0px!important;
bottom: 0px!important;
left: 0px!important;
right: 0px!important;
}

.list-modal-bg {
background: rgba(255, 255, 255, 0.6)!important;
}

.list-modal-toolbar-inner {
margin: 20px 0px 0px 0px;
padding: 20px 20px 20px 20px;
border-top: 1px solid #DDD;
position: fixed;
z-index: 100;
bottom: 0px;
left: 0px;
right: 0px;
background: #FFF;
height: 30px;
}
.list-modal-toolbar-inner-left {

}
.list-modal-toolbar-inner-right {
  position: absolute;
  right: 20px;
}

.LINOADMIN-tab-content {
  position: absolute;
  top: 52px;
  bottom: 0px;
  left: 0px;
  right: 0px;
  overflow: auto;
}


.composer-modal-close, .composer-item-save {
  padding: 4px 10px!important;
  line-height: 17px!important;
  font-size: 12px!important;
}

.composer-modal {

    top: 8px!important;
    bottom: 10px!important;
    left: 10px!important;
    right: 8px!important;
    z-index: 99999!important;
    border: 1px solid #ddd;
    margin-left: 0%;
    border-left: 1px solid #ddd;
    overflow: hidden;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
      
}

.media-modal-backdrop {
    background: transparent!important;
}

.media-modal {
    position: fixed!important;
    /* top: 10px!important; */
    /* left: 10px!important; */
    /* right: 10px!important; */
    /* bottom: 10px!important; */
    /* border: 1px solid #eaeaea; */
}
.media-modal-content {
    box-shadow: 0 0px 5px rgba(0,0,0,0.1)!important;
}
.LINOADMIN-tab-nav-item .active {
    box-shadow: none;
    border-bottom: 4px solid #0085ba;
    color: #0085ba;
}
</style>

<?php

do_action( 'linotype_editor_print_styles' );
do_action( 'linotype_editor_print_scripts' );

global $hook_suffix;

do_action( 'admin_enqueue_scripts', $hook_suffix );

  do_action( "admin_print_styles-{$hook_suffix}" );

  do_action( 'admin_print_styles' );

  do_action( "admin_print_scripts-{$hook_suffix}" );

  do_action( 'admin_print_scripts' );

  do_action( "admin_head-{$hook_suffix}" );

  do_action( 'admin_head' );

?>

</head>

<body class="<?php echo esc_attr( $body_class ); ?>">

<?php

switch( $_REQUEST['composer_type'] ) {

  case 'block':

    $LINOTYPE_EDITOR['type'] = 'block';
    $LINOTYPE_EDITOR['data'] = LINOTYPE::$BLOCKS->get($_REQUEST['id']);
    $LINOTYPE_EDITOR['fields'] = LINOTYPE::$FIELDS->get();

    

  break;
  
  case 'field':

    $LINOTYPE_EDITOR['type'] = 'field';
    $LINOTYPE_EDITOR['data'] = LINOTYPE::$FIELDS->get($_REQUEST['id']);
    $LINOTYPE_EDITOR['fields'] = LINOTYPE::$FIELDS->get();

  break;

}

global $LINOTYPE_EDITOR;

    $items = array();

    $data = json_decode( stripslashes( get_option( 'LINOTYPE_composer_temp_settings' ) ), true );

    if ( ! isset( $data['options_mobile'] ) ) $data['options_mobile'] = array();
    if ( ! isset( $data['options_tablet'] ) ) $data['options_tablet'] = array();
    if ( ! isset( $data['options_desktop'] ) ) $data['options_desktop'] = array();

    switch( $_REQUEST['device'] ) {

      case 'mobile':
    
        $data['options'] = $data['options_mobile'];

      break;
      
      case 'tablet':
    
        $data['options'] = $data['options_tablet'];
    
      break;

      case 'desktop':
    
        $data['options'] = $data['options_desktop'];
    
      break;
    
    }

    $item = $LINOTYPE_EDITOR['data'];

    $tabs = array();

    //order by tab
    if( isset( $item['options'] ) ){
      foreach ( $item['options'] as $field_id => $field ) {

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

      if ( isset( $tabs ) && count( $tabs ) > 0 ) {

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
              echo '<div class="linoadmin-row linoadmin-no-gutters">';

                if( isset( $tab ) ){
                  foreach ( $tab as $field_id => $field ) {

                    $field['id'] = $field_id;

                    if ( isset( $data['options'][ $field['id'] ] ) ) {

                      $field['value'] = $data['options'][ $field['id'] ];

                    } else {

                      $field['value'] = "";

                    }

                    if ( isset( $field['options']['placeholder'] ) && $field['default'] != "" ) {

                      $field['options']['placeholder'] = $field['default'];

                      if ( isset( $field['options']['data'] ) && $field['options']['data'] ) {
                        foreach ( $field['options']['data'] as $options_data_key => $options_data ) {

                          if ( $options_data['value'] == $field['default'] ) $field['options']['placeholder'] =  $options_data['title'];

                        }

                      }

                    }

                    if ( isset ( $LINOTYPE_EDITOR['fields'][ $field['type'] ]['dir'] ) && file_exists( $LINOTYPE_EDITOR['fields'][ $field['type'] ]['dir'] . '/' . 'template.php' ) ) {

                        if ( ! $field['col'] ) $field['col'] = "col-12";

                        echo '<div class="linoadmin-'. $field['col'] .'">';
                          
                          include $LINOTYPE_EDITOR['fields'][ $field['type'] ]['dir'] . '/' . 'template.php';
                        
                        echo '</div>';

                    } else {
                      // _HANDYLOG( $field );
                      echo $field['type'];
                      //LINOTYPE::$FIELDS->display('linotype_field_composer', $field );
                      
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

    echo '<div class="composer-modal-toolbar-inner">';

      if ( ! isset( $item['infos'] ) ) $item['infos'] = '';
      
      //echo '<div class="composer-modal-toolbar-inner-left"><textarea style="display:none" class="composer-modal-item-infos" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="">' . $item['infos'] . '</textarea></div>';
      //echo '<div class="composer-modal-toolbar-inner-right"><div class="composer-modal-close button  button-error">Cancel</div> <div class="composer-item-save button  button-success">OK</div></div>';

    echo '</div>';

do_action( 'admin_footer', '' );

do_action( "admin_print_footer_scripts-{$hook_suffix}" );

do_action( 'admin_print_footer_scripts' );

do_action( "admin_footer-{$hook_suffix}" );

do_action( 'linotype_editor_print_footer_scripts' ); ?>

<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
</body>
</html>

<?php

$TRADUCTIONS = get_option( 'linotype_translate' );

if ( $TRADUCTIONS ) { 

  self::$admin->addLocation( 'linotype_theme_traductions', array(
    "type" => 'option',
    "capability" => 'linotype_edit',
    "name"=>'Traductions',
    "subname"=>'Traductions',
    "title"=>'Traductions',
    'order' => 65,
    'margin' => '20px',
    'icon' => 'dashicons-translation',
    "bt_save" => true
  ));

  $wp_lang = strtolower( array_reverse( explode( '_', get_option('WPLANG', 'en') ) )[0] );
  if ( $wp_lang == 'gb' ) $wp_lang = 'uk';
  $current_lang = $wp_lang;
  $translations = array( $wp_lang );

  if ( function_exists('pll_current_language') ) {

    $translations_object = get_terms( 'term_language' );
    
    if ( $translations_object ) {
      foreach( $translations_object as $lang ) {
        array_push( $translations, str_replace( 'pll_', '', $lang->slug ) );
      }
    }
  
  }

  if ( $translations ) {
    foreach( $translations as $lang ) {
      
      if ( $lang == $current_lang ) {
        $lang_title = ' ' . $current_lang;
        $lang_code = '';
      } else {
        $lang_title = ' ' . $lang;
        $lang_code = '_' . $lang;
      }

      self::$admin->addMetabox( 'linotype_theme_traductions_metabox' . $lang_code, array(
      "name"=>'Traductions ' . $lang_title,
      "context"=>'normal',
      "priority"=>'default',
      "force_state" => "open",
      "hide_box_style" => false,
      "hide_handle" => false,
      "disable_switch" => false,
      "disable_sortable" => false,
      "remove_padding" => true,
      "tabs_style"=>"nav",
      ));

      foreach( $TRADUCTIONS as $TRADUCTION_key => $TRADUCTION ) {

        self::$admin->addMeta( '_traduction_' . $TRADUCTION_key . $lang_code, array(

          "title"=> '',
          "type" => LINOTYPE::$FIELDS->get( 'linotype_field_textarea' )['dir'] . 'template.php',
          "desc" => '',
          "info" => '<b>original:</b> ' . $TRADUCTION,
          "options" => array(
            "height" => '30px',
            "placeholder" => $TRADUCTION
          ),
          "col" => 'col-12',
          "tab" => '',
          "help" => false,
          "padding" => "10px",
          "fullwidth" => true,

        ));

      }
        
    }
  }

}

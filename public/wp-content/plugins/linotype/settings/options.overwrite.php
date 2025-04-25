<?php

$GLOBAL_OPTIONS = LINOTYPE::$CURRENT->get();

if ( isset( $GLOBAL_OPTIONS['options'] ) ) {
  $GLOBAL_OPTIONS = $GLOBAL_OPTIONS['options'];
} else {
  $GLOBAL_OPTIONS = [];
}

$all_template = LINOTYPE::$TEMPLATES->get();
$all_modules = LINOTYPE::$MODULES->get();

function find_overwrite_options_ids( $datas = array(), $ids = array() ) {
  
  if ( $datas ) {
    foreach ( $datas as $data_key => $data ) {

      $default_field_options = LINOTYPE::$BLOCKS->get( $data['type'] );

      if ( isset( $data['options']['_overwrite'] ) && $data['options']['_overwrite'] ) {
        
        foreach ( $data['options']['_overwrite'] as $new_key => $new ) {

          if ( isset( $new['overwrite_target'] ) && ( $new['overwrite_target'] == 'both' || $new['overwrite_target'] == 'option' ) ) {
            
            if ( $new['id'] == '_composer_contents' ) {

              $new['type'] = 'linotype_field_composer';

              $default_composer_items_only = array();

              if ( isset( $data['contents'] ) && is_array( $data['contents'] ) ) {
                foreach ( $data['contents'] as $content_key => $content ) {
                  $default_composer_items_only[ $content['type'] ] = $content['type'];
                }
                $default_composer_items_only = array_values( $default_composer_items_only );
              }

              $default_composer_options = array(
                'name' => 'Edit',
                'title' => '',
                'desc' => '',
                'collapsed' => true,
                'default_source' => false,
                'maxDepth' => 5,
                'group' => 1,
                'min_height' => 80,
                'type' => 'block',
                'items' => array(),
                'items_only' => $default_composer_items_only,
                'items_not' => array(),
                'actions' => array( 'add', 'edit' ,'sort', 'clone', 'copy', 'delete' ),
                'border' => true,
                'toolbar' => false,
                'devices' => false,
                'overwrite' => false,
                'empty' => false,
                'layout' => 'default',
                'root_class' => $data['type'],
              );

              if ( current_user_can( 'linotype_admin' ) ) {

                $default_composer_options['actions'] = array( 'add', 'edit' ,'sort', 'clone', 'copy', 'delete', 'source', 'link' );
                $default_composer_options['devices'] = true;
                $default_composer_options['overwrite'] = true;
                $default_composer_options['toolbar'] = true;

              }

              $new['options'] = wp_parse_args( $new['options'], $default_composer_options );

            } else {

              if ( ! isset( $new['meta_id'] ) || $new['meta_id'] == "" ) $new['meta_id'] = $new['id'];
              unset( $default_field_options['options'][ $new['id'] ]['padding'] );
              $new = array_merge( (array) $default_field_options['options'][ $new['id'] ], array_filter( $new ) );

            }
            
            $ids[ $new['meta_id'] ] = $new;
          
          }

        }
        
      }

      if ( isset( $data['contents'] ) ) {
      
        $ids = find_overwrite_options_ids( $data['contents'], $ids );
      
      }

    }
  }

  return $ids;

}

$overwrite_ids = array();

if ( $all_template ){
  foreach ( $all_template as $template_key => $template ) {

    $overwrite_ids = array_merge( find_overwrite_options_ids( $template['template'] ), $overwrite_ids );

  }
}

if ( $all_modules ){
  foreach ( $all_modules as $module_key => $module ) {

    $overwrite_ids = array_merge( find_overwrite_options_ids( $module['module'] ), $overwrite_ids );

  }
}

$OPTIONS = array();

if ( $GLOBAL_OPTIONS ){
  foreach( $GLOBAL_OPTIONS as $GLOBAL_OPTION_key => $GLOBAL_OPTION ) {

    $GLOBAL_OPTION_format = array_merge(
      array(
        "title" => "",
        "info" => "",
        "type" => $GLOBAL_OPTION['type'],
        "options" => [],
        "default" => "",
        "fullwidth" => true,
        "help" => false,
        "col" => "col-4",
        "disabled" => "",
        "desc" => "",
        "tab" => "Globals",
        "id" => $GLOBAL_OPTION['options']['id'],
        "overwrite_target" => "option",
        "meta_id" => '_globals_' . $GLOBAL_OPTION['options']['id'],
        'meta_id_strict' => 'yes',
      ),
      $GLOBAL_OPTION['options']
    );
    
    $OPTIONS[] = $GLOBAL_OPTION_format;

  }
}

if ( $overwrite_ids ) {

  foreach( $overwrite_ids as $overwrite_key => $overwrite ) {
    
    if( isset( $overwrite['overwrite_target'] ) && $overwrite['overwrite_target'] ) $OPTIONS[] = $overwrite;

  }

  if ( $OPTIONS ) { 

    self::$admin->addLocation( 'linotype_theme_options', array(
      "type" => 'option',
      "capability" => 'linotype_edit',
      "name"=>'Options',
      "subname"=>'Options',
      "title"=>'Options',
      'order' => 65,
      'margin' => '20px',
      'icon' => 'dashicons-image-filter',
      "bt_save" => true
    ));

    //start add polylang compatibility
    
    $wp_lang = strtolower( array_reverse( explode( '_', get_option('WPLANG', 'en') ) )[0] );
    if ( $wp_lang == 'gb' ) $wp_lang = 'uk';
    $current_lang = $wp_lang;
    $translations = array( $wp_lang );

    if ( function_exists('pll_current_language') ) {

      $translations_object = get_terms( 'term_language' ); //array_keys( pll_the_languages( array( 'raw' => 1 ) ) );
      
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

        self::$admin->addMetabox( 'linotype_theme_options_metabox' . $lang_code, array(
        "name"=>'Options ' . $lang_title,
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

        foreach( $OPTIONS as $OPTION_key => $OPTION ) {

          $pre_meta_id = '_overwrite_';
          if ( isset( $OPTION['meta_id_strict'] ) && $OPTION['meta_id_strict'] === 'yes' ) $pre_meta_id = '';

          self::$admin->addMeta( $pre_meta_id . $OPTION['meta_id'] . $lang_code, array(

            "title"=> $OPTION['title'],
            "type" => LINOTYPE::$FIELDS->get( $OPTION['type'] )['dir'] . 'template.php',
            "desc" => $OPTION['desc'],
            "options" => $OPTION['options'],
            "col" => $OPTION['col'],
            "tab" => $OPTION['tab'],
            "help" => false,
            "padding" => "10px 20px 20px 20px",
            "fullwidth" => true,

          ));

        }
          
      }
    }

  }

}


<?php

$all_template = LINOTYPE::$TEMPLATES->get();

$current_template_id = '';

if ( LINOADMIN::$post_type && isset( LINOTYPE::$THEME['map'][ LINOADMIN::$post_type ]['types']['single']['template'] ) ) {

    $template_placeholder = 'Autoload';

    if ( get_post_meta( LINOADMIN::$post_id, '_linotype_template', true ) ) {
    
        $current_template_id = get_post_meta( LINOADMIN::$post_id, '_linotype_template', true );
    
    } else {
        
        $current_template_id = LINOTYPE::$THEME['map'][ LINOADMIN::$post_type ]['types']['single']['template'];
    
    }

}

if ( get_post_meta( LINOADMIN::$post_id, '_linotype_template', true  ) ) $current_template_id = get_post_meta( LINOADMIN::$post_id, '_linotype_template', true  );

if ( isset( $all_template[$current_template_id]['template'] ) ) {

  if ( isset( $all_template[$current_template_id]['template'] ) && $all_template[$current_template_id]['template'] ) $current_template = $all_template[$current_template_id]['template'];

  $linotype_enable = null;
  if ( isset( LINOADMIN::$post_id ) ) $linotype_enable = get_post_meta( LINOADMIN::$post_id, '_linotype_content', true );
  if ( LINOTYPE::$SETTINGS['linotype_content_by_default'] && $pagenow == 'post-new.php' ) $linotype_enable = true;
  if (defined('DOING_AJAX') && DOING_AJAX) $linotype_enable = true;

  function find_overwrite_ids( $datas = array(), $ids = array() ) {
    
    if ( is_array( $datas ) && $datas ) {

      foreach ( $datas as $data_key => $data ) {
        
        $default_field_options = LINOTYPE::$BLOCKS->get( $data['type'] );
        
        if ( isset( $data['options']['_overwrite'] ) && $data['options']['_overwrite'] ) {
          
          foreach ( $data['options']['_overwrite'] as $new_key => $new ) {

            $template_value = '';
            if ( isset( $data['options'][ $new['id'] ] ) ) $template_value = $data['options'][ $new['id'] ];
            
            $group = $default_field_options['title'];
            if ( isset( $new['group'] ) && $new['group'] ) $group = $new['group'];

            if ( isset( $new['overwrite_target'] ) && ( $new['overwrite_target'] == 'both' || substr( $new['overwrite_target'], 0, 4 ) === "meta" || $new['overwrite_target'] == 'post_content' ) ) {
              
              if ( $new['overwrite_target'] == 'post_content' ) $new['meta_id'] = 'post_content';

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

              $new['template_value'] = $template_value;

              $ids[$group][ $new['meta_id'] ] = $new;
              
            }

          }
          
        } else {

          if ( isset( $data['options'] ) && $data['options'] ) {

            foreach ( $data['options'] as $data_option_key => $data_option ) {

              $ids = find_overwrite_ids( $data_option, $ids );
            
            }
          
          }

        }
        
        if ( isset( $data['contents'] ) ) {
          
          $ids = find_overwrite_ids( $data['contents'], $ids );
        
        }

      }

    }

    return $ids;

  }
  
  $overwrite_ids = find_overwrite_ids( $current_template );

  if ( $overwrite_ids ) {

    self::$admin->addLocation( 'page', array(
      "capability" => 'linotype_edit',
      "type" => 'post',
    ));
    
    if ( $overwrite_ids ) {
      
      foreach( $overwrite_ids as $overwrite_group_key => $overwrite_group ) {

        if ( $overwrite_group ) {

          $boxtitle = $overwrite_group_key;

          self::$admin->addMetabox( 'composer_metabox_custom_option_' . $overwrite_group_key, array(
            "name"=> $boxtitle,
            "context"=>'normal',
            "priority"=>'high',
            "force_state" => "open",
            "hide_box_style" => false,
            "hide_handle" => false,
            "disable_switch" => false,
            "disable_sortable" => false,
            "remove_padding" => true,
            "tabs_style"=>"nav",
          ));
          
          foreach( $overwrite_group as $overwrite_key => $overwrite ) {

            $pre_meta_id = '_overwrite_';
            if ( isset( $overwrite['meta_id_strict'] ) && $overwrite['meta_id_strict'] === 'yes' ) $pre_meta_id = '';
            
            if ( $overwrite['overwrite_target'] == 'post_content' ) {
              $pre_meta_id = '';
              $overwrite['meta_id'] = 'post_content';
             }

            if ( ! isset( $overwrite['padding'] ) || $overwrite['padding'] == "" ) $overwrite['padding'] = "20px";

            if ( ! is_array( $overwrite['options'] ) ) $overwrite['options'] = array();
            
            $overwrite_options = array(
              "title"=> $overwrite['title'],
              "type" => LINOTYPE::$FIELDS->get( $overwrite['type'] )['dir'] . 'template.php',
              "desc" => $overwrite['desc'],
              "options" => $overwrite['options'],
              "template_value" => $overwrite['template_value'],
              "col" => $overwrite['col'],
              "tab" => $overwrite['tab'],
              "help" => false,
              "padding" => $overwrite['padding'],
              "fullwidth" => true
            );

            self::$admin->addMeta( $pre_meta_id . $overwrite['meta_id'], $overwrite_options );

          }

        }

      }
    }

    self::$admin->clone_settings( 'page', LINOADMIN::$post_type );
    
  }

  
}

<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 *
 * LINOTYPE_themes
 *
 */
class LINOTYPE_current {

  public $ID;

  public $CURRENT;
  
  /*
   *
   * __construct
   *
   */
  function __construct( $id ) {
    
    $this->ID = $id;

    $this->CURRENT = $this->init( $id );

  }

  public function get() {
    
    return $this->CURRENT;

  }

  public function init() {
    
    $THEME = LINOTYPE::$THEMES->get( $this->ID );

    //get templates from theme map and post overwrite
    $THEME['templates'] = $this->get_active_templates( $THEME['map'] );

    //get modules from all active templates
    $THEME['modules'] = $this->get_active_modules( $THEME['templates'] );

    //get blocks from all templates 
    $THEME['blocks'] = $this->get_active_blocks( $THEME['templates'], $THEME['modules'] );
    
    //get all fields from active blocks
    $THEME['fields'] = $this->get_active_fields( $THEME['blocks'] );
    
    //get all libraries from active blocks
    $THEME['libraries'] = $this->get_active_libraries( $THEME['blocks'] );
    
    return $THEME;

  }

  public function get_active_templates( $theme_map ) {

    $templates = array();
    
    if ( $theme_map ) {

      foreach ( $theme_map as $map_key => $map ) {

        foreach ( $map['types'] as $type_key => $type ) {

          if ( isset( $type['template'] ) && $type['template'] ) $templates[ $type['template'] ] = $type['template'];

          if ( isset( $type['rules'] ) && $type['rules'] ) {

            foreach ( $type['rules'] as $rule_key => $rule ) {

              if ( $rule['template'] ) $templates[ $rule['template'] ] = $rule['template'];

            }

          }

        }

      }

    }

    return array_unique( array_merge( array_values( $templates ), array_filter( array_values( LINOTYPE_helpers::get_meta_values('_linotype_template') ) ) ) );

  }




  public function get_active_modules( $template_ids = array() ) {

    $modules = array();

    function get_modules( $data, $modules = array() ) {

      foreach ( $data as $data_key => $data ) {
  
        if ( isset( $data['contents'] ) && $data['contents'] ) {
  
          if ( strpos( $data['type'], '_module_') !== false ) $modules[$data['type']] = $data['type'];

          $modules = array_merge( $modules, get_modules( $data['contents'], $modules ) );
  
        } else {

          if ( strpos( $data['type'], '_module_') !== false ) $modules[$data['type']] = $data['type'];
        
        }

      }
  
      return $modules;
  
    }

    foreach ( $template_ids as $template_id ) {

      $template = LINOTYPE::$TEMPLATES->get( $template_id );

      if ( $template['template'] ) $modules = array_merge( $modules, get_modules( $template['template'] ) );

    }

    return array_unique( array_values( $modules ) );

  }




  public function get_active_blocks( $template_ids = array(), $module_ids = array() ) {

    $blocks = array();

    function get_blocks( $data, $blocks = array() ) {

      foreach ( $data as $data_key => $data ) {
  
        if ( isset( $data['contents'] ) && $data['contents'] ) {
  
          if ( strpos( $data['type'], '_block_') !== false ) $blocks[$data['type']] = $data['type'];

          $blocks = array_merge( $blocks, get_blocks( $data['contents'], $blocks ) );
  
        } else {

          if ( strpos( $data['type'], '_block_') !== false ) $blocks[$data['type']] = $data['type'];
        
        }

      }
  
      return $blocks;
  
    }

    foreach ( $template_ids as $template_id ) {

      $template = LINOTYPE::$TEMPLATES->get( $template_id );

      if ( $template['template'] ) $blocks = array_merge( $blocks, get_blocks( $template['template'] ) );

    }

    foreach ( $module_ids as $module_id ) {

      $module = LINOTYPE::$MODULES->get( $module_id );
      
      if ( $module['module'] ) $blocks = array_merge( $blocks, get_blocks( $module['module'] ) );

    }

    return array_unique( array_values( $blocks ) );

  }


  public function get_active_fields( $block_ids = array() ) {

    $fields = array();

    function loop_get_fields( $data, $fields = array() ) {

      foreach ( $data as $data_key => $data ) {
  
        if ( isset( $data['contents'] ) && $data['contents'] ) {
  
          if ( strpos( $data['type'], '_field_') !== false ) $fields[$data['type']] = $data['type'];

          $fields = array_merge( $fields, loop_get_fields( $data['contents'], $fields ) );
  
        } else {

          if ( strpos( $data['type'], '_field_') !== false ) $fields[$data['type']] = $data['type'];
        
        }

      }
  
      return $fields;
  
    }

    foreach ( $block_ids as $block_id ) {

      $block = LINOTYPE::$BLOCKS->get( $block_id );

      if ( $block['options'] ) $fields = array_merge( $fields, loop_get_fields( $block['options'] ) );

    }
    
    return array_unique( array_values( $fields ) );

  }


  public function get_active_libraries( $block_ids = array() ) {

    $libraries = array();

    foreach ( $block_ids as $block_id ) {

      $block = LINOTYPE::$BLOCKS->get( $block_id );
    
      if ( $block['libraries'] ) $libraries = array_merge( $libraries, $block['libraries'] );

    }
    
    return array_unique( array_values( $libraries ) );

  }

  
}

<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*
* LINOTYPE_admin
*
**/
class LINOTYPE_admin {

  function __construct(){
    
    $post_types = handypress_helper::all_post_types();

    if ( $post_types ) {
      foreach ( $post_types as $key => $type ) {
        
        add_filter( 'manage_' . $type['value'] . '_posts_columns', array( $this, 'template_columns' ) );
        add_action( 'manage_' . $type['value'] . '_posts_custom_column', array( $this, 'template_column' ), 10, 2);
      
      }
    }
    
  }


  public function template_columns( $columns ) {
      
    $pos = array_search('title', array_keys($columns)) + 1;

    $columns = array_slice($columns, 0, $pos, true) + array("_linotype_template" => __( 'Template' )) + array_slice($columns, $pos, count($columns)-$pos, true);

    return $columns;

  }


  public function template_column( $column, $post_id ) {
   
    if ( '_linotype_template' === $column ) {

      $template_id = get_post_meta( $post_id, '_linotype_template', true );
      $template = LINOTYPE::$TEMPLATES->get( $template_id );

      if ( isset( $template['editor_link'] ) && $template['editor_link'] ) {

        $html = '<span style="background: #2c74aa; color: #fff; padding: 1px 4px; border-radius: 3px;">' . $template['title'] . '</span>';
        if ( current_user_can('linotype_save') ) $html .= '  - <a title="' . $template_id . '" href="' . $template['editor_link'] . '">edit</a>';

      } else {

        $html = '-';

      }
      
      echo $html;

    }
  }

}

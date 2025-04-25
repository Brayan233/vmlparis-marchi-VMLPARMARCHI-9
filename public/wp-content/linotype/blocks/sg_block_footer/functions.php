<?php

function sg_block_footer_list( $array, $settings, $i = 0 ) {
	
  $i++; 
  
  if ( empty($array) ) return '';
  
  $level = null;
  if ( isset( $settings['level_' . $i ] ) ) $level = $settings['level_' . $i ];
  
  $class_div = '';
  if ( isset( $level['div'] ) && $level['div'] ) $class_div = ' class="' . $level['div'] . '"';

  $class_ul = '';
  if ( isset( $level['ul'] ) && $level['ul'] ) $class_ul = ' class="' . $level['ul'] . '"';
  
  $class_li = '';
  if ( isset( $level['li'] ) && $level['li'] ) $class_li = ' class="' . $level['li'] . '"';
  
  $class_a = '';
  if ( isset( $level['a'] ) && $level['a'] ) $class_a = ' class="' . $level['a'] . '"';
  
  $class_a_child = '';
  if ( isset( $level['a_child'] ) && $level['a_child'] ) $class_a_child = ' class="' . $level['a_child'] . '"';
  
  $before = '';
  if ( isset( $level['before'] ) && $level['before'] ) $before = $level['before'];
  
  $after = '';
  if ( isset( $level['after'] ) && $level['after'] ) $after = $level['after'];

  $output = '';

  if ( $class_div !== "" ) $output .= '<div ' . $class_div . '>';
    
    $output .= '<ul' . $class_ul . '>';
    
        foreach ( $array as $subArray ) {

            $output .= '<li' . $class_li . '>';
                $class_current = $class_a;
                if ( $subArray->children ) $class_current = $class_a_child;
                $output .= '<a href="' . $subArray->url . '"' . $class_current . ' data-title="' . $subArray->title . '">' . $before . $subArray->title . $after . '</a>';
                $output .= sg_block_footer_list( $subArray->children, $settings, $i );
            $output .= '</li>';

        }
    
    $output .= '</ul>';

  if ( $class_div !== "" ) $output .= '</div>';

  return $output;
  
}

function sg_block_footer_tree( $menu_id = "" ) {
    
    if ( ! $menu_id ) return;

    if ( strpos( '[', $menu_id ) !== -1 ) {

        $slug = str_replace( ['[',']'],['',''], $menu_id );

        if( $locations = get_nav_menu_locations() ) $menu_id = $locations[ $slug ];

    }

  $nav_menu_items_array = wp_get_nav_menu_items( $nav_menu_items_array );
    
  	if ( $nav_menu_items_array ) {
      foreach ( $nav_menu_items_array as $key => $value ) {
          $value->children = array();
          $nav_menu_items_array[ $key ] = $value;
      }
    }

    $nav_menu_levels = array();
    $index = 0;
    if ( ! empty( $nav_menu_items_array ) ) do {
        if ( $index == 0 ) {
            foreach ( $nav_menu_items_array as $key => $obj ) {
                if ( $obj->menu_item_parent == 0 ) {
                    $nav_menu_levels[ $index ][] = $obj;
                    unset( $nav_menu_items_array[ $key ] );
                }
            }
        } else {
            foreach ( $nav_menu_items_array as $key => $obj ) {
                if ( in_array( $obj->menu_item_parent, $last_level_ids ) ) {
                    $nav_menu_levels[ $index ][] = $obj;
                    unset( $nav_menu_items_array[ $key ] );
                }
            }
        }
        $last_level_ids = wp_list_pluck( $nav_menu_levels[ $index ], 'db_id' );
        $index++;
    } while ( ! empty( $nav_menu_items_array ) );

    $nav_menu_levels_reverse = array_reverse( $nav_menu_levels );

    $nav_menu_tree_build = array();
    $index = 0;
    if ( ! empty( $nav_menu_levels_reverse ) ) do {
        if ( count( $nav_menu_levels_reverse ) == 1 ) {
            $nav_menu_tree_build = $nav_menu_levels_reverse;
        }
        $current_level = array_shift( $nav_menu_levels_reverse );
        if ( isset( $nav_menu_levels_reverse[ $index ] ) ) {
            $next_level = $nav_menu_levels_reverse[ $index ];
            foreach ( $next_level as $nkey => $nval ) {
                foreach ( $current_level as $ckey => $cval ) {
                    if ( $nval->db_id == $cval->menu_item_parent ) {
                        $nval->children[] = $cval;
                    }
                }
            }
        }
    } while ( ! empty( $nav_menu_levels_reverse ) );

    $nav_menu_object_tree = $nav_menu_tree_build[ 0 ];
    return $nav_menu_object_tree;
    
}

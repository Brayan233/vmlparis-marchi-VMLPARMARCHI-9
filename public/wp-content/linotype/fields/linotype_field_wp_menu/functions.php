<?php

register_nav_menu( 'general', 'General' );

register_nav_menu( 'header', 'Header' );
register_nav_menu( 'footer', 'Footer' );

register_nav_menu( 'primary', 'Primary' );
register_nav_menu( 'secondary', 'Secondary' );

register_nav_menu( 'extra_01', 'Extra 01' );
register_nav_menu( 'extra_02', 'Extra 02' );
register_nav_menu( 'extra_03', 'Extra 03' );
register_nav_menu( 'extra_04', 'Extra 04' );

//check if menu location and retun menu id
function linotype_field_wp_menu_location( $menu = 'general' ) {
  
  if ( strpos( '[', $menu ) !== -1 ) {
    
    $slug = str_replace( ['[',']'],['',''], $menu );

    if( $locations = get_nav_menu_locations() ) return $locations[ $slug ];
    
  }
	
  return false;
  
}

function linotype_field_wp_menu_list( $array, $settings, $i = 0 ) {
	
  $i++; 
  
  if ( empty($array) ) return '';
  
  $level = null;
  if ( isset( $settings['level_' . $i ] ) ) $level = $settings['level_' . $i ];
  
  $class_div = '';
  if ( isset( $level['div'] ) && $level['div'] ) $class_div = ' class="' . $level['div'] . '"';

  $class_ul = '';
  if ( isset( $level['ul'] ) && $level['ul'] ) $class_ul = ' class="' . $level['ul'] . '"';
  
  $class_li = '';
  if ( isset( $level['li'] ) && $level['li'] ) $class_li = $level['li'];
  
  $class_a = '';
  if ( isset( $level['a'] ) && $level['a'] ) $class_a = $level['a'];
  
  $class_a_child = '';
  if ( isset( $level['a_child'] ) && $level['a_child'] ) $class_a_child = $level['a_child'];
  
  $before = '';
  if ( isset( $level['before'] ) && $level['before'] ) $before = $level['before'];
  
  $after = '';
  if ( isset( $level['after'] ) && $level['after'] ) $after = $level['after'];

  $output = '';

  if ( $class_div !== "" ) $output .= '<div ' . $class_div . '>';
    
    $output .= '<ul' . $class_ul . '>';
    
        foreach ( $array as $subArray ) {

            $class_current = '';
            if ( $subArray->current ) $class_current = ' current';

            $class_active = '';
            //if ( $subArray->active ) $class_active = ' active';

            $output .= '<li class="' . $class_li . $class_current . '">';
                $class_link = $class_a;
                if ( $subArray->children ) $class_link = $class_a_child;
                $noopener = '';
                if ( $subArray->target == '_blank' ) $noopener = 'rel="noopener"';
                $output .= '<a href="' . $subArray->url . '" target="' . $subArray->target . '" ' . $noopener . ' class="' . $class_link . $class_active . '">' . $before . $subArray->title . $after . '</a>';
                $output .= linotype_field_wp_menu_list( $subArray->children, $settings, $i );
            $output .= '</li>';

        }
    
    $output .= '</ul>';

  if ( $class_div !== "" ) $output .= '</div>';

  return $output;
  
}

function linotype_field_wp_menu_tree( $nav_slug ) {

    $parse_url = array_merge( array( "path" => "", "query" => "" ), parse_url( $_SERVER['REQUEST_URI'] ) );

    $current_url_path = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $parse_url['path'];
    $current_url_path_arg = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $parse_url['path'] . $parse_url['query'];

    if ( wp_get_nav_menu_object( intval( $nav_slug ) ) ) {
        
        $current_parent_id = 0;
        $nav_menu_items_array = wp_get_nav_menu_items( $nav_slug );

        foreach( $nav_menu_items_array as $key => $nav_menu_item ) {
            if ( $current_url_path == $nav_menu_item->url || $current_url_path_arg == $nav_menu_item->url ) {
                $nav_menu_items_array[ $key ]->active = "active";
                $nav_menu_items_array[ $key ]->current = "current";
                $current_parent_id = (int) $nav_menu_item->menu_item_parent;
            } else {
                $nav_menu_items_array[ $key ]->active = "";
                $nav_menu_items_array[ $key ]->current = "";
            }
        }
        if ( $current_parent_id ) {
            foreach( $nav_menu_items_array as $key => $nav_menu_item ) {
                if ( $current_parent_id == (int) $nav_menu_item->db_id ) {
                    $nav_menu_items_array[ $key ]->current = "current";
                    $nav_menu_items_array[ $key ]->active = "active";
                    $current_parent_id = (int) $nav_menu_item->menu_item_parent;
                }
            }
        }
        if ( $current_parent_id ) {
            foreach( $nav_menu_items_array as $key => $nav_menu_item ) {
                if ( $current_parent_id == (int) $nav_menu_item->db_id ) {
                    $nav_menu_items_array[ $key ]->current = "current";
                    $nav_menu_items_array[ $key ]->active = "active";
                    $current_parent_id = (int) $nav_menu_item->menu_item_parent;
                }
            }
        }
        
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

    } else {
        
        return false;

    }

}

function linotype_field_wp_menu( $menu = 'general', $settings = array() ) {

    return linotype_field_wp_menu_list( linotype_field_wp_menu_tree( linotype_field_wp_menu_location( $menu ) ), $settings );

}

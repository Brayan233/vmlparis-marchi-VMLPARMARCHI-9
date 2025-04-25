<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*
* LINOTYPE_helpers
*
**/
class LINOTYPE_helpers {

  static $THEME;

  /*
   *
   * get_theme
   *
   */
  static function get_theme( $only_current = false ){

    $theme = self::get_theme_map();

    $templates = handypress_db::get_all_data( 'composer_templates' );

    //add existing template
    if ( $templates ) {
      foreach ( $templates as $template_key => $template ) {

        //get index template
        if ( $template['ID'] == get_option('_composer_theme_template_default') ) {

          $theme['index']['single'][ $template['ID'] ] = $template;

        } else {

          //define template type
          $target = explode( '_', $template['type'] );
          if ( ! isset( $target[1] ) ) $target[1] = 'single';


          $template['filters'] = array_filter( (array) $template['filters'], function($var){ if ( $var ) { return $var; } } );

          if( ! is_admin() && $template['filters'] ){

            if ( $target[1] == 'single' ){

              if ( $template['filters']['if_post_id'] && $template['filters']['if_post_id'] == get_queried_object_id() ) {

                $theme[ $target[0] ][ $target[1] ][ $template['ID'] ] = $template;

              }

            }

          } else {

            $theme[ $target[0] ][ $target[1] ][ $template['ID'] ] = $template;

          }

        }

      }
    }

    if ( $only_current ) {

      foreach ( $theme as $template_types_key => $template_types ) {
        if ( $template_types ) {
          foreach ( $template_types as $template_type_key => $templates ) {

            if ( $templates ) {
              $theme[ $template_types_key ][ $template_type_key ] = array_keys( $templates )[0];
            }

          }
        }
      }
    }

    LINOTYPE_helpers::$THEME = $theme;

    return  $theme;

  }

  /*
   *
   * get_theme_map
   *
   */
   static function get_element_map( $element_id = null ) {

     if( $element_id ) {

       //loops result
       $MAP = array();

       //get content
       $element = handypress_db::get_row_data( 'composer_elements', $element_id );

       //loops result
       $loops = array();

       //regex params
       $content = $element['template'];
       $wrap_in = "\[\[";
       $loops_close = "\/";
       $loops_id = ".*?";
       $wrap_out = "\]\]";
       $tag_in = $wrap_in . $loops_id . $wrap_out;
       $tag_out = $wrap_in . $loops_close . $loops_id . $wrap_out;

       //get loop ids
       $regex = "/" . $wrap_in . "(?!" . $loops_close . ")(" . $loops_id . ")" . $wrap_out . "/";
       preg_match_all( $regex, $content, $loops_found_ids );

       //make it unique
       $loops_ids = array();
       if( isset( $loops_found_ids[1] ) ) $loops_ids = array_values( $loops_found_ids[1] );

       //extract loops
       if ( $loops_ids ) {

         foreach ( $loops_ids as $loops_id ) {

           //get loop
           $regex = "/" . $wrap_in . $loops_id . $wrap_out . "((.|\n)*?)" . $wrap_in . $loops_close . $loops_id . $wrap_out . "/si";
           preg_match_all( $regex, $content, $loops_found );

           if( isset( $loops_found[0][0] ) ) {

             //add loop
             $loops[$loops_id]['id'] = $loops_id;
             $loops[$loops_id]['tag'] = '[[' . $loops_id . ']]';

             //get options inside loops
             preg_match_all( "/" . "\{\{" . "(" . ".*?" . ")" .  "\}\}" . "/", $loops_found[1][0], $options_found_ids );

             //add options
             if ( isset( $options_found_ids[1] ) ){
               foreach ( $options_found_ids[1] as $option_id ) {
                   $loops[$loops_id]['options'][$option_id]['id'] = $option_id;
                   $loops[$loops_id]['options'][$option_id]['tag'] = '{{' . $option_id . '}}';
               }
             }

             //add match
             $loops[$loops_id]['match'] = $loops_found[0][0];

             //add inner
             $loops[$loops_id]['inner'] = $loops_found[1][0];

             //remove matching loop to unmatch inner loop
             $content = str_replace( $loops_found[0], '', $content );

           }

         }

       }

     }

     $options = array();

     //get options outside loops
     preg_match_all( "/" . "\{\{" . "(" . ".*?" . ")" .  "\}\}" . "/", $content, $options_found_ids );

     //make it unique
     if ( isset( $options_found_ids[1] ) ){
       foreach ( $options_found_ids[1] as $option_id ) {
           $options[$option_id]['id'] = $option_id;
           $options[$option_id]['tag'] = '{{' . $option_id . '}}';
       }
     }

     $MAP = array( 'element' => $element, 'settings' => array( 'options' => $options, 'loops' => $loops ) );

     return $MAP;

   }

   static function get_element_remap( $field ){

     if ( ! is_array( $field['value'] ) ) $field['value'] = json_decode(  $field['value'], true );

     $field['default'] = array_values( $field['default'] );

     if ( $field['value'] ) {
     	foreach ( $field['value'] as $value_key => $value ) {

     		$field['_value'][$value['id']] = $value;

     		if ( isset( $field['_value'][$value['id']]['loop'] ) ) {

     			if ( ! is_array( $field['_value'][$value['id']]['loop'] ) ) $field['_value'][$value['id']]['loop'] = json_decode(  $field['_value'][$value['id']]['loop'], true );

          $reloop = array();

     			if ( $field['_value'][$value['id']]['loop'] ) {
     				foreach ( $field['_value'][$value['id']]['loop'] as $loop_key => $loop ) {

     					$reloop[ $loop['id'] ] = $loop;

     				}
     			}

     			$field['_value'][$value['id']]['loop'] = $reloop;

     		}

     	}
     }
     if ( $field['default'] ) {
     	foreach ( $field['default'] as $map_key => $map ) {

     		$field['options']['remap'][$map['id']] = $map;

        if ( isset( $field['options']['remap'][$map['id']]['loop'] ) ) {

     			if ( ! is_array( $field['options']['remap'][$map['id']]['loop'] ) ) $field['options']['remap'][$map['id']]['loop'] = json_decode(  $field['options']['remap'][$map['id']]['loop'], true );

     			if ( $field['options']['remap'][$map['id']]['loop'] ) {
     				foreach ( $field['options']['remap'][$map['id']]['loop'] as $loop_key => $loop ) {

     					$reloop[ $loop['id'] ] = $loop;

     				}
     			}

     			$field['options']['remap'][$map['id']]['loop'] = $reloop;

     		}

     	}
     }
     if ( isset( $field['_value'] ) && $field['_value'] ) {
     	foreach ( $field['options']['remap'] as $map_key => $map ) {

     		//if new option
     		if ( ! isset( $field['_value'][$map_key] ) ) $field['_value'][$map_key] = $map;

     		if ( $map['type'] == 'loops' ) {

     			$loop_map = array();

     			if ( $map['loop'] ) {
     				foreach ( $map['loop'] as $item_map_key => $item_map ) {

     					//if new loop option
     					if ( ! isset( $field['_value'][$map_key]['loop'][ $item_map['id'] ] ) ) $field['_value'][$map_key]['loop'][ $item_map['id'] ] = $item_map;

     				}
     			}


     		}

     	}
     }
     if ( isset( $field['_value'] ) && $field['_value'] ) {
     	foreach ( $field['_value'] as $map_key => $map ) {

     		//if new option
     		if ( ! isset( $field['options']['remap'][$map_key] ) ) unset( $field['_value'][$map_key] );

     		if ( $map['type'] == 'loops' ) {

     			$loop_map = array();

     			if ( $map['loop'] ) {
     				foreach ( $map['loop'] as $item_map_key => $item_map ) {

              //if new loop option
     					if ( ! isset( $field['options']['remap'][$map_key]['loop'][ $item_map_key ] ) ) {

                //$field['_value'][$map_key]['loop'][ $item_map['id'] ]['title'] = $field['_value'][$map_key]['loop'][ $item_map['id'] ]['title'] . ' [unused]';
                unset( $field['_value'][$map_key]['loop'][ $item_map['id'] ] );

              }

     				}
     			}


     		}

     	}
     }

     $return = '';
     if ( isset( $field['_value'] ) && $field['_value'] ) $return = $field['_value'];

     return $return;

   }

  static function get_element( $element = null, $data = array() ) {

    //regex params
    $content = $element['template'];
    $wrap_in = "\[\[";
    $loops_close = "\/";
    $loops_id = ".*?";
    $wrap_out = "\]\]";
    $tag_in = $wrap_in . $loops_id . $wrap_out;
    $tag_out = $wrap_in . $loops_close . $loops_id . $wrap_out;

    //get loop ids
    $regex = "/" . $wrap_in . "(?!" . $loops_close . ")(" . $loops_id . ")" . $wrap_out . "/";
    preg_match_all( $regex, $content, $loops_found_ids );

    //make it unique
    $loops_ids = array();
    if( isset( $loops_found_ids[1] ) ) $loops_ids = array_values( $loops_found_ids[1] );

    //extract loops
    if ( $loops_ids ) {

      foreach ( $loops_ids as $loops_id ) {

        //$loop = '';

        //get loop
        $regex = "/" . $wrap_in . $loops_id . $wrap_out . "((.|\n)*?)" . $wrap_in . $loops_close . $loops_id . $wrap_out . "/si";
        preg_match_all( $regex, $content, $loops_found );

        if( isset( $loops_found[0][0] ) ) {

          $loop = '';

          //get options inside loops
          preg_match_all( "/" . "\{\{" . "(" . ".*?" . ")" .  "\}\}" . "/", $loops_found[1][0], $options_found_ids );

          if ( isset( $data[$loops_id] ) ){
            foreach ( $data[$loops_id] as $data_loop ) {

              $inner = $loops_found[1][0];

              if ( isset( $options_found_ids[1] ) ){
                foreach ( $options_found_ids[1] as $option_id ) {


                    if( ! isset( $data_loop['settings'][$option_id] ) ) $data_loop['settings'][$option_id] = '';

                    if ( $data_loop['settings'][$option_id] == '' && isset( $element['settings'][ $loops_id ]['options']['items'][ $loops_id ]['settings'][$option_id]['default'] ) ) $data_loop['settings'][$option_id] = $element['settings'][ $loops_id ]['options']['items'][ $loops_id ]['settings'][$option_id]['default'];

                    $inner = str_replace( '{{' . $option_id . '}}', $data_loop['settings'][$option_id], $inner );

                }
              }

              $loop .= $inner;

            }
          }

          //remove matching loop to unmatch inner loop
          $content = str_replace( $loops_found[0][0], $loop, $content );

        }

      }

    }

    //get options outside loops
    preg_match_all( "/" . "\{\{" . "(" . ".*?" . ")" .  "\}\}" . "/", $content, $options_found_ids );

    //make it unique
    if ( isset( $options_found_ids[1] ) ){
      foreach ( $options_found_ids[1] as $option_id ) {

          if( ! isset( $data[$option_id] ) ) $data[$option_id] = '';

          if ( $data[$option_id] == '' && isset( $element['settings'][ $option_id ]['default'] ) ) $data[$option_id] = $element['settings'][ $option_id ]['default'];

          $content = str_replace( '{{' . $option_id . '}}', $data[$option_id], $content );

      }
    }


    return $content;

  }

  static function get_element_expert( $element = null, $data = array() ) {

    include COMPOSER::$plugin['dir'] . 'themes/default/inc/elements/' . $data['_type'] . '/template.php';

  }

  static function get_element_fields( $FIELD = null ){
    
    
    if ( $FIELD ) {

      $COMPOSER_fields = new COMPOSER_fields();

    // if ( isset( LINOTYPE::$FIELDS->ITEMS ) && LINOTYPE::$FIELDS->ITEMS ) {
    //   foreach( LINOTYPE::$FIELDS->ITEMS as $ITEM_KEY => $ITEM ){

        //$COMPOSER_fields->add( $FIELD['id'], $FIELD['field_id'] , $FIELD['field_title'], $FIELD['field_desc'], $FIELD['field_options']);
      
    //   }

    // }
    
    //$FIELDS = $COMPOSER_fields->get();

    } 

    // _HANDYLOG( $COMPOSER_fields );

    return $COMPOSER_fields;

  }

  static function get_element_options(){
    
    $COMPOSER_fields = new COMPOSER_fields();

    $COMPOSER_fields->add( 'text', 'text', 'Text', '', array(
      "style" => "",
      "placeholder" => "",
    ));

    $COMPOSER_fields->add( 'textarea', 'textarea', 'Textarea', '', array(
      "height" => "",
      "style" => "",
      "placeholder" => "",
    ));

    $COMPOSER_fields->add( 'googlefonts', 'googlefonts', 'Google Font', '', array(

    ));

    $COMPOSER_fields->add( 'checkbox', 'checkbox', 'Checkbox', '', array(
      "label" => "",
      "style" => "",
    ));

    $COMPOSER_fields->add( 'selectize', 'selectize', 'Select', '', array(
      "data" => array(
        array('title' => 'Default', 'value' => 'default' ),
      ),
      "plugins" => array('restore_on_backspace','drag_drop','remove_button','optgroup_columns'),
      'clear_button' => true,
      "custom" => false,
      "placeholder" => "",
      "delimiter" => ',',
      "persist" => false,
      "maxOptions" => 1000,
      "maxItems" => 1,
      "hideSelected" => false,
      "allowEmptyOption" => true,
      "closeAfterSelect" => true,
    ));

    $COMPOSER_fields->add( 'selectize', 'wp_menu', 'WP Menu', '', array(
      "data" => 'handypress_helper::get_menus',
      "plugins" => array('restore_on_backspace','drag_drop','remove_button','optgroup_columns'),
      'clear_button' => true,
      "custom" => false,
      "placeholder" => "",
      "delimiter" => ',',
      "persist" => false,
      "maxOptions" => 1000,
      "maxItems" => 1,
      "hideSelected" => false,
      "allowEmptyOption" => true,
      "closeAfterSelect" => true,
    ));

    $COMPOSER_fields->add( 'image', 'image', 'Image', '', array(
      "output" => "url",
      "height" => "",
      "wrapstyle" => "",
      "imgstyle" => "",
      "input" => false,
      "placeholder" => "",
    ));

    $COMPOSER_fields->add( 'editor', 'editor', 'Editor', '', array(
      "hide_tools" 		=> false,
      "hide_toolbar" 		=> false,
      "media_buttons" 	=> true,
      "height" 			=> "",
      "toolbar" 			=> array(),
      "show_menubar" 		=> false,
      "hide_statusbar" 	=> false,
      "autoheight" 		=> true,
      "placeholder" 		=> "",
      "style" =>  "",
    ));


    $COMPOSER_fields->add( 'json', 'json', 'Json', '', array(
      "data" => "",
    ));

    $COMPOSER_fields->add( 'dashicons', 'dashicons', 'Dashicons', '', array());

    $COMPOSER_fields->add( 'icons', 'icons', 'Icons', '', array());

    $COMPOSER_fields->add( 'color', 'color', 'Color', '', array(
    	'alpha' => false,
    	'default' => '#F1F1F1',
    ));

    $FIELDS = $COMPOSER_fields->get();


    return $FIELDS;

  }

  /*
   *
   * get_theme_map
   *
   */
  static function get_theme_map(){

    $map = array();

    //add post type
    $post_types = get_post_types( array( 'public'   => true ), 'objects' );

    if ( $post_types ) {
      foreach ( $post_types as $key => $post_type ) {

        $POSTS = array();

        $METAS = array();

        $args = array(
          'post_type' => $post_type->name,
          'post_status' => 'publish',
          'posts_per_page' => -1,
        );

        $query = new WP_Query( $args );

        $rows = $query->get_posts();

        if ( $rows ) {
          foreach ( $rows as $post ) {

            $POSTS[ $post->ID ] = array(
                "ID" => $post->ID,
                "title" => $post->post_title ,
                //'metas' => get_post_meta( $post->ID ),
                //"link" => get_permalink( $post->ID ),
                //"post" => $post,
            );

          }
        }

        $map[ $post_type->name ]['infos']["title"] = $post_type->labels->name;
        $map[ $post_type->name ]['infos']["slug"] = $post_type->name;
        $map[ $post_type->name ]['infos']["posts"] = $POSTS;
        // $map[ $post_type->name ]['infos']['metas'] = $METAS;

        $map[ $post_type->name ]['types']['single'] = array( "title" => __("Single"), "template" => "", "templates" => "", "target" => $POSTS, "rules" => array() );

        if ( get_post_type_object( $post_type->name )->has_archive OR $post_type->name == 'post' ) $map[ $post_type->name ]['types']['archive'] = array( "title" => __("Archive"), "template" => "", "templates" => "", "target" => array(), "rules" => array() );

      }
    }

    //add builtin
    $map['author']['infos'] = array( "title" => __('Author'), "slug" => 'author' );
    $map['author']['types'] = array(
      'single' => array( "title" => __("Single"), "template" => "", "templates" => "", "target" => array(), "rules" => array() ),
    );
    $map['error']['infos'] = array( "title" => __('Error'), "slug" => 'error' );
    $map['error']['types'] = array(
      'single' => array( "title" => __("Single"), "template" => "", "templates" => "", "target" => array(), "rules" => array() ),
    );
    $map['search']['infos'] = array( "title" => __('Search'), "slug" => 'search' );
    $map['search']['types'] = array(
      'single' => array( "title" => __("Single"), "template" => "", "templates" => "", "target" => array(), "rules" => array() ),
    );


    //add taxonomy
    $taxonomies = get_taxonomies( array( "public" => true ) );

    if ( $taxonomies ){

      foreach ( $taxonomies as $tax_key => $tax ) {

        $TERMS = array();

        $taxonomy = get_taxonomy( $tax );

        $terms = get_terms( $tax );

        if ( $terms ) {
          foreach ( $terms as $term_key => $term ) {

            $TERMS[ $term->term_id ] = array(
                "ID" => $term->term_id,
                "title" => $term->name,
                "taxonomy" => $tax,
                //"link" => get_term_link( $term->term_id ),
                //"post" => $post,
            );

          }
        }

        if ( $taxonomy->object_type ) {
          foreach ( $taxonomy->object_type as $key => $target ) {

            if ( isset( $map[ $target ] ) ) {

              $map[ $target ]['infos']['taxonomies'][ $taxonomy->name ] = $TERMS;

              $map[ $target ]['types'][ $taxonomy->name ] = array( "title" => $taxonomy->label, "template" => "", "templates" => "", "target" => $TERMS, "rules" => array() );

            }

          }
        }

      }

    }

    //add templates
    //$templates = handypress_db::get_all_data( 'composer_templates' );
    $templates = LINOTYPE::$TEMPLATES->get();

    $templates_map = array();

    // _HANDYLOG('$templates', $templates );

    if ( $templates ) {
      foreach ( $templates as $template_key => $template ) {

        $template_target = explode('_', $template['type'] );
        if ( count( $template_target ) == 1 ) array_push( $template_target, 'single' );

        if ( $template_target[1] == 'taxonomy' ) {

          $map[ $template_target[0] ]['types'][ 'product_cat' ]['templates'][ $template['id'] ] = array(
            "ID" => $template['id'],
            "title" => $template['title'],
            //"link" => 'admin.php?page=composer_templates&action=edit&post='. $template['ID']
          );
          $map[ $template_target[0] ]['types'][ 'product_tag' ]['templates'][ $template['id'] ] = array(
            "ID" => $template['id'],
            "title" => $template['title'],
            //"link" => 'admin.php?page=composer_templates&action=edit&post='. $template['id']
          );

        } else if ( isset( $map[ $template_target[0] ]['types'][ $template_target[1] ] ) ) {
          $map[ $template_target[0] ]['types'][ $template_target[1] ]['templates'][ $template['id'] ] = array(
            "ID" => $template['id'],
            "title" => $template['title'],
            //"link" => 'admin.php?page=composer_templates&action=edit&post='. $template['id']
          );
        }

      }
    }

    //_HANDYLOG('mapppp', $map );
    return $map;

  }

  /*
   *
   * get_theme_types
   *
   */
  static function get_theme_types(){

    $theme_types =  array(
      array( 'title'=> 'Page', 'value' => 'page' ),
      array( 'title'=> 'Post > Archive', 'value' => 'post_archive' ),
      array( 'title'=> 'Post > Single', 'value' => 'post' ),
      array( 'title'=> 'Post > Category', 'value' => 'post_category' ),
      array( 'title'=> 'Post > Tag', 'value' => 'post_tag' ),
      array( 'title'=> 'Post > Taxonomy', 'value' => 'post_taxonomy' ),
      array( 'title'=> 'Author', 'value' => 'author' ),
      array( 'title'=> 'Error', 'value' => 'error' ),
      array( 'title'=> 'Search', 'value' => 'search' ),
    );

    $post_types = handypress_helper::all_post_types();

    if ( $post_types ) {
      foreach ( $post_types as $key => $type ) {

        if( ! in_array( $type['value'], array( 'post', 'page', 'attachment' ) ) ) {

          array_push( $theme_types, array( 'title'=> $type['title'] . ' > Archive', 'value' => $type['value'] . '_archive' ) );
          array_push( $theme_types, array( 'title'=> $type['title'] . ' > Single', 'value' => $type['value'] ) );
          array_push( $theme_types, array( 'title'=> $type['title'] . ' > Taxonomy', 'value' => $type['value'] . '_taxonomy' ) );

        }

      }
    }

    return $theme_types;

  }

  static function get_all_elements(){

    $elements = array();

    $elements_db = handypress_db::get_all_data( 'composer_elements' );

    if ( $elements_db ) {
      foreach ( $elements_db as $element_db_key => $element_db ) {

          array_push( $elements, array( 'title'=> $element_db['title'], 'value' => $element_db['ID'] ) );

      }
    }

    return $elements;

  }


  /*
   *
   * get_all_templates
   *
   */
  static function get_all_templates() {

    $templates = handypress_db::get_all_data( 'composer_templates' );

    $templates_select = array();

    if ( $templates ) {
      foreach ( $templates as $template_key => $template ) {
        array_push( $templates_select, array(
          "title" => $template['title'],
          "value" => $template['ID'],
          "data" => $template,
        ));
      }
    }

    $templates_select[] = array( "title" => 'Blank ( only page content )', "value" => 'blank' );

    return  $templates_select;

  }


  /*
   *
   * get_all_posts
   *
   */
  static function get_all_posts() {

    $PRODUCTS = array();

    global $wpdb;

    global $post, $wpdb;
    //post_type = 'product' AND
    $all_products = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status IN ('publish', 'draft')");

    if ( $all_products ){
      foreach ( $all_products as $products_key => $product ) {

        $PRODUCTS[] = array( "title" => $product->post_title, "value" => $product->ID );

      }
    }

    return $PRODUCTS;

  }

  /*
   *
   * get_meta_values
   *
   */
  static function get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {

    global $wpdb;

    if( empty( $key ) )
        return;

    $r = $wpdb->get_col( $wpdb->prepare( "
        SELECT meta_value FROM {$wpdb->postmeta} 
        WHERE meta_key = '%s'
    ", $key ) );

    return $r;
  }

  /*
   *
   * get_templates_type_filter
   *
   */
  static function get_templates_type_filter(){

    $templates_types = array(

      array( "title" => "if Wordpress default", "value" => "wp_" . "default" ),
      array( "title" => "if Wordpress embed", "value" => "wp_" . "embed" ),
      array( "title" => "if Wordpress 404", "value" => "wp_" . "404" ),
      array( "title" => "if Wordpress search", "value" => "wp_" . "search" ),
      array( "title" => "if Wordpress home", "value" => "wp_" . "home" ),
      array( "title" => "if Wordpress post_type_archive", "value" => "wp_" . "post_type_archive" ),
      array( "title" => "if Wordpress attachment", "value" => "wp_" . "attachment" ),
      array( "title" => "if Wordpress single", "value" => "wp_" . "single" ),
      array( "title" => "if Wordpress page", "value" => "wp_" . "page" ),
      array( "title" => "if Wordpress singular", "value" => "wp_" . "singular" ),
      array( "title" => "if Wordpress category", "value" => "wp_" . "category" ),
      array( "title" => "if Wordpress tag", "value" => "wp_" . "tag" ),
      array( "title" => "if Wordpress author", "value" => "wp_" . "author" ),
      array( "title" => "if Wordpress date", "value" => "wp_" . "date" ),
      array( "title" => "if Wordpress archive", "value" => "wp_" . "archive" ),

      array( "title" => "if WooCommerce default", "value" => "wc_" . "default" ),
      array( "title" => "if WooCommerce shop", "value" => "wc_" . "shop" ),
      array( "title" => "if WooCommerce taxonomy", "value" => "wc_" . "taxonomy" ),
      array( "title" => "if WooCommerce category", "value" => "wc_" . "category" ),
      array( "title" => "if WooCommerce tag", "value" => "wc_" . "tag" ),
      array( "title" => "if WooCommerce product", "value" => "wc_" . "product" ),
      array( "title" => "if WooCommerce cart", "value" => "wc_" . "cart" ),
      array( "title" => "if WooCommerce checkout", "value" => "wc_" . "checkout" ),
      array( "title" => "if WooCommerce checkout_pay_page", "value" => "wc_" . "checkout_pay_page" ),
      array( "title" => "if WooCommerce endpoint", "value" => "wc_" . "endpoint" ),
      array( "title" => "if WooCommerce account", "value" => "wc_" . "account" ),
      array( "title" => "if WooCommerce order", "value" => "wc_" . "order" ),
      array( "title" => "if WooCommerce password", "value" => "wc_" . "password" ),

    );

    return  $templates_types;

  }

  /*
   *
   * get_current_template_id
   *
   */
  static function get_current_template( $id ) {

    $template_id = false;

    $type = array();

    if( is_embed() ) $type[] = array( 'wp', 'embed' );
    if( is_404() ) $type[] = array( 'wp', '404' );
    if( is_search() ) $type[] = array( 'wp', 'search' );
    if( is_front_page() ) $type[] = array( 'wp', 'home' );
    if( is_home() ) $type[] = array( 'wp', 'home' );
    if( is_post_type_archive() ) $type[] = array( 'wp', 'post_type_archive' );
    if( is_tax() ) $type[] = array( 'wp', 'tax' );
    if( is_attachment() ) $type[] = array( 'wp', 'attachment' );
    if( is_single() ) $type[] = array( 'wp', 'single' );
    if( is_page() ) $type[] = array( 'wp', 'page' );
    if( is_singular() ) $type[] = array( 'wp', 'singular' );
    if( is_category() ) $type[] = array( 'wp', 'category' );
    if( is_tag() ) $type[] = array( 'wp', 'tag' );
    if( is_author() ) $type[] = array( 'wp', 'author' );
    if( is_date() ) $type[] = array( 'wp', 'date' );
    if( is_archive() ) $type[] = array( 'wp', 'archive' );

    if( is_woocommerce() ) $type[] = array( 'wc', 'shop' );
    if( is_shop() ) $type[] = array( 'wc', 'shop' );
    if( is_product_taxonomy() ) $type[] = array( 'wc', 'taxonomy' );
    if( is_product_category() ) $type[] = array( 'wc', 'category' );
    if( is_product_tag() ) $type[] = array( 'wc', 'tag' );
    if( is_product() ) $type[] = array( 'wc', 'product' );
    if( is_cart() ) $type[] = array( 'wc', 'cart' );
    if( is_checkout() ) $type[] = array( 'wc', 'checkout' );
    if( is_checkout_pay_page() ) $type[] = array( 'wc', 'checkout_pay_page' );
    if( is_wc_endpoint_url() ) $type[] = array( 'wc', 'endpoint' );
    if( is_account_page() ) $type[] = array( 'wc', 'account' );
    if( is_view_order_page() ) $type[] = array( 'wc', 'order' );
    if( is_edit_account_page() ) $type[] = array( 'wc', 'account' );
    if( is_order_received_page() ) $type[] = array( 'wc', 'account' );
    if( is_add_payment_method_page() ) $type[] = array( 'wc', 'account' );
    if( is_lost_password_page() ) $type[] = array( 'wc', 'password' );

    $type = array_reverse($type);

    //get all template
    $templates = handypress_db::get_all_data( 'composer_templates' );

    array_reverse( $templates );

    $template_by_filter = array();

    if ( $templates ) {
      foreach ( $templates as $template_key => $template ) {

        if ( $template['settings']['filter'] ) {

          $filters = explode(',', $template['settings']['filter'] );

          if ( $filters ) {
            foreach ( $filters as $filter_key => $filter ) {

              $template_by_filter[ $filter ] = $template['ID'];

            }
          }

        }

      }
    }

    if ( $template_by_filter[ $type[0][0] . '_' . $type[0][1] ] ){

        $template_id = $template_by_filter[ $type[0][0] . '_' . $type[0][1] ];

    }

    if ( $type[0][1] == 'singular' ) {

      $manual_id = get_post_meta( $id, '_composer_template', true );

      if ( $manual_id ) $template_id = $manual_id;

    }

    return $template_id;

  }


  /*
   *
   * get_current_template_id
   *
   */
  static function get_current_template_id( $object_id, $object_type ) {

    $template_id = null;

    if ( $object_type[1] == 'post' ) {

      $post_template_id = (int) get_post_meta( $object_id, '_composer_selector_template', true );

      if ( ! empty( $post_template_id ) ) $template_id = $post_template_id;

    }

    if ( ! $template_id ) {

      $option_template_id = (int) get_option( '_composer_' . $object_type[0] . '_' . $object_type[1] . '_template' );

      if ( ! empty( $option_template_id ) ) $template_id = $option_template_id;

    }

    return $template_id;

  }

  /*
   *
   * get_current_template_blocks
   *
   */
  static function get_current_template_blocks( $template_id, $object_type, $object_id ) {

    $template_blocks = get_post_meta( $template_id, '_composer_blocks', false );

    if ( $template_blocks[0] ) {

      $template_blocks = $template_blocks[0];

      if ( $template_blocks ) {
        foreach ( $template_blocks as $key => $value ) {

          $block_from_post = get_post_meta( $object_id, '_composer_block_' . $key, true );

          if ( $block_from_post ) {

            $template_blocks[$key] = $block_from_post;

          } else {

            $template_blocks[$key] = null;

          }

        }
      }

    }

    return $template_blocks;

  }


  static function get_object_type() {

    $type = array( 'core', 'post' );

    //if buddypress load
    if ( function_exists('bp_current_component') ) {

      //if is buddypress page
      if( bp_current_component() ) $type = array( 'core', bp_current_component() );

    }

    //if woocommerce load
    if ( class_exists( 'WooCommerce' ) ) {

      //if is woocommerce page
      if( is_woocommerce() ) $type = array( 'woocommerce', 'shop' );
      if( is_shop() ) $type = array( 'woocommerce', 'shop' );
      if( is_product_taxonomy() ) $type = array( 'woocommerce', 'taxonomy' );
      if( is_product_category() ) $type = array( 'woocommerce', 'category' );
      if( is_product_tag() ) $type = array( 'woocommerce', 'tag' );
      if( is_product() ) $type = array( 'woocommerce', 'product' );
      if( is_cart() ) $type = array( 'woocommerce', 'cart' );
      if( is_checkout() ) $type = array( 'woocommerce', 'checkout' );
      if( is_checkout_pay_page() ) $type = array( 'woocommerce', 'checkout_pay_page' );
      if( is_wc_endpoint_url() ) $type = array( 'woocommerce', 'endpoint' );
      if( is_account_page() ) $type = array( 'woocommerce', 'account' );
      if( is_view_order_page() ) $type = array( 'woocommerce', 'order' );
      if( is_edit_account_page() ) $type = array( 'woocommerce', 'account' );
      if( is_order_received_page() ) $type = array( 'woocommerce', 'account' );
      if( is_add_payment_method_page() ) $type = array( 'woocommerce', 'account' );
      if( is_lost_password_page() ) $type = array( 'woocommerce', 'password' );

    }

    return $type;

  }

  static function get_curly( $content ){

    $curly = array();

    //match currly
    preg_match_all("/{{([^:}]*):?([^:}]*):?([^:}]*)}}/", $content, $matches );

    if ( $matches ) {
      foreach ( $matches[1] as $id_key => $id ) {

        $curly[ $matches[1][$id_key] ] = array(
          "id" => $matches[1][$id_key],
          "curly" => $matches[0][$id_key],
          "params" => array(
            $matches[2][$id_key],
            $matches[3][$id_key],
          ),
        );

      }
    }

    return $curly;

  }


  static function preprocessor_css( $processor = 'scss', $code = '' ){

    if ( $code !== '' ) {
      
      switch ( $processor ) {
        
        case 'scss':

          try {
            
            $scss = new \Leafo\ScssPhp\Compiler();
            //$scss->setImportPaths( $file_dir );
            $scss->setFormatter( '\Leafo\ScssPhp\Formatter\Crunched' );
            
            return $scss->compile( $code );

          } catch (exception $e) {
            
            file_put_contents( $file_css, '/*' . PHP_EOL . $code . PHP_EOL . '*/' );
            file_put_contents( $file_scss, '/* ' . $e->getMessage() . ' */' . PHP_EOL . PHP_EOL . $code );

          }
          
        break;

        case 'less':

          try {
              
            $less = new lessc;
            $less->addImportDir( $file_dir );
            $less->setFormatter("compressed");
            $less->setPreserveComments(false);
            $less->setVariables(array(
              //"xxx" => "xxx",
            ));
            $less->setVariables(array(
              //"url" => "'http://example.com.com/'"
            ));

            return $less->compile( $code );

          } catch (exception $e) {
            
            file_put_contents( $file_css, '/*' . PHP_EOL . $code . PHP_EOL . '*/' );
            file_put_contents( $file_less, '/* ' . $e->getMessage() . ' */' . PHP_EOL . PHP_EOL . $code );

          }
          
        break;
        
      }

    }

    return false;

  }

  static function save_css( $file = null, $code = '', $processor = 'css' ){

    if ( $file ) {

      $file_css = $file;
      $file_dir = dirname( $file ) . '/';
      $file_scss = str_replace('.css','.scss', $file );
      $file_less = str_replace('.css','.less', $file );

      if ( $code !== '' ) {
        
        switch ( $processor ) {
          
          case 'less':

            try {
                
              $less = new lessc;
              $less->addImportDir( $file_dir );
              $less->setFormatter("compressed");
              $less->setPreserveComments(false);
              $less->setVariables(array(
                //"xxx" => "xxx",
              ));
              $less->setVariables(array(
                //"url" => "'http://example.com.com/'"
              ));
              file_put_contents( $file_css, $less->compile( $code ) );
              file_put_contents( $file_less, $code );

            } catch (exception $e) {
              
              file_put_contents( $file_css, '/*' . PHP_EOL . $code . PHP_EOL . '*/' );
              file_put_contents( $file_less, '/* ' . $e->getMessage() . ' */' . PHP_EOL . PHP_EOL . $code );

            }
            
            if ( file_exists( $file_scss ) ) unlink( $file_scss );

          break;
          
          case 'scss':

            try {
              
              $scss = new \Leafo\ScssPhp\Compiler();
              $scss->setImportPaths( $file_dir );
              $scss->setFormatter( '\Leafo\ScssPhp\Formatter\Crunched' );
              file_put_contents( $file_css, $scss->compile( $code ) );
              file_put_contents( $file_scss, $code );

            } catch (exception $e) {
              
              file_put_contents( $file_css, '/*' . PHP_EOL . $code . PHP_EOL . '*/' );
              file_put_contents( $file_scss, '/* ' . $e->getMessage() . ' */' . PHP_EOL . PHP_EOL . $code );

            }
            
            if ( file_exists( $file_less ) ) unlink( $file_less );
          
          break;

          case 'css':
          
            file_put_contents( $file_css, $code );
            if ( file_exists( $file_scss ) ) unlink( $file_scss );
            if ( file_exists( $file_less ) ) unlink( $file_less );

          break;
          
        }

      } else {
        
        if ( file_exists( $file_css ) )  unlink( $file_css );
        if ( file_exists( $file_scss ) ) unlink( $file_scss );
        if ( file_exists( $file_less ) ) unlink( $file_less );

      }

    }

  }


  static function file_save( $file = null, $data = '', $rmempty = false, $chmod = '0775' ){

    if ( $file && $data ) {

      if ( self::is_json( $data ) ) {
        
        $data = json_decode( $data, true );
        $data = json_encode( $data, JSON_PRETTY_PRINT );
      
      }

      wp_mkdir_p( dirname( $file ) );

      $saved_file = file_put_contents( $file, $data, LOCK_EX );
      
      if ( $saved_file === false || $saved_file == -1 ) die( "ERROR : LINOTYPE_helpers::file_save : File is not writable : " . $file );

    } else if ( $rmempty && file_exists( $file ) ) {

      unlink( $file );

      if ( file_exists( $file ) ) die( "ERROR : LINOTYPE_helpers::file_save : File can't be auto remove on empty" );

    }
    
  }

  static function rrmdir($dir) {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
          if (filetype($dir."/".$object) == "dir") 
            self::rrmdir($dir."/".$object); 
          else unlink   ($dir."/".$object);
        }
      }
      reset($objects);
      rmdir($dir);
    }
  }

  static function is_json($string) {
    return ((is_string($string) &&
            (is_object(json_decode($string)) ||
            is_array(json_decode($string))))) ? true : false;
  }

  static function create_webp( $source ) {

   // $source_infos = pathinfo( str_replace( get_bloginfo('url'), ABSPATH, $source ) );
  //  _HANDYLOG( $source_infos );
    /*
    $destination = __DIR__ . '/logo.jpg.webp';

    $success = WebPConvert\WebPConvert::convert($source, $destination, [
        // It is not required that you set any options - all have sensible defaults.
        // We set some, for the sake of the example.
        'quality' => 'auto',
        'max-quality' => 80,
        'converters' => ['cwebp', 'gd', 'imagick', 'wpc', 'ewww'],  // Specify conversion methods to use, and their order

        'converter-options' => [
            'ewww' => [
                'key' => 'your-api-key-here'
            ],
            'wpc' => [
                'api-version' => 1,
                'url' => 'https://example.com/wpc.php',
                'api-key' => 'my dog is white'
            ]
        ]

        // more options available! - see the api
    ]);
    */

  }

  // public function recurse_copy($src,$dst) {
  //   $dir = opendir($src); 
  //   @mkdir($dst); 
  //   while(false !== ( $file = readdir($dir)) ) { 
  //       if (( $file != '.' ) && ( $file != '..' )) { 
  //           if ( is_dir($src . '/' . $file) ) { 
  //               recurse_copy($src . '/' . $file,$dst . '/' . $file); 
  //           } 
  //           else { 
  //               copy($src . '/' . $file,$dst . '/' . $file); 
  //           } 
  //       } 
  //   } 
  //   closedir($dir); 
  // }





  static function get_template_blocks( $template, $ids = array() ) {

    foreach( $template as $block ) {

      if ( $block['type'] == 'linotype_block_composer' ) {

        foreach( LINOTYPE::$BLOCKS->get() as $composer_block ) {

          $ids[ $composer_block['id'] ] = $composer_block['id'];

        }

      } else if ( strpos( $block['type'], '_module_') !== false ) {
        
        $module = LINOTYPE::$MODULES->get( $block['type'] );

        if ( isset( $module['module'] ) ) {

          $ids = self::get_template_blocks( $module['module'], $ids );

        }
        
      } else {

        $ids[ $block['type'] ] = $block['type'];

        if ( isset( $block['contents'] ) ) {

            $ids = self::get_template_blocks( $block['contents'], $ids );
        
          }

      }

    }

    return array_values( $ids );

  }

  static function get_template_assets( $template, $assets = null ) {

    if ( $assets === null ) {

      $assets = array( 
        'styles' => array(
          'libraries' => array(), 
          'blocks' => array(), 
        ),
        'scripts' => array(
          'libraries' => array(), 
          'blocks' => array(), 
        ) 
      );

    }

    $blocks = self::get_template_blocks( $template );

    foreach( $blocks as $block_id ) {

      $block_data = LINOTYPE::$BLOCKS->get( $block_id );

      if ( file_exists( $block_data['dir'] . '/style.css' ) ) {

        $assets['styles']['blocks'][$block_id] = $block_data['url'] . '/style.css';

      }

      if ( file_exists( $block_data['dir'] . '/script.js' ) ) {

        $assets['scripts']['blocks'][$block_id] = $block_data['url'] . '/script.js';

      }
      
      if ( isset( $block_data['libraries'] ) ) {
        foreach( $block_data['libraries'] as $library_id ) {

          $library_data = LINOTYPE::$LIBRARIES->get( $library_id );

          if ( file_exists( $library_data['dir'] . '/style.css' ) ) {

            $assets['styles']['libraries'][$library_id] = $library_data['url'] . '/style.css';
  
          }
  
          if ( file_exists( $library_data['dir'] . '/script.js' ) ) {
  
            $assets['scripts']['libraries'][$library_id] = $library_data['url'] . '/script.js';
  
          }

        }
      }

    }
    
    return $assets;

  }


}

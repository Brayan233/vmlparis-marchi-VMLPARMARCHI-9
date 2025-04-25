<?php

function block( $action, $data = array(), $params = array() ) {
  
    switch ( $action ) {
      
      case 'header':
  
        extract( $data );
  
        $item['params'] = $params;
  
        LINOTYPE_composer::render_element_header( $item, $type, $title, $icon, $options, $contents, $elements, $editor );
  
      break;
  
      case 'render':
  
        extract( $params );

        $contents = $data;

        LINOTYPE_composer::render_elements( $contents, $elements, $editor );
  
      break;

      case 'option':
  
        extract( $params );
        
        if ( current_user_can('linotype_edit') ) $html = '<span class="linotype-edit-value">';

          $html .= $options[ $data ];

        if ( current_user_can('linotype_edit') ) $html .= '</span>';

        return $html;
  
      break;

      case 'content':
  
        extract( $data );
  
        LINOTYPE_composer::render_elements( $contents, $elements, $editor );
  
      break;

      case 'render':
  
        //LINOTYPE_composer::render( array( $data ), $elements, false, $editor );

      break;
  
      case 'wc_get_template':
  
        add_filter( 'wc_get_template', function( $located, $template_name, $args, $template_path, $default_path ) use ( $data ) { 
          
          if ( isset( $data ) ){
            foreach( $data as $data_name => $data_file ){
  
              if ( $template_name == $data_name  )  $located = $data_file;
        
            }
          }
  
          return $located;
        
        }, 10, 5 );
  
      break;
  
      case 'footer':
  
        extract( $data );
  
        $item['params'] = $params;
  
        LINOTYPE_composer::render_element_footer( $item, $type, $title, $icon, $options, $contents, $elements, $editor );
  
      break;
  
      case 'dir':
        
        extract( $data );
        echo $elements[ $type ]['url'];
  
      break;

      case 'get_dir':
        
        extract( $data );
        return $elements[ $type ]['url'];

      break;
  
      case 'url':
        
        extract( $data );
        echo $elements[ $type ]['url'];
        
      break;

      case 'get_url':
        
        extract( $data );
        return $elements[ $type ]['url'];
        
      break;
  
      case 'get':
      
        LINOTYPE::$STYLES .= $data;
      
      break;
  
      case 'styles':
  
        $style_minify = '';
       
        if ( $params ) {
          foreach( $params as $target => $css  ) {
            
            $css_value = '';
  
            if ( $css ) {
              
              if ( $css ) {
                foreach( $css as $css_key => $css_val  ) {
  
                  if ( $css_val ) $css_value .= $css_key . ':' . $css_val . ';';
                }
              }
            }
  
            if ( $css_value && $target ) {
              
              $full_target = array();
  
              $targets = explode( ',', $target );
  
              if ( $targets ) {
                foreach( $targets as $target_part  ) {
                  
                  $target_part = str_replace('_root *', ' *', $target_part );
                  $target_part = str_replace('_root', '', $target_part );
                  $full_target[] = '#block_' . $data['item']['id'] . $target_part;
  
                }
              } else {
  
                $target = str_replace('_root *', ' *', $target );
                $target = str_replace('_root', '', $target );
                $full_target[] = '#block_' . $data['item']['id'] . $target;
  
              }
  
              $style_minify .= implode( ', ', array_values( $full_target ) ) . '{' . $css_value . '}';
  
            }
            
          } 
        }
  
        LINOTYPE::$STYLES .= $style_minify;
  
      break;
  
      case 'add_style':
  
        LINOTYPE::$STYLES .= $data;
  
      break;
  
      case 'add_script':
  
        LINOTYPE::$SCRIPTS .= $data;
  
      break;
  
    }
  
  }
  
  function block_composer( $action, $data = array(), $params = array() ) {
    
    switch ( $action ) {
      
      case 'header':
  
        extract( $data );
  
        $item['params'] = $params;
  
        LINOTYPE_composer::render_editor_element_header( $item, $type, $title, $icon, $options, $contents, $elements, $editor );
  
      break;
  
      case 'preview':

        $settings = $data;

        extract( $data );

        if ( file_exists( LINOTYPE::$BLOCKS->get( $type )['dir'] . '/preview.html' ) ) {

          echo '<div class="composer-item-preview">';
          
            $mustache = new Mustache_Engine;
            echo $mustache->render( file_get_contents( LINOTYPE::$BLOCKS->get( $type )['dir'] . '/preview.html' ), $options );

          echo '</div>';

        } else if ( file_exists( LINOTYPE::$BLOCKS->get( $type )['dir'] . '/preview.php' ) ) {

          echo '<div class="composer-item-preview">';

            include LINOTYPE::$BLOCKS->get( $type )['dir'] . '/preview.php';
          
          echo '</div>';

        }
            
      break;

      case 'content':
  
        extract( $data );
  
        LINOTYPE_composer::render_contents( $type, $contents, $params, $elements, $editor );
  
      break;
  
      case 'footer':
  
        extract( $data );
  
        $item['params'] = $params;
  
        LINOTYPE_composer::render_editor_element_footer( $item, $type, $title, $icon, $options, $contents, $elements, $editor );
  
      break;
  
    }
  
  }

  function linoption( $id = '' ) {

    $value = null;

    if ( ! $id ) return $value;

    $wp_lang = strtolower( array_reverse( explode( '_', get_option('WPLANG', 'en') ) )[0] );
    if ( $wp_lang == 'gb' ) $wp_lang = 'uk';
    $current_lang = $wp_lang;
    $translations = array( $wp_lang );
    $lang_code = '';

    if ( function_exists('pll_current_language') ) {

      $current_lang = pll_current_language();

      if ( $current_lang !== $wp_lang ) $lang_code = '_' . $current_lang;
    
    }

    $value = get_option( '_globals_' . $id . $lang_code, null );
  
    if ( $value == null ) $value = get_option( '_overwrite_' . $id . $lang_code, $value );

    if ( $value == null ) $value = get_option( $id . $lang_code, $value );

    if ( $value ) $value = stripslashes( $value );

    return $value;

  }


  global $TRADUCTIONS;

  function linotrad( $txt = null ) {
    
    if ( $txt == null || ! is_string( $txt ) ) return $txt;

    global $TRADUCTIONS;

    if ( ! $TRADUCTIONS ) $TRADUCTIONS = get_option( 'linotype_translate' );
    if ( ! $TRADUCTIONS ) $TRADUCTIONS = array();

    $TRADUCTIONS = array_filter( array_values( $TRADUCTIONS ) );

    if ( ! in_array( $txt, $TRADUCTIONS ) ) {

      array_push( $TRADUCTIONS, $txt );

      update_option( 'linotype_translate', $TRADUCTIONS );

      return $txt;

    }
    
    $id = array_search( $txt, $TRADUCTIONS );

    if ( $id !== false ) {
      
      $wp_lang = strtolower( array_reverse( explode( '_', get_option('WPLANG', 'en') ) )[0] );
      if ( $wp_lang == 'gb' ) $wp_lang = 'uk';
      $current_lang = $wp_lang;
      $translations = array( $wp_lang );
      $lang_code = '';

      if ( function_exists('pll_current_language') ) {

        $current_lang = pll_current_language();

        if ( $current_lang !== $wp_lang ) $lang_code = '_' . $current_lang;
      
      }

      $txt_new = get_option( '_traduction_' . $id . $lang_code, null );

      if ( $txt_new ) $txt = stripslashes( $txt_new );
    }

    return $txt;

  }
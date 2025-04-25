<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 *
 * COMPOSER_core
 *
 */
class COMPOSER_build {

  public $THEME;

  public $TEMPLATES;

  /*
   *
   * __construct
   *
   */
  function __construct( $data ) {

    $this->THEME = array();

    $this->THEME['templates_ids'] = $this->get_active_templates( $data['theme']['map'] );

    $this->THEME['blocks_ids'] = array_keys( $data['blocks']  );

    $libraries_styles = $this->generate_libraries_styles( $this->THEME['blocks_ids'], $data['blocks'], LINOTYPE::$LIBRARIES->get() );
    $libraries_scripts = $this->generate_libraries_scripts( $this->THEME['blocks_ids'], $data['blocks'], LINOTYPE::$LIBRARIES->get() );

    $plugins_styles = $this->generate_plugins_styles( LINOTYPE::$SETTINGS['cache']['all_assets']['styles'], LINOTYPE::$SETTINGS['cache']['styles_selected'] );
    $plugins_scripts = $this->generate_plugins_scripts( LINOTYPE::$SETTINGS['cache']['all_assets']['scripts'], LINOTYPE::$SETTINGS['cache']['scripts_selected'] );

    $global_styles = $this->generate_global_styles();
    $global_scripts = $this->generate_global_scripts();

    $blocks_scripts = $this->generate_blocks_scripts( $this->THEME['blocks_ids'], $data['blocks'] );
    $blocks_styles  = $this->generate_blocks_styles( $this->THEME['blocks_ids'], $data['blocks'] );

    $this->build( LINOTYPE::$SETTINGS['dir_cache'], $blocks_styles, $blocks_scripts, $global_styles, $global_scripts, $libraries_styles, $libraries_scripts, $plugins_styles, $plugins_scripts );

  }

  public function get_active_templates( $theme_map = array() ) {

    $templates = array();

    foreach ( $theme_map as $map_key => $map ) {

      foreach ( $map['types'] as $type_key => $type ) {

        if ( isset( $type['template'] ) && $type['template'] ) $templates[] = $type['template'];

        if ( isset( $type['rules'] ) && $type['rules'] ) {

          foreach ( $type['rules'] as $rule_key => $rule ) {

            if ( $rule['template'] ) $templates[] = $rule['template'];

          }

        }

      }

    }

    return $templates;

  }

  public function generate_libraries_styles( $blocks_ids, $blocks, $libraries ) {

    $STYLE = '';

    $libraries_ids = array();

    foreach ( $blocks_ids as $blocks_id ) {

      if ( isset( $blocks[$blocks_id] ) ) {

        if ( isset( $blocks[$blocks_id]['libraries'] ) && $blocks[$blocks_id]['libraries'] ) {

          $libraries_ids = array_merge( $libraries_ids, $blocks[$blocks_id]['libraries'] );

        }

      }

    }

    $libraries_ids = array_unique( $libraries_ids );

    foreach ( $libraries_ids as $libraries_id ) {

      if ( isset( $libraries[$libraries_id] ) ) {

        if ( file_exists( $libraries[$libraries_id]['dir']  . '/style.css' ) ) {

          $STYLE_CONTENT = linotype_file_get_contents( $libraries[$libraries_id]['url']  . '/style.css?ver=' . time() );

          if ( LINOTYPE::$SETTINGS['cache']['minify_css'] ) {

            $minifier = new MatthiasMullie\Minify\CSS( $STYLE_CONTENT );
            $STYLE_CONTENT = $minifier->minify();

          }

          if ( $STYLE_CONTENT ) $STYLE .= $STYLE_CONTENT;

        }

      }

    }

    return $STYLE;

  }

  public function generate_plugins_scripts( $plugins, $selected ) {

    $SCRIPT = '';

    if ( $selected ) {
      foreach ( $selected as $plugin_handle ) {

        $src_path = str_replace( get_bloginfo('url'), ABSPATH, $plugins[$plugin_handle]['src'] );
        $src = $plugins[$plugin_handle]['src'];

        if ( file_exists( $src_path ) ) {

          $SCRIPT_CONTENT = linotype_file_get_contents( $src . '?ver=' . time() );

          if ( LINOTYPE::$SETTINGS['cache']['minify_plugins'] ) {

            $minifier = new MatthiasMullie\Minify\JS( $SCRIPT_CONTENT );
            $SCRIPT_CONTENT = $minifier->minify();

          }

          if ( $this->endsWith( $SCRIPT_CONTENT, ';' ) !== true ) $SCRIPT_CONTENT .= ';';

          if ( $SCRIPT_CONTENT ) $SCRIPT .= $SCRIPT_CONTENT;

        }

      }
    }

    return $SCRIPT;

  }

  public function generate_plugins_styles( $styles, $selected ) {

    $STYLE = '';

    if ( $selected ) {
      foreach ( $selected as $style_handle ) {

        if ( isset( $styles[$style_handle] ) ) {
          $src_path = str_replace( get_bloginfo('url'), ABSPATH, $styles[$style_handle]['src'] );
          $src = $styles[$style_handle]['src'];

          if ( file_exists( $src_path ) ) {

            $STYLE_CONTENT = linotype_file_get_contents( $src . '?ver=' . time() );

            $STYLE_CONTENT = $STYLE_CONTENT = str_replace( '../', dirname( dirname( $styles[$style_handle]['src'] ) ) . '/' , $STYLE_CONTENT );

            $STYLE_CONTENT = $STYLE_CONTENT = str_replace( './', dirname( $styles[$style_handle]['src'] ) . '/' , $STYLE_CONTENT );

            if ( LINOTYPE::$SETTINGS['cache']['minify_plugins'] ) {

              $minifier = new MatthiasMullie\Minify\CSS( $STYLE_CONTENT );
              $STYLE_CONTENT = $minifier->minify();

            }

            if ( $STYLE_CONTENT ) $STYLE .= $STYLE_CONTENT;

          }
        }

      }
    }

    return $STYLE;

  }

  public function generate_libraries_scripts( $blocks_ids, $blocks, $libraries ) {

    $SCRIPT = '';

    $libraries_ids = array();

    foreach ( $blocks_ids as $blocks_id ) {

      if ( isset( $blocks[$blocks_id] ) ) {

        if ( isset( $blocks[$blocks_id]['libraries'] ) && $blocks[$blocks_id]['libraries'] ) {

          $libraries_ids = array_merge( $libraries_ids, $blocks[$blocks_id]['libraries'] );

        }

      }

    }

    $libraries_ids = array_unique( $libraries_ids );

    foreach ( $libraries_ids as $libraries_id ) {

      if ( isset( $libraries[$libraries_id] ) ) {

        if ( file_exists( $libraries[$libraries_id]['dir']  . '/script.js' ) ) {

          $SCRIPT_CONTENT = linotype_file_get_contents( $libraries[$libraries_id]['url']  . '/script.js?ver=' . time() );

          if ( LINOTYPE::$SETTINGS['cache']['minify_js'] ) {

            $minifier = new MatthiasMullie\Minify\JS( $SCRIPT_CONTENT );
            $SCRIPT_CONTENT = $minifier->minify();

          }

          if ( $this->endsWith( $SCRIPT_CONTENT, ';' ) !== true ) $SCRIPT_CONTENT .= ';';

          if ( $SCRIPT_CONTENT ) $SCRIPT .= $SCRIPT_CONTENT;

        }

      }

    }

    $SCRIPT = '(function($) {' . $SCRIPT . '}(jQuery));';

    return $SCRIPT;

  }

  public function endsWith($haystack, $needle) {
      $length = strlen($needle);
      if ($length == 0) {
          return true;
      }

      return (substr($haystack, -$length) === $needle);
  }

  public function generate_blocks_styles( $blocks_ids, $blocks ) {

    $STYLE = '';

    foreach ( $blocks_ids as $blocks_id ) {

      $STYLE_CONTENT = '';

      if ( isset( $blocks[$blocks_id] ) ) {

        if ( file_exists( $blocks[$blocks_id]['dir']  . '/style.css' ) ) {

          $STYLE_CONTENT = linotype_file_get_contents( $blocks[$blocks_id]['url']  . '/style.css?ver=' . time() );

          if ( LINOTYPE::$SETTINGS['cache']['minify_css'] ) {

            $minifier = new MatthiasMullie\Minify\CSS( $STYLE_CONTENT );
            $STYLE_CONTENT = $minifier->minify();

          }

          if ( $STYLE_CONTENT ) $STYLE .= $STYLE_CONTENT;

        }

      }

    }

    return $STYLE;

  }

  public function generate_global_styles() {

    $STYLE = '';

    if ( file_exists( LINOTYPE::$THEME['dir']  . '/reset.css' ) ) {

      $STYLE_CONTENT = linotype_file_get_contents( LINOTYPE::$THEME['url']  . '/reset.css?ver=' . time() );

      if ( LINOTYPE::$SETTINGS['cache']['minify_css'] ) {

        $minifier = new MatthiasMullie\Minify\CSS( $STYLE_CONTENT );
        $STYLE_CONTENT = $minifier->minify();

      }

      if ( $STYLE_CONTENT ) $STYLE .= $STYLE_CONTENT;

    }

    if ( file_exists( LINOTYPE::$THEME['dir']  . '/style.css' ) ) {

      $STYLE_CONTENT = linotype_file_get_contents( LINOTYPE::$THEME['url']  . '/style.css?ver=' . time() );

      if ( LINOTYPE::$SETTINGS['cache']['minify_css'] ) {

        $minifier = new MatthiasMullie\Minify\CSS( $STYLE_CONTENT );
        $STYLE_CONTENT = $minifier->minify();

      }

      if ( $STYLE_CONTENT ) $STYLE .= $STYLE_CONTENT;

    }

    return $STYLE;

  }

  public function generate_global_scripts() {

    $SCRIPT = '';

    if ( LINOTYPE::$SETTINGS['cache']['lazyload'] ) {

      $lazyload = LINOTYPE_plugin::$plugin['dir'] . '/lib/lazyload/lazyload.min.js';

      if ( $lazyload ) {

        $SCRIPT_CONTENT = file_get_contents( $lazyload );

        if ( LINOTYPE::$SETTINGS['cache']['minify_js'] ) {

          $minifier = new MatthiasMullie\Minify\JS( $SCRIPT_CONTENT );
          $SCRIPT_CONTENT = $minifier->minify();

        }

        if ( $this->endsWith( $SCRIPT_CONTENT, ';' ) !== true ) $SCRIPT_CONTENT .= ';';

        if ( $SCRIPT_CONTENT ) $SCRIPT .= $SCRIPT_CONTENT;

      }

    }

    return $SCRIPT;

  }

  public function generate_blocks_scripts( $blocks_ids, $blocks ) {

    $SCRIPT = '';

    foreach ( $blocks_ids as $blocks_id ) {

      $SCRIPT_CONTENT = '';

      if ( isset( $blocks[$blocks_id] ) ) {

        if ( file_exists( $blocks[$blocks_id]['dir']  . '/script.js' ) ) {

          $SCRIPT_CONTENT = linotype_file_get_contents( $blocks[$blocks_id]['url']  . '/script.js?ver=' . time() );

          if ( LINOTYPE::$SETTINGS['cache']['minify_js'] ) {

            $minifier = new MatthiasMullie\Minify\JS( $SCRIPT_CONTENT );
            $SCRIPT_CONTENT = $minifier->minify();

          }

          if ( $this->endsWith( $SCRIPT_CONTENT, ';' ) !== true ) $SCRIPT_CONTENT .= ';';

          if ( $SCRIPT_CONTENT ) $SCRIPT .= $SCRIPT_CONTENT;

        }

      }

    }

    $SCRIPT = '(function($) {' . $SCRIPT . '}(jQuery));';

    return $SCRIPT;

  }

  public function build( $dir, $blocks_styles, $blocks_scripts, $global_styles, $global_scripts, $libraries_styles, $libraries_scripts, $plugins_styles, $plugins_scripts ) {

    file_put_contents( $dir . '/public/assets/css/blocks_' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.css', $blocks_styles );
    file_put_contents( $dir . '/public/assets/js/blocks_' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.js', $blocks_scripts );

    file_put_contents( $dir . '/public/assets/css/libraries_' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.css', $libraries_styles );
    file_put_contents( $dir . '/public/assets/js/libraries_' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.js', $libraries_scripts );

    file_put_contents( $dir . '/public/assets/css/plugins_' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.css', $plugins_styles );
    file_put_contents( $dir . '/public/assets/js/plugins_' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.js', $plugins_scripts );

    $plugins_styles_all = '';
    if( LINOTYPE::$SETTINGS['cache']['minify_plugins'] ) $plugins_styles_all .= $plugins_styles;
    $plugins_styles_all .= $libraries_styles;
    $plugins_styles_all .= $global_styles;
    $plugins_styles_all .= $blocks_styles;
    file_put_contents( $dir . '/public/assets/css/bundle.' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.css', $plugins_styles_all );

    $plugins_scripts_all = '';
    if( LINOTYPE::$SETTINGS['cache']['minify_plugins'] ) $plugins_scripts_all .= $plugins_scripts;
    $plugins_scripts_all .= $global_scripts;
    $plugins_scripts_all .= $libraries_scripts;
    $plugins_scripts_all .= $blocks_scripts;
    file_put_contents( $dir . '/public/assets/js/bundle.' . LINOTYPE::$SETTINGS['cache']['timestamp'] . '.js', $plugins_scripts_all );

  }

  static function minify_css($str){
    # remove comments first (simplifies the other regex)
    $re1 = <<<'EOS'
(?sx)
  # quotes
  (
    "(?:[^"\\]++|\\.)*+"
  | '(?:[^'\\]++|\\.)*+'
  )
|
  # comments
  /\* (?> .*? \*/ )
EOS;

    $re2 = <<<'EOS'
(?six)
  # quotes
  (
    "(?:[^"\\]++|\\.)*+"
  | '(?:[^'\\]++|\\.)*+'
  )
|
  # ; before } (and the spaces after it while we're here)
  \s*+ ; \s*+ ( } ) \s*+
|
  # all spaces around meta chars/operators
  \s*+ ( [*$~^|]?+= | [{};,>~+-] | !important\b ) \s*+
|
  # spaces right of ( [ :
  ( [[(:] ) \s++
|
  # spaces left of ) ]
  \s++ ( [])] )
|
  # spaces left (and right) of :
  \s++ ( : ) \s*+
  # but not in selectors: not followed by a {
  (?!
    (?>
      [^{}"']++
    | "(?:[^"\\]++|\\.)*+"
    | '(?:[^'\\]++|\\.)*+'
    )*+
    {
  )
|
  # spaces at beginning/end of string
  ^ \s++ | \s++ \z
|
  # double spaces to single
  (\s)\s+
EOS;

    $str = preg_replace("%$re1%", '$1', $str);
    return preg_replace("%$re2%", '$1$2$3$4$5$6$7', $str);
}

}

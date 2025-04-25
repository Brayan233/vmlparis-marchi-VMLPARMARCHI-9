<?php

class linotype_field_image {

  public $settings;

  static $picid;
  public $sources;
  public $ratio;
  public $lazyload;
  public $webp;
  public $alt;
  public $class;

  function __construct( $settings = array() ) {

    $this->init_settings( $settings );
    $this->add_sizes();
    $this->create();

  }

  public function init_settings( $settings ) {

    $this->settings = array_merge( array(
      'sources' => array(),
      'ratio' => true,
      'lazyload' => false,
      'srcsetname' => 'srcset',
      'srcname' => 'srcset',
      'srcdefault' => false,
      'compress'=> 85,
      'webp' => false,
      'fadein' => false,
      'alt' => '',
      'class' => '',
    ), $settings );

    $this->sources = $this->settings['sources'];

    $this->ratio = $this->settings['ratio'];

    $this->compress = intval( $this->settings['compress'] );
    if ( $this->compress > 100 ) $this->compress = 100;
    if ( $this->compress < 0 ) $this->compress = 0;

    $this->webp = false;
    if ( extension_loaded("gd") ) $this->webp = $this->settings['webp'];

    $this->alt = $this->settings['alt'];

    $this->class = '';
    if ( $this->settings['class'] ) $this->class .= ' ' . $this->settings['class'];

  }

  public function create() {

    $sources = "";
    $errors = array();
    $ratio = array();

    self::$picid++;

    $count = count($this->sources);
    $counter = 0;
    $default_source = null;
    $last_source = null;

    $classes = '';
    $src = '';
    if ( $this->settings['lazyload'] === true ) {
      $this->settings['srcsetname'] = 'data-' . $this->settings['srcsetname'];
      $this->settings['srcname'] = 'data-' . $this->settings['srcname'];
    }
    if ( $this->settings['srcdefault'] !== false ) $default_source = $this->settings['srcdefault'];
    
    $dir = wp_upload_dir();
    if ( ! isset( $dir['baseurl'] ) ) return false;

    $sourcefile = '';
    $largefile = '';

    foreach( $this->sources as $source ) {

      $source = array_merge( array(
        'id' => null,
        'break' => 0,
        'crop' => true,
        'x' => 0,
        'y' => 0,
      ), $source );

      $media = '';
      if ( $source['break'] && $count !== $counter ) $media = 'media="(min-width: ' . $source['break'] . 'px)"';

      if ( ! $source['id'] && $last_source ) $source['id'] = $last_source;

      if ( $source['id'] ) {

        $largefile = wp_get_attachment_image_src( $source['id'], 'large', false );

        if ( isset( $largefile[0] ) && $largefile[0] ) {
          $largefile = $largefile[0];
        }

        $sourcefile = wp_get_attachment_image_src( $source['id'], 'full', false );
        $sourcefile = isset( $sourcefile[0] ) ? $sourcefile[0] : '';

        if ( $sourcefile ) {

          $pathinfo = pathinfo( $sourcefile );
          $name = $source['x'] . 'x' . $source['y'];
          if ( $source['crop'] == true ) $name .= '-crop';
          if ( $this->compress !== 85 ) $name .= '-compress' . $this->compress;

          $resize_dir =  str_replace( $dir['baseurl'], $dir['basedir'], $sourcefile );

          $subdir = '/resize' . str_replace( $dir['baseurl'], '', $pathinfo['dirname'] );

          $resize1x =  $dir['baseurl'] . $subdir . '/' . $pathinfo['filename'] . '-' . $name . '.' . $pathinfo['extension'];
          $resize1x_dir =  $dir['basedir'] . $subdir . '/' . $pathinfo['filename'] . '-' . $name . '.' . $pathinfo['extension'];
          $resize2x =  $dir['baseurl'] . $subdir . '/' . $pathinfo['filename'] . '-' . $name . '@2x.' . $pathinfo['extension'];
          $resize2x_dir =  $dir['basedir'] . $subdir . '/' . $pathinfo['filename'] . '-' . $name . '@2x.' . $pathinfo['extension'];

          wp_mkdir_p( dirname( $resize1x_dir ) );
          wp_mkdir_p( dirname( $resize2x_dir ) );

          $file1x = linotype_field_image_sizes::resize( $resize_dir, $resize1x_dir, $pathinfo['extension'], $source['x'], $source['y'], $source['crop'], $this->compress );
          $file2x = linotype_field_image_sizes::resize( $resize_dir, $resize2x_dir, $pathinfo['extension'], ($source['x']*2), ($source['y']*2), $source['crop'], $this->compress );

          $x = $source['x'];
          if ( $x == 0 ) $x = $file1x[1];

          $y = $source['y'];
          if ( $y == 0 ) $y = $file1x[2];

          if ( $x && $y ) {

            $ratio_val = ( $y / $x ) * 100;

            if ( $media ) {

              $ratio[] = '@media (min-width: ' . $source['break'] . 'px) { #linotype_field_image-' . self::$picid . '.ratio { padding-top:' . $ratio_val . '%; } }';

            } else {

              $ratio[] = '#linotype_field_image-' . self::$picid . '.ratio { padding-top:' . $ratio_val . '%; }';

            }

          }

          if ( $x > $y ) {
            $classes = ' landscape';
          } else {
            $classes = ' portrait';
          }
          if ( $this->settings['lazyload'] === true ) $classes .= ' lazyload';
          if ( $this->settings['fadein'] === true ) $classes .= ' lazyload-fadein';

  				$file1x = isset( $file1x[0] ) ? str_replace( isset( $dir['basedir'] ) ? $dir['basedir'] : '', isset( $dir['baseurl'] ) ? $dir['baseurl'] : '', $file1x[0] ) : '';
          $file2x = isset( $file2x[0] ) ? str_replace( isset( $dir['basedir'] ) ? $dir['basedir'] : '', isset( $dir['baseurl'] ) ? $dir['baseurl'] : '', $file2x[0] ) : '';

          if ( $pathinfo['extension'] == 'jpg' || $pathinfo['extension'] == 'jpeg' || $pathinfo['extension'] == 'png' || $pathinfo['extension'] == 'bmp' || $pathinfo['extension'] == 'gif' ) {

            $file1xinfo = pathinfo( $file1x );
            $file2xinfo = pathinfo( $file2x );
            
            if ( $this->webp && isset($file1xinfo['dirname']) && isset($file2xinfo['dirname'] ) ) {

              $file1x_dir =  str_replace( $dir['baseurl'], $dir['basedir'], $file1x );
              $file2x_dir =  str_replace( $dir['baseurl'], $dir['basedir'], $file2x );

              $subdir = '/webp' . str_replace( $dir['baseurl'], '', $file1xinfo['dirname'] );

              $file1x_webp =  $dir['baseurl'] . $subdir . '/' . $file1xinfo['filename'] . '.webp';
              $file1x_webp_dir =  $dir['basedir'] . $subdir . '/' . $file1xinfo['filename'] . '.webp';
              $file2x_webp =  $dir['baseurl'] . $subdir . '/' . $file2xinfo['filename'] . '.webp';
              $file2x_webp_dir =  $dir['basedir'] . $subdir . '/' . $file2xinfo['filename'] . '.webp';

              wp_mkdir_p( dirname( $file1x_webp_dir ) );
              wp_mkdir_p( dirname( $file2x_webp_dir ) );

              $create_webp_1x = linotype_field_image_sizes::webp( $file1x_dir, $file1x_webp_dir, $pathinfo['extension'], $this->compress );
              $create_webp_2x = linotype_field_image_sizes::webp( $file2x_dir, $file2x_webp_dir, $pathinfo['extension'], $this->compress );

              $srcset_webp = '';
              if ( $file1x !== $sourcefile ) $srcset_webp = $this->settings['srcsetname'] . '="' . $file1x_webp . '"';
				      if ( $file1x !== $sourcefile && $file2x !== $sourcefile ) $srcset_webp = $this->settings['srcsetname'] . '="' . $file1x_webp . ' 1x, ' . $file2x_webp . ' 2x"';

				      if ( $srcset_webp ) $sources .= '<source type="image/webp" ' . $srcset_webp . ' ' . $media . '>';

            }

            $srcset = '';
            if ( $file1x !== $sourcefile ) $srcset = $this->settings['srcsetname'] . '="' . $file1x . '"';
            if ( $file1x !== $sourcefile && $file2x !== $sourcefile ) $srcset = $this->settings['srcsetname'] . '="' . $file1x . ' 1x, ' . $file2x . ' 2x"';

            if ( $file1x == $sourcefile ) $errors[] = $source['x'] . 'x' . $source['y'];
            if ( $file2x == $sourcefile ) $errors[] = ( $source['x'] * 2 ) . 'x' . ( $source['y'] * 2 );

            if ( $srcset ) $sources .= '<source type="image/' . $pathinfo['extension'] . '" ' . $srcset . ' ' . $media . '>';

            if ( $default_source == null ) $default_source = $file1x;

            $last_source = $source['id'];

          } else {

            $this->ratio = false;

            if ( $default_source == null ) $default_source = $sourcefile;

          }

        } else {

          $errors[] = 'no image file';

        }

      }

      $counter++;

    }

    $src = 'src="'.$default_source.'"';
    $src_original = 'data-original="'.$sourcefile.'"';
    $src_large = 'data-large="'.$largefile.'"';
    $src_fallback = 'data-fallback="'.$default_source.'"';
    $default_source_ie = $default_source;
    if ( $this->settings['lazyload'] === true ) {
      $src = 'src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="'.$default_source.'"';
    }

    $img = '<img class="linotype_field_image-img' . $classes . '" ' . $src . ' ' . $src_large . ' ' . $src_original . ' ' . $src_fallback . ' alt="' . $this->alt . '">';

    if ( $errors ) {
      $img .= '<!-- The source image is too small to generate the following formats ( ';
        foreach( $errors as $error ) {
          $img .= $error . ' ';
        }
      $img .= '), please download a high quality image -->';
    }

    $picture = '';

    if ( $this->ratio && $ratio ) {

      $this->class = ' ratio' . $this->class;

      $picture .= '<style id="linotype_field_image-' . self::$picid . '-ratio" type="text/css">' . implode( PHP_EOL,  array_reverse( array_values( $ratio ) ) ) . '</style>';

    }

    $picture .= '<picture id="linotype_field_image-' . self::$picid . '" class="linotype_field_image' . $this->class . '">' . $sources . $img . '</picture>';


    $this->picture = $picture;

  }


  public function add_sizes() {

    $sizes = array();

    foreach( $this->sources as $source ) {

      $size = array();
      $size['x'] = 0;
      if ( $source['x'] ) $size['x'] = $source['x'];
      $size['y'] = 0;
      if ( $source['y'] ) $size['y'] = $source['y'];
      $size['crop'] = true;
      if ( isset( $source['crop'] ) && $source['crop'] == false ) $size['crop'] = false;

      $name = 'linotype_field_image-' . $size['x'] . 'x' . $size['y'];
      if ( $size['crop'] ) $name .= '-crop';

      $sizes[ $name ] = $size;

      $size_retina = $size;
      $size_retina['x'] = $size['x'] * 2;
      $size_retina['y'] = $size['y'] * 2;

      $sizes[ $name . '-retina' ] = $size_retina;

    }

    // linotype_field_image_sizes::add( $sizes );

  }

  public function get() {

    return $this->picture;

  }

}

class linotype_field_image_sizes {

  function __construct() {

    $this->load();

    add_filter( 'upload_mimes', array( $this, 'add_custom_mimes_to_upload_mimes' ) , 10, 1 );

    add_action('admin_head', array( $this, 'fix_custom_mimes_thumb_display' ) );

    // if ( ! is_admin() && current_user_can('administrator') ) {

    //   linolog( 'wp_get_additional_image_sizes', wp_get_additional_image_sizes() );

    // }

    add_action('wp_head', array( $this, 'load_picture_style' ) );
    add_action('admin_head', array( $this, 'load_picture_style' ) );

  }

  public function load_picture_style() {

    echo '<style>
    .linotype_field_image.ratio {
      position: relative;
      display: block;
      width: 100%;
      overflow:hidden;
      }
      .linotype_field_image.ratio img{
        position: absolute;
        top:0;
        bottom: 0;
        left: 0;
        right: 0;
        object-fit: cover;
        height: 100%;
        width: 100%;
      }
    </style>';

  }

  public function add_custom_mimes_to_upload_mimes( $upload_mimes ) {

    $upload_mimes['webp'] = 'application/webp';
    $upload_mimes['svg'] = 'image/svg+xml';
    $upload_mimes['svgz'] = 'image/svg+xml';

    return $upload_mimes;

  }

  public function fix_custom_mimes_thumb_display() {
    echo '<style>
      td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail { 
        width: 100% !important; 
        height: auto !important; 
      }
    </style>';
  }

  public function load() {

    $file = __DIR__ . "/sizes.json";

    if ( file_exists( $file ) ) {
      $file = file_get_contents($file);
      $all_size = json_decode($file, true);
    } else {
      $all_size = array();
    }

    if ( $all_size ) {
      foreach ( $all_size as $size_name => $size ) {

        //add_image_size( $size_name, $size['x'], $size['y'], $size['crop'] );

      }
    }

  }

  static function add( $sizes = array() ) {

    if ( ! is_admin() && current_user_can('administrator') ) {

      $file = __DIR__ . "/sizes.json";

      $all_size = array();
      $file_data = '';

      if ( file_exists( $file ) ) {
        $file_data = file_get_contents($file);
        $all_size_current = json_decode($file_data, true);
        if ( $all_size_current ) $all_size = $all_size_current;
      }

      $all_size = array_merge( $all_size, $sizes );

      $all_size_data = json_encode( $all_size, JSON_PRETTY_PRINT );

      if ( $file_data !== $all_size_data ) {

        file_put_contents( $file, $all_size_data );

        //linolog('linotype_field_image_sizes::add', $all_size );

      }

    }

  }

  static function webp( $filename, $filename_webp, $mime_type, $compress = 90 ) {

		if ( ! file_exists( $filename ) ) {
			return false;
		}

    if ( ( isset( $_GET['regenerate'] ) && current_user_can( 'administrator' ) ) || ! file_exists( $filename_webp ) ) {

      @set_time_limit( 60 );

      $ret = false;
      switch ( $mime_type ) {
        case 'jpg':
        case 'jpeg':
          $src = imagecreatefromjpeg( $filename );
          $img = imagecreatetruecolor( imagesx( $src ), imagesy( $src ) );
          imagefill( $img, 0, 0, imagecolorallocate( $img, 255, 255, 255 ) );
          imagealphablending( $img, true );
          break;
        case 'png':
          $src = imagecreatefrompng( $filename );
          $img = imagecreatetruecolor( imagesx( $src ), imagesy( $src ) );
          imagealphablending( $img, false );
          imagesavealpha( $img, true );
          break;
        case 'bmp':
          $src = imagecreatefrombmp( $filename );
          $img = imagecreatetruecolor( imagesx( $src ), imagesy( $src ) );
          break;
        case 'gif':
          $src = imagecreatefromgif( $filename );
          $img = imagecreatetruecolor( imagesx( $src ), imagesy( $src ) );
          $bgcolor = imagecolorallocatealpha( $img, 0, 0, 0, 127 );
          imagefill( $img, 0, 0, $bgcolor );
          imagecolortransparent( $img, $bgcolor );
          break;
      }

      imagecopy( $img, $src, 0, 0, 0, 0, imagesx( $src ), imagesy( $src ) );
      imagedestroy( $src );
      $ret = imagewebp( $img, $filename_webp, $compress );
      imagedestroy( $img );

      return $ret;

    } else {

      return false;

    }

  }

  static function image_resize_dimensions( $orig_w, $orig_h, $dest_w, $dest_h, $crop = false ) {

    if ( $orig_w <= 0 || $orig_h <= 0 ) {
      return false;
    }
    // at least one of dest_w or dest_h must be specific
    if ( $dest_w <= 0 && $dest_h <= 0 ) {
      return false;
    }

    // Stop if the destination size is larger than the original image dimensions.
    if ( empty( $dest_h ) ) {
      if ( $orig_w < $dest_w ) {
        return false;
      }
    } elseif ( empty( $dest_w ) ) {
      if ( $orig_h < $dest_h ) {
        return false;
      }
    } else {
      if ( $orig_w < $dest_w && $orig_h < $dest_h ) {
        return false;
      }
    }

    if ( $crop ) {
      // Crop the largest possible portion of the original image that we can size to $dest_w x $dest_h.
      // Note that the requested crop dimensions are used as a maximum bounding box for the original image.
      // If the original image's width or height is less than the requested width or height
      // only the greater one will be cropped.
      // For example when the original image is 600x300, and the requested crop dimensions are 400x400,
      // the resulting image will be 400x300.
      $aspect_ratio = $orig_w / $orig_h;
      $new_w        = min( $dest_w, $orig_w );
      $new_h        = min( $dest_h, $orig_h );

      if ( ! $new_w ) {
        $new_w = (int) round( $new_h * $aspect_ratio );
      }

      if ( ! $new_h ) {
        $new_h = (int) round( $new_w / $aspect_ratio );
      }

      $size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

      $crop_w = round( $new_w / $size_ratio );
      $crop_h = round( $new_h / $size_ratio );

      if ( ! is_array( $crop ) || count( $crop ) !== 2 ) {
        $crop = array( 'center', 'center' );
      }

      list( $x, $y ) = $crop;

      if ( 'left' === $x ) {
        $s_x = 0;
      } elseif ( 'right' === $x ) {
        $s_x = $orig_w - $crop_w;
      } else {
        $s_x = floor( ( $orig_w - $crop_w ) / 2 );
      }

      if ( 'top' === $y ) {
        $s_y = 0;
      } elseif ( 'bottom' === $y ) {
        $s_y = $orig_h - $crop_h;
      } else {
        $s_y = floor( ( $orig_h - $crop_h ) / 2 );
      }
    } else {
      // Resize using $dest_w x $dest_h as a maximum bounding box.
      $crop_w = $orig_w;
      $crop_h = $orig_h;

      $s_x = 0;
      $s_y = 0;

      list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );

    }

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );

  }

  static function resize( $filename, $filename_resize, $mime_type, $width = 288, $height = 202, $crop = 0, $compress = 90 ) {

    if ( ! file_exists( $filename ) ) {
			return false;
    }

    if ( ( isset( $_GET['regenerate'] ) && current_user_can( 'administrator' ) ) || ! file_exists( $filename_resize ) ) {

      @set_time_limit( 60 );

      if(!list($w, $h) = getimagesize($filename)) return "Unsupported picture type!";

      //define width height if auto
      if ( $width == 0 ) {
        $width = $w * ($height/$h);
      }
      if ( $height == 0 ) {
        $height = $h * ($width/$w);
      }

      //return full image if to small
      if ( $w < $width || $h < $height ) {
        return array(
          $filename,
          $w,
          $h,
        );
      }

      if($mime_type == 'jpeg') $mime_type = 'jpg';
      switch($mime_type){
        case 'bmp': $img = imagecreatefromwbmp($filename); break;
        case 'gif': $img = imagecreatefromgif($filename); break;
        case 'jpg': $img = imagecreatefromjpeg($filename); break;
        case 'png': $img = imagecreatefrompng($filename); break;
        default : return "Unsupported picture type!";
      }

      $size = self::image_resize_dimensions( $w, $h, $width, $height, $crop );

      $new = imagecreatetruecolor($width, $height);

      // preserve transparency
      if($mime_type == "gif" or $mime_type == "png"){
        imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
        imagealphablending($new, false);
        imagesavealpha($new, true);
      }

      imagecopyresampled( $new, $img, $size[0], $size[1], $size[2], $size[3], $size[4], $size[5], $size[6], $size[7] );

      switch($mime_type){
        case 'bmp': imagewbmp($new, $filename_resize, $compress ); break;
        case 'gif': imagegif($new, $filename_resize, $compress ); break;
        case 'jpg': imagejpeg($new, $filename_resize, $compress ); break;
        case 'png': imagepng($new, $filename_resize, $compress/10 ); break;
      }

    }

    if(!list($filename_resize_w, $filename_resize_h) = getimagesize($filename_resize)) {

      unlink($filename_resize);
      return array();

    }

    return array(
      $filename_resize,
      $filename_resize_w,
      $filename_resize_h,
    );

  }

}

new linotype_field_image_sizes();

function get_linotype_field_image( $settings ) {

  if ( ! isset( $settings['sources'] ) ) return 'error';

  $image = new linotype_field_image( $settings );

  return $image->get();

}

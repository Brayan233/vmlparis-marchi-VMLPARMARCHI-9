<?php

function get_linotype_field_image( $options ) {
	
  $html = '';

  $options = array_merge( array(
    'dom' => 'picture',
    'class_picture' => 'linotype_field_image',
    'class' => 'flex-img',
  	'image' => false,
    'alt' => '',
    'lazy' => false,
    'srcset' => array(),
    'size' => "2000",
    'responsive' => true,
    'webp' => true
  ), $options );
  
  $options['size'] = (int) $options['size'];
  if ( ! $options['size'] ) $options['size'] = 2000;
  
  if ( $options['responsive'] ) {
    
    foreach( array( 2000, 1500, 1000, 800, 500, 300 ) as $size ) {

      if ( $size <= $options['size'] ) array_push( $options['srcset'], $size );

    }
    
  } else {
  	
    array_push( $options['srcset'], $options['size'] );
  
  }
  
  if ( $options['image'] ) {

    $lazy_class = 'lazy';
    $lazy_data = 'data-'; 
    if ( $options['lazy'] == false ) {
      $lazy_class = ''; 
      $lazy_data = ''; 
    }

    $image_src = wp_get_attachment_image_src( $options['image'], 'blocks-' . $options['srcset'][0], false );
    
    if ( $options['dom'] == 'img' ) {

      $html .= '<img class="' . $options['class'] . ' has-srcset ' . $lazy_class .'" ' . $lazy_data . 'src="' . $image_src[0] . '" alt="' . $options['alt'] . '" />';
      
    } else {

      $html .= '<picture class="' . $options['class_picture'] . '" >';

        $srcset_webp = array();
    
        $srcset_data = array();

        if ( $options['srcset'] ) {

          foreach( $options['srcset'] as $srcsize ) {

            if ( in_array( $srcsize, $options['srcset'] ) && $srcsize <= $options['srcset'][0] )  { 

              $image_srcset  = wp_get_attachment_image_src( $options['image'], 'blocks-' . $srcsize, false );
              $image_srcset_retina  = wp_get_attachment_image_src( $options['image'], 'blocks-retina-' . $srcsize, false );

              $srcset_webp = '';
              $srcset_webp_retina = '';
              if ( $options['webp'] ) {
                $srcset_webp = get_kraken_webp( $image_srcset[0] );
                $srcset_webp_retina = get_kraken_webp( $image_srcset_retina[0] );
              }
              
              $srcset_params = array(
                'src' => $image_srcset[0],
                'src_retina' => $image_srcset_retina[0],
                'src_webp' => $srcset_webp,
                'src_webp_retina' => $srcset_webp_retina,
              );
              
              if ( count( $options['srcset'] ) === 1 ) {
                $srcset_params['media'] = '';
              } else if ( $srcsize == $options['size'] ) {
                $srcset_params['media'] = '(min-width: ' . $srcsize . 'px)';
              } else if ( $srcsize == 300 ) {
                $srcset_params['media'] = '(min-width: 0px)';
              } else {
                $srcset_params['media'] = '(min-width: ' . $srcsize . 'px)';
              }
              
              $srcset_data[] = $srcset_params;
              
            }

          }


        }

        foreach( $srcset_data as $srcset_data_item ) {
          
          if ( $options['webp'] ) $html .= '<source  type="image/webp" srcset="' . $srcset_data_item['src_webp'] . ' 1x, ' . $srcset_data_item['src_webp_retina'] . ' 2x, " media="' . $srcset_data_item['media'] . '" />';
          $html .= '<source srcset="' . $srcset_data_item['src'] . ' 1x, ' . $srcset_data_item['src_retina'] . ' 2x, " media="' . $srcset_data_item['media'] . '" />';
          
        }
    
        $html .= '<img class="' . $options['class'] . ' has-srcset ' . $lazy_class .'" ' . $lazy_data . 'src="' . $image_src[0] . '" alt="' . $options['alt'] . '"/>';

      $html .= '</picture>';

    }
  
  }

  return $html;
  
}

// add_image_size( 'blocks-12', 12, 0, false );
// add_image_size( 'blocks-16', 16, 0, false );
// add_image_size( 'blocks-24', 24, 0, false );
// add_image_size( 'blocks-32', 32, 0, false );
// add_image_size( 'blocks-64', 64, 0, false );
// add_image_size( 'blocks-128', 128, 0, false );
add_image_size( 'blocks-300', 300, 0, false );
add_image_size( 'blocks-500', 500, 0, false );
add_image_size( 'blocks-800', 800, 0, false );
add_image_size( 'blocks-1000', 1000, 0, false );
add_image_size( 'blocks-1500', 1500, 0, false );
add_image_size( 'blocks-2000', 2000, 0, false );

// add_image_size( 'blocks-retina-12', 24, 0, false );
// add_image_size( 'blocks-retina-16', 32, 0, false );
// add_image_size( 'blocks-retina-24', 48, 0, false );
// add_image_size( 'blocks-retina-32', 64, 0, false );
// add_image_size( 'blocks-retina-64', 128, 0, false );
// add_image_size( 'blocks-retina-128', 256, 0, false );
add_image_size( 'blocks-retina-300', 600, 0, false );
add_image_size( 'blocks-retina-500', 1000, 0, false );
add_image_size( 'blocks-retina-800', 1600, 0, false );
add_image_size( 'blocks-retina-1000', 2000, 0, false );
add_image_size( 'blocks-retina-1500', 3000, 0, false );
add_image_size( 'blocks-retina-2000', 4000, 0, false );
  

function add_custom_mimes_to_upload_mimes( $upload_mimes ) { 
  $upload_mimes['webp'] = 'application/webp';
	$upload_mimes['svg'] = 'image/svg+xml'; 
	$upload_mimes['svgz'] = 'image/svg+xml'; 
	return $upload_mimes; 
} 
add_filter( 'upload_mimes', 'add_custom_mimes_to_upload_mimes', 10, 1 );

function fix_custom_mimes_thumb_display() {
  echo '<style>
    td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail { 
      width: 100% !important; 
      height: auto !important; 
    }
  </style>';
}
add_action('admin_head', 'fix_custom_mimes_thumb_display');


class Kraken {
    protected $auth = array();
    private $timeout;
    private $proxyParams;

    public function __construct($key = '', $secret = '', $timeout = 30, $proxyParams = array()) {
        $this->auth = array(
            "auth" => array(
                "api_key" => $key,
                "api_secret" => $secret
            )
        );
        $this->timeout = $timeout;
        $this->proxyParams = $proxyParams;
    }

    public function url($opts = array()) {
        $data = json_encode(array_merge($this->auth, $opts));
        $response = self::request($data, 'https://api.kraken.io/v1/url', 'url');

        return $response;
    }

    public function upload($opts = array()) {
        if (!isset($opts['file'])) {
            return array(
                "success" => false,
                "error" => "File parameter was not provided"
            );
        }

        if (preg_match("/\/\//i", $opts['file'])) {
            $opts['url'] = $opts['file'];
            unset($opts['file']);

            return $this->url($opts);
        }

        if (!file_exists($opts['file'])) {
            return array(
                "success" => false,
                "error" => 'File `' . $opts['file'] . '` does not exist'
            );
        }

        if (class_exists('CURLFile')) {
			$file = new CURLFile($opts['file']);
		} else {
			$file = '@' . $opts['file'];
		}

        unset($opts['file']);

        $data = array_merge(array(
            "file" => $file,
            "data" => json_encode(array_merge($this->auth, $opts))
        ));

        $response = self::request($data, 'https://api.kraken.io/v1/upload', 'upload');

        return $response;
    }

    public function status() {
        $data = array('auth' => array(
            'api_key' => $this->auth['auth']['api_key'],
            'api_secret' => $this->auth['auth']['api_secret']
        ));

        $response = self::request(json_encode($data), 'https://api.kraken.io/user_status', 'url');

        return $response;
    }

    private function request($data, $url, $type) {
        $curl = curl_init();

        if ($type === 'url') {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
        }

        curl_setopt($curl, CURLOPT_URL, $url);

        // Force continue-100 from server
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.85 Safari/537.36");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FAILONERROR, 0);
        //curl_setopt($curl, CURLOPT_CAINFO, __DIR__ . "/cacert.pem");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);

        if (isset($this->proxyParams['proxy'])) {
            curl_setopt($curl, CURLOPT_PROXY, $this->proxyParams['proxy']);
        }

        $response = json_decode(curl_exec($curl), true);

        if ($response === null) {
            $response = array (
                "success" => false,
                "error" => 'cURL Error: ' . curl_error($curl)
            );
        }

        curl_close($curl);

        return $response;
    }
}


function get_kraken_webp( $origin, $wrap = false, $media = '' ) {
  
  $pathinfo = pathinfo( $origin );
  
  $webp_file = str_replace( get_bloginfo('url'), ABSPATH, $pathinfo['dirname'] ) . '/' . $pathinfo['filename'] . '.webp';
  $webp_file_url = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.webp';
  

  if ( file_exists( $webp_file ) ) {
    
    if ( $wrap ) {
        
      return '<source srcset="' . $webp_file_url . '" media="' . $media . '" type="image/webp" />'; 

    } else {

      return $webp_file_url;

    }
      
  } else {
  
    $kraken = new Kraken("72b3f0a130d891e75d42741ff99a6f17", "92662b738c6179abd0009c8d82f7bdb4e87048eb");

    $params = array(
        "url" => $origin,
        "wait" => true,
        "lossy" => false,
        "webp" => true
    );

    $data = $kraken->url( $params );

    if ( isset( $data['kraked_url'] ) && $data['kraked_url'] ) {

      $webp_data = file_get_contents( $data['kraked_url'] );

      file_put_contents( $webp_file, $webp_data );

      if ( file_exists( $webp_file ) ) {
      
        if ( $wrap ) {
        
          return '<source srcset="' . $webp_file_url . '" media="' . $media . '" type="image/webp" />'; 
          
        } else {
        	
          return $webp_file_url;
        
        }
      
      }
      
   }
  
  }
  
  return false;
  
}


?>
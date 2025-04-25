<?php

/**
 * handypress_uploader
 *
 * Create wordpress custom uploader
 *
 * @package WordPress
 * @subpackage LINOADMIN
 * @version 1.0.0
 * @author Yannick Armspach
 * @link http://www.handypress.io
 *
 */
class handypress_uploader {

  public $uploader;

  public $uploader_id;

  public $default_options;

  /**
   * __construct
   *
   * - init the HANDYPRINTER
   *
   */
  function __construct( $uploader_id, $options = array() ) {

    //create ajax call
    if ( $uploader_id ) {

      $this->uploader_id = $uploader_id;

      //create default dir
      $upload_dir = wp_upload_dir();
      $dir = $upload_dir['path'] . '/handypress_uploader/';

      $this->options = array(
        "id" => $uploader_id,
        "type" => "default",
        "title" => "",
        "title_pos" => "top",
        "hide_add_button" => false,
        "dir" => $dir,
        "auto_sub_dir" => false,
        "unique_dir" => false,
        "unique_names" => true,
        "multi_selection" => false,
        "max_chunk_size" => (1048576*1),
        "max_file_size" => wp_max_upload_size(),
        "mime_types" => "jpeg,jpg,png,pdf",
        "padding" => "",
        "success" => false,
        // "post_id" => 0,
        "style_button_primary_class" => 'button button-primary',
        "style_button_class" => 'button',
        "fullframe" => false,
      );
      
      //parse with default option
      $this->options = wp_parse_args( $options, $this->options );

      //create dir
      wp_mkdir_p( $this->options['dir'] );
      
      //create temp dir
      $this->options['dir_temp'] = $this->options['dir'] . '_temps/';
      wp_mkdir_p( $this->options['dir_temp'] );

      //add ajax
      add_action("wp_ajax_field_uploader_" . $this->uploader_id . "_upload", array($this, "upload_file" ) );
      add_action("wp_ajax_nopriv_field_uploader_" . $this->uploader_id . "_upload", array($this, "upload_file" ) );
      //add_action("wp_ajax_field_uploader_" . $this->uploader_id . "_delete", array($this, "delete_file" ) );
      //add_action("wp_ajax_nopriv_field_uploader_" . $this->uploader_id . "_delete", array($this, "delete_file" ) );
    
    }

  }

  public function get( $key = 'default', $options = array() ) {
    
    // if ( ! isset( $this->options['post_id'] ) ) {
    //   global $post;
    //   $this->options['post_id'] = $post->ID;
    // }
    
    //parse with default option
    $this->options = wp_parse_args( $options, $this->options );

    //define uploader key
    $this->uploader_key = $this->uploader_id . '_' . $key;

    //if custom option, create transient custom settings to retrive option in ajax call
    if ( $options ) set_transient( $this->uploader_key, $options, 60*60 );

    $this->fullframe = '';
    if ( $this->options['fullframe'] ) $this->fullframe = 'fullframe';

    // HANDYLOG( 'handypress_uploader', $this->options );

    if( $this->options['type'] == 'uploadcare_widget' ) {

      return $this->create_uploadcare();

    } else {

      return $this->create_plupload();
      
    }

  }

  public function create_uploadcare() {

      //wp_enqueue_script('uploadcare', 'https://ucarecdn.com/widget/2.1.2/uploadcare/uploadcare.min.js', array('jquery'), '2.1.2', true );

     return '<input class="PRINTFLIGHT-file-uploadcare" role="uploadcare-uploader" data-clearable="" type="hidden">';
        
  }
  
  public function create_plupload(){
    
    wp_enqueue_script('plupload-all');

    wp_enqueue_style( 'field-uploader', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/uploader.css', false, false, 'screen' ); 
    wp_enqueue_script('field-uploader', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/uploader.js', array( 'jquery' ), '1.0', true );

    wp_localize_script( 'field-uploader', 'field_uploader', array(
      // 'uploader_key'          => $this->uploader_key,
      'ajaxurl'               => admin_url( 'admin-ajax.php' ),
      'plupload_init'         => array(
        'runtimes'            => 'html5,flash,silverlight,html4',
        'browse_button'       => 'plupload-browse-button',
        'browse_button_hover' => 'plupload-browse-button-hover',
        'container'           => 'plupload-upload-ui',
        'drop_element'        => 'drag-drop-area',
        'url'                 => admin_url( 'admin-ajax.php' ),
        'file_data_name'      => 'async-upload',
        'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
        'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
        'filters'             => array(
          'max_file_size'   => $this->options['max_file_size'],
          'mime_types'      => array( array( "extensions" => $this->options['mime_types'] ) )
        ),
        'multi_selection'     => $this->options['multi_selection'],
        'unique_names'        => $this->options['unique_names'],
        'chunk_size'          => $this->options['max_chunk_size'] . 'b',
        'multipart_params'    => array( 
          'action' => "auto",
          'uploader_key' => "auto",
        ),
      ),
    ));  

    //redefine dir
    //$this->options['dir'] = $this->get_directory( $this->options );

    //GET DIR FILES
    if ( $this->options['dir'] ) {
      $this->options['files'] = scandir( $this->options['dir'], 1 );
      if ( $this->options['files'] ) {
        foreach ( $this->options['files'] as $file_key => $file_name ) {
          if ( ! is_file( $this->options['dir'] . $file_name ) ){
            unset( $this->options['files'][$file_key] );
          }
        }
      }
    }

    $html = '';

    $html .= '<div class="handypress-uploader ' . $this->fullframe . '" data-uploader-key="' . $this->uploader_key . '" >';

      //$html .= '<p>dir: ' . $this->options['dir'] . '</p>';
      //$html .= '<p>uploader_key: ' . $this->uploader_key . '</p>';
      //$html .= '<p>post_id: ' . $this->options['post_id'] . '</p>';

      $html .= '<div class="field-uploader" data-id="' . $this->uploader_id . '" data-mime-types="' . $this->options['mime_types'] . '" data-max-file-size="' . $this->options['max_file_size'] . '" data-max-chunk-size="' . $this->options['max_chunk_size'] . '">';

        $html .= '<div class="field-uploader-form uploader-inline">';
            
          $html .= '<div id="media-upload-notice"></div>';

          $html .= '<div id="media-upload-error"></div>';

          $html .= '<div id="plupload-upload-ui" class="hide-if-no-js drag-drop">';

            $html .= '<div id="drag-drop-area">';

              $html .= '<div class="drag-drop-inside">';

                $html .= '<div class="drag-drop-inside-border">';

                  $html .= '<div class="drag-drop-inside-content">';
        
                    $html .= '<p class="drag-drop-info" style="font-size: 20px;">' . __('Drop a file') . '</p>';
                    $html .= '<p>' . __('or') . '</p>';
                    $html .= '<p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="' . __('Select File') . '" class="' . $this->options['style_button_primary_class'] . '"></input></p>';

                    $html .= '<br/>';

                    $html .= '<p>';
                     
                      $html .= __( 'Type' ) . ': ';
                      $mime_types = explode( ',', $this->options['mime_types'] );
                      foreach ( $mime_types as $mime_type ) {
                        $html .= '<span>' . strtoupper( $mime_type ) . '</span>';
                      }
                      
                      $html .=   __( 'Max Size' ) . ': ' . '<span>' . strtoupper( esc_html( size_format( $this->options['max_file_size'] ) ) ) . '</span>';
                    
                    $html .= '</p>';
                            
                  $html .= '</div>';

                $html .= '</div>';

              $html .= '</div>';

            $html .= '</div>';

          $html .= '</div>';
  
        $html .= '</div>';
          
        $html .= '<div class="field-uploader-files" style="display:none">';

        $html .= '</div>';

        //<!-- clone -->
        $html .= '<div class="field-uploader-file-clone" style="display:none">';

            $html .= '<div class="media-item" data-dir="" data-filename="">';
          
              $html .= '<div class="progress"><div class="percent">0%</div><div class="bar"></div></div>';

              $html .= '<div class="filename original"></div>';

              $html .= '<a style="display:none" class="' . $this->options['style_button_class'] . ' field-uploader-bt-delete page-title-action" href="#" >' . __('Delete') . '</a>';

          $html .= '</div>';

        $html .= '</div>';

      $html .= '</div>';

    $html .= '</div>';

    return $html;

  }
  

  /*
  *
  * upload_file
  *
  */
  public function upload_file() {

    // Make sure file is not cached (as it happens for example on iOS devices)
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // 5 minutes execution time
    @set_time_limit(5 * 60);

    $cleanupTargetDir = true; // Remove old files
    $maxFileAge = 5000 * 3600; // Temp file age in seconds

    // $post_id = 0;
    // if ( $_REQUEST['post_id'] ) $post_id = $_REQUEST['post_id'];
    
    $custom_params = array();
    if ( $_REQUEST['custom_params'] ) $custom_params = $_REQUEST['custom_params'];

    $uploader_key = 'default';
    if ( $_REQUEST['uploader_key'] ) $uploader_key = $_REQUEST['uploader_key'];

    //check if dynamic options
    if ( $uploader_key ) {
      
      //get transient option
      $options = get_transient( $uploader_key );
      
      //parse with default option
      $options = wp_parse_args( $options, $this->options );

    } else {

      $options = $this->options;

    }
    
    // Get a file name
    if ( isset( $_REQUEST["name"] ) ) {
        
        $filename_default = $_REQUEST["name"];

    } elseif ( ! empty( $_FILES ) ) {
        
        $filename_default = $_FILES["async-upload"]["name"];
    
    } else {
        
        $filename_default = uniqid("file_");
    
    }

    //$filePath = utf8_decode( $options['dir_temp'] . $filename_default );
    $filePath = $options['dir_temp'] . $filename_default;

    //Chunking might be enabled
    $chunk = isset( $_REQUEST["chunk"] ) ? intval($_REQUEST["chunk"]) : 0;
    $chunks = isset( $_REQUEST["chunks"] ) ? intval($_REQUEST["chunks"]) : 0;

    // Remove old temp files    
    if ( $cleanupTargetDir ) {
    
        if ( ! is_dir( $options['dir_temp'] ) || ! $options['dir_temp'] = opendir( $options['dir_temp'] ) ) {
        
            $output = array( 'type' => 'error', 'message' => 'Failed to open temp directory: ' . $options['dir_temp'] );
            die( json_encode( $output ) );

        }

        while ( ( $file = readdir( $options['dir_temp'] ) ) !== false ) {
    
            //$tmpfilePath = utf8_decode( $options['dir_temp'] . $file );
            $tmpfilePath = $options['dir_temp'] . $file;

            // If temp file is current file proceed to the next
            if ( $tmpfilePath == "{$filePath}.part" ) {

                continue;
            
            }

            // Remove temp file if it is older than the max age and is not the current file
            if ( preg_match('/\.part$/', $file ) && ( @filemtime( $tmpfilePath ) < time() - $maxFileAge ) ) {
                
                @unlink( $tmpfilePath );
            
            }

        }

        closedir( $options['dir_temp'] );
    
    }   

    if ( ! $out = @fopen( "{$filePath}.part", $chunks ? "ab" : "wb" ) ) {
        
        $output = array( 'type' => 'error', 'message' => 'Failed to open output stream.' );
        die( json_encode( $output ) );

    }

    if ( ! empty( $_FILES ) ) {

        if ( $_FILES["async-upload"]["error"] || ! is_uploaded_file( $_FILES["async-upload"]["tmp_name"] ) ) {
        
            $output = array( 'type' => 'error', 'message' => 'Failed to move uploaded file.' );
            die( json_encode( $output ) );

        }

        if ( ! $in = @fopen( $_FILES["async-upload"]["tmp_name"], "rb" ) ) {
        
            $output = array( 'type' => 'error', 'message' => 'Failed to open input stream.' );
            die( json_encode( $output ) );

        }

    } else {    

        if ( ! $in = @fopen( "php://input", "rb" ) ) {
        
            $output = array( 'type' => 'error', 'message' => 'Failed to open input stream.' );
            die( json_encode( $output ) );

        }
    
    }

    while ( $buff = fread( $in, 4096 ) ) {
    
        fwrite( $out, $buff );
    
    }

    @fclose( $out );
    @fclose( $in );

    if ( ! $chunks || $chunk == 1 ) $_REQUEST['chunk_it'] = "yes";

    $options['chunk'] = $_REQUEST['chunk_it'];

    // Check if file has been uploaded
    if ( ! $chunks || $chunk == $chunks - 1 ) {
      
      //remove .part
      $filePart = "{$filePath}.part";
      $filePath_old = $filePath;
      rename( $filePart, $filePath );

      //create option
      $options['REQUEST'] = $_REQUEST;
      $options['FILES'] = $_FILES;

      $data = array(
        "title" => $_REQUEST["source"],
        "filename" => $_REQUEST["name"],
        "filename_default" => $filename_default,
        "dir" => $filePath,
        "url" => str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $filePath ),
        // "post_id" => $post_id,
        "uploader_key" => $uploader_key,
        // "uniqkey" => $uniqkey,
        "custom_params" => $custom_params,
      );
      
      //default filter
      //$data = $this->data_filter_default( $data, $options );

      //filter upload
      if ( is_callable( $options['data_filter'] ) ) {

        $data = $options['data_filter']( $data, $options );
      
      }

      //return
      die( json_encode( $data ) );

    }

  }
  
  public function data_filter_default( $data, $options ) {

    $data['old_dir'] = $data['dir'];

    $data['dir'] = $options['dir'] . $data['title'];
    $data['url'] = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $data['dir'] );

    rename( $data['old_dir'], $data['dir'] );

    return $data;

  }

  /*
  *
  * delete_file
  *
  
  // public function delete_file() {
      
  //   if ( ! isset( $_REQUEST ) && $_REQUEST['filename'] == "" ) {
    
  //       $output = array( 'type' => 'error', 'message' => "Delete error, no filename found" );
  //       die( json_encode( $output ) );
    
  //   }

  //   $filename = $_REQUEST['filename'];

  //   $dir = $this->get_directory( $this->options );
    
  //   unlink( $dir . $filename );

  //   $output = array( 'type' => 'success', 'message' => $filename . " was deleted with success", "data" => $this->options );
  //   die( json_encode( $output ) );

  // }

  /*
  *
  * get_directory
  *
  */
  public function get_directory( $options ) {

    if( $options['auto_sub_dir'] ) {
      
      if ( $options['post_id'] ) {

        $directory = $options['dir'] . $options['id'] . '/' . $options['post_id'] . '/';
      
      } else {
      
        $directory = $options['dir'] . $options['id'] . '/';
      
      }

    } else {

      $directory = $options['dir'];
    
    }

    wp_mkdir_p( $directory );

    return $directory;

  }

  /*
  *
  * uniqdir
  *
  */
  function uniqdir( $dir, $id ){

    $uniqdir = md5( uniqid( $id ) );             

    while ( is_dir( $dir . $uniqdir ) ){

        $uniqdir = md5( uniqid( $id ) );
    
    }

    return $uniqdir;

  }


}

?>
<?php

if ( !class_exists('LINOADMIN_uploader') ){
class LINOADMIN_uploader {

    function __construct( $META_ID, $META ) {

        $this->field_id = $META_ID;
        $this->field = $META;
        $this->field['id'] = $META_ID;

        add_action("wp_ajax_field_uploader_" . $this->field_id . "_upload", array($this, "upload_file" ) );
        add_action("wp_ajax_nopriv_field_uploader_" . $this->field_id . "_upload", array($this, "upload_file" ) );

        add_action("wp_ajax_field_uploader_" . $this->field_id . "_delete", array($this, "delete_file" ) );
        add_action("wp_ajax_nopriv_field_uploader_" . $this->field_id . "_delete", array($this, "delete_file" ) );

    }


    /*
    *
    * upload_file
    *
    */
    public function upload_file() {

        // if ( ! isset( $_REQUEST ) ) {

        //     $output = array( 'type' => 'error', 'message' => "Upload error, no name found" );
        //     die( json_encode( $output ) );

        // }

        // 5 minutes execution time
        @set_time_limit(5 * 60);

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // Create target dir
        if( $this->field['options']['auto_sub_dir'] ){

            $post_id = isset($_REQUEST["post_id"]) ? $_REQUEST["post_id"] : false;

            if ( $post_id ) {
                $this->field['options']['dir'] = $this->field['options']['dir'] . $this->field['id'] . '/' . $post_id . '/';
            } else {
                $this->field['options']['dir'] = $this->field['options']['dir'] . $this->field['id'] . '/';
            }

        }
        // wp_mkdir_p( $this->field['options']['dir'] );
        if (!file_exists( $this->field['options']['dir'] ) ) {

            @mkdir( $this->field['options']['dir'], 0755, true );

        }

        // Get a file name
        if (isset($_REQUEST["name"])) {

            $fileName = $_REQUEST["name"];

        } elseif (!empty($_FILES)) {

            $fileName = $_FILES["async-upload"]["name"];

        } else {

            $fileName = uniqid("file_");

        }

        $filePath = $this->field['options']['dir'] . $fileName;

        //Chunking might be enabled
        $chunk = isset( $_REQUEST["chunk"] ) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset( $_REQUEST["chunks"] ) ? intval($_REQUEST["chunks"]) : 0;

        // Remove old temp files
        if ( $cleanupTargetDir ) {

            if ( ! is_dir( $this->field['options']['dir'] ) || ! $dir = opendir( $this->field['options']['dir'] ) ) {

                $output = array( 'type' => 'error', 'message' => 'Failed to open temp directory.' );
                die( json_encode( $output ) );

            }

            while ( ( $file = readdir($dir) ) !== false ) {

                $tmpfilePath = $this->field['options']['dir'] . $file;

                // If temp file is current file proceed to the next
                if ( $tmpfilePath == "{$filePath}.part" ) {

                    continue;

                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {

                    @unlink($tmpfilePath);

                }

            }

            closedir($dir);

        }


        // Open temp file
        if ( ! $out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb") ) {

            $output = array( 'type' => 'error', 'message' => 'Failed to open output stream.' );
            die( json_encode( $output ) );

        }

        if (!empty($_FILES)) {

            if ($_FILES["async-upload"]["error"] || !is_uploaded_file($_FILES["async-upload"]["tmp_name"])) {

                $output = array( 'type' => 'error', 'message' => 'Failed to move uploaded file.' );
                die( json_encode( $output ) );

            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["async-upload"]["tmp_name"], "rb")) {

                $output = array( 'type' => 'error', 'message' => 'Failed to open input stream.' );
                die( json_encode( $output ) );

            }

        } else {

            if (!$in = @fopen("php://input", "rb")) {

                $output = array( 'type' => 'error', 'message' => 'Failed to open input stream.' );
                die( json_encode( $output ) );

            }

        }

        while ($buff = fread($in, 4096)) {

            fwrite($out, $buff);

        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {

            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);

        }

        $output = array( 'type' => 'success', 'message' => 'File uploader with success', "data" => $this->field );
        die( json_encode( $output ) );

    }

    /*
    *
    * delete_file
    *
    */
    public function delete_file() {

        if ( ! isset( $_REQUEST ) && $_REQUEST['filename'] == "" ) {

            $output = array( 'type' => 'error', 'message' => "Delete error, no filename found" );
            die( json_encode( $output ) );

        }

        if( $this->field['options']['auto_sub_dir'] ){

            $post_id = isset($_REQUEST["post_id"]) ? $_REQUEST["post_id"] : false;

            if ( $post_id ) {
                $this->field['options']['dir'] = $this->field['options']['dir'] . $this->field['id'] . '/' . $post_id . '/';
            } else {
                $this->field['options']['dir'] = $this->field['options']['dir'] . $this->field['id'] . '/';
            }

        }

        $filename = $_REQUEST['filename'];

        unlink( $this->field['options']['dir'] . $filename );

        $output = array( 'type' => 'success', 'message' => $filename . " was deleted with success", "data" => $this->field );
        die( json_encode( $output ) );

    }

}
}

new LINOADMIN_uploader( $META_ID, $META );

?>

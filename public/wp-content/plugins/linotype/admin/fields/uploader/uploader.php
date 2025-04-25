<?php 

wp_enqueue_script('plupload-all');

wp_enqueue_style( 'field-uploader', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/uploader.css', false, false, 'screen' ); 
wp_enqueue_script('field-uploader', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/uploader.js', array( 'jquery' ), '1.0', true );

wp_localize_script( 'field-uploader', 'field_uploader', array( 
	'ajaxurl' => admin_url( 'admin-ajax.php' ),
	'plupload_init' => array(
	    'runtimes'            => 'html5,flash,silverlight,html4',
	    'browse_button'       => 'plupload-browse-button',
	    'container'           => 'plupload-upload-ui',
	    'drop_element'        => 'drag-drop-area',
	    'url'				  => admin_url( 'admin-ajax.php' ),
	    'file_data_name'      => 'async-upload',
	    'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
	    'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
	    'filters' => array(
	        'max_file_size'   => wp_max_upload_size(),
	        'mime_types' => array(
	            array( "extensions" => '*' ),
	        )
	    ),
	    'multi_selection' => true,
	    'chunk_size' => (1048576*4) . 'b',
	    'multipart_params' => array( 'action' => "field_uploader_none" ),
	),
));  

$default_options = array(
    "auto_sub_dir" => false,
    "dir" => "",
    "title_pos" => "",
    "mime_types" => "*",
    "max_file_size" => wp_max_upload_size(),
    "max_chunk_size" => "",
    "title_pos" => "top",
    "title" => "",
    "hide_add_button" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if( ! is_dir( $field['options']['dir'] ) ) {

	$upload_dir = wp_upload_dir();
    
    $field['options']['dir'] = $upload_dir['basedir'];
    wp_mkdir_p( $field['options']['dir'] );

}

if( $field['options']['auto_sub_dir'] ){
	
	if ( isset( $_GET['post'] ) && $_GET['post'] ) {
		$field['options']['dir'] = $field['options']['dir'] . $field['id'] . '/' . $_GET['post'] . '/';
	} else {
		$field['options']['dir'] = $field['options']['dir'] . $field['id'] . '/';
	}

} 

wp_mkdir_p( $field['options']['dir'] );

//if( ! $field['options']['title_pos'] ) $field['options']['title_pos'] = 'top';
//if( ! $field['options']['mime_types'] ) $field['options']['mime_types'] = '*';
//if( ! $field['options']['max_file_size'] ) $field['options']['max_file_size'] = wp_max_upload_size();


//GET DIR FILES
if ( $field['options']['dir'] ) {
	$dir_files = scandir( $field['options']['dir'], 1 );
	if ( $dir_files ) {
		foreach ( $dir_files as $file_key => $file_name ) {
			if ( $file_name === '.' || $file_name === '..' ){
				unset( $dir_files[$file_key] );
			}
		}
	}
}




?>

<li class="wp-field wp-field-uploader <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>	
	<?php } ?>

	<div class="field-content">
		
		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="field-uploader" data-id="<?php echo $field['id']; ?>" data-post-id="<?php if ( isset( $_GET['post'] ) ) { echo $_GET['post']; } ?>" data-mime-types="<?php echo $field['options']['mime_types']; ?>" data-max-file-size="<?php echo $field['options']['max_file_size']; ?>" data-max-chunk-size="<?php echo $field['options']['max_chunk_size']; ?>">

			<?php if( $field['options']['title_pos'] === 'top' ) { ?>
			<div class="field-uploader-panel">
				<div class="field-uploader-title" ><?php if( $field['options']['title'] ) { ?><h1><?php echo $field['options']['title']; ?></h1><?php } ?><?php if( $field['options']['hide_add_button'] !== true ) { ?><div class="field-uploader-bt-add button" style="<?php if ( ! $dir_files ){ echo "display:none;"; }; ?>"><?php _e('Add'); ?></div><?php } ?></div>
			</div>
			<?php } ?>

			<div class="field-uploader-form uploader-inline" style="<?php if ( $dir_files ){ echo "display:none;"; }; ?>">
			 	
			 	<?php 
			 	 if ( ! _device_can_upload() ) {
			        echo '<p>' . __('The web browser on your device cannot be used to upload files.') . '</p>';
			        return;
			    }
			    ?>

			    <div id="media-upload-notice"></div>

			    <div id="media-upload-error"></div>

			    <div id="plupload-upload-ui" class="hide-if-no-js drag-drop" style="style="background-color:<?php echo $field['padding']; ?>"">

			        <div id="drag-drop-area">
			            <div class="drag-drop-inside" style="margin: 50px;">

			            	<div class="field-uploader-bt-close dashicons dashicons-no"></div>
			                
			                <p class="drag-drop-info" style="font-size: 20px;"><?php _e('Drop files here'); ?></p>
			                <p><?php _ex('or', 'Uploader: Drop files here - or - Select Files'); ?></p>
			                <p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="<?php esc_attr_e('Select Files'); ?>" class="button" /></p>

			                <p class="max-upload-size">
			                <?php echo __( 'Type' ) . ': ' . $field['options']['mime_types']; ?>
			                <?php echo ' ' . __( 'Size' ) . ': ' . esc_html( size_format( $field['options']['max_file_size'] ) ); ?>
			                </p>
			                
			            </div>
			        </div>

			    </div>

			    <div id="html-upload-ui" class="hide-if-js">
			        
			        <p>Activate Javascript to upload files</p>

			        <div class="clear"></div>

			        <p class="upload-html-bypass hide-if-no-js">Vous utilisez l&#8217;outil de mise en ligne du navigateur. Le nouvel outil de mise en ligne de WordPress inclus la possibilit&#233; de t&#233;l&#233;charger plusieurs fichiers &#224; la fois par glisser/d&#233;poser. <a href="#">Passer au nouvel outil de mise en ligne</a>.</p>
			    
			    </div>
				
			</div>
			
			<div class="field-uploader-files">

				<?php 
				
				if ( $dir_files ) {
					foreach ( $dir_files as $file_key => $file_name ) {

						echo '<div class="media-item" data-dir="' . $field['options']['dir'] . '" data-filename="' . $file_name . '">';
							
							echo '<div class="filename original">' . $file_name . '</div>';

							echo '<a href="#" class="field-uploader-bt-delete page-title-action">' . __('Delete') . '</a>';

						echo '</div>';
						
					}
				}

				?>

			</div>

			<div class="field-uploader-file-clone" style="display:none">

				<div class="media-item" data-dir="<?php echo $field['options']['dir']; ?>" data-filename="">
					
					<div class="progress"><div class="percent">0%</div><div class="bar"></div></div>

					<div class="filename original"></div>

					<a style="display:none" class="field-uploader-bt-delete page-title-action" href="#" ><?php _e('Delete'); ?></a>

				</div>

			</div>

			<?php if( $field['options']['title_pos'] === 'bottom' ) { ?>
			<div class="field-uploader-panel">
				<div class="field-uploader-title" ><?php if( $field['options']['title'] ) { ?><h1><?php echo $field['options']['title']; ?></h1><?php } ?><?php if( $field['options']['hide_add_button'] !== true ) { ?><div class="field-uploader-bt-add button" style="<?php if ( ! $dir_files ){ echo "display:none;"; }; ?>"><?php _e('Add'); ?></div><?php } ?></div>
			</div>
			<?php } ?>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

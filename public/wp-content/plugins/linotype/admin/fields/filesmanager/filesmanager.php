<?php

wp_enqueue_style( 'field-filesmanager', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/filesmanager.css', false, false, 'screen' );
wp_enqueue_script('field-filesmanager', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/filesmanager.js', array('jquery'), '1.0', true );

$max_upload = min( LINOADMIN_filesmanager::asBytes( ini_get('post_max_size') ), LINOADMIN_filesmanager::asBytes( ini_get('upload_max_filesize') ) );

$default_options = array(
	'dir' => null,
	"auto_sub_dir" => true,
    "allow_delete" => true,
    "allow_create_folder" => true,
    "allow_upload" => true,
    "allow_direct_link" => true,
    "max_upload" => $max_upload,
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-filesmanager <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>


		<div id="top">
		   <?php if( $field['options']['allow_upload'] == true): ?>
			<form action="?" method="post" id="mkdir" />
				<label for=dirname>Create New Folder</label><input id=dirname type=text name=name value="" />
				<input type="submit" value="create" />
			</form>

		   <?php endif; ?>

		   <?php if( $field['options']['allow_upload'] == true): ?>

			<div id="file_drop_target">
				Drag Files Here To Upload
				<b>or</b>
				<input type="file" multiple />
			</div>
		   <?php endif; ?>
			<div id="breadcrumb">&nbsp;</div>
		</div>

		<div id="upload_progress"></div>
		<table id="table"><thead><tr>
			<th>Name</th>
			<th>Size</th>
			<th>Modified</th>
			<th>Permissions</th>
			<th>Actions</th>
		</tr></thead><tbody id="list">

		</tbody></table>

		<textarea class="wp-field-settings" ><?php echo json_encode( $field['options'] ); ?></textarea>

		<div class="field-input">
			<textarea style="display:<?php if( $field['options']['input'] ) { echo 'block'; } else { echo 'none'; } ?>;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>"><?php if ( $field['value'] ) { echo '[' . json_encode( $field['value'] ) . ']'; } ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

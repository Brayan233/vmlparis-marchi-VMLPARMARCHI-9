<?php

$default_options = array(
    "height" => "",
    "style" => "",
    "placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-presets <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>	
	<?php } ?>
	
	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>


		<?php

		/*
		*
		* GET CURRENT SETTINGS
		* 
		*/
		$current_data = array();

		if ( $this->LINOADMIN ) {
			foreach ( $this->LINOADMIN as $page_key => $page ) {

				if ( $page['metabox'] ) {
					foreach ( $page['metabox'] as $metabox_key => $metabox ) {

						if ( $metabox['tabs_contents'] ) {
							foreach ( $metabox['tabs_contents'] as $tab_key => $tab ) {

								if ( $tab['meta'] ) {
									foreach ( $tab['meta'] as $meta_key => $meta ) {

										if ( $meta['type'] !== 'presets' ) $current_data[ $meta_key ] = get_option( $meta_key, "" );

									}
								}

							}
						}
						

					}
				}
				
			}
		}

		/*
		*
		* GET CURRENT PRESETS
		* 
		*/
		$all_presets = json_decode( $field['value'], true );


		/*
		*
		* ACTIONS
		* 
		*/
		if ( isset( $_REQUEST['action'] ) && 'preset_action' == $_REQUEST['action'] ) {

			$preset_output = '';

			//IMPORT
			if ( isset( $_POST['preset_select'] ) && $_POST['preset_select'] ) {

				if ( $_POST['preset_select'] == '_preset_custom' && isset( $_POST['preset_import_data'] ) && $_POST['preset_import_data'] ) {
				
					$import_data = json_decode( stripslashes( $_POST['preset_import_data'] ), true );

				} else if ( isset( $all_presets[ $_POST['preset_select'] ] ) ) {
					
					$import_data = $all_presets[ $_POST['preset_select'] ];

				} else {

					$preset_output = 0;

				}

				if ( $import_data ) {

					foreach ( $current_data as $id => $value ) {
						
						update_option( $id, $import_data[$id] );

						$preset_output = 1;

					}

				} else {

					$preset_output = 2;

				}

			}


			//SAVE AS
			if ( isset( $_POST['preset_save_as'] ) && $_POST['preset_save_as'] ) {

				$all_presets = array();

				if ( $field['value'] ) $all_presets = json_decode( $field['value'], true );

				$all_presets[ $_POST['preset_save_as'] ] = $current_data;

				update_option( $field['id'], json_encode( $all_presets ) );

				$preset_output = 3;

			}

			//preset_reset
			if ( isset( $_POST['preset_reset'] ) && $_POST['preset_reset'] ) {

				$all_presets = array();

				if ( $field['value'] ) $all_presets = json_decode( $field['value'], true );

				if ( isset( $all_presets[ $_POST['preset_reset'] ] ) ) unset( $all_presets[ $_POST['preset_reset'] ] );

				update_option( $field['id'], json_encode( $all_presets ) );

				$preset_output = 4;

			}

			//preset_reset_current
			if ( isset( $_POST['preset_reset_current'] ) && $_POST['preset_reset_current'] ) {

				if ( $current_data ) {

					foreach ( $current_data as $id => $value ) {
						
						delete_option( $id );

					}

				}

				$preset_output = 5;

			}

			$preset_output = '&preset_output=' . $preset_output;

			wp_redirect( $_SERVER['HTTP_REFERER'] . $preset_output );

		}

		$preset_message = '';

		$preset_output_message = array( 
			'No Preset',
			'Preset Success',
			'Preset Error',
			'Preset Saved',
			'Preset Deleted',
			'Settings Reseted',
		);

		if ( isset( $_GET['preset_output'] ) && $preset_output_message[ $_GET['preset_output'] ] ) {

			$preset_message = '<div id="message" class="info notice is-dismissible below-h2"><p><strong>' . $preset_output_message[ $_GET['preset_output'] ] . '</strong></p></div>';

		}

		?>

		<?php echo $preset_message; ?>

		<h3>Presets</h3> 
		<p>Select or paste your preset and click import</p>

		<?php 
			
			if ( $all_presets ) {
				foreach ( $all_presets as $preset_key => $preset ) {
					
					echo '<label title="title"><input name="preset_select" value="' . $preset_key . '" type="radio"> <span>' . $preset_key . '</span></label><br/>';
				
				}
				
			}

			echo '<label title="title"><input name="preset_select" value="_preset_custom" type="radio" checked="checked"> <span>Custom</span></label><br/>';
				
		?>

		<br/>
		
		<div class="field-input">
			<textarea type="textarea" name="preset_import_data" autocorrect="off" autocomplete="off" spellcheck="false"/></textarea>
		</div>
		<p><input class="button button-success" name="save" type="submit" value="Import"></p>
		
		<br/><br/>

		<h3>Export</h3>
		<p>Copy and paste this data in a new wordpress install to apply the same settings of this website</p>
		<div class="field-input">
			<textarea type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" readonly/><?php echo json_encode( $current_data ); ?></textarea>
		</div>
		<p><input style="vertical-align: top; margin: 0px 4px 0px 0px; line-height: 21px;"class="" name="preset_save_as" type="text" value=""><input class="button button-primary" name="save" type="submit" value="Save as Preset"></p>
		<p><input style="vertical-align: top; margin: 0px 4px 0px 0px; line-height: 21px;"class="" name="preset_reset" type="text" value=""><input class="button button-error" name="save" type="submit"     value="Delete Preset"></p>
		
		<br/><br/>

		<h3>Reset</h3>
		<p>Delete all settings and use of default value</p>
		<p><input class="" name="preset_reset_current" type="checkbox" value="true"> Check to reset all current settings</p>
		<p><input class="button button-error" name="save" type="submit" value="Reset all current settings"></p>
		
		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

		<!-- <input type="hidden" name="action" value="save"> -->
		<input type="hidden" name="action" value="preset_action">

		<p class="error"><b><i>! ATTENTION: This field is under developement and work only for simple options (not multiple field with "meta_id@xxx" options or post and custompost )</i></b></p>
		

	</div>
	
</li>
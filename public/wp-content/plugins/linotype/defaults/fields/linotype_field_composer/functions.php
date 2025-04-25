<?php

if ( ! class_exists('LINOTYPE_composer') ) {

function ajax_LINOTYPE_composer_temp_settings() {

  update_option( 'LINOTYPE_composer_temp_settings', $_REQUEST["settings"] );

  die( json_encode( array( 'type' => 'success', 'block_id' => $_REQUEST["block_id"]) ) );

}

add_action( 'wp_ajax_LINOTYPE_composer_temp_settings', 'ajax_LINOTYPE_composer_temp_settings' );

function ajax_LINOTYPE_composer_admin_refresh() {

	$item_id = null;
	if ( isset( $_REQUEST["item_id"] ) && $_REQUEST["item_id"] ) $item_id = stripcslashes( $_REQUEST["item_id"] );

	$params = array();
	if ( isset( $_REQUEST["params"] ) && $_REQUEST["params"] ) {
		
		$params = stripcslashes( $_REQUEST["params"] );
		if ( $params !== "" ) $params = json_decode( $params, true );
	
	}

	$file = null;
	
	$settings = array( 
		'item' => LINOTYPE::$BLOCKS->get( $params['type'] ), 
		'type' => $params['type'], 
		'title' => false, 
		'icon' => false, 
		'options' => $params['options'],
		'contents' => false, 
		'elements' => LINOTYPE::$BLOCKS->get(),
		'editor' => false,
	);

	extract( $settings );

	ob_start();

		if ( file_exists( LINOTYPE::$BLOCKS->get( $item_id )['dir'] . '/preview.html' ) ) {

		// 	echo '<div class="composer-item-preview">';
			
				$mustache = new Mustache_Engine;
				echo $mustache->render( file_get_contents( LINOTYPE::$BLOCKS->get( $item_id )['dir'] . '/preview.html' ), $params['options'] );

		// 	echo '</div>';

		} else if ( file_exists( LINOTYPE::$BLOCKS->get( $item_id )['dir'] . '/preview.php' ) ) {

			//echo '<div class="composer-item-preview">';

				include LINOTYPE::$BLOCKS->get( $item_id )['dir'] . '/preview.php';
			
			//echo '</div>';

		}
		
		$content = ob_get_contents();

	ob_end_clean();

	die( json_encode( array( 'type' => 'success', 'item_id' => $item_id, 'file' => $file, 'params' => $params, 'content' => $content ) ) );

}

add_action( 'wp_ajax_LINOTYPE_composer_admin_refresh', 'ajax_LINOTYPE_composer_admin_refresh' );


  
class LINOTYPE_composer {

	static $ITEM_COUNT = 0;

	static $EDITOR_STYLE;

	static function render( $items, $elements, $editor = false, $preview = false ) {

		if ( $editor ) self::render_editor_header( $items, $elements, $editor, $preview );

			self::render_elements( $items, $elements, $editor, $preview );

		if ( $editor ) self::render_editor_footer( $items, $elements, $editor, $preview );

	}

	static function render_elements( $items = array(), $elements = array(), $editor = false, $preview = false ) {

		if ( isset( $items ) && is_array( $items ) && $items ) {

			foreach ( $items as $element_key => $item ) {
				
				if ( isset( $item['type'] ) && isset( $elements[ $item['type'] ] ) ) {

					if ( ! isset( $item['contents'] ) ) $item['contents'] = array();

					//set uniq id
					$item['id'] = handypress_helper::getUniqueID();

					//set default options
					$options = array();

					$item['params'] = array();

					//set default options
					if( $elements[ $item['type'] ]['options'] ){
						foreach ( $elements[ $item['type'] ]['options'] as $option_key => $option ) {

							if ( $preview && isset( $option['dummy'] ) ) {
								$options[$option_key] = $option['dummy'];
							} else if ( isset( $item['options'][$option_key] ) && $item['options'][$option_key] ) {
								$options[$option_key] = $item['options'][$option_key];
							} else if ( isset( $option['default'] ) && $option['default'] && $option['type'] !== 'checkbox' ) {
								$options[$option_key] = $option['default'];
							} else {
								$options[$option_key] = "";
							}

						}
					}
					
					//add custom options if exist
					if ( isset( $item['options'] ) && $item['options'] ) $options = array_merge( $options, $item['options'] );

					//set default content
					$contents = array();
					if ( isset( $item['contents'] ) ) $contents = $item['contents'];

					$type = $item['type'];
					$title = $elements[ $item['type'] ]['title'];
					if ( $elements[ $item['type'] ]['icon'] ) { $icon = $elements[ $item['type'] ]['icon']; } else { $icon = 'dashicons dashicons-admin-generic'; }
	
					//create element
					if ( $editor ) {

						//clean template data
						$item = array_filter( $item );

						//exec
						if ( isset( $elements[ $item['type'] ]['editor'] ) && is_callable( $elements[ $item['type'] ]['editor'] ) ) {

							call_user_func( $elements[ $item['type'] ]['editor'], $item, $type, $title, $icon, $options, $contents, $elements, $editor, $preview );

						} else {

							LINOTYPE_composer::render_editor_element( $item, $type, $title, $icon, $options, $contents, $elements, $editor, $preview );

						}

					} else {

						//TEMPS: disable device options because many conflict
						//get device options
						//if ( isset( $item['options_mobile'] ) && LINOTYPE::$SETTINGS['device'] == 'mobile' ) $options = array_merge( $options, $item['options_mobile'] );
						//if ( isset( $item['options_tablet'] ) && LINOTYPE::$SETTINGS['device'] == 'tablet' ) $options = array_merge( $options, $item['options_tablet'] );
						//if ( isset( $item['options_desktop'] ) && LINOTYPE::$SETTINGS['device'] == 'desktop' ) $options = array_merge( $options, $item['options_desktop'] );
							
						//exec
						if ( isset( $elements[ $item['type'] ]['render'] ) && is_callable( $elements[ $item['type'] ]['render'] ) ) {
							
							call_user_func( $elements[ $item['type'] ]['render'], $item, $type, $title, $icon, $options, $contents, $elements, $editor, $preview );
							
						}

					}

				} else {
					
					$elements[ $item['type'] ]['title'] = '<b>ERROR</b> : block missing : <i>' . $item['type'] . '</i>';
					$elements[ $item['type'] ]['icon'] = 'dashicons dashicons-editor-help';
					
					LINOTYPE_composer::render_editor_element( $item, 'composer-error', '<b>ERROR</b> : block missing : <i>' . $item['type'] . '</i>', 'dashicons dashicons-editor-help', array(), array(), $elements, $editor, $preview );

				}

			}

		}

	}

	static function render_editor_element( $item, $type, $title, $icon, $options, $contents, $elements, $editor ) {

		self::render_editor_element_header( $item, $type, $title, $icon, $options, $contents, $elements, $editor );

		self::render_editor_element_footer( $item, $type, $title, $icon, $options, $contents, $elements, $editor );

	}

	static function render_element_header( $item, $type, $title, $icon, $options, $contents, $elements, $editor ) {
		
		$params_defaults = array(
			'class' => '',
      		'data' => array(),
		);

		$params = array_merge( $params_defaults, $item['params'] );
		
		$class = $type;
		if ( isset( $options['_block_custom_class'] ) ) $class .= ' ' . $options['_block_custom_class'];
			if ( $params['class'] && is_array( $params['class'] ) ) $params['class'] = implode(' ', array_filter( $params['class'] ) );
			if ( $params['class'] ) $class .= ' ' . $params['class'];
			//if ( $options['_block_default_display'] ) $class .= ' ' . str_replace(',', ' ', $options['_block_default_display'] );
			
		// if ( $item['editable'] === true ) $class .= ' linotype-edit';
		
			$datas = '';
		foreach( $params['data'] as $data_id => $data ) {
			
		if ( $data && is_array( $data ) ) {
			$datas .= ' data-' . $data_id . "='" . json_encode( $data ) . "'";
		} else {
			$datas .= ' data-' . $data_id . '="' . $data . '"';
		}
		
		}
    
		$font_family = '';
		if ( isset( $options['_block_default_font_family'] ) ) {

			wp_enqueue_style( 'googlefont-' . $options['_block_default_font_family'], 'https://fonts.googleapis.com/css?family=' . $options['_block_default_font_family'], false, false, 'screen' ); 
			
			$font_family = '"' . str_replace( '+', ' ', $options['_block_default_font_family'] ) . '"';

		}

		$custom_styles = array( '_root' => array(), '_root, _root *' => array() );

		if( isset( $options['_block_default_position'] ) ) $custom_styles['_root']['position'] = $options['_block_default_position'];
		if( isset( $options['_block_default_display'] ) ) $custom_styles['_root']['display'] = $options['_block_default_display'];
		if( isset( $options['_block_default_index'] ) ) $custom_styles['_root']['z-index'] = $options['_block_default_index'];
		if( isset( $options['_block_default_opacity'] ) ) $custom_styles['_root']['opacity'] = $options['_block_default_opacity'];
		if( isset( $options['_block_default_margin'] ) ) $custom_styles['_root']['margin'] = $options['_block_default_margin'];
		if( isset( $options['_block_default_padding'] ) ) $custom_styles['_root']['padding'] = $options['_block_default_padding'];
		if( isset( $options['_block_default_bg_color'] ) ) $custom_styles['_root']['background-color'] = $options['_block_default_bg_color'];
		if( isset( $options['_block_default_width'] ) ) $custom_styles['_root']['width'] = $options['_block_default_width'];
		if( isset( $options['_block_default_height'] ) ) $custom_styles['_root']['height'] = $options['_block_default_height'];
		if( isset( $options['_block_custom_css'] ) ) $custom_styles['_root']['_inline_css'] = $options['_block_custom_css'];

		if( isset( $font_family ) ) $custom_styles['_root, _root *']['font-family'] = $font_family;
		if( isset( $options['_block_default_font_weight'] ) ) $custom_styles['_root, _root *']['font-weight'] = $options['_block_default_font_weight'];
		if( isset( $options['_block_default_font_size'] ) ) $custom_styles['_root, _root *']['font-size'] = $options['_block_default_font_size'];
		if( isset( $options['_block_default_line_height'] ) ) $custom_styles['_root, _root *']['line-height'] = $options['_block_default_line_height'];
		if( isset( $options['_block_default_color'] ) ) $custom_styles['_root, _root *']['color'] = $options['_block_default_color'];
		

		block( 'styles', array( 'item' => $item, 'type' => $type, 'title' => $title, 'icon' => $icon, 'options' => $options, 'contents' => $contents, 'elements' => $elements, 'editor' => $editor ), $custom_styles );


		

		?>
		
		<div class="<?php echo $class; ?>" id="block_<?php echo $item['id']; ?>" <?php echo $datas; ?>>
			
		<?php

		// if ( get_option( 'linotype_helper' ) && current_user_can( 'linotype_admin' ) ) {

		// 	echo '<linotype class="linotype-helper-block">';
				
		// 		echo '<linotype class="linotype-helper-block-title"><b>' . $title . '</b><span>+</span><a  class="linotype-helper-block-link" href="' . $elements[ $type ]['editor_link'] . '">Edit</a></linotype>';
				
		// 		echo '<linotype class="linotype-helper-block-infos">';
					
		// 			linodump( array(
		// 				'type' => $type,
		// 				'options' => $options,
		// 				'params' => $params,
		// 				//'element' => $elements[ $type ]['editor_link'],
		// 			) );

		// 			echo '</linotype>';

		// 		echo '<linotype class="linotype-helper-block-content">';
			
		// 		echo '</linotype>';

				

		// 	echo '</linotype>';

		// }

	}

	static function render_element_footer( $item, $type, $title, $icon, $options, $contents, $elements, $editor ) {

		?>

		</div>
		
		<?php

	}
	
	static function render_editor_element_header( $item, $type, $title, $icon, $options, $contents, $elements, $editor ) {
		
		$element_class = '.composer-item.' . $type;

		$style_background = '';
		if ( isset( $elements[ $item['type'] ]['background'] ) ) $style_background = $elements[ $item['type'] ]['background'];
		$style_color = '';
		if ( isset( $elements[ $item['type'] ]['color'] ) ) $style_color = $elements[ $item['type'] ]['color'];
		//if( $elements[ $item['type'] ]['background'] ) $style_background = handypress_helper::hex2rgb( $elements[ $item['type'] ]['background'], true, false );
		//if( $elements[ $item['type'] ]['color'] ) $style_color = handypress_helper::hex2rgb( $elements[ $item['type'] ]['color'], true, false );

		$element_style = '';

		$element_style .= $element_class . ' > .composer-item-bg {';
			$element_style .= 'background-color:' . $style_background . ';';
		$element_style .= '}';
		$element_style .= $element_class . ' > .composer-item-toolbar *,';
		$element_style .= $element_class . '.hover > .composer-item-toolbar * {';
			$element_style .= 'color:' . $style_color . '!important;';
		$element_style .= '}';
		$element_style .= $element_class . ' ' . $element_class . '-contents > .composer-item-toolbar-bottom * {';
			$element_style .= 'color:' . $style_color . '!important;';
		$element_style .= '}';

		//$params_class = '';
		//if ( isset( $item['params']['class'] ) ) $params_class = $item['params']['class'];
		
		$class = $type;
		
    
    if ( strpos( $item['type'], '_module_') !== false ) {
    
      $class .= ' composer-item-module';
    
    } else {
    	
      $class .= ' composer-item-block';
      
    }
		
    if ( isset( $item['params'] ) ) {
			
			$params_defaults = array(
				'class' => '',
			);

			$params = array_merge( $params_defaults, $item['params'] );
			
			if ( $params['class'] && is_array( $params['class'] ) ) $params['class'] = implode(' ', array_filter( $params['class'] ) );
			if ( $params['class'] ) $class .= ' ' . $params['class'];
			
		}

		self::add_editor_style( $element_style );
		
		//if ( ! isset( $item['id'] ) || ! $item['id'] ) $item['id'] = handypress_helper::getUniqueID();
		
		$item_id = handypress_helper::getUniqueID();

		?>

		<div id="<?php echo $item_id; ?>" class="composer-item <?php echo $class; ?>" composer-item-type="<?php echo $item['type']; ?>" composer-item-title="<?php echo $elements[ $item['type'] ]['title']; ?>" composer-item-icon="<?php echo $elements[ $item['type'] ]['icon']; ?>">
		
			<div class="composer-item-toolbar">

				<div class="composer-item-move composer-item-handlebar handle"></div>

				<div class="composer-item-toolbar-align composer-item-toolbar-left">
					
					<div class="composer-item-toolbar-align composer-item-toolbar-infos">
						

						<span class="composer-item-title handle"><span class="composer-item-icon composer-bt <?php if ( $elements[ $item['type'] ]['icon'] ) { echo $elements[ $item['type'] ]['icon']; } else { echo 'dashicons dashicons-admin-generic'; } ?>"></span><?php echo $elements[ $item['type'] ]['title']; ?></span>
						
						<?php
						
						$element_infos = '';

						if ( isset( $elements[ $item['type'] ]['infos'] ) ) {

							if ( isset( $item['options'] ) ){
								foreach ( $item['options'] as $option_key => $option_value) {
									if ( !is_array($option_value) ) $elements[ $item['type'] ]['infos'] = str_replace( '{{' . $option_key . '}}', $option_value, $elements[ $item['type'] ]['infos'] );
								}
							}

							$element_infos = preg_replace( '({{.*?}})', '', $elements[ $item['type'] ]['infos'] );
						
						}
						
						?>

						<div class="composer-item-toolbar-actions">
							
							<?php if( in_array( 'edit', $editor['options']['actions'] ) ) echo '<div class="composer-item-edit composer-bt fa fa-pencil"></div>'; ?>	
							<?php if( in_array( 'sort', $editor['options']['actions'] ) ) echo '<div class="composer-item-move composer-bt fa fa-arrows handle"></div>'; ?>
							<?php if( in_array( 'clone', $editor['options']['actions'] ) ) echo '<div class="composer-item-clone composer-bt fa fa-files-o"></div>'; ?>
							<?php if( in_array( 'copy', $editor['options']['actions'] ) ) echo '<div class="composer-item-copy composer-bt fa fa-clipboard"></div>'; ?>
							<?php if( in_array( 'delete', $editor['options']['actions'] ) ) echo '<div class="composer-item-delete composer-bt fa fa-trash-o"></div>'; ?>
							<?php if( in_array( 'source', $editor['options']['actions'] ) ) echo '<span class="composer-item-source composer-bt fa fa-align-left"></span>'; ?>
							<?php if( in_array( 'link', $editor['options']['actions'] ) && isset( $elements[ $item['type'] ]['editor_link'] ) ) echo '<a class="composer-item-editor composer-bt fa fa-link" href="' . $elements[ $item['type'] ]['editor_link'] . '"></a>'; ?>
							
						</div>
					
					</div>
					
					<span class="composer-item-infos"><?php echo $element_infos; ?></span>

				</div>

				<div class="composer-item-toolbar-align composer-item-toolbar-right">
				
				<div class="composer-action-collapse composer-bt dashicons dashicons-arrow-down" style="display:none;"></div>

			</div>
				
		</div>


		<?php

	}

	static function render_editor_element_footer( $item, $type, $title, $icon, $options, $contents, $elements, $editor ) {

		?>

			<textarea style="display:none" class="composer-item-value" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" placeholder=""><?php echo  json_encode( $item, JSON_UNESCAPED_UNICODE ); ?></textarea>
			
			<!-- <div class="composer-item-bg"></div> -->

		</div>

		<?php

	}

	static function render_contents( $type, $contents, $params = array(), $composer, $editor, $preview = false ) {

		$params_defaults = array(
			'sortable' => true,
			'inline' => false,
			'add_button_pos' => '',
			'placeholder' => true,
			'class' => '',
		);

		$params = array_merge( $params_defaults, (array) $params );

		$class = $type . '-contents';
		if ( $params['sortable'] == true ) $class  .= ' composer-items-sortable';
		if ( $params['inline'] == true ) { $class  .= ' composer-items-sortable-horizontal'; } else { $class  .= ' composer-items-sortable-vertical'; }
		//if ( $params['class'] ) $class .= ' ' . $params['class'];
		if ( $params['class'] && is_array( $params['class'] ) ) $params['class'] = implode(' ', array_filter( $params['class'] ) );
		if ( $params['class'] ) $class .= ' ' . $params['class'];
		if ( $params['add_button_pos'] ) $class  .= ' composer-items-button-' . $params['add_button_pos'];

		?><div class="composer-items <?php echo $class; ?>">

			<?php if ( $params['placeholder'] ) { ?>
				<div class="composer-item-empty"></div>
			<?php } ?>

			<?php LINOTYPE_composer::render_elements( $contents, $composer, $editor, $preview ); ?>
			
			<div class="composer-item-toolbar-bottom">
				<div class="composer-item-add-content composer-bt fa fa-plus"></div>
			</div>
			
		</div><?php

	}

	static function render_editor_header( $items, $elements, $editor, $preview = false ) {

		wp_enqueue_style( 'dragula', LINOTYPE_plugin::$plugin['url'] . '/lib/dragula/dragula.css', false, false, 'screen' );
		wp_enqueue_script('dragula', LINOTYPE_plugin::$plugin['url'] . '/lib/dragula/dragula.js', array('jquery'), '1.0', true );

		wp_enqueue_style( 'LINOTYPE_composer', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
		wp_enqueue_script('LINOTYPE_composer', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/script.js', array('jquery'), '1.0', true );

		wp_localize_script( 'LINOTYPE_composer', 'LINOTYPE_composer_settings', array(
			//'editurl' => str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/composer-editor.php',
      		'editurl' =>  LINOTYPE_plugin::$plugin['url'] . '/admin.php',
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'adminurl' => admin_url()
		));
		
    if( ! isset( $editor['path'] ) ) $editor['path'] = "";
		?>

		<div class="composer-editor composer-layout-<?php echo $editor['options']['layout']; ?>" composer-id="<?php echo $editor['path']; ?>" composer-item-type="<?php echo $editor['options']['type']; ?>" style="visibility:hidden;">
			
			<?php if ( $editor['options']['toolbar'] ){ ?>

			<div class="composer-toolbar-top">

				<div class="composer-toolbar-left">

					<span class="composer-action-undo composer-bt fa fa-undo"></span>
					<span class="composer-action-repeat composer-bt fa fa-repeat"></span>
					<!-- <span class="composer-action-add composer-bt fa fa-plus"></span> -->
				</div>

				<div class="composer-toolbar-right">
					<!-- <span class="composer-action-preview composer-bt fa fa-eye"></span> -->
					<!-- <span class="composer-action-xray composer-bt fa fa-low-vision"></span> -->
					<span class="composer-action-source composer-bt fa fa-code"></span>
					<!-- <span class="composer-action-debug composer-bt fa fa-bug"></span> -->
					<!-- <span class="composer-action-fullscreen composer-bt fa fa-arrows-alt"></span> -->
				</div>

			</div>

			<?php } ?>

			<div class="composer-editor-scroll">

				<div class="composer-layout">

					<div class="composer-items composer-items-root composer-items-sortable composer-items-sortable-vertical <?php echo $editor['options']['root_class']; ?>">

	<?php

	}

	static function render_editor_footer( $items, $elements, $editor, $preview = false ) {

		?>

					</div>


					<div class="composer-toolbar-bottom">

		        <div class="composer-add-items-quick">

		          <ul>

		             <li class="composer-add-item-quick">

		              <span class="composer-item-add-content composer-item-add-root composer-bt fa fa-plus"></span>

		            </li>

		          </ul>

		        </div>

		      </div>

		      
					<div class="composer-source" style="">
					
					<?php 
					$editor_value = "";
					if ( $editor['value'] ) $editor_value = json_encode( $editor['value'], JSON_UNESCAPED_UNICODE ); 
					?>

		        	<textarea name="<?php echo $editor['id']; ?>" id="<?php echo $editor['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="" /><?php echo $editor_value; ?></textarea>
		      
        </div>

		      <textarea style="display: none;" class="wp-field-options" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" placeholder=""/><?php echo json_encode( $editor['options'], JSON_UNESCAPED_UNICODE ); ?></textarea>

				</div>

			</div>
			
			<?php self::render_modal_edit( $editor ); ?>

			<?php self::render_modal_items( $items, $elements, $editor, $preview ); ?>

		</div>

		<?php

		self::render_editor_style();

	}

	static function add_editor_style( $style = '' ) {

		self::$EDITOR_STYLE .= $style;

	}

	static function render_editor_style() {

		echo '<style>' . self::$EDITOR_STYLE . '</style>';

	}

	static function render_modal_edit( $editor ) {

		echo '<div class="composer-modal-edit composer-modal">';

			echo '<div class="composer-modal-bg composer-modal-close"></div>';

		 	echo '<div class="composer-modal-container">';

		    	echo '<div class="composer-modal-toolbar-top">';

		      		echo '<div class="composer-modal-toolbar-left">';

		      			echo '<span class="composer-modal-toolbar-title">Title</span>';
							
						  	if ( $editor['options']['devices'] ) {

								echo '<ul class="composer-item-device">';
									echo '<li class="composer-item-device-global selected" data-target="composer-iframe-edit-global">Global</li>';
									echo '<li class="composer-item-device-desktop" data-target="composer-iframe-edit-desktop">Desktop</li>';
									echo '<li class="composer-item-device-tablet" data-target="composer-iframe-edit-tablet">Tablet</li>';
									echo '<li class="composer-item-device-mobile" data-target="composer-iframe-edit-mobile">Mobile</li>';
								echo '</ul>';

							}

		      		echo '</div>';

		      		echo '<div class="composer-modal-toolbar-right">';

						echo '<div class="composer-modal-close button-txt  button-error">cancel</div>';
						echo ' <div class="composer-item-save button  button-primary">validate</div>';

		      		echo '</div>';

		    	echo '</div>';

		    	echo '<div class="composer-modal-content">';

					echo '<iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" id="composer-iframe-edit-global" class="composer-iframe-edit selected" name="composer-iframe-edit" src="" sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation"></iframe>';
    			
    				if ( $editor['options']['devices'] ) { 
						
						echo '<iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" id="composer-iframe-edit-mobile" class="composer-iframe-edit" name="composer-iframe-edit-mobile" data-src=""></iframe>';
						echo '<iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" id="composer-iframe-edit-tablet"  class="composer-iframe-edit" name="composer-iframe-edit-tablet" data-src=""></iframe>';
    					echo '<iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" id="composer-iframe-edit-desktop" class="composer-iframe-edit" name="composer-iframe-edit-desktop" data-src=""></iframe>';
					
					}

					echo '<span class="spinner spinner-center"></span>';

				echo '</div>';

			echo '</div>';

		echo '</div>';

	}

	static function render_modal_items( $items, $elements, $editor, $preview = false ) {
	
		echo '<div class="composer-modal-add composer-modal">';

			echo '<div class="composer-modal-bg composer-modal-close"></div>';

		 	echo '<div class="composer-modal-container">';

		    	echo '<div class="composer-modal-toolbar-top">';

		      		echo '<div class="composer-modal-toolbar-left">';

		      			echo '<span class="composer-modal-toolbar-title">Add Elements</span>';

		      		echo '</div>';

		      		echo '<div class="composer-modal-toolbar-right">';

		      			echo '<span class="composer-modal-close composer-bt dashicons dashicons-no"></span>';

		      		echo '</div>';

		    	echo '</div>';

		    	echo '<div class="composer-modal-content">';

					echo '<div class="composer-add-items">';

						$elements_allow_rules = array();
						

						if ( $elements ) {
							
							foreach ( $elements as $element_key => $item ) {

								//linolog( $editor['options']['root_class'] );

								//
								

								$elements_allow_rules[ $element_key ]['title'] = $item['title'];

								//
								if ( isset( $item['parent'] ) && $item['parent'] ) {

									//$parent_arr = explode( ',', $item['parent'] );

									foreach ( $item['parent'] as $parent ) {
										
										//remove parent if overwrite root
										if ( $parent == $editor['options']['root_class'] ) {

											$elements_allow_rules[ $element_key ]['parent'] = 'all';
										
										} else {

											$elements_allow_rules[ $element_key ]['parent'][] = $parent;

										}

									}

								} else {

									$elements_allow_rules[ $element_key ]['parent'] = 'all';

								}

								if ( isset( $item['accept'] ) && $item['accept'] ) {

									//$accept_arr = explode( ',', $item['accept'] );

									foreach ( $item['accept'] as $accept ) {

										$elements_allow_rules[ $element_key ]['accept'][] = $accept;

									}

								} else {

									$elements_allow_rules[ $element_key ]['accept'] = 'all';

								}

								
								$accept_classes = '';
								
								if ( isset( $item['accept'] ) && $item['accept'] ) {
								
									//$accept_arr = explode( ',', $item['accept'] );
								
									foreach ( $item['accept'] as $accept ) {
								
										$accept_classes .= ' .composer-accept-only-element_' . $accept;
								
									}
								
								} else {
								
									$accept_classes = '.composer-accept-only-all';
								
								}

								$parent = '';
								if( isset( $item['parent'] ) && is_array( $item['parent'] ) ) $parent = implode( ',', $item['parent'] );
								
								$accept = '';
								if( isset( $item['accept'] ) && is_array( $item['accept'] ) ) $accept = implode( ',', $item['accept'] );
								
								if( ! $item['icon'] ) $item['icon'] = 'dashicons dashicons-admin-generic';

								echo '<div id="' . $element_key . '" class="composer-add-item composer-item-add" composer-item-type="' . $element_key . '" composer-item-title="' . $item['title'] . '" composer-item-icon="' . $item['icon'] . '" composer-item-parent="' . $parent . '" composer-item-accept="' . $accept . '" >';

									echo '<div class="composer-add-item-content">';

										echo '<div class="composer-add-item-bottom-left">';

											echo '<div class="composer-item-title"><span class="composer-item-icon ' . $item['icon'] . '"></span> ' . $item['title'] . '</div>';
											echo '<div class="composer-item-desc">' . $item['desc'] . '</div>';

										echo '</div>';

										echo '<div class="composer-add-item-bottom-right">';

											//echo '<span class="composer-item-add composer-bt fa fa-plus"></span>';
											//echo '<div class="composer-item-add button">ADD</div>';

										echo '</div>';

									echo '</div>';

									//create element settings
									$element_data = array( 'type' => $element_key, 'options' => array(), 'contents' => array() );

									if ( $item['options'] ){
										foreach ( $item['options'] as $option_id => $option ) {

											if ( isset( $option['default'] ) && $option['default'] !== "" ) {
												$element_data['options'][ $option_id ] = $option['default'];
											} else {
												$element_data['options'][ $option_id ] = '';
											}

										}
									}
									
									$template = '';

									ob_start();

										if ( isset( $elements[ $element_data['type'] ] ) ) {

											if ( $editor ) {
													
												if ( isset( $elements[ $element_data['type'] ]['editor'] ) && is_callable( $elements[ $element_data['type'] ]['editor'] ) ) {
													
                            						call_user_func( $elements[ $element_data['type'] ]['editor'], $element_data, $element_data['type'], $elements[ $element_data['type'] ]['title'], $elements[ $element_data['type'] ]['icon'], $element_data['options'], $element_data['contents'], $elements, $editor, $preview );

												} else {

						            				LINOTYPE_composer::render_editor_element( $element_data, $element_data['type'], $elements[ $element_data['type'] ]['title'], $elements[ $element_data['type'] ]['icon'], $element_data['options'], $element_data['contents'], $elements, $editor, $preview );

						            			}

											}

										}

						      	$template = ob_get_contents();

						      ob_end_clean();
									
									echo '<script class="composer-template-item" type="text/template">' . $template . '</script>';

								echo '</div>';

							}

						}
						
    				
						

						// _HANDYLOG( $elements_allow_rules );

						echo '</div>';

					echo '</div>';

				echo '</div>';

			echo '</div>';
    
    echo '<textarea style="display:none" class="LINOTYPE_composer_elements_allow">' . json_encode( $elements_allow_rules, JSON_UNESCAPED_UNICODE ) . '</textarea>';
    

		}

	}

}

// disable bad link parse make json crash in post content save
add_filter('wp_targeted_link_rel', function () { return; });
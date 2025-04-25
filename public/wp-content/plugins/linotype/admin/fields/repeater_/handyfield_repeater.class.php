<?php

class linoadmin_repeater  {
	
	public $options;
	
	public $path;

	/**
	*
	* CONSTRUCT
	*
	**/
	function __construct( $options, $path ) {

		$this->options = $options;

		$this->path = $path;

		if( ! $this->path ) $this->path = array( 'dir' =>  dirname( __FILE__ ), 'url' => str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ )  ) );
		
		$this->add_assets();

	}

	/**
	*
	* add_assets
	*
	**/
	public function get() {

		$html = '<div class="field-repeater">';

			$html .= '<ul class="repeater-items">';
				
				$html .= $this->get_current_fields();

			$html .= '</ul>';

		$html .= '</div>';

		$html .= '<p>Templates</p>';

		//$html .= '<textarea style="width:100%;min-height:600px">' . $this->get_templates() . '</textarea>';
		
		$html .= $this->get_templates();

		return $html;

	}

	public function get_current_fields() {
		
		$html = '';
		
		 if ( $this->options['templates'] ){
		 	foreach ( $this->options['templates'] as $template_key => $template ) {

				$html .= $this->get_template( $template );

		 	}

		 }

		return $html;

	}

	public function get_templates() {
		
		$html = '';
		
		 if ( $this->options['templates'] ){
		 	foreach ( $this->options['templates'] as $template_key => $template ) {

				$html .= '<script id="field-multiple-clone-' . $template_key . '" type="text/x-jquery-tmpl">';

					$html .= $this->get_template( $template );

				$html .= '</script>';

		 	}

		 }

		return $html;

	}


	public function get_template( $template ) {
		
		$html = '';
		
		$html .= '<li class="repeater-item">';

			$html .= '<div class="repeater-item-header">';
				$html .= '<div class="repeater-item-drag dashicons dashicons-menu"></div><div class="repeater-item-open dashicons dashicons-arrow-up"></div>';
				$html .= '<div class="repeater-item-header-title">' . $template['title'] . '</div>';
			$html .= '</div>';

			$html .= '<div class="repeater-item-content">';

				$html .= '<ul class="repeater-item-fields">';
				
				if ( $template['fields'] ){
					foreach ( $template['fields'] as $field_key => $field ) {

						$html .= $this->get_field( $field_key, $field );

					}
				}

				$html .= '</ul>';

			$html .= '</div>';

		$html .= '</li>';

		return $html;

	}	

	public function get_field( $subfield_id, $subfield ){

		$ob_cache = ob_start();

		$field = $subfield;
		$field['multiple'] = true;
		
		if ( isset( $ITEM['value'][ $subfield_id ] ) ) {
			$field['value'] = $ITEM['value'][ $subfield_id ];
		} else {
			$field['value'] = "";
		}

		$field['id_multiple'] = $subfield_id;
		$field['id'] = $subfield_id . '_' . $key;
		
		$default_field = array(
			'title' => "",
			'desc' => "",
			'info' => "",
			'padding' => "",
			'options' => array(),
			'fullwidth' => false,
			'disabled' => false,
		);

		$field = wp_parse_args( $field, $default_field );

		if ( file_exists( dirname( $this->path['dir'] ) . '/'.$field['type'].'/'.$field['type'].'.php' ) ) { 
					
			include dirname( $this->path['dir'] ) . '/' . $field['type'] . '/' . $field['type'] . '.php';
					
		} else {
					
			echo "Error field (" . $field['type'] . ").<br />";
					
		}

		$ob_content = ob_get_contents();

		ob_clean();

		return preg_replace('/^\s+|\n|\r|\s+$/m', '', $ob_content );

	}

	/**
	*
	* add_assets
	*
	**/
	public function add_assets() {

		//repeater
		wp_enqueue_script('jquery-ui-droppable');
		wp_enqueue_script('jquery-ui-sortable');


		wp_enqueue_script('tmpl', $this->path['url'] . '/tmpl.min.js', array('jquery'), '1.0', true );

		wp_enqueue_style( 'field-repeater', $this->path['url'] . '/repeater.css', false, false, 'screen' ); 
		wp_enqueue_script('field-repeater', $this->path['url'] . '/repeater.js', array('jquery'), '1.0', true );

		if ( $this->options['fields'] ){
			foreach ( $this->options['fields'] as $field_key => $field ) {
			
				if ( $field['type'] ) {
						
					switch ( $type ) {
						
						case 'editor':
							
							wp_enqueue_media();
							wp_enqueue_style( 'field-editor', $this->path['url'] . '/editor/editor.css', false, false, 'screen' ); 

						break;
						
						// case 'post':
							
						// 	wp_enqueue_script('jquery-ui-draggable');
						// 	wp_enqueue_script('jquery-ui-sortable');
						// 	wp_enqueue_script('jquery-ui-droppable');
					
						// 	wp_enqueue_style( 'field-post', get_template_directory_uri().'/functions/_includes/fields/post/post.css', false, false, 'screen' ); 
						// 	wp_enqueue_script('field-post', get_template_directory_uri().'/functions/_includes/fields/post/post.js', array('jquery'), '1.0', true );

						// break;
						
						// case 'location':
							
						// 	wp_enqueue_script('google-map', 'http://maps.googleapis.com/maps/api/js?sensor=false', array('jquery'), '1.0', true );
						// 	wp_enqueue_script('gmap3', get_template_directory_uri().'/functions/_assets/js/gmap3/gmap3.min.js', array('jquery'), '1.0', true );
						// 	wp_enqueue_script('gmap3-autocomplete', get_template_directory_uri().'/functions/_assets/js/gmap3/jquery-autocomplete.js', array('jquery'), '1.0', true );
							
						// 	wp_enqueue_style( 'field-location', get_template_directory_uri().'/functions/_includes/fields/location/location.css', false, false, 'screen' ); 
						// 	wp_enqueue_script('field-location', get_template_directory_uri().'/functions/_includes/fields/location/location.js', array('jquery'), '1.0', true );

						// break;
						
						case 'image':
							
							wp_enqueue_media();

							wp_enqueue_style( 'field-image', $this->path['url'] . '/image/image.css', false, false, 'screen' ); 
							wp_enqueue_script('field-image', $this->path['url'] . '/image/image.js', array('jquery'), '1.0', true );

						break;
						
						// case 'date':
							
						// 	wp_enqueue_script('jquery-ui-datepicker');

						// 	wp_enqueue_style( 'field-date', get_template_directory_uri().'/functions/_includes/fields/date/date.css', false, false, 'screen' ); 
						// 	wp_enqueue_script('field-date', get_template_directory_uri().'/functions/_includes/fields/date/date.js', array('jquery'), '1.0', true );

						// break;
						
						case 'color':
							
							wp_enqueue_style( 'wp-color-picker' );

							wp_enqueue_script( 'LINOADMIN_field_color', $this->path['url'] . '/color/color.js', array( 'wp-color-picker' ), false, true );

						break;
						
					}
						
					
				}

			}
		}

	}



}



?>

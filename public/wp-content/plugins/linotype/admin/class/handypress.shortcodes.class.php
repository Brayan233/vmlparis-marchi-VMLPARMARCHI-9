<?php

if ( ! class_exists('handypress_shortcodes') ) {
	
class handypress_shortcodes {

// public $shortcode_pre = "shortcode_";

/**
*
* CONSTRUCT
*
* @desc wordpress action and filter
*
**/
function __construct( $source, $id, $settings = array() ) {

	//define source shortcode
	$this->source = $source;

	//add pre to make unique id
	$this->shortcode_id = $id;

	//default
	$default_settings = array(

		"name" => "",
		"description" => "",
		"icon" => "",

		"as_parent" => null,
		"as_child" => null,
		"content_element" => null,
		"show_settings_on_create" => null,
		"is_container" => null,
		"js_view" => null,

		"fields" => array(),

		"function" => null,
		"function_args" => array(),
		
	);

	//init settings
	$this->settings = wp_parse_args( $settings, $default_settings );

	//init vc
	add_action( 'vc_before_init', array( $this, 'vc_map' ) );

	//create shortcode
	add_shortcode( $this->shortcode_id, array( $this, 'shortcode' ) );

}


public function vc_map() {

	$vc_map = array(

		"category" => $this->source,
		"base" => $this->shortcode_id,

		"name" => $this->settings['name'],
		"description" => $this->settings['description'],
		"icon" => $this->settings['icon'],

		"as_parent" => $this->settings['as_parent'],
		"as_child" => $this->settings['as_child'],
		"content_element" => $this->settings['content_element'],
		"show_settings_on_create" => $this->settings['show_settings_on_create'],
		"is_container" => $this->settings['is_container'],
		"js_view" => $this->settings['js_view'],

		"params" => array(),

	);

	if ( $this->settings['fields'] ) {
		foreach ( $this->settings['fields'] as $field_key => $field ) {

			//format value
			if ( $this->settings['fields'][$field_key]['type'] == 'textarea_raw_html' ) $this->settings['fields'][$field_key]['value'] = base64_encode( $this->settings['fields'][$field_key]['value'] );

			//default field
			$default_field = array(
				"title"=> '',
				"type"=>'textfield',
				"options" => array(),
				"padding" => "20px 0px 0px 0px",
				"fullwidth" => true,
				"value" => "",
				"admin_label" => false,
				"tab" => "",
			);

			//parse arg
			$this->settings['fields'][$field_key] = wp_parse_args( $this->settings['fields'][$field_key], $default_field );

			//visualcomposer params
			$params = array(
				"param_name"	=> $field_key,
				"heading"		=> $this->settings['fields'][$field_key]['title'],
				"type"			=> $this->settings['fields'][$field_key]['type'],
				"admin_label"	=> $this->settings['fields'][$field_key]['admin_label'],
				"value"			=> $this->settings['fields'][$field_key]['value'],
				"description"	=> $this->settings['fields'][$field_key]['description'],
				"group"			=> $this->settings['fields'][$field_key]['tab'],
			);

			//set default if checkbox
			if ( $this->settings['fields'][$field_key]['type'] == 'checkbox' && $this->settings['fields'][$field_key]['type'] ){

				//set default value
				$params['std'] = $this->settings['fields'][$field_key]['type'];

				//set label
				if( isset( $this->settings['fields'][$field_key]['options']['label'] ) && $this->settings['fields'][$field_key]['options']['label'] ) $params['value'] = array( $this->settings['fields'][$field_key]['options']['label'] => $params['value'] );

			}

			//add params
			$vc_map['params'][] = $params;

		}
	}

	//remove empty
	$vc_map = array_filter( $vc_map );

	//exec vc_map
	vc_map( $vc_map );

}

public function shortcode( $atts = array(), $content = "" ) {

	if ( $this->settings['fields'] ) {
		foreach ( $this->settings['fields'] as $field_key => $field ) {

			if ( ! isset( $atts[$field_key] ) && isset( $field['default'] ) ) {

				$atts[$field_key] = $field['default'];

			} else if ( ! isset( $atts[$field_key] ) && ! isset( $field['default'] ) ) {

				$atts[$field_key] = "";

			}

			//decode field textarea_raw_html
			if ( $field['type'] == 'textarea_raw_html' ) $atts[$field_key] = stripslashes( urldecode( base64_decode( $atts[$field_key] ) ) );

		}
	}

	if ( $this->settings['function'] ) {

		if ( is_array( $this->settings['function'] ) ) {

			return call_user_func_array( $this->settings['function'], array( $atts, $content, $this->settings['function_args'] ) );

		} else if ( is_callable( $this->settings['function'] ) ) {

			return $this->settings['function']( $atts, $content, $this->settings['function_args'] );

		}

	}

}

static function get_shortcode_wrapper( $atts = array(), $content = "" ) {

	if ( $atts['google_fonts'] ) {

	  $fontsdata = explode( '|', urldecode( $atts['google_fonts'] ) );
	  $fontsfamily = explode( ':', $fontsdata[0] );
	  $fontsfamily = $fontsfamily[1];
	  $fontsstyle = explode( ':', $fontsdata[1] );
	  $fontsstyle = $fontsfamily[1];

	  if ( $fontsfamily ) wp_enqueue_style( 'google_fonts_' . $fontsfamily, '//fonts.googleapis.com/css?family=' . $fontsfamily );

	}

	$html = '';

	if ( $atts['link'] ) $html .= '<a href ="' . $atts['link'] . '" >';

	if ( $atts['display'] == '' || $atts['display'] == 'yes' || ( $atts['display'] == 'hide_if_empty' && ! empty( $content ) ) ) {

	  if ( $atts['before'] ) $html .= $atts['before'];

	  $html_class = 'class="';
	  if ( $atts['icon'] ) $html_class .= $atts['icon'];
	  if ( $atts['class'] ) $html_class .= $atts['class'];
	  $html_class .= '"';

	  $html_style = 'style="';
	  if ( $atts['text_align'] ) $html_style .= 'text-align:' . $atts['text_align'] . ';';
	  if ( $atts['custom_font'] )  $html_style .= 'font-family:' . $fontsfamily . ';';
	  if ( $atts['font_size'] ) $html_style .= 'font-size:' . $atts['font_size'] . ';';
	  if ( $atts['line_height'] ) $html_style .= 'line-height:' . $atts['line_height'] . ';';
	  if ( $atts['tag_display'] ) $html_style .= 'display:' . $atts['tag_display'] . ';';
	  if ( $atts['tag_color'] ) $html_style .= 'color:' . $atts['tag_color'] . ';';
	  if ( $atts['tag_bg_color'] ) $html_style .= 'background-color:' . $atts['tag_bg_color'] . ';';
	  if ( $atts['tag_margin'] ) $html_style .= 'margin:' . $atts['tag_margin'] . ';';
	  if ( $atts['tag_padding'] ) $html_style .= 'padding:' . $atts['tag_padding'] . ';';

	  if ( $atts['tag_css'] ) $html_style .= $atts['tag_css'];

	  $html_style .= '"';

	  if ( $atts['html_tag'] ) $html .= '<' . $atts['html_tag'] . ' ' . $html_class . ' ' . $html_style . '>';

	      if ( $atts['icon'] ) $html .= '<i aria-hidden="true" ' . $html_class . ' ' . $html_style . '></i>';

	      if ( $content ) $html .= $content;

	  if ( $atts['html_tag'] ) $html .= '</' . $atts['html_tag'] . '>';

	  if ( $atts['before'] ) $html .= $atts['after'];

	}

	if ( $atts['link'] ) $html .= '</a>';

	return $html;

}

static function add_default_params( $params ) {

	$default_params = array(
	  "display" => array(
	      "title" => __("Display",'handypress-topcolor'),
	      "type" => "dropdown",
	      "description" => "",
	      "value" => array('yes', 'no', 'hide_if_empty' ),
	      "default" => "",
	      "tab" => "General",
	      "admin_label" => false,
	  ),
	  "link" => array(
	      "title" => __("Link",'handypress-topcolor'),
	      "type" => "textfield",
	      "description" => "",
	      "value" => "",
	      "default" => "",
	      "tab" => "General",
	      "admin_label" => true,
	  ),

	  "html_tag" => array(
	      "title" => __("Tag",'handypress-topcolor'),
	      "type" => "dropdown",
	      "description" => "",
	      "value" => array('','h1','h2','h3','h4','h5','h6','h7','p','span','code','pre'),
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => true,
	  ),
	  "custom_font" => array(
	      "title" => __("Google Fonts",'handypress-topcolor'),
	      "type" => "checkbox",
	      "description" => "",
	      "value" => array('' => 'yes'),
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => true,
	  ),
	  "google_fonts" => array(
	      "title" => __("",'handypress-topcolor'),
	      'type' => 'google_fonts',
	      // 'value' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
	      'value' => '',
	      'settings' => array(
	          'fields' => array(
	              'font_family_description' => __( 'Select font family.', 'js_composer' ),
	              'font_style_description' => __( 'Select font styling.', 'js_composer' ),
	          ),
	      ),
	      "tab" => "Style",
	      "admin_label" => false,
	      // 'dependency' => array(
	      //     'element' => 'custom_font',
	      //     'value' => array( 'yes' ),
	      // ),
	  ),
	  "font_size" => array(
	      "title" => __("Font Size",'handypress-topcolor'),
	      "type" => "textfield",
	      "description" => "",
	      "value" => "",
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => true,
	  ),
	  "line_height" => array(
	      "title" => __("Line Height",'handypress-topcolor'),
	      "type" => "textfield",
	      "description" => "",
	      "value" => "",
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => true,
	  ),
	  "text_align" => array(
	      "title" => __("Align",'handypress-topcolor'),
	      "type" => "dropdown",
	      "description" => "",
	      "value" => array('left','center','right'),
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => true,
	  ),
	  "tag_display" => array(
	      "title" => __("Display",'handypress-topcolor'),
	      "type" => "dropdown",
	      "description" => "",
	      "value" => array('block','inline-block','inline'),
	      "default" => "block",
	      "tab" => "Style",
	      "admin_label" => false,
	  ),
	  "tag_color" => array(
	      "title" => __("Text Color",'handypress-topcolor'),
	      "type" => "colorpicker",
	      "description" => "",
	      "value" => "",
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => false,
	  ),
	  "tag_bg_color" => array(
	      "title" => __("Background Color",'handypress-topcolor'),
	      "type" => "colorpicker",
	      "description" => "",
	      "value" => "",
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => false,
	  ),
	  "icon" => array(
	      "title" => __("Icon",'handypress-topcolor'),
	      "type" => "textfield",
	      "description" => "",
	      "value" => "",
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => true,
	  ),
	  "tag_margin" => array(
	      "title" => __("Margin",'handypress-topcolor'),
	      "type" => "textfield",
	      "description" => "",
	      "value" => "",
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => false,
	  ),
	  "tag_padding" => array(
	      "title" => __("Padding",'handypress-topcolor'),
	      "type" => "textfield",
	      "description" => "",
	      "value" => "",
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => false,
	  ),

	  "tag_css" => array(
	      "title" => __("Inline CSS",'handypress-topcolor'),
	      "type" => "textfield",
	      "description" => "",
	      "value" => "",
	      "default" => "",
	      "tab" => "Style",
	      "admin_label" => false,
	  ),
	  "class" => array(
	    "title" => __("Class",'handypress-accounts'),
	    "type" => "textfield",
	    "description" => "",
	    "value" => "",
	    "default" => "",
	    "tab" => "Style",
	    "admin_label" => true,
	  ),
	  "before" => array(
	    "title" => __("Before content",'handypress-accounts'),
	    "type" => "textarea",
	    "description" => "",
	    "value" => "",
	    "default" => "",
	    "tab" => "Code",
	    "admin_label" => false,
	  ),
	  "after" => array(
	    "title" => __("After content",'handypress-accounts'),
	    "type" => "textarea",
	    "description" => "",
	    "value" => "",
	    "default" => "",
	    "tab" => "Code",
	    "admin_label" => false,
	  ),
	);

	$params = array_merge( $params, $default_params );

	return $params;

}





}

}

<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*
* COMPOSER_fields
*
**/
class COMPOSER_fields {

  public $FIELDS = array();

  function __construct() {

    $this->default_options_first = array(

      "id" => array(
          "title"=>'Option ID',
          "desc"=>'Define unique id to get value in your block template with $options[\'myID\']',
          'disabled' => false,
          "type"=>'text',
          "info"=>'',
          "options" => array(
            "placeholder" => "",
          ),
          "default"=>"",
          "fullwidth" => true,
          "col" => 'col-12',
          "help" => false,
          "padding" => "20px 20px 0px 20px;background-color:#F2F2F2;",
          "tab" => "General"
      ),

      "default" => array(
        "title"=>'Option default value',
        "info"=>'',
        "desc"=>'Define default value if option empty',
        "col"=>'col-12',
        'disabled' => false,
        "type"=>'text',
        "options" => array(
          "placeholder" => "",
        ),
        "default"=>"",
        "fullwidth" => true,
        "help" => false,
        "padding" => "20px 20px 0px 20px;background-color:#F2F2F2;",
        "tab" => "General"
    ),

    "dummy" => array(
      "title"=>'Option dummy value',
      "info"=>'',
      "desc"=>'Define dummy value for the preview',
      "col"=>'col-12',
      'disabled' => false,
      "type"=>'textarea',
      "options" => array(
        "style" => "min-height:32px;height:32px;",
      ),
      "default"=>"",
      "fullwidth" => true,
      "help" => false,
      "padding" => "20px 20px 25px 20px;background-color:#F2F2F2;border-bottom:1px solid #E5E5E5;",
      "tab" => "General"
    ),

      "title" => array(
          "title"=>'Field Title',
          "info"=>'',
          "desc"=>'',
          "col"=>'col-12',
          'disabled' => false,
          "type"=>'text',
          "options" => array(
            "placeholder" => "",
          ),
          "default"=>"",
          "fullwidth" => true,
          "help" => false,
          "padding" => "20px 20px 0px 20px",
          "tab" => "General"
      ),

      "info" => array(
          "title"=>'Field Info',
          "info"=>'',
          "desc"=>'',
          "col"=>'col-12',
          'disabled' => false,
          "type"=>'text',
          "options" => array(
            "placeholder" => "",
          ),
          "default"=>"",
          "fullwidth" => true,
          "help" => false,
          "padding" => "20px 20px 0px 20px",
          "tab" => "General"
      ),

      "desc" => array(
          "title"=>'Field Description',
          "info"=>'',
          "desc"=>'',
          "col"=>'col-12',
          'disabled' => false,
          "type"=>'textarea',
          "options" => array(
            "placeholder" => "",
          ),
          "default"=>"",
          "fullwidth" => true,
          "help" => false,
          "padding" => "20px 20px 0px 20px",
          "tab" => "General"
      ),

      // "tab" => array(
      //   "title"=>'Tab',
      //   "info"=>'',
      //   "desc"=>'',
      //   'disabled' => false,
      //   "type"=>'text',
      //   "options" => array(
      //     "placeholder" => "",
      //   ),
      //   "default"=>"",
      //   "fullwidth" => true,
      //   "col" => 'col-12',
      //   "help" => false,
      //   "padding" => "20px 20px 0px 20px",
      //   "tab" => "General"
      // ),

      

    );

    $this->default_options_last = array(

      "col" => array(
          "title"=>'Field column size',
          "info"=>'',
          "desc"=>'',
          "col"=>'col-12',
          'disabled' => false,
          "type"=>'selectize',
          "options" => array(
            "data" => array(
              array("title" => "Col-1", "value" => "col-1"),
              array("title" => "Col-2", "value" => "col-2"),
              array("title" => "Col-3", "value" => "col-3"),
              array("title" => "Col-4", "value" => "col-4"),
              array("title" => "Col-5", "value" => "col-5"),
              array("title" => "Col-6", "value" => "col-6"),
              array("title" => "Col-7", "value" => "col-7"),
              array("title" => "Col-8", "value" => "col-8"),
              array("title" => "Col-9", "value" => "col-9"),
              array("title" => "Col-10", "value" => "col-10"),
              array("title" => "Col-11", "value" => "col-11"),
              array("title" => "Col-12", "value" => "col-12"),
            ),
            "maxItems" => 1,
            "placeholder" => "col-12",
            "clear_button" => true,
          ),
          "default"=>"",
          "fullwidth" => true,
          "help" => false,
          "padding" => "20px 20px 0px 20px",
          "tab" => "Style"
      ),

      "fullwidth" => array(
          "title"=>'',
          "info"=>'',
          "desc"=>'',
          "col"=>'col-12',
          'disabled' => false,
          "type"=>'checkbox',
          "options" => array(
            "label" => "Fullwidth",
          ),
          "default"=>"",
          "fullwidth" => true,
          "help" => false,
          "padding" => "5px 20px 0px 20px",
          "tab" => "Style"
      ),

      "padding" => array(
          "title"=>'Field padding',
          "info"=>'',
          "desc"=>'',
          "col"=>'col-12',
          'disabled' => false,
          "type"=>'text',
          "options" => array(
            "placeholder" => "20px 20px 0px 20px",
          ),
          "default"=>"",
          "fullwidth" => true,
          "help" => false,
          "padding" => "20px 20px 0px 20px",
          "tab" => "Style"
      ),

    );

  }

  public function add( $type, $id, $title, $desc = "", $settings = array() ){

    $this->FIELDS[$id] = array(

        "title" => $title,
        "desc" => $desc,
        "icon" => "",
        "background" => "",
        "color" => "",
        "padding" => "",
        "infos" => "ID: <b>{{id}}</b> Title: <b>{{title}}</b>",
        "preview" => false,
        "parent" => "",
        "target" => "",
        "accept" => "",
        "render" => false,
        "field_type" => $type,
        "options" => array_merge(
          $this->default_options_first,
          array(
            "field_options" => array(
                "title"=>'Field Options',
                "info"=>'',
                "desc"=>'',
                "col"=>'col-12',
                "disabled"=>false,
                "type"=>'json',
                "options" => array(
                  "data" => json_encode( $settings ),
                ),
                "default"=> "",
                "fullwidth" => true,
                "help" => false,
                "padding" => "20px 20px 0px 20px",
                "tab" => "Settings"
            ),
          ),
          $this->default_options_last
        ),

    );

  }

  public function get() {
    
    global $LINOADMIN;

    $this->FIELDS['loops'] = array(

      "title" => "Loop",
      "desc" => "",
      "icon" => "",
      "background" => "",
      "color" => "",
      "padding" => "",
      "parent" => "",
      "target" => "",
      "accept" => "",
      "infos" => "ID: <b>{{id}}</b> Title: <b>{{title}}</b>",
      "preview" => false,
      "render" => function( $data ) {},
      "options" => array_merge(
        $this->default_options_first,
        array(
          "loop" => array(
              "title"=>'Options',
              "type"=> LINOTYPE_plugin::$plugin['dir'] . 'admin/fields/composer',
              "options" => array(
                'border' => false,
              	'toolbar' => false,
                'empty' => false,
                "items" => array( $this, 'get' ),
              ),
              "default"=>'',
              "help" => false,
              "padding" => "20px",
              "fullwidth" => true,
              "tab" => "Loop",
              "path" => "composer_elements/composer_elements_metabox_element/options",
          ),
        ),
        $this->default_options_last
      ),

    );

    return $this->FIELDS;

  }

}

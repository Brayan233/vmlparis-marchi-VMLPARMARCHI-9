<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*
* LINOTYPE_helpers
*
**/
class COMPOSER_woocommerce {

  public $element_template = "";
  public $current_template = "";

  function __construct() {

    add_filter( 'wc_get_template', array( $this, 'filter_wc_get_template' ), 10, 5 );

    add_action( 'woocommerce_before_template_part', array( $this, 'woocommerce_before_template_part' ), 10, 4 );

  }

  public function woocommerce_before_template_part( $template_name, $template_path, $located, $args ) {

    global $COMPOSER_element;

    $COMPOSER_element['composer_woocommerce_current_template_part'] = $template_name;

  }

  public function filter_wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {

    if ( $this->current_template && in_array( $template_name, $this->current_template ) ) {

      return $this->element_template;

    }

    return $located;

  }

  public function map_template( $element_template, $current_template ) {

    $this->element_template = $element_template;
    $this->current_template = $current_template;

  }

}

<?php

require_once ('inc/Slimdown.php');

class linotype_field_text {

  public $text;

  function __construct( $text = "", $markdown = false ) {

    $this->text = $text;
    $this->markdown = $markdown;

  }

  private function render_markdown( $text ) {
    
    return Slimdown::render( $text );

  }

  public function get() {
    
    if ( $this->markdown ) $this->text = $this->render_markdown( $this->text );

    return $this->text;

  }

}

function get_linotype_field_text( $text, $markdown = false ) {

  $text = new linotype_field_text( $text, $markdown );

  return $text->get();

}

<?php

$default_options = array(
  "collapsed" => false,
  "height" => "500px",
  'data'=> array(
    array(
      'id' => 'value',
      'title' => '',
      'desc' => '',
      'type' => 'text',
      'width' => '100%',
    ),
  ),
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

$html = '';

if ( is_array( $field['value'] ) ) {
        
  foreach ( $field['value'] as $lv1 ) {
    
    $count = 0;

    foreach ( $lv1 as $lv2 ) {

      if ( $count != 0 ) $html .= ' | ';
      $html .= $lv2;
      $count++;

    }
    
    $html .= '<br/>';

  }

} else {

  $html .= '-';

}

echo $html;

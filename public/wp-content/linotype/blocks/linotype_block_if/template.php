<?php 

$display = false;

if ( $options['target'] && $options['operator'] && $options['value'] ) {

  switch ( $options['target'] ) {

    case "user_logged":
      if ( $options['value'] && is_user_logged_in() ) $display = true;
    break;

    default:
      switch ( $options['operator'] ) {
        case "==":
          if ( $options['target'] == $options['value'] ) $display = true;
        break;
        case "!=":
          if ( $options['target'] != $options['value'] ) $display = true;
        break;
        case "<":
          if ( $options['target'] < $options['value'] ) $display = true;
        break;
        case ">":
          if ( $options['target'] > $options['value'] ) $display = true;
        break;
        case "<=":
          if ( $options['target'] <= $options['value'] ) $display = true;
        break;
        case ">=":
          if ( $options['target'] >= $options['value'] ) $display = true;
        break;
      }
    break;

  }

}

if ( $display ) block( 'content', $settings );

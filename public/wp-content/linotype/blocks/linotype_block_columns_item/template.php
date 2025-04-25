<?php 

if ( $options['col_xs'] == "" && $options['col_sm'] == "" && $options['col_md'] == "" && $options['col_lg'] == "" && $options['col_xlg'] == "" ) $options['col_sm'] = 'col-sm-auto';

$col_classes = array();

$col_classes = implode( ' ', array_filter( array( $options['col_xs'], $options['offset_xs'], $options['col_sm'], $options['offset_sm'], $options['col_md'], $options['offset_md'], $options['col_lg'], $options['offset_lg'], $options['col_xlg'], $options['offset_xlg'], $options['align_v'], $options['mobile_order_first'] ) ) ); 

$style = "";

$style .= '#' . $item['id'] .'.linotype_block_columns_item {';
  // if ( $options['min_width'] ) $style .= 'min-width:' . $options['min_width'] . ';';
	// if ( $options['max_width'] ) $style .= 'max-width:' . $options['max_width'] . ';';
  // if ( $options['min_height'] ) $style .= 'min-height:' . $options['min_height'] . ';';
	// if ( $options['max_height'] ) $style .= 'max-height:' . $options['max_height'] . ';';
$style .= '}';

$style .= '#' . $item['id'] .'.linotype_block_columns_item > .col-inner {';
  //if ( $options['inner_margin'] ) $style .= 'margin:' . $options['inner_margin'] . ';'; 
  //if ( $options['inner_padding'] ) $style .= 'padding:' . $options['inner_padding'] . ';'; 
$style .= '}';

block( 'add_style', $style );

?>

<?php block( 'header', $settings, array('class' => $col_classes ) ); ?>

  <div class="col-inner">

    <?php block( 'content', $settings ); ?>
  
  </div>

<?php block( 'footer', $settings ); ?>



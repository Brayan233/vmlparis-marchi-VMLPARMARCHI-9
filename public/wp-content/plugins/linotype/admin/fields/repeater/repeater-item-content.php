<div class="dd-inside" style="position:relative;">

	<?php
	$the_title = ''; 
	if ( isset( $item['title'] ) ) { 

		$the_title = strip_tags( $item['title'] ); 
	
	} else if ( $the_title == '' && isset( $item['name'] ) ) { 

		$the_title = strip_tags( $item['name'] );

	}
	?>

  <div class="dd-title">
  	<span class="the_icon <?php  if ( isset( $item['icon'] ) ) { echo $item['icon']; } else { echo 'no-icon'; } ?>"></span>
  	<span class="the_title"><?php echo $the_title; ?></span>
  	<span class="is-submenu"></span>
  </div>

  <span class="item-controls">
    <span class="item-type"><?php if ( isset( $item['id'] ) ) { echo strip_tags( $item['id'] ); } ?></span>
    <span class="dd-edit item-edit dashicons dashicons-admin-generic" ></span>
  </span>

</div>

<div class="item-settings-holder">

</div>
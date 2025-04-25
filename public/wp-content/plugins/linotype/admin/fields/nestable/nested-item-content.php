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
    <a class="dd-edit item-edit" title="edit" href="#"></a>
  </span>

</div>

<div class="menu-item-settings-holder">

</div>
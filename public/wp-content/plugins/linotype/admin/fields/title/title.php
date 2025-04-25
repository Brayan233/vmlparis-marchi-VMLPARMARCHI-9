<li class="wp-field wp-field-title <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if ( $field['title'] ) { ?>
	<h2 class="field-title"><?php echo $field['title']; ?></h2>	
	<?php } ?>
		
	<?php if ( $field['desc'] ) { ?>
	<div class="field-desc"><?php echo $field['desc']; ?></div>
	<?php } ?>
		
</li>


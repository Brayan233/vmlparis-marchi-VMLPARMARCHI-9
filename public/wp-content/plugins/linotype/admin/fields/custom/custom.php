<?php

/**
 * 
 * LINOADMIN
 * Option Page builder
 * v1.0.0
 * by Yannick Armspach
 * http://www.handypress.io
 *
 * @FIELD : wp-field-custom
 *
 *
**/

?>

<li class="wp-field wp-field-custom <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>	
	<?php } ?>
	
	<!-- <div class="field-content"> -->
		
		<?php if ( is_callable( $field['options']['function'] ) ) $field['options']['function']( $field, $post_id ); ?>
		
		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	<!-- </div> -->

</li>

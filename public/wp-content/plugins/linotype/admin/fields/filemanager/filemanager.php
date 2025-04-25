<?php

//wp_enqueue_style( 'field-filemanager', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/filemanager.css', false, false, 'screen' );
//wp_enqueue_script('field-filemanager', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/filemanager.js', array('jquery'), '1.0', true );

$default_options = array(
	
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-filemanager <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>


		<iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation"  width="765" height="550" frameborder="0" src="<?php echo str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/filemanager/dialog.php?type=0'; ?>"></iframe>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

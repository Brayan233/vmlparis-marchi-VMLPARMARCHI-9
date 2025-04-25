<?php

//wp_enqueue_style( 'handypress-list', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/list.css', false, false, 'screen' );
wp_enqueue_script('handypress-dirmanager', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/dirmanager.js', array('jquery'), '1.0', true );

$default_options = array(
	'id' => '',
	'dir' => '',
	'url' => '',
	'capability' => 'edit_files',
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

update_option( $field['options']['id'], $field['options'] );

?>

<li class="wp-field wp-field-dirmanager <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>


		<iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" src="<?php echo str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/filesmanager.php?dirmanager_id=' . $field['options']['id'] . '&p='; ?>" style="overflow:hidden;width:100%;min-height:100px;"></iframe>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

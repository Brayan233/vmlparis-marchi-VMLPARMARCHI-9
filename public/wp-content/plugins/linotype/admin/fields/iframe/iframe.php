<?php

wp_enqueue_style( 'field-iframe', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/iframe.css', false, false, 'screen' ); 
wp_enqueue_script('field-iframe', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/iframe.js', array('jquery'), '1.0', true );

// wp_localize_script( 'field-iframe', 'LINOADMIN_AJAX', array( 
// 	'ajaxurl' => admin_url( 'admin-ajax.php' ),
// ));

$default_options = array(
	"url" => "http://google.com",
	"width" => "100%",
	"height" => "300px",
	"autoload" => true,
	"hide" => false,
	"button" => false,
	"style" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-iframe <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="iframe-content" style="<?php if( $field['options']['hide'] ) { echo "display:none;"; } ?>">

			<p><?php echo $field['options']['url']; ?></p>
			<iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" style="<?php if( $field['options']['style'] ) { echo $field['options']['style']; } ?>" src="<?php if ( $field['options']['autoload'] ) echo $field['options']['url']; ?>" data-src="<?php echo $field['options']['url']; ?>" width="<?php echo $field['options']['width']; ?>" height="<?php echo $field['options']['height']; ?>"></iframe>

		</div>

		<?php if ( $field['options']['button'] ) { ?>

			<p><div class="button iframe-run" ><?php if ( is_bool( $field['options']['button'] ) ) { echo 'open'; } else { echo $field['options']['button']; } ?></div></p>

			<?php } else {  ?>

		<?php } ?>

		<textarea class="field-options" style="display:none;"><?php echo json_encode( $field['options'] ); ?></textarea>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>
	
	</div>

</li>


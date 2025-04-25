<?php

$default_options = array(
	"content" => '',
	"content_right" => '',
	"status" => "",
	'dismissible' => false,
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-notice <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if ( $field['title'] ) { ?>
	<div class="field-title"><?php echo $field['title']; ?></div>
	<?php } ?>
	
	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<?php 

		$content = '';

		if ( is_callable( $field['options']['content'] ) ) {
		
			global $post_id;
			$content = $field['options']['content']( $post_id ); 

		} else {
			
			$content = $field['options']['content'];
		
		}

		$content_right = '';
		
		if ( is_callable( $field['options']['content_right'] ) ) {
		
			global $post_id;
			$content_right .= '<div style="position: absolute;right: 10px;top: 15px;">';
				$content_right .= $field['options']['content_right']( $post_id ); 
			$content_right .= '</div>';

		} else {
			
			$content_right .= '<div style="position: absolute;right: 10px;top: 15px;">';
				$content_right .= $field['options']['content_right'];
			$content_right .= '</div>';
		}

		$class = '';
		if ( $field['options']['status'] ) $class .= $field['options']['status'];

		$dismissible = false;
		if ( $field['options']['dismissible'] ) {
			$dismissible = true;
			$class .= ' is-dismissible';
		}

		?>
		
		<div id="message" class="notice <?php echo $class; ?> below-h2">
	
			<?php echo $content; ?>

			<?php echo $content_right; ?>
			
			<?php if ( $dismissible ) { ?>

				<button type="button" class="notice-dismiss"></button>
				
				<button type="button" class="notice-dismiss">	
					<span class="screen-reader-text">Dismiss this notice.</span>
				</button>

			<?php } ?>

		</div>


		<?php if ( $field['desc'] ) { ?>
		<div class="field-desc"><?php echo $field['desc']; ?></div>
		<?php } ?>

	<div>

</li>

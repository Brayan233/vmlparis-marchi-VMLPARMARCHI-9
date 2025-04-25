<?php

wp_enqueue_style( 'field-indexing', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/indexing.css', false, false, 'screen' ); 
wp_enqueue_script('field-indexing', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/indexing.js', array('jquery'), '1.0', true );

wp_localize_script( 'field-indexing', 'LINOADMIN_AJAX', array( 
	'ajaxurl' => admin_url( 'admin-ajax.php' ),
));

$default_options = array(
	"message_indexed" => "",
	"message_noindex" => "",
	"nonce" => wp_create_nonce( $field['id'] ),
	"action" => null,
	"batch_size" => 10,
	"file_dir" => "",
	"file_url" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-indexing <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>	
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="indexing-content" >

			<?php 

			if ( file_exists( $field['options']['file_dir'] ) ) { echo '<div class="indexing-index-link" ><a href="' . $field['options']['file_url'] . '" target="_blank">view index file</a></div>';}

			if ( file_exists( $field['options']['file_dir'] && $field['options']['message_indexed'] ) ) { 

				echo '<div class="indexing-message" >' . $field['options']['message_indexed'] . '</div>';

			} else if ( $field['options']['message_noindex'] ) { 

				echo '<div class="indexing-message" >' . $field['options']['message_noindex'] . '</div>';

			} 
			?>
			

			<div class="progress<?php if ( file_exists( $field['options']['file_dir'] ) ) { echo ' progress-success'; }?>"><div class="progress-bar"><div class="progress-percent"></div></div></div>

			<?php if ( file_exists( $field['options']['file_dir'] ) ) { ?>

				<!--<a class="button" href="<?php echo $field['options']['file_url'] ?>" target="_blank">View File</a>-->

				<div class="button start-indexing">Rebuilt Index</div>
				
			<?php } else { ?> 

				<div class="button button-success start-indexing">Start Indexing</div>

			<?php } ?>

			<div class="button button-delete stop-indexing" style="display:none">Stop Indexing</div>

			<ul class="messages"></ul>

		</div>

		<textarea class="field-options" style="display:none;"><?php echo json_encode( $field['options'] ); ?></textarea>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>
	
	</div>

</li>


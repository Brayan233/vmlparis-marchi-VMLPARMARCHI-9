<?php

wp_enqueue_script('epub', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/epub.min.js', array('jquery'), '4.0.1', true );
wp_enqueue_script('zip', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/zip.min.js', array('jquery'), '4.0.1', true );

wp_enqueue_style( 'field-epub', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/epub.css', false, false, 'screen' ); 
//wp_enqueue_script('field-epub', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/epub.js', array('jquery'), '1.0', true );

?>



<li class="wp-field wp-field-epub <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>	
	<?php } ?>
	
	<div class="field-content">

		<?php 

		if( ! $field['options']['width'] ) $field['options']['width'] = 595;
		if( ! $field['options']['height'] ) $field['options']['height'] = 842;
		if( ! $field['options']['padding'] ) $field['options']['padding'] = 20;

		$width = ( $field['options']['width'] + ($field['options']['padding'] * 2) ) . "px";
		$height = ( $field['options']['height'] + ($field['options']['padding'] * 2) ) . "px";

		$epub_width = $field['options']['width'] . "px";
		$epub_height = $field['options']['height'] . "px";
		$epub_padding = $field['options']['padding'] . "px";
		
		?>

		<script>
		(function($) {

		$(document).ready(function(){

			var epub_<?php echo $field['id']; ?> = ePub("<?php echo $field['options']['epub']; ?>" );
        	epub_<?php echo $field['id']; ?>.renderTo("epub-<?php echo $field['id']; ?>");

        	$('.epub-prev').on('click', function(){
        		 epub_<?php echo $field['id']; ?>.prevPage();
        	});
        	$('.epub-next').on('click', function(){
        		 epub_<?php echo $field['id']; ?>.nextPage();
        	});
        	
		});

		}(jQuery));

        </script>

        <div class="epub-wrap">
			<div class="epub-bar" style="width:<?php echo $width; ?>;">
				<span class="epub-prev">‹</span>
				<?php if( $field['info'] ) { ?>
					<div class="field-info" style="position: absolute; top: 6px; text-align: center; left: 40px; right: 40px;"><?php echo $field['info']; ?></div>
				<?php } ?>
				<span class="epub-next">›</span>
			</div>
			<div id="epub-<?php echo $field['id']; ?>" class="epub-bookarea epub-shadow" style="display:inline-block;width:<?php echo $epub_width; ?>;height:<?php echo $epub_height; ?>;padding:<?php echo $epub_padding; ?>"></div>
			<div class="epub-bar" style="width:<?php echo $width; ?>;">
				<span class="epub-prev">‹</span>
				<?php if( $field['desc'] ) { ?>
					<div class="field-description" style="position: absolute; top: 6px; text-align: center; left: 40px; right: 40px;"><?php echo $field['desc']; ?></div>
				<?php } ?>
				<span class="epub-next">›</span>
			</div>
		</div>
		

		
	
	</div>

</li>

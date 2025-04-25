<?php


wp_enqueue_style( 'field-youtube', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/youtube.css', false, false, 'screen' );

wp_enqueue_script('youtube-player-api', '//www.youtube.com/player_api', array('jquery'), '1.0', true );

wp_enqueue_script('field-youtube', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/youtube.js', array('jquery'), '1.0', true );

$default_options = array(
	"placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-youtube <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="youtube-player" style="display:none">
	  	<iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" id="youtube-video" width="100%" height="300px" src="https://www.youtube.com/embed/<?php echo $field['value']; ?>/?enablejsapi=1&html5=1&autoplay=0&controls=0&rel=0&cc_load_policy=0&color=white&disablekb=1&iv_load_policy=3&modestbranding=1&showinfo=0" frameborder="0" allowfullscreen></iframe>
	  </div>

		<div id="youtube-searchbar">
			<div class="field-input">
				<input type="search" name="youtube-search" id="youtube-search" placeholder="" class="youtube-search ui-autocomplete-input" autocomplete="off">
			</div>
			<div id="youtube-search-button" class="" >Search</div>
		</div>

		<div id="youtube-video-list-content" class="">
				<ul id="youtube-video-list" class=""></ul>
		</div>

		<div>
				<input type="hidden" id="pageToken" value="">
				<div class="btn-group" role="group" aria-label="...">
					<button type="button" id="pageTokenPrev" value="" class="button" style="display:none">Prev</button>
					<button type="button" id="pageTokenNext" value="" class="button" style="display:none">Next</button>
				</div>
		</div>

		<div class="field-input" style="display:none;">
			<input name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" value="<?php echo $field['value']; ?>" <?php if( $field['disabled'] ) { echo "disabled"; } ?> />
		</div>

		<textarea class="youtube-options" style="display:none;"><?php echo json_encode( $field['options'] ); ?></textarea>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

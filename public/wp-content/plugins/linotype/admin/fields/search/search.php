<?php


wp_enqueue_style( 'field-search', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/search.css', false, false, 'screen' );

wp_enqueue_script('field-search', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/search.js', array('jquery'), '1.0', true );

$default_options = array(
	"placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-search <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div id="search-select">
			<img src="<?php echo $field['value']; ?>"/>
		</div>
		
		<div id="search-searchbar">
			<div class="field-input">
				<input type="search" name="search-search" id="search-search" placeholder="" class="search-search ui-autocomplete-input" autocomplete="off">
			</div>
			<div id="search-search-button" class="" >Search</div>
		</div>

		<div id="search-list-content" class="">
				<ul id="search-list" class=""></ul>
		</div>

		<div>
				<input type="hidden" id="pageToken" value="1">
				<div class="btn-group" role="group" aria-label="...">
					<button type="button" id="pageTokenPrev" value="" class="button" style="display:none">Prev</button>
					<button type="button" id="pageTokenNext" value="" class="button" style="display:none">Next</button>
				</div>
		</div>

		<div class="field-input" style="display:none;">
			<input name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" value="<?php echo $field['value']; ?>" <?php if( $field['disabled'] ) { echo "disabled"; } ?> />
		</div>

		<textarea class="search-options" style="display:none;"><?php echo json_encode( $field['options'] ); ?></textarea>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

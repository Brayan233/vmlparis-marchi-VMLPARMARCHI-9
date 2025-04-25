<?php

wp_enqueue_script("jquery-ui-core");
wp_enqueue_script("jquery-ui-sortable");

wp_enqueue_style( 'reavis_library_selectize', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/reavis_library_selectize/style.css', false, false, 'screen' );
wp_enqueue_script('reavis_library_selectize', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/reavis_library_selectize/script.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'linotype_field_terms', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script( 'linotype_field_terms', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array( 'jquery' ), false, true );

//load default options
$default_options = array();
$default_options_data = "";
if ( file_exists( dirname( __FILE__ ) .'/options.json' ) ) $default_options_data = file_get_contents( dirname( __FILE__ ) .'/options.json' );
if ( $default_options_data ) $default_options = json_decode( $default_options_data, true );
$field['options'] = wp_parse_args( $field['options'], $default_options );
//load default options end 

if ( isset( $field['options']['data'] ) && $field['options']['data'] && is_callable( $field['options']['data'] ) ) {

	$field['options']['data'] = call_user_func( $field['options']['data'] );

}

if ( $field['options']['clear_button'] ) $field['options']["plugins"] = array_merge( array('clear_button'), $field['options']["plugins"] );

if ( is_callable( $field['options']['data'] ) ) {

	$field['options']['data'] = call_user_func( $field['options']['data'] );

} else {

	$field['options']['data'] = array_values( $field['options']['data'] );

}

$field['options']['data'] = array_values( $field['options']['data'] );

global $wpdb;

$terms = get_terms( array(
    'taxonomy' => $field['options']['taxonomy'],
    'hide_empty' => false,
) );

if ( $terms ) {
	foreach( $terms as $term ) {
		array_push( $field['options']['data'], array( 'title' => $term->name . '(' . $term->count . ')', 'value' => $term->term_id ) );
	}
}

?>

<li class="linotype_field_terms reavis_library_selectize wp-field  <?php if( $field['options']['fullwidthitem'] ) { echo 'fullwidthitem'; } ?> <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>
		
		<div class="field-input">

			<input name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" value="<?php echo $field['value']; ?>" />

		</div>

		<textarea class="selectize-options" style="display:none;"><?php echo json_encode( $field['options'] ); ?></textarea>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>
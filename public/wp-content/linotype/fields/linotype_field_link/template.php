<?php

wp_enqueue_script("jquery-ui-core");
wp_enqueue_script("jquery-ui-sortable");

wp_enqueue_style( 'reavis_library_selectize', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/reavis_library_selectize/style.css', false, false, 'screen' );
wp_enqueue_script('reavis_library_selectize', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( dirname( dirname( __FILE__ ) ) ) ) . '/libraries/reavis_library_selectize/script.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'linotype_field_link', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script( 'linotype_field_link', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array( 'jquery' ), false, true );

$default_options = array();
$default_options_data = "";
if ( file_exists( dirname( __FILE__ ) .'/options.json' ) ) $default_options_data = file_get_contents( dirname( __FILE__ ) .'/options.json' );
if ( $default_options_data ) $default_options = json_decode( $default_options_data, true );
$field['options'] = wp_parse_args( $field['options'], $default_options );

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

$querystr = "
    SELECT $wpdb->posts.ID,$wpdb->posts.post_title 
    FROM $wpdb->posts, $wpdb->postmeta
    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
    AND $wpdb->posts.post_status = 'publish' 
    ORDER BY $wpdb->posts.post_date DESC
 ";

// $POST_LIST = $wpdb->get_results($querystr, ARRAY_A);

// if ( $POST_LIST ) {
//  foreach( $POST_LIST as $POST_ITEM ) {
// 	if ( $POST_ITEM['post_title'] ) array_push( $field['options']['data'], array( 'title' => get_the_permalink( $POST_ITEM['ID'] ) . ' — ' . $POST_ITEM['post_title'], 'value' => intval( $POST_ITEM['ID'] ) ) );
//  }
// }

if ( is_array( $field['value'] ) ) {

	$link = $field['value'];
	
	if ( $link['url'] ) array_push( $field['options']['data'], array( 'title' => $link['url'], 'value' => $link['url'] ) );
	
	$field['value'] = json_encode( $field['value'], true );
	
} else {

	$link = array(
		'title' => '',
		'url' => '',
		'target' => '',
	);

	$field['value'] = "";

}

if ( is_numeric( $link['url'] ) ) {
	$link['url'] = str_replace( home_url(), '', get_the_permalink( $link['url'] ) );
}

?>

<li class="linotype_field_link wp-field <?php if( isset( $field['options']['fullwidthitem'] ) ) { echo 'fullwidthitem'; } ?> <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>
		
		<div class="field-input">

			<div class="field-input-col">Title</div>
			<div class="field-input-col-title">
				<input class="linotype_field_link-input linotype_field_link-title" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="" value="<?php echo $link['title']; ?>" />
			</div>
			<div class="field-input-col">Link</div>
			<div class="field-input-col-url">
				<input class="linotype_field_link-input linotype_field_link-url" type="text" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="<?php echo $field['options']['placeholder']; ?>" value="<?php echo $link['url']; ?>" />
			</div>
			<div class="field-input-col-target">
				<input class="linotype_field_link-input linotype_field_link-target" type="checkbox" <?php if ( $link['target'] == '_blank' ) { echo 'checked="checked"'; } ?> /> open new window
			</div>

			<textarea style="display:none" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" ><?php echo $field['value']; ?></textarea>

		</div>

		<textarea class="selectize-options" style="display:none;"><?php echo json_encode( $field['options'] ); ?></textarea>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>
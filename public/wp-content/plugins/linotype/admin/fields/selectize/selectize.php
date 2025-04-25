<?php

wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-droppable');
wp_enqueue_script('jquery-ui-sortable');

wp_enqueue_style( 'selectize', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/selectize/css/selectize.css', false, false, 'screen' );
wp_enqueue_script('selectize', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/selectize/js/standalone/selectize.min.js', array('jquery'), '0.12.1', true );

wp_enqueue_style( 'field-selectize', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/selectize.css', false, false, 'screen' );
wp_enqueue_script('field-selectize', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/selectize.js', array('jquery'), '1.0', true );

$default_options = array(
	"data" => array(),
	"plugins" => array('restore_on_backspace','drag_drop','remove_button','optgroup_columns'),
	'clear_button' => false,
	"custom" => false,
	"placeholder" => "",
	"delimiter" => ',',
	"persist" => false,
	"maxOptions" => 1000,
	"maxItems" => 1000,
	"hideSelected" => false,
	"allowEmptyOption" => true,
	"closeAfterSelect" => true,
	"labelField" => 'title',
	"valueField" => 'value',
);

$field['options'] = array_merge( $default_options, $field['options'] );

if ( isset( $field['options']['data'] ) && $field['options']['data'] && is_callable( $field['options']['data'] ) ) {

	$field['options']['data'] = call_user_func( $field['options']['data'] );

}

if ( $field['options']['clear_button'] ) $field['options']["plugins"] = array_merge( array('clear_button'), $field['options']["plugins"] );

if ( is_callable( $field['options']['data'] ) ) {

	$field['options']['data'] = call_user_func( $field['options']['data'] );

} else if ( $field['options']['data'] ) {

	$field['options']['data'] = array_values( $field['options']['data'] );

}

//if ( $field['options']['data'] ) $field['options']['data'] = array_filter($field['options']['data'], function($var){return !is_null($var);} );

//$field['options']['data'] = array_values( $field['options']['data'] );

// _HANDYLOG($field);
/*
//add custom value if exist
if ( $field['options']['custom'] ) {

$data_values = array();
if ( $field['options']['data'] ) {
	foreach ( $field['options']['data'] as $data_key => $data ) {

		$data_values[] = $data['value'];

		if ( in_array( 'optgroup_columns', $field['options']['plugins'] ) && !isset( $data["optgroup"] ) ) $data["optgroup"] = "Default";
		$field['options']['data'][$data_key] = $data;

	}
}

$field_values = explode( ',', $field['value'] );

	if ( $field_values ) {

		foreach ( $field_values as $field_value ) {

			if ( ! in_array( $field_value, $data_values ) ) {

				$optgroup_name = "";
				if ( in_array( 'optgroup_columns', $field['options']['plugins'] ) ) $optgroup_name = 'Custom';

				$field['options']['data'][] = array( "value" => $field_value, "title" => $field_value, "optgroup" => $optgroup_name );

			}

		}

	}

}
*/
?>

<li class="wp-field wp-field-selectize <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

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

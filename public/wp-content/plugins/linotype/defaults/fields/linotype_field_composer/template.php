<?php

$default_options = array(
	'name' => 'Edit',
	'title' => '',
	'desc' => '',
	'collapsed' => true,
	'default_source' => false,
	'maxDepth' => 5,
	'group' => 1,
	'min_height' => 80,
	'type' => 'block',
	'items' => array(),
	'items_only' => array(),
	'items_not' => array(),
	'item_default_id' => '',
	'item_default_data' => 'data',
	'border' => true,
	'toolbar' => true,
	'actions' => array( 'add', 'edit' ,'sort', 'clone', 'copy', 'delete', 'source', 'link' ),
	'devices' => true,
	'overwrite' => true,
	'empty' => false,
	'layout' => 'default',
	'root_class' => '',
);

if ( ! isset( $field['id'] ) ) $field['id'] = '';
if ( ! isset( $field['value'] ) ) $field['value'] = '';
if ( ! isset( $field['title'] ) ) $field['title'] = '';
if ( ! isset( $field['info'] ) ) $field['info'] = '';
if ( ! isset( $field['desc'] ) ) $field['desc'] = '';
if ( ! isset( $field['path'] ) ) $field['path'] = '';

if ( ! isset( $field['padding'] ) ) $field['padding'] = '';
if ( ! isset( $field['options'] ) ) $field['options'] = array();

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( $field['options']['type'] == 'field' ) {

	if ( ! empty( $field['options']['items_only'] ) ) {
		
		$field['options']['items'] = LINOTYPE::$FIELDS->get( $field['options']['items_only'] );

	} else {

		$field['options']['items'] = LINOTYPE::$FIELDS->get();

	}

} else {

	if ( ! empty( $field['options']['items_only'] ) ) {
		
		$field['options']['items'] = LINOTYPE::$BLOCKS->get( $field['options']['items_only'] );

	} else {

		$field['options']['items'] = LINOTYPE::$BLOCKS->get();

	}

}

if ( is_callable( $field['options']['items'] ) ) $field['options']['items'] = call_user_func( $field['options']['items'] );

if ( ! is_array( $field['value'] ) && $field['value'] ) {
	
	$field_value = json_decode( stripslashes( $field['value'] ), true );

	$json_error = "";

	switch (json_last_error()) {
		case JSON_ERROR_NONE:
			//echo 'Aucune erreur';
		break;
		case JSON_ERROR_DEPTH:
			$json_error = 'Profondeur maximale atteinte';
		break;
		case JSON_ERROR_STATE_MISMATCH:
			$json_error = 'Inadéquation des modes ou underflow';
		break;
		case JSON_ERROR_CTRL_CHAR:
			$json_error = 'Erreur lors du contrôle des caractères';
		break;
		case JSON_ERROR_SYNTAX:
			$json_error = 'Erreur de syntaxe, JSON malformé';
		break;
		case JSON_ERROR_UTF8:
			$json_error = 'Caractères UTF-8 malformés, probablement une erreur d\'encodage';
		break;
		default:
			$json_error = 'Erreur inconnue';
		break;
	}

	if ( ! $json_error ) {
		$field['value'] = $field_value;
	} else if ( $field['options']['item_default_id'] ) {
		$field['value'] = array(
			array(
				"type" => $field['options']['item_default_id'],
				"options" => array(
					$field['options']['item_default_data'] => $field['value'],
				)
			)
		);
	} else {
		
		echo '<div class="linotype_field_composer_error"><div>' . $json_error . '</div><pre>' . htmlentities( $field['value'] ) . '</pre></div>';

	}
	
}

?>

<div class="wp-field linotype_field_composer fullwidth <?php if( isset( $field['fullheight'] ) && $field['fullheight'] === true ) { echo 'fullheight'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

			<?php LINOTYPE_composer::render( $field['value'], $field['options']['items'], $field ); ?>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</div>

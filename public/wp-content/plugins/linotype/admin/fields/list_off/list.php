<?php

$default_options = array(
	'data' => array(),
	'name' => 'Edit',
	'title' => '',
	'desc' => '',
	'collapsed' => true,
	'default_source' => false,
	'border' => true,
	'toolbar' => true,
	'maxDepth' => 5,
	'group' => 1,
	'row' => 1,
	'column' => 1,
	'items' => array(),
	'map' => array(),
	'actions' => array( 'edit' ,'sort' ,'delete' ,'add' ,'clone' ),
);

$field['options'] = wp_parse_args( $field['options'], $default_options );


update_option( $field['id'] . '_items', json_encode( $field['options']['items'] ) );

if ( ! is_array( $field['value'] ) ) $field['value'] = json_decode(  $field['value'], true );

?>

<div class="wp-field wp-field-list <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

			<?php

			include dirname( __FILE__ ) . '/list.class.php';

			$list = new handypress_list( $field['id'], $field['options']['items'], $field['options'] );

			$list->load( $field['value'] );

			$list->editor();

			?>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</div>

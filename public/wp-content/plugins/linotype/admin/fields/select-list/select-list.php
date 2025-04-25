<?php

wp_enqueue_style( 'field-select-list', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/select-list.css', false, false, 'screen' );
wp_enqueue_script('field-select-list', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/select-list.js', array('jquery'), '1.0', true );

$default_options = array(
    "data" => array(),
    'data_map' => array( 'title' => 'title', 'value' => 'value' ),
    'add_title' => '',
    'add_link' => '',
    "multiple" => true,
    "label_select" => "Add",
    "label_unselect" => "Remove",
    "height" => '500px',
    "min-width" => '100%',
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-select-list <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
	<h1><?php echo $field['title']; ?><span class="title-count list-count"><?php echo count( $field['options']['data'] ); ?></span><?php if ( $field['options']['add_title'] ) { ?><a href="<?php echo $field['options']['add_link']; ?>" class="hide-if-no-js page-title-action"><?php echo $field['options']['add_title']; ?></a><?php } ?></h1>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
		<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<?php

		if ( $field['value'] ) $field['value'] = stripslashes( $field['value'] );

		if ( $field['value'] ) $field['value_arr'] = json_decode( $field['value'], true );

		$label_select = __('Select');
		if ( $field['options']['label_select'] ) $label_select = $field['options']['label_select'];
		$label_unselect = __('Unselect');
		if ( $field['options']['label_unselect'] ) $label_unselect = $field['options']['label_unselect'];

		?>

		<div class="select-list <?php if ( $field['options']['multiple'] ) { echo 'multiple'; } ?>">

			<?php
			if ( $field['options']['data'] ) {
			foreach ( $field['options']['data'] as $item_key => $item ) {

				$active = false;
				if ( isset( $field['value_arr'] ) && in_array( $item[ $field['options']['data_map']['value'] ], $field['value_arr'] ) ) $active = 'active';

			?>

			<div class="select-item <?php echo $active; ?>" data-value="<?php echo $item[ $field['options']['data_map']['value'] ] ?>" tabindex="0" >

				<div class="select-item-title"><?php echo $item[ $field['options']['data_map']['title'] ] ?></div>

				<div class="select-item-data">

					<?php

					$item_data = '<ul>';

						if ( isset( $item['data'] ) && $item['data'] ) {
							foreach ( $item['data'] as $data ) {

								//if ( $data[ $field['options']['data_map']['value'] ] ) {

									$item_data .= '<li>';

										$item_data .= '<span class="select-item-data-title">' . $data[ $field['options']['data_map']['title'] ] . '</span>';

										if ( $data['value'] && is_array( $data[ $field['options']['data_map']['value'] ] ) ) $data[ $field['options']['data_map']['value'] ] = json_decode( $data[ $field['options']['data_map']['value'] ], true);
										$item_data .= '<span class="select-item-data-value">' . $data[ $field['options']['data_map']['value'] ] . '</span>';

										$item_data .= '<span class="select-item-data-desc">' . $data['desc'] . '</span>';

									$item_data .= '</li>';

								//}

							}

						}

					$item_data .= '</ul>';

					echo $item_data;

					?>

				</div>

				<div class="select-item-actions">

					<?php if ( isset( $item['data'] ) && $item['data'] ) { ?>
					<!-- <a class="button button-light action-data" style="margin-left:3px;margin-right:0px;" ><span class="dashicons dashicons-visibility"></span></a> -->
					<?php } ?>

					<?php if ( $field['options']['multiple'] ) { ?>
					<a class="button button-light action-desactivate" style="margin-left:3px;margin-right:0px;" ><?php echo $label_unselect; ?></a>
					<?php } ?>

					<a class="button button-primary button-light action-activate" style="margin-left:3px;margin-right:0px;" ><?php echo $label_select; ?></a>



				</div>

			</div>

			<?php } } ?>

		</div>

		<div class="field-input" style="display:none;">
			<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false"><?php echo $field['value']; ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
		<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

<?php

wp_enqueue_media();

wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script( 'wp-color-picker-alpha', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.2', true );

wp_enqueue_style( 'field-panel-setting', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/panel-setting.css', false, false, 'screen' );
wp_enqueue_script('field-panel-setting', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/panel-setting.js', array('jquery'), '1.0', true );

$default_options = array(
    "small_buttons" => false,
    "max_width" => "",
    "items" => array(),
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-panel-setting <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php

		if ( $field['value'] ) {

			$field['value'] = json_decode( stripslashes( $field['value'] ), true )[0];

		} else {

			$field['value'] = array();

		}

		$button_small = '';
		if ( $field['options']['small_buttons'] ) $button_small = 'button-small';

		?>

		<div class="panel-setting">

			<div class="panel-setting-wrap" style="max-width:<?php if( $field['options']['max_width'] ) { echo $field['options']['max_width']; } ?>">

				<?php
				$html = "";

				if( $field['options']['items'] ) {
				foreach ( $field['options']['items'] as $item_key => $item ) {

					// $default_item_options = array(
					//     "title" => "",
     //                    "id" => "item_" . $item_key,
     //                    "type" => "button",
     //                    "options" => array(),
     //                    "custom" => false,
					// );

					// $item['options'] = wp_parse_args( $item['options'], $default_item_options );

					if ( isset( $field['value'][ $item['id'] ] ) ) {
						$item['value'] = $field['value'][ $item['id'] ];
					} else {
						$item['value'] = "";
					}

					if ( isset( $item['type'] ) ) {

						$html .= '<div class="panel-setting-params-field">';

							switch ( $item['type'] ) {

								case 'title':

									$html .= '<h2>' . $item['title'] . '</h2>';

								break;

								case 'button':

									$html .= '<span>' . $item['title'] . '</span>';

									$group_count = count( $item['options'] );
									if ( $item['custom'] ) $group_count++;

									$html .= '<div class="panel-setting-action button-grouped grouped-' . $group_count . '">';

										foreach ( $item['options'] as $option_val => $option_label ) {

											$selected = '';
											if( $option_val == $item['value'] ) $selected = 'button-current';
											$html .= '<div class="panel-setting-setter button ' . $button_small . ' '. $selected .'" data-set="'. $option_val .'">'. $option_label .'</div>';

										}

										if ( $item['custom'] ) {

											$selected = '';
											if( $item['value'] && ! in_array( $item['value'], array_keys( $item['options'] ) ) ) $selected = 'button-current';
											$html .= '<div class="panel-setting-setter button ' . $button_small . ' button-custom-data '. $selected .'" data-set="">custom</div>';

										}

									$html .= '</div>';

									$input_visibility = '';
									if( $item['value'] == "" || in_array( $item['value'], array_keys( $item['options'] ) ) ) $input_visibility = 'style="display:none;"';
									$html .= '<input data-value-id="' . $item['id'] . '" type="text" class="panel-setting-params" '. $input_visibility .' value="' . $item['value'] . '" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="">';

								break;

								case 'select':

									$html .= '<span>' . $item['title'] . '</span>';

									$html .= '';

								break;

								default:

									$html .= '<span>' . $item['title'] . '</span>';

									$html .= "";

								break;

							}

						$html .= '</div>';

					}

				}
				}

				echo $html;

				?>

			</div>

			<textarea style="display:none;width:100%;" type="text" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" <?php if(!isset($field['id_multiple'])) echo 'name="' . $field['id'] . '"'; ?> autocorrect="off" autocomplete="off" spellcheck="false" ><?php if ( $field['value'] ) { echo '[' . json_encode( $field['value'] ) . ']'; } ?></textarea>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

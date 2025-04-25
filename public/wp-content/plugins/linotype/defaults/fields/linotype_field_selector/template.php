<?php

wp_enqueue_style( 'linotype_field_selector', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script( 'linotype_field_selector', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array( 'jquery' ), false, true );

$default_options = array(
	"data" => array(),
	"default_title" => __('default'),
	"default_color" => '#ddd',
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="linotype_field_selector wp-field wp-field-selector <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<?php

			echo '<div class="selector">';

				$color = $field['options']['default_color'];

				echo '<ul class="selector-items">';

					echo '<li class="selector-item" style="background:' . $field['options']['default_color'] . '" data-color="' . $field['options']['default_color'] . '" data-value="">' . $field['options']['default_title'] . '</li>';

					if ( isset( $field['options']['data'] ) && is_array( $field['options']['data'] ) ) {
						foreach ( $field['options']['data'] as $item_id => $item ) {
						
						$selected = '';
						if ( isset( $field['value'] ) && $item['value'] && $item['value'] == $field['value'] ) {
							$color = $item['color'];
							$selected = ' selected';
						}

						echo '<li class="selector-item' . $selected . '" style="background:' . $item['color'] . '" data-color="' . $item['color'] . '" data-value="' . $item['value'] . '">' . $item['title'] . '</li>';

						}
					}

					
					echo '</ul>';

					echo '<input id="' . $field['id'] . '" name="' . $field['id'] . '" class="wp-field-value meta-field" value="' . $field['value'] . '" style="background:'. $color .'" readonly>';

				echo '</div>';

			?>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>
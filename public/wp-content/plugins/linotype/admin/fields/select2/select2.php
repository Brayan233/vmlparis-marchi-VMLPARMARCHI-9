<?php

wp_enqueue_style( 'select2-4', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/select2-4.0.2/css/select2.css', false, false, 'screen' ); 
wp_enqueue_script('select2-4', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/select2-4.0.2/js/select2.js', array('jquery'), '4.0.2', true );

wp_enqueue_style( 'field-select2', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/select2.css', false, false, 'screen' ); 
wp_enqueue_script('field-select2', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/select2.js', array('jquery'), '1.0', true );

wp_localize_script( 'field-select2', 'field_select2', array( 
	'ajaxurl' => str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/select2-ajax.php',
));

$default_options = array(
    "data" => array(),
    "multiple" => false,
    "search" => false,
    "clear" => false,
    "tags" => false,
    "placeholder" => "",
    "min-width" => "",
    "height" => "",
    "style" => "",
    "custom" => false,
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-select2 <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>	
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="field-input" >
			
			<?php 

			if ( ! $field['options']['multiple'] ) $field['value'] = array( $field['value'] );
			if ( ! $field['value'] ) $field['value'] = array();

			$SEARCH = "";
			if ( $field['options']['search'] ) $SEARCH = "true";

			$PLACEHOLDER = "";
			if ( $field['options']['placeholder'] != "" ) $PLACEHOLDER = $field['options']['placeholder'];

			$CLEAR = "";
			if ( $field['options']['clear'] != "" ) $CLEAR = "true";

			$MULTIPLE = "";
			if ( $field['options']['multiple'] === true ) $MULTIPLE = 'multiple="multiple"';

			$TAGS = "";
			if ( $field['options']['tags'] ) $TAGS = 'true';

			//check if custom value
			if (!function_exists('select2_search_value')) {
				function select2_search_value($array, $key, $value)
				{
				    $results = array();

				    if (is_array($array)) {
				        if (isset($array[$key]) && $array[$key] == $value) {
				            $results[] = $array;
				        }

				        foreach ($array as $subarray) {
				            $results = array_merge($results, select2_search_value($subarray, $key, $value));
				        }
				    }

				    return $results;
				}
			}

			if ( $field['value'] ){

				$custom_value = array();

				foreach ( $field['value'] as $value_key => $value ) {
				
					$select2_search_result = select2_search_value( $field['options']['data'], 'value', $value );

					if ( ! $select2_search_result && $value ) {

						$custom_value[] = array( "title" => $value, "value" => $value, "info" => 'custom' );

					}

				}

				if ( $custom_value ) {

					$custom_group = array( "optgrouplabel" => "custom", "options" => $custom_value );
					$field['options']['data'] = array_merge( $field['options']['data'], array( $custom_group ) );
				
				}

			}

			?>

			<select name="<?php echo $field['id']; ?><?php if ( $MULTIPLE ) { echo '[]'; } ?>" id="<?php echo $field['id']; ?>" style="display:none;<?php if( $field['fullwidth'] ) { echo 'width:100%'; } ?>;" class="wp-field-value meta-field linoadmin-select2-select" <?php echo $MULTIPLE; ?>>
				
				<?php if ( $field['options']['data'] ) { ?>
					<?php foreach ( $field['options']['data'] as $key => $option ) { 

						//check if group
						if ( isset( $option['optgrouplabel'] ) && $option['optgrouplabel'] && $option['options'] ) {

							echo '<optgroup label="' . $option['optgrouplabel'] . '">';
							
								foreach ( $option['options'] as $optgroup_key => $optgroup ) { 
								
								$selected = '';
								if ( $field['value'] && in_array( $optgroup['value'], $field['value'] ) ) $selected = 'selected="selected"';
								
								if ( $optgroup['value'] ) $value = "value='" . stripslashes( str_replace("'", '"',  $optgroup['value'] ) ) . "'";
								
								?>
								<option autocorrect="off" autocomplete="off" spellcheck="false" <?php echo $value; ?> <?php echo $selected; ?> data-info="<?php if ( $optgroup['info'] ) { echo $optgroup['info']; } ?>" ><?php echo stripslashes( str_replace("'", '"',  $optgroup['title'] ) ); ?></option>
								<?php 
								
								}

							echo '</optgroup>';
						
						} else if ( ! isset( $option['options'] ) ) {

							$selected = '';
							if ( $field['value'] && in_array( $option['value'], $field['value'] ) ) $selected = 'selected="selected"';
							
							if ( $option['value'] != "" ) $value = 'value="' . stripslashes( str_replace("'", '"',  $option['value'] ) ) . '"';

							?>
							<option autocorrect="off" autocomplete="off" spellcheck="false" <?php echo $value; ?> <?php echo $selected; ?> data-info="<?php if ( isset( $option['info'] ) ) { echo $option['info']; } ?>" ><?php echo stripslashes( str_replace("'", '"',  $option['title'] ) ); ?></option>
							<?php 
							
						}

					} ?>
				<?php } ?>

			</select> 

			<textarea class="select2-options" style="display:none;"><?php echo json_encode( $field['options'] ); ?></textarea>
			
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>
	
	</div>

</li>


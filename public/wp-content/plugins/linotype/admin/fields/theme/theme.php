<?php

wp_enqueue_script('jquery-ui-sortable');

wp_enqueue_style( 'handypress-theme', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/theme.css', false, false, 'screen' );
wp_enqueue_script('handypress-theme', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/theme.js', array('jquery'), '1.0', true );


if ( isset( $field['options']['map'] ) && $field['options']['map'] ) {

	$field['options']['map'] = call_user_func( $field['options']['map'] );

}

//if ( $field['value'] ) $field['value'] = stripslashes( $field['value'] );

$default_options = array(
	"map" => array(),
	"default" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

$MAP = $field['value'];

$field['all_templates'] = LINOTYPE::$TEMPLATES->get();

//_HANDYLOG( 'map', $field['options'] );
//_HANDYLOG( 'value', $MAP );
// return;
?>

<div class="wp-field wp-field-theme <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="linoadmin-row-">

		<?php

			$theme_tab_counter = 0;

			echo '<ul class="theme-tabs">';

				if ( $field['options']['map'] ) {
					foreach ( $field['options']['map'] as $map_key => $map ) {

						$tab_select = "";
						if ( $theme_tab_counter == 0 ) $tab_select = "select";
						
						if ( isset( $map['infos'] ) ) {
								
							echo '<li class="theme-tab ' . $tab_select .'" data-target=".theme-content-'.$map_key.'">';

							
								 echo $map['infos']['title'];
							

							echo '</li>';
						
						}

						$theme_tab_counter++;

					}
				}

			echo '</ul>';

			echo '<ul class="theme-contents">';

			$theme_content_counter = 0;

			if ( $field['options']['map'] ) {

				foreach ( $field['options']['map'] as $map_key => $map ) {

					$content_show = "";
					if ( $theme_content_counter == 0 ) $content_show = "show";

					echo '<li class="theme-content theme-content-'.$map_key.' ' . $content_show . '" style="display:none">';

						echo '<ul class="type-tabs">';

							$type_tab_counter = 0;

							// echo 'Type: ';

							if ( $map['types'] ) {

								foreach ( $map['types'] as $type_key => $type ) {

									if ( isset( $type['title'] ) && $type['title'] ) {

										$type_tab_select = "";
										if ( $type_tab_counter == 0 ) $type_tab_select = "select";

										echo '<li class="type-tab ' . $type_tab_select .'" data-target=".type-content-'.$type_key.'">';

											echo $type['title'];

										echo '</li>';

										if ( $type_tab_counter < ( count($map['types']) - 1 ) ) echo ' - ';

										$type_tab_counter++;

									}

								}

							}

						echo '</ul>';


						echo '<ul class="type-contents">';

							$type_content_counter = 0;

							if ( $map['types'] ) {

								foreach ( $map['types'] as $type_key => $type ) {

									if ( isset( $type['title'] ) && $type['title'] ) {

										$type_content_show = "";
										if ( $type_content_counter == 0 ) $type_content_show = "show";

										echo '<li class="type-content type-content-'.$type_key.' ' . $type_content_show . '" style="display:none">';

											//echo '<div class="linoadmin-container-fluid">';

												//echo '<div class="linoadmin-row">';

													//echo '<div class="linoadmin-col-12">';

														//echo '<h2>'. $map['infos']['title'] . ' > ' . $type['title'] . '</h2>';

														echo '<p>Default <i>'. $map['infos']['title'] . '</i> template for <i>' . $type['title'] . '</i> type:</p>';

														echo '<select class="template-field template-default" data-map="'.$map_key.'" data-type="'.$type_key.'" style="min-width:400px">';

															echo '<option value="">-</option>';

															//echo '<option value="linotype_template_page">debug: linotype_template_page</option>';

															array_push( $type['templates'], array( 'title' => 'Default', 'value' => $field['options']['default'] )  );

															

															if ( $field['all_templates'] ){
																foreach ( $field['all_templates'] as $template_id => $template ) {
																	
																	if ( isset( $template_id ) && $template_id ) {
																		$select = '';
																		if ( isset( $MAP[$map_key]['types'][$type_key]['template'] ) && $MAP[$map_key]['types'][$type_key]['template'] ==  $template_id ) $select = 'selected="selected"';
																		echo '<option value="' . $template_id . '" ' . $select . '>' . $template['title'] . '</option>';
																	}

																}
															}

														echo '</select>';

														echo ' <a class="button add-template" href="#&type=' . $map_key . '">Create New ' . $map['infos']['title'] . ' Template</a>';

														echo '<br/><br/>';

														echo '<p>Custom rules to overide default <i>'. $map['infos']['title'] . '</i> <i>' . $type['title'] . '</i> template:</p>';

														echo '<div class="template-rules" data-map="'.$map_key.'" data-type="'.$type_key.'">';

															echo '<ul>';

																if ( isset( $MAP[$map_key]['types'][$type_key]['rules'] ) && $MAP[$map_key]['types'][$type_key]['rules'] ){

																	foreach ( $MAP[$map_key]['types'][$type_key]['rules'] as $rule_key => $rule ) {

																		include 'theme-rules.php';

																	}

																}

															echo '</ul>';

															echo '<div class="button add-rule">Add Rule</div>';

															echo '<script class="template-rule-item" type="template/text">';

																include 'theme-rules-map.php';

															echo '</script>';

														echo '</div>';

													//echo '</div>';

												//echo '</div>';

											//echo '</div>';

										echo '</li>';

										$type_content_counter++;
									
									}
								
								}

							}

						echo '</ul>';

					echo '</li>';

					$theme_content_counter++;

				}
			}

			echo '</ul>';

		?>

		</div>
		
		<br/>

		
		<div class="field-input" style="display:none">
			<textarea class="wp-field-map meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php echo json_encode( $field['options']['map'] ); ?></textarea>
		</div>

		
		<div class="field-input" style="display:none">
			<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php echo json_encode( $field['value'] ); ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</div>

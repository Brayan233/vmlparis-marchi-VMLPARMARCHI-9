<?php



	echo '<li class="template-rule" data-map="'.$map_key.'" data-type="'.$type_key.'">';

	echo '<span class="order-rule dashicons dashicons-menu"></span>';


	//
	// IF
	//
	echo 'if ';

	echo '<select class="template-field rule-if">';

		echo '<option value="">-</option>';

		$ifs = array(
			array( "label" => "Post", "value" => "post" ),
			array( "label" => "Taxonomy", "value" => "taxonomy" ),
			array( "label" => "Archive", "value" => "archive" ),
			array( "label" => "Archive Last", "value" => "archive_last" ),
			array( "label" => "Meta", "value" => "meta" ),
			array( "label" => "User", "value" => "user" ),
			array( "label" => "WP", "value" => "wp" ),
		);
		if ( $ifs ){
			foreach ( $ifs as $if_key => $if ) {

				$select = '';
				if ( isset( $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['if'] ) && $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['if'] ==  $if['value'] ) $select = 'selected="selected"';
				echo '<option value="' . $if['value'] . '" ' . $select . '>' . $if['label'] . '</option>';

			}
		}

	echo '</select>';


	//
	// IS
	//
	echo ' is ';

	//select

	$hide_is_select = '';
	if ( isset( $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['if'] ) && ! in_array( $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['if'], array( 'archive', 'taxonomy', 'post' ) ) ) $hide_is_select = 'display:none;';

	echo '<select class="template-field rule-is-select" style="' . $hide_is_select . '">';

		echo '<option value="">-</option>';

		if ( isset( $map['infos']['posts'] ) && $map['infos']['posts'] ){
			foreach ( $map['infos']['posts'] as $post_key => $post ) {

				$select = '';
				if ( $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['is'] ==  $post['ID'] ) $select = 'selected="selected"';

				$hide_option = '';
				if ( $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['if'] !== 'post' ) $hide_option = 'display:none;';

				echo '<option class="post" style="' . $hide_option . '" value="' . $post['ID'] . '" ' . $select . '>' . $post['title'] . '</option>';

			}
		}

		if ( isset( $map['infos']['taxonomies'] ) && $map['infos']['taxonomies'] ) {
			foreach ( $map['infos']['taxonomies'] as $taxonomie_key => $taxonomie ) {

				if ( $taxonomie ){
					foreach ( $taxonomie as $tax_key => $tax ) {

					$select = '';
					if ( $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['is'] ==  $tax['taxonomy'] . '=' . $tax['ID'] ) $select = 'selected="selected"';

					$hide_option = 'display:none;';
					if ( $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['if'] == 'taxonomy' ) $hide_option = '';
					if ( $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['if'] == 'archive' ) $hide_option = '';

					echo '<option class="taxonomy" style="' . $hide_option . '" value="' . $tax['taxonomy'] . '=' . $tax['ID'] . '" ' . $select . '>' . $tax['taxonomy'] . ' > ' . $tax['title'] . '</option>';

				}
			}

			}
		}

	echo '</select>';

	$hide_is_text = '';
	if ( in_array( $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['if'], array( 'archive', 'taxonomy', 'post' ) ) ) $hide_is_text = 'display:none;';

	echo '<input type="text" style="' . $hide_is_text . '" class="template-field rule-is" value="' . $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['is'] . '" style="min-width:20%">';

	//
	// USE
	//
	echo ' use ';

	echo '<select class="template-field rule-template">';

		echo '<option value="">-</option>';

		if ( isset( $field['all_templates'] ) && $field['all_templates'] ){
			foreach ( $field['all_templates'] as $template_id => $template ) {

				if ( isset( $template_id ) && $template_id ) {

					$select = '';
					if ( isset( $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['template'] ) && $MAP[$map_key]['types'][$type_key]['rules'][$rule_key]['template'] ==  $template_id ) $select = 'selected="selected"';
					echo '<option value="' . $template_id . '" ' . $select . '>' . $template['title'] . '</option>';
				
				}
				
			}
		}

	echo '</select>';


	echo ' <div class="button delete-rule">Delete</div>';

	echo '</li>';



?>

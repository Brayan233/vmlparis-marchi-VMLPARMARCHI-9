<?php

include dirname( dirname( __FILE__ ) ) . '/icons/functions.php';

wp_enqueue_style( 'field-icon', dirname( str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) ) . '/icons/icons.css', false, false, 'screen' );
wp_enqueue_script('field-icon', dirname( str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) ) . '/icons/icons.js', array('jquery'), '1.0', true );

wp_enqueue_script('jquery-nestable', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/jquery.nestable.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'field-nestable', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/nestable.css', false, false, 'screen' );
wp_enqueue_script('field-nestable', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/nestable.js', array('jquery'), '1.0', true );

$default_options = array(
	'data' => array(),
	'name' => 'Edit',
	'title' => '',
	'desc' => '',
	'collapsed' => true,
	'default_source' => false,
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<div class="wp-field wp-field-nestable <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>


			<div class="nav-menus-php">

			<?php

			$NESTABLE_SOURCES = $field['options']['data'];

			$field['value'] = stripslashes( $field['value'] );

			if ( $field['value'] == '[]' ) $field['value'] = '';

			if ( $field['value'] ) {

				$NESTABLE = json_decode( $field['value'], true );

			} else {

				if ( $field['options']['default_source'] ) {
					$NESTABLE = $NESTABLE_SOURCES;
				} else {
					$NESTABLE = false;
				}
			}

			$is_collapsed = $field['options']['collapsed'];

			$disable_first_submenu = false;

			echo '<div id="nav-menus-frame" class="wp-clearfix">';

				echo '<div id="menu-settings-column" class="metabox-holder">';

					include dirname( __FILE__ ) . '/nested-add-items.php';

				echo '</div>';

				echo '<div id="menu-management-liquid"><div id="menu-management"><div id="update-nav-menu">';

					echo '<div class="menu-edit">';

						echo '<div id="nav-menu-header" style="padding:0px;"">';

							echo '<h3 style="display:inline-block;font-size:14px;margin:0px;padding: 10px 10px 11px 14px;line-height: 21px;">' . $field['options']['name'] . '</h3>';

							//echo '<div class="major-publishing-actions wp-clearfix">';

								//echo '<div class="publishing-action"><input type="submit" name="save_menu" id="save_menu_header" class="button button-primary menu-save" value="Enregistrer le menu"></div>';

							//echo '</div>';

						echo '</div>';

						echo '<div id="post-body">';

							echo '<div id="post-body-content" class="wp-clearfix" style="position: relative;">';

								if ( $field['options']['title'] ) echo '<h3>' . $field['options']['title'] . '</h3>';

								if ( $field['options']['desc'] ) echo '<p class="post-body-plain" id="menu-name-desc">' . $field['options']['desc'] . '</p>';

								echo '<div class="menu-settings">';

									include dirname( __FILE__ ) . '/nested-tree.php';

								echo '</div>';

							echo '</div><!-- /#post-body-content -->';

						echo '</div>';

						// echo '<div id="nav-menu-footer">';

						// 	echo '<div class="major-publishing-actions wp-clearfix">';
						// 		echo '<div class="publishing-action">';
						// 			echo '<input type="submit" name="save_menu" id="save_menu_footer" class="button button-primary menu-save" value="Créer le menu">';
						// 		echo '</div><!-- END .publishing-action -->';
						// 	echo '</div><!-- END .major-publishing-actions -->';

						// echo '</div>';

					echo '</div>';

				echo '</div></div></div>';

			echo '</div>';

			?>

		</div>

		<!-- menu-item-settings-editor end -->

		<div class="field-input">
			<textarea style="display: none;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="" /><?php echo $field['value']; ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</div>

<?php

include dirname( dirname( __FILE__ ) ) . '/icons/functions.php';

wp_enqueue_style( 'field-icon', dirname( str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) ) . '/icons/icons.css', false, false, 'screen' );
wp_enqueue_script('field-icon', dirname( str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) ) . '/icons/icons.js', array('jquery'), '1.0', true );

wp_enqueue_script('jquery-nestable', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/jquery.nestable.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'field-repeater', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/repeater.css', false, false, 'screen' );
wp_enqueue_script('field-repeater', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/repeater.js', array('jquery'), '1.0', true );

$default_options = array(
	'data' => array(),
	'name' => 'Edit',
	'title' => '',
	'desc' => '',
	'collapsed' => true,
	'default_source' => false,
	'border' => true,
	'toolbar_pos' => 'bottom',
	'maxDepth' => 5,
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<div class="wp-field wp-field-repeater <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

			<div style="<?php if ( $field['options']['border'] ) echo 'border: 1px solid #ddd;'; ?>">

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

			?>


			<?php if ( $field['options']['toolbar_pos'] == 'top' ) include dirname( __FILE__ ) . '/repeater-add-items.php'; ?>

			<?php include dirname( __FILE__ ) . '/repeater-tree.php'; ?>

			<?php if ( $field['options']['toolbar_pos'] == 'bottom' ) include dirname( __FILE__ ) . '/repeater-add-items.php'; ?>


			<div class="field-input" style="display:none;">
				<textarea style="border-left:none;border-right:none;border-top: 1px solid #DDDDDD;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="" /><?php echo $field['value']; ?></textarea>
			</div>

			<textarea style="display: none;" class="wp-field-options" /><?php echo json_encode( $field['options'] ); ?></textarea>


		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</div>

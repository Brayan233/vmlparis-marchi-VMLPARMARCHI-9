<?php

/**
 *
 * LINOADMIN
 * Option Page builder
 * v1.0.0
 * by Yannick Armspach
 * http://www.handypress.io
 *
 * @FIELD : wp-field-icon
 *
 * $array
 * - id             string    global unique field id for db save
 * - id_multiple    string    unique id for repeater field
 * - title          string    the field title
 * - desc           string    the field description
 * - fullscreen     boolean   if true force full width field
 * - options        array     specific option array for this field
 *   - style     string    css style for text
 *
**/

?>

<?php

wp_enqueue_style( 'field-icon', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/icons.css', false, false, 'screen' );
wp_enqueue_script('field-icon', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/icons.js', array('jquery'), '1.0', true );

?>

<li class="wp-field wp-field-icons <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" wp-field-id="<?php echo $field['id']; ?>" style="display: block;padding:<?php echo $field['padding']; ?>">


	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="icon-picker">

			<div class="linoadmin_modal_icons-open button" style="">
				<i class="icon-preview <?php echo $field['value']; ?>" style="height: 29px; line-height: 29px; font-size: 20px;"></i>
			</div>

			<div class="field-input">
				<input type="text" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" name="<?php echo $field['id']; ?>" value="<?php echo $field['value']; ?>" />
			</div>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>


</li>

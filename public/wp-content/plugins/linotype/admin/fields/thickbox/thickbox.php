<?php

/**
 *
 * LINOADMIN
 * Option Page builder
 * v1.0.0
 * by Yannick Armspach
 * http://www.handypress.io
 *
 * @FIELD : wp-field-thickbox
 *
 * $array
 * - id          string    global unique field id for db save
 * - id_multiple string    unique id for repeater field
 * - title       string    the field title
 * - desc        string    the field description
 * - fullscreen  boolean   if true force full width field
 * - options     array     specific option array for this field
 *   - xxx       string    xxxxxxx
 *
**/

?>

<?php

wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');

//wp_enqueue_style( 'field-thickbox', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/thickbox.css', false, false, 'screen' );
wp_enqueue_script('field-thickbox', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/thickbox.js', array('jquery'), '1.0', true );

?>

<li class="wp-field wp-field-thickbox <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" wp-field-output="<?php echo $field['output']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<span class="spinner"></span>

	<div class="field-content" style="visibility:hidden;">


		<input id="thickbox_button_select" class="button-primary field-thickbox-open" type="button" value="Open">

		<div class="field-input" style="display:none">
			<input type="text" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" <?php if(!isset($field['id_multiple'])) echo 'name="' . $field['id'] . '"'; ?> value="<?php echo $field['value']; ?>" autocorrect="off" autocomplete="off" spellcheck="false" >
		</div>

		<div id="thickbox_<?php echo $field['id']; ?>" style="display:none;">
		     <p>
		          ...
		     </p>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

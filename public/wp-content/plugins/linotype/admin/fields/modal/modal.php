<?php

/**
 *
 * LINOADMIN
 * Option Page builder
 * v1.0.0
 * by Yannick Armspach
 * http://www.handypress.io
 *
 * @FIELD : wp-field-modal
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

//load template
//add_action( 'admin_footer', array( $this, 'add_templates' ) );

wp_enqueue_script( 'field-modal', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/modal.js', array( 'jquery', 'backbone', 'underscore', 'wp-util' ) );

wp_localize_script( 'field-modal', 'aut0poietic_backbone_modal_l10n',
array(
	'replace_message' => __( 'This is dummy content. You should add something here.', 'backbone_modal' )
) );

wp_enqueue_style( 'field-modal', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/modal.css', false, false, 'screen' );

?>

<li class="wp-field wp-field-modal <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<!-- <span class="spinner"></span> -->

	<div class="field-content" style="visibility:visible;">

		<input id="open-backbone_modal" class="button-primary field-modal-open" type="button" value="Open">

		<div class="field-input" style="display:none">
			<input type="text" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" <?php if(!isset($field['id_multiple'])) echo 'name="' . $field['id'] . '"'; ?> value="<?php echo $field['value']; ?>" autocorrect="off" autocomplete="off" spellcheck="false" >
		</div>

		<?php include dirname( __FILE__ ) . '/template-data.php'; ?>

		<div id="modal_<?php echo $field['id']; ?>" style="display:none;">
		     <p>
		          ...
		     </p>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

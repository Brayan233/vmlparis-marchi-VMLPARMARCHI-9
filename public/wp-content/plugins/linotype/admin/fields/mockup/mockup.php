<?php

/**
 *
 * LINOADMIN
 * Option Page builder
 * v1.0.0
 * by Yannick Armspach
 * http://www.handypress.io
 *
 * @FIELD : wp-field-image
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

wp_enqueue_script('jquery');
wp_enqueue_script('json2');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-draggable');

wp_enqueue_script('numeric', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/lib/numeric-1.2.6.min.js', array('jquery'), '1.2.6', true );

wp_enqueue_style( 'field-mockup', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/mockup.css', false, false, 'screen' );
wp_enqueue_script('field-mockup', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/mockup.js', array('jquery'), '1.0', true );

//_HANDYLOG('$field',$field);

if ( empty( $field['value'] ) ) $field['value'] = json_encode( $field['params']['coordinate'] );

?>

<li class="wp-field wp-field-mockup <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" wp-field-output="<?php echo $field['options']['output']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="wp-field-mockup-message"></div>

	<div class="wp-field-mockup-editor">

		<div class="wp-field-mockup-container" style="width:<?php echo $field['options']['params']['width_max']; ?>px;height:<?php echo $field['options']['params']['height_max']; ?>px;" >

		    <div class="perspective-controler">
		    	<div class="perspective-point point-0 topLeft" data-point-index="0"><span></span></div>
		    	<div class="perspective-point point-1 topRight" data-point-index="1"><span></span></div>
		    	<div class="perspective-point point-2 bottomRight" data-point-index="2"><span></span></div>
		    	<div class="perspective-point point-3 bottomLeft" data-point-index="3"><span></span></div>
		    	<div class="source-drag"><span></span></div>
		    </div>

			<?php if ( $field['options']['mask']['url'] ) { ?>
		    <img class="mask" src="<?php echo $field['options']['mask']['url']; ?>" width="<?php echo $field['options']['mask']['width']; ?>" height="<?php echo $field['options']['mask']['height']; ?>">
		    <?php } ?>

		    <?php if ( $field['options']['source']['url'] ) { ?>
		    <img class="source" src="<?php echo $field['options']['source']['url']; ?>" width="<?php echo $field['options']['source']['width']; ?>" height="<?php echo $field['options']['source']['height']; ?>">
		    <?php } ?>

		    <canvas class="screenCanvas" width="<?php echo $field['options']['params']['width_max']; ?>" height="<?php echo $field['options']['params']['height_max']; ?>"></canvas>

		    <?php if ( $field['options']['background']['url'] ) { ?>
		    <img class="background" src="<?php echo $field['options']['background']['url']; ?>" width="<?php echo $field['options']['background']['width']; ?>" height="<?php echo $field['options']['background']['height']; ?>">
		    <?php } ?>

		</div>

	</div>

	<textarea style="display:block;" type="text" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" <?php if(!isset($field['id_multiple'])) echo 'name="' . $field['id'] . '"'; ?> autocorrect="off" autocomplete="off" spellcheck="false" ><?php echo $field['value']; ?></textarea>

	<?php if( $field['desc'] ) { ?>
		<div class="field-description" ><?php echo $field['desc']; ?></div>
	<?php } ?>

</li>

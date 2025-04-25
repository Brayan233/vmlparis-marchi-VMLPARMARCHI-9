<?php

wp_enqueue_style( 'field-tree', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/tree.css', false, false, 'screen' );
wp_enqueue_script('field-tree', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/tree.js', array('jquery'), '1.0', true );

$default_options = array(
    "title" => "",
    "dir" => "",
    "type" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( is_array( $field['value'] ) ) $field['value'] =  json_encode( $field['value'] );
if ( $field['value'] ) $field['value'] =  stripslashes( $field['value'] );

?>

<li class="wp-field wp-field-tree <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="tree-master" >

      <?php if( $field['options']['title'] ) { ?>
    		<div class="tree-title" ><?php echo $field['options']['title']; ?></div>
    	<?php } ?>

			<?php

      $list = linoadmin_tree::get( $field['options']['dir'], $field['options']['type']  );

      echo $list;

			?>

		</div>

    <div class="field-input" style="display:none;">
      <textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php echo $field['value']; ?></textarea>
    </div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

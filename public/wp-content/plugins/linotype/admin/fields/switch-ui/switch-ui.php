<?php

wp_enqueue_style( 'field-switch-ui', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/switch-ui.css', false, false, 'screen' );
wp_enqueue_script('field-switch-ui', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/switch-ui.js', array('jquery'), '1.0', true );

$default_options = array(
  'type' => '',
    'action' => 'toggle',
    'height' => '38px',
    "button" => array(),
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-switch-ui <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

    <?php
    $targets = array();
    $targets_hide = array();
    foreach ( $field['options']['button'] as $key => $button ) {
      $targets[] = $button['target'];
      if ( ! $button['active'] ) $targets_hide[] = $button['target'];
    }
    $targets = implode(',',$targets);
    $targets_hide = implode(',',$targets_hide);
    ?>

    <?php

    switch ( $field['options']['type'] ) {
      case 'tabs':
      ?>

      <h2 class="switch-ui-buttons nav-tab-wrapper" style="padding:0px;" data-targets="<?php echo $targets; ?>" data-targets-hide="<?php echo $targets_hide; ?>" >

  			<?php

        foreach ( $field['options']['button'] as $key => $button ) {

          $selected = '';
          if ( $button['active'] ) $selected = ' nav-tab-active';

          echo '<a class="switch-ui-button nav-tab' . $selected . '" data-target="' . $button['target'] . '"><span>';

            echo $button['html'];

          echo '</span></a>';

        }

  			?>

  		</h2>

      <?php
      break;

      default:
      ?>

        <div class="switch-ui-buttons switch-buttons" data-targets="<?php echo $targets; ?>" data-targets-hide="<?php echo $targets_hide; ?>" >

    			<?php

          $width = '100';
          if ( $field['options']['button'] ) $width = 100/count($field['options']['button']);

          foreach ( $field['options']['button'] as $key => $button ) {

            $selected = '';
            if ( $button['active'] ) $selected = ' nav-tab-active';

            echo '<div class="switch-ui-button switch-button' . $selected . '" style="width:' . $width . '%;line-height:' . $field['options']['height'] . ';" data-target="' . $button['target'] . '"><span>';

              echo $button['html'];

            echo '</span></div>';

          }

    			?>

    		</div>

      <?php
      break;
    }
    ?>

    <div class="field-input" style="display:none;">
      <textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php echo stripslashes( $field['value'] ); ?></textarea>
    </div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

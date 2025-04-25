<?php

wp_enqueue_media();

wp_enqueue_style( 'dragula', LINOTYPE_plugin::$plugin['url'] . '/lib/dragula/dragula.css', false, false, 'screen' );
wp_enqueue_script('dragula', LINOTYPE_plugin::$plugin['url'] . '/lib/dragula/dragula.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'linotype_field_data', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script('linotype_field_data', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array('jquery'), '1.0', true );

$default_options = array(
    "collapsed" => false,
    "height" => "500px",
    'data'=> array(
      array(
        'id' => 'value',
        'title' => '',
        'desc' => '',
        'type' => 'text',
        'width' => '100%',
      ),
    ),
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( $field['value'] && ! is_array( $field['value'] ) ) $field['value'] = json_decode( stripslashes( $field['value'] ), true );

if ( ! function_exists('get_linotype_field_data_item') ) {

	function get_linotype_field_data_item( $DATA, $VALUE = array() ) {

    $html = '';

    if ( isset( $DATA ) && $DATA ) {

      $html .= '<div class="linotype_field_data-item">';

        $html .= '<div class="linotype_field_data-item-title">#Item</div>';

        $html .= '<div class="linotype_field_data-item-move"><span></span></div>';

        $html .= '<div class="linotype_field_data-item-expand"><span>more</span></div>';

        $html .= '<div class="linotype_field_data-item-delete">x</div>';

        $html .= '<div class="linotype_field_data-item-datas">';


          foreach ( $DATA as $data_id => $data ) {

            if ( ! isset( $data['width'] ) || $data['width'] == "" ) $data['width'] = '100%';

            $html .= '<div class="linotype_field_data-item-data" style="width:' . $data['width'] . '">';

              $html .= '<div class="linotype_field_data-item-data-padding">';

              $value = '';
              if ( isset( $VALUE[$data['id']] ) ) $value = $VALUE[$data['id']];

              if ( isset( $data['options'] ) && is_callable( $data['options'] ) ) $data['options'] = $data['options']();

              $html .= '<div class="linotype_field_data-item-data-label">' . $data['title'] . '</div>';

              switch( $data['type'] ){

                case 'select_custom':

                  $html .= '<div class="linotype_field_data-select_custom">';

                    $selected_value = '#999';

                    $html_select = '<ul>';

                      if ( isset( $data['options'] ) && $data['options'] ) {

                        foreach ( $data['options'] as $option_id => $option ) {

                          $selected = '';
                          if ( $option['value'] == $value ) {
                            $selected_value = $value;
                            $selected = 'selected';
                          }

                          $html_select .= '<li class="' . $selected . '" style="background:' . $option['value'] . '" data-value="' . $option['value'] . '">' . $option['title'] . '</li>';

                        }

                      }

                    $html_select .= '</ul>';

                    $html .= '<input readonly style="background:'. $selected_value .'" data-id="' . $data['id'] . '" class="linotype_field_data-item-data-value" value="' . $value . '"/>';

                    $html .= $html_select;

                  $html .= '</div>';

                break;

                case 'select':

                  $html .= '<select data-id="' . $data['id'] . '" class="linotype_field_data-select linotype_field_data-item-data-value">';

                    if ( isset( $data['options'] ) && $data['options'] ) {

                      $html .= '<option value="">-</option>';

                      foreach ( $data['options'] as $option_id => $option ) {

                        $selected = '';
                        if ( $option['value'] == $value ) $selected = 'selected="selected"';

                        $html .= '<option value="' . $option['value'] . '" ' . $selected . '>' . $option['title'] . '</option>';

                      }

                    }

                  $html .= '</select>';

                break;

                case 'json':

                  $html .= '<textarea data-id="' . $data['id'] . '" class="linotype_field_data-json linotype_field_data-item-data-value">' . json_encode( $value, JSON_PRETTY_PRINT ) . '</textarea>';

                break;

                case 'textarea':

                  $html .= '<textarea data-id="' . $data['id'] . '" class="linotype_field_data-textarea linotype_field_data-item-data-value">' . $value . '</textarea>';

                break;

                case 'image':

					$image = false;
					$image_src = wp_get_attachment_image_src( $value, '300' );
					if ( isset( $image_src[0] ) && $image_src[0] ) $image = $image_src[0];
					$link = wp_get_attachment_url( $value );

					$html .= '<div class="linotype_field_data_image">';
                    $html .= '<input data-id="' . $data['id'] . '" class="linotype_field_data-text linotype_field_data-item-data-value" value="' . $value . '"/>';
                    $html .= '<div class="actions"><div class="select button">' . __('Choose a File') . '</div> <div class="delete button">' . __('Delete') . '</div></div>';
					$html .= '<div class="link">';
                        if ( $link ) $html .= $link;
					$html .= '</div>';
                    $html .= '<div class="image">';
                      if ( $image ) $html .= '<img  style="max-width:200px;max-height:200px;" src="' . $image . '" />';
                      $html .= '</div>';
                  $html .= '</div>';

                break;

                default:

                  $html .= '<input data-id="' . $data['id'] . '" class="linotype_field_data-text linotype_field_data-item-data-value" value="' . $value . '"/>';

                break;

              }

              $html .= '</div>';

            $html .= '</div>';

          }

        $html .= '</div>';

      $html .= '</div>';

    }

    return $html;

  }
}

?>
<li id="linotype_field_data-<?php echo $field['id']; ?>" class="linotype_field_data wp-field wp-field-text <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" wp-field-id="<?php echo $field['id']; ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

  <?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="linotype_field_data-content">

      <div class="linotype_field_data-items <?php if ( $field['options']['collapsed'] ) { echo 'collapsed'; } ?>"><?php

        if ( isset( $field['value'] ) && $field['value'] && is_array( $field['value'] ) ) {
          foreach ( $field['value'] as $item_id => $item ) {

            echo get_linotype_field_data_item( $field['options']['data'], $item );

          }
        } else {

          //echo get_linotype_field_data_item( $field['options']['data'] );

        }

        echo '<div class="empty">' . __('Items list') . '</div>';

        ?></div>

      <div class="linotype_field_data-footer">

        <div class="button button-primary linotype_field_data-item-add"><?php echo __('Add'); ?></div>

      </div>

    </div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

  </div>


  <?php

    echo '<script type="html/template" class="linotype_field_data-item-template" >';

      echo get_linotype_field_data_item( $field['options']['data'] );

    echo '</script>';

    ?>

  <textarea style="display:none;width: 100%;height: 300px;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" autocorrect="off" autocomplete="off" spellcheck="false"><?php echo json_encode( $field['value'] ); ?></textarea>

</li>

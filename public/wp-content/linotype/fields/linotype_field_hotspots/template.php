<?php

wp_enqueue_media();

wp_enqueue_script( 'jquery-ui-draggable' );

wp_enqueue_style( 'linotype_field_hotspots', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );
wp_enqueue_script('linotype_field_hotspots', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array('jquery'), '1.0', true );

$default_options = array(
    "input" => false,
    "height" => "500px",
    "title" => true,
    "desc" => true,
    "img" => false,
    "labels" => false,
    "colors" => false,
    "sizes" => false,
);

if ( $field['options']['height'] == 'full' ) {
  //$field['options']['height'] = '100%';
  //$fullscreen = ' full';
}
$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<div class="linotype_field_hotspots<?php echo $fullscreen; ?>"  style="height:<?php echo $field['options']['height']; ?>" wp-field-id="<?php echo $field['id']; ?>">

  <div class="linotype_field_hotspots-action">
      
    <ul class="linotype_field_hotspots-action-contents">
      
      <?php

      if ( isset( $field['value']['hotspots'] ) && $field['value']['hotspots'] ) {
        foreach ( $field['value']['hotspots'] as $hotspot_id => $hotspot ) {
            
          echo '<li class="linotype_field_hotspots-item-content" hotspot-id="' . ( $hotspot_id + 1 ) . '" hotspot-left="' . $hotspot['left'] . '" hotspot-top="' . $hotspot['top'] . '">';
            
            echo '<div class="linotype_field_hotspots-item-header">Hotspot  #' . ( $hotspot_id + 1 ) . '</div>';
            
            echo '<div class="linotype_field_hotspots-item-delete">delete</div>';
            
            if ( $field['options']['img'] ) echo '<div class="linotype_field_hotspots-item-value linotype_field_hotspots-item-image linotype_field_hotspots-image-select" data-value="' . $hotspot['image'] . '" data-width="100px" data-padding="10px"></div>';
            
            if ( $field['options']['title'] ) echo '<textarea class="linotype_field_hotspots-item-value linotype_field_hotspots-item-title">' . $hotspot['title'] . '</textarea>';
            
            echo '<select class="linotype_field_hotspots-item-value linotype_field_hotspots-item-label">';

              echo '<option value="">-</option>';

              if ( is_array( $field['value']['labels'] ) ) {

                foreach( $field['value']['labels'] as $label_value => $label ) {
                  
                  $selected = "";
                  if ( $label['color'] == $hotspot['label'] ) $selected = 'selected="selected"';

                  echo '<option ' . $selected . ' value="' . $label['color'] . '" >' . $label['title'] . '</option>';

                }
              
              }
              
            echo '</select>';
            
            if ( $field['options']['sizes'] ) {

              echo '<select class="linotype_field_hotspots-item-value linotype_field_hotspots-item-size">';

                foreach( $field['options']['sizes'] as $size ) {
                  
                  $selected = "";
                  if ( $size == $hotspot['size'] ) $selected = 'selected="selected"';

                  echo '<option ' . $selected . '>' . $size . '</option>';

                }
                
              echo '</select>';
            
            }
          
            if ( $field['options']['desc'] ) echo '<textarea class="linotype_field_hotspots-item-value linotype_field_hotspots-item-content-value">' . $hotspot['content'] . '</textarea>';
          
          echo '</li>';

        }
      } else {
      
        echo '<div class="linotype_field_hotspots-item-empty">Select image and click on to add dots</div>';
        
      }   

      ?>

    </ul>

    <div class="button linotype_field_hotspots-show-items" style="display:none;">Show all</div>
  
    <textarea style="display:none;width: 100%;height: 300px;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" autocorrect="off" autocomplete="off" spellcheck="false"><?php echo json_encode( $field['value'] ); ?></textarea>

  </div>
  
  

	<div class="linotype_field_hotspots-content">
		
    <div class="linotype_field_hotspots-image-select" data-value="" data-width="50px" data-img-target=".linotype_field_hotspots-image > img"></div>
    
    <div class="linotype_field_hotspots-image" >
					
      <?php

      if ( isset( $field['value']['image'] ) && $field['value']['image'] ) {
        echo '<img src="' . $field['value']['image'] . '"/>';
      } else {
        echo '<img src=""/>';
      }
      
      
      if ( isset( $field['value']['hotspots'] ) && $field['value']['hotspots'] ) {
        foreach ( $field['value']['hotspots'] as $hotspot_id => $hotspot ) {

          echo '<div class="linotype_field_hotspots-item hotspot-id-' . ( $hotspot_id + 1 ) . '" hotspot-id="' . ( $hotspot_id + 1 ) . '" style="top:' . $hotspot['top'] . '%; left:' . $hotspot['left'] . '%;">' . ( $hotspot_id + 1 ) . '</div>';

        }
      }
      
    	?>
        
      
		</div>
    
    <div class="linotype_field_hotspots-labels">
      <ul class="linotype_field_hotspots-labels-contents">
        <?php
        if ( is_array( $field['value']['labels'] ) ) {

          $tpl = '';

          foreach( $field['value']['labels'] as $label_value => $label ) {
            
            $tpl .= '<li class="linotype_field_hotspots-label-content" >';
    
              if ( is_array( $field['options']['colors'] ) ) {

                $tpl .= '<div class="linotype_select_custom">';

                  $selected_value = '#999';

                  $html_select = '<ul>';

                    if ( isset( $field['options']['colors'] ) && $field['options']['colors'] ) {
                    
                      foreach ( $field['options']['colors'] as $option_id => $option ) {
                        
                        $selected = '';
                        if ( $option['value'] == $label['color'] ) {
                          $selected_value = $label['color'];
                          $selected = 'selected';
                        }

                        $html_select .= '<li class="' . $selected . '" style="background:' . $option['value'] . '" data-value="' . $option['value'] . '">' . $option['title'] . '</li>';
                  
                      }
                      
                    }
                  
                  $html_select .= '</ul>';

                  $tpl .= '<input readonly style="background:'. $selected_value .'" data-id="' . $data['id'] . '" class="linotype_field_hotspots-item-value linotype_field_hotspots-label-color" value="' . $label['color'] . '"/>';

                  $tpl .= $html_select;

                $tpl .= '</div>';
              
              }

              $tpl .= '<textarea class="linotype_field_hotspots-item-value linotype_field_hotspots-label-title">' . $label['title'] . '</textarea>';

              $tpl .= '<div class="linotype_field_hotspots-label-delete">delete</div>';

            $tpl .= '</li>';

          }

          echo $tpl;

        }
        ?>
      </ul>
    <div class="button linotype_field_hotspots-label-add">Add label</div>
  </div>

	</div>
  
  <?php

//hotspots template

$tpl = "";

$tpl .= '<script id="template-item" type="text/template">';

$tpl .= '<li class="linotype_field_hotspots-item-content" hotspot-id="" hotspot-left="" hotspot-top="">';
  
  $tpl .= '<div class="linotype_field_hotspots-item-header">Hotspot  #new</div>';
  
  $tpl .= '<div class="linotype_field_hotspots-item-delete">delete</div>';
  
  if ( $field['options']['img'] ) $tpl .= '<div class="linotype_field_hotspots-item-value linotype_field_hotspots-item-image linotype_field_hotspots-image-select" data-value="" data-width="100px" data-padding="10px"></div>';
  
  if ( $field['options']['title'] ) $tpl .= '<textarea class="linotype_field_hotspots-item-value linotype_field_hotspots-item-title"></textarea>';

  $tpl .= '<select class="linotype_field_hotspots-item-value linotype_field_hotspots-item-label">';

    echo '<option value="" selected="selected">-</option>';

    if ( is_array( $field['value']['labels'] ) ) {

      foreach( $field['value']['labels'] as $label_value => $label ) {
        
        $tpl .= '<option value="' . $label['color'] . '" >' . $label['title'] . '</option>';

      }
      
    }

  $tpl .= '</select>';
  
  if ( is_array( $field['options']['sizes'] ) ) {

    $tpl .= '<select class="linotype_field_hotspots-item-value linotype_field_hotspots-item-size">';

      foreach( $field['options']['sizes'] as $size ) {
        
        $tpl .= '<option>' . $size . '</option>';

      }
      
    $tpl .= '</select>';
  
  }

  if ( $field['options']['desc'] ) $tpl .= '<textarea class="linotype_field_hotspots-item-value linotype_field_hotspots-item-content-value"></textarea>';

$tpl .= '</li>';

$tpl .= '</script>';

echo $tpl;



// labels templates

$tpl = "";

$tpl .= '<script id="template-label" type="text/template">';

$tpl .= '<li class="linotype_field_hotspots-label-content" >';
  
  if ( is_array( $field['options']['colors'] ) ) {

    $tpl .= '<div class="linotype_select_custom">';

      $selected_value = '#999';

      $html_select = '<ul>';

        if ( isset( $field['options']['colors'] ) && $field['options']['colors'] ) {
        
          foreach ( $field['options']['colors'] as $option_id => $option ) {
            
            $selected = '';

            $html_select .= '<li class="' . $selected . '" style="background:' . $option['value'] . '" data-value="' . $option['value'] . '">' . $option['title'] . '</li>';
      
          }
          
        }
      
      $html_select .= '</ul>';

      $tpl .= '<input readonly style="background:'. $selected_value .'" data-id="' . $data['id'] . '" class="linotype_field_hotspots-item-value linotype_field_hotspots-label-color" value=""/>';

      $tpl .= $html_select;

    $tpl .= '</div>';
  
  }

  $tpl .= '<textarea class="linotype_field_hotspots-item-value linotype_field_hotspots-label-title"></textarea>';

  $tpl .= '<div class="linotype_field_hotspots-label-delete">delete</div>';

$tpl .= '</li>';

$tpl .= '</script>';

echo $tpl;

?>
    
</div>
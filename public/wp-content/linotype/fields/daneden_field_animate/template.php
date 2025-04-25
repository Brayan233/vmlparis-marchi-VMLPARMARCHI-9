<?php

$default_options = array(
    "data" => array(),
    "multiple" => false,
  	"empty" => true,
    "min-width" => "",
    "height" => "",
    "style" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

$field['options']['data'] = wp_parse_args( $field['options']['data'], array(
	
  array( 'title' => 'Attention Seekers', 'optgroup' => array( 
    array('value' => 'bounce', 'title' => 'bounce'),
    array('value' => 'flash', 'title' => 'flash'),
    array('value' => 'pulse', 'title' => 'pulse'),
    array('value' => 'rubberBand', 'title' => 'rubberBand'),
    array('value' => 'shake', 'title' => 'shake'),
    array('value' => 'swing', 'title' => 'swing'),
    array('value' => 'tada', 'title' => 'tada'),
    array('value' => 'wobble', 'title' => 'wobble'),
    array('value' => 'jello', 'title' => 'jello'),
  )),
  array( 'title' => 'Bouncing Entrances', 'optgroup' => array( 
      array('value' => 'bounceIn', 'title' => 'bounceIn'),
      array('value' => 'bounceInDown', 'title' => 'bounceInDown'),
      array('value' => 'bounceInLeft', 'title' => 'bounceInLeft'),
      array('value' => 'bounceInRight', 'title' => 'bounceInRight'),
      array('value' => 'bounceInUp', 'title' => 'bounceInUp'),
  )),
  array( 'title' => 'Bouncing Exits', 'optgroup' => array( 
      array('value' => 'bounceOut', 'title' => 'bounceOut'),
      array('value' => 'bounceOutDown', 'title' => 'bounceOutDown'),
      array('value' => 'bounceOutLeft', 'title' => 'bounceOutLeft'),
      array('value' => 'bounceOutRight', 'title' => 'bounceOutRight'),
      array('value' => 'bounceOutUp', 'title' => 'bounceOutUp'),
  )),
  array( 'title' => 'Fading Entrances', 'optgroup' => array( 
      array('value' => 'fadeIn', 'title' => 'fadeIn'),
      array('value' => 'fadeInDown', 'title' => 'fadeInDown'),
      array('value' => 'fadeInDownBig', 'title' => 'fadeInDownBig'),
      array('value' => 'fadeInLeft', 'title' => 'fadeInLeft'),
      array('value' => 'fadeInLeftBig', 'title' => 'fadeInLeftBig'),
      array('value' => 'fadeInRight', 'title' => 'fadeInRight'),
      array('value' => 'fadeInRightBig', 'title' => 'fadeInRightBig'),
      array('value' => 'fadeInUp', 'title' => 'fadeInUp'),
      array('value' => 'fadeInUpBig', 'title' => 'fadeInUpBig'),
  )),
  array( 'title' => 'Fading Exits', 'optgroup' => array( 
      array('value' => 'fadeOut', 'title' => 'fadeOut'),
      array('value' => 'fadeOutDown', 'title' => 'fadeOutDown'),
      array('value' => 'fadeOutDownBig', 'title' => 'fadeOutDownBig'),
      array('value' => 'fadeOutLeft', 'title' => 'fadeOutLeft'),
      array('value' => 'fadeOutLeftBig', 'title' => 'fadeOutLeftBig'),
      array('value' => 'fadeOutRight', 'title' => 'fadeOutRight'),
      array('value' => 'fadeOutRightBig', 'title' => 'fadeOutRightBig'),
      array('value' => 'fadeOutUp', 'title' => 'fadeOutUp'),
      array('value' => 'fadeOutUpBig', 'title' => 'fadeOutUpBig'),
  )),
  array( 'title' => 'Flippers', 'optgroup' => array( 
      array('value' => 'flip', 'title' => 'flip'),
      array('value' => 'flipInX', 'title' => 'flipInX'),
      array('value' => 'flipInY', 'title' => 'flipInY'),
      array('value' => 'flipOutX', 'title' => 'flipOutX'),
      array('value' => 'flipOutY', 'title' => 'flipOutY'),
  )),
  array( 'title' => 'Lightspeed', 'optgroup' => array( 
      array('value' => 'lightSpeedIn', 'title' => 'lightSpeedIn'),
      array('value' => 'lightSpeedOut', 'title' => 'lightSpeedOut'),
  )),
  array( 'title' => 'Rotating Entrances', 'optgroup' => array( 
      array('value' => 'rotateIn', 'title' => 'rotateIn'),
      array('value' => 'rotateInDownLeft', 'title' => 'rotateInDownLeft'),
      array('value' => 'rotateInDownRight', 'title' => 'rotateInDownRight'),
      array('value' => 'rotateInUpLeft', 'title' => 'rotateInUpLeft'),
      array('value' => 'rotateInUpRight', 'title' => 'rotateInUpRight'),
  )),
  array( 'title' => 'Rotating Exits', 'optgroup' => array( 
      array('value' => 'rotateOut', 'title' => 'rotateOut'),
      array('value' => 'rotateOutDownLeft', 'title' => 'rotateOutDownLeft'),
      array('value' => 'rotateOutDownRight', 'title' => 'rotateOutDownRight'),
      array('value' => 'rotateOutUpLeft', 'title' => 'rotateOutUpLeft'),
      array('value' => 'rotateOutUpRight', 'title' => 'rotateOutUpRight'),
  )),
  array( 'title' => 'Sliding Entrances', 'optgroup' => array( 
      array('value' => 'slideInUp', 'title' => 'slideInUp'),
      array('value' => 'slideInDown', 'title' => 'slideInDown'),
      array('value' => 'slideInLeft', 'title' => 'slideInLeft'),
      array('value' => 'slideInRight', 'title' => 'slideInRight'),
  )),
  array( 'title' => 'Sliding Exits', 'optgroup' => array( 
      array('value' => 'slideOutUp', 'title' => 'slideOutUp'),
      array('value' => 'slideOutDown', 'title' => 'slideOutDown'),
      array('value' => 'slideOutLeft', 'title' => 'slideOutLeft'),
      array('value' => 'slideOutRight', 'title' => 'slideOutRight'),
  )),
  array( 'title' => 'Zoom Entrances', 'optgroup' => array( 
      array('value' => 'zoomIn', 'title' => 'zoomIn'),
      array('value' => 'zoomInDown', 'title' => 'zoomInDown'),
      array('value' => 'zoomInLeft', 'title' => 'zoomInLeft'),
      array('value' => 'zoomInRight', 'title' => 'zoomInRight'),
      array('value' => 'zoomInUp', 'title' => 'zoomInUp'),
  )),
  array( 'title' => 'Zoom Exits', 'optgroup' => array( 
      array('value' => 'zoomOut', 'title' => 'zoomOut'),
      array('value' => 'zoomOutDown', 'title' => 'zoomOutDown'),
      array('value' => 'zoomOutLeft', 'title' => 'zoomOutLeft'),
      array('value' => 'zoomOutRight', 'title' => 'zoomOutRight'),
      array('value' => 'zoomOutUp', 'title' => 'zoomOutUp'),
  )),
  array( 'title' => 'Specials', 'optgroup' => array( 
      array('value' => 'hinge', 'title' => 'hinge'),
      array('value' => 'jackInTheBox', 'title' => 'jackInTheBox'),
      array('value' => 'rollIn', 'title' => 'rollIn'),
      array('value' => 'rollOut', 'title' => 'rollOut'),
  )),
  
));

?>

<li class="wp-field wp-field-select <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="field-input" >

			<?php

			if ( $field['value'] ) {
				if ( $field['options']['multiple'] ) {

					$field['value'] = $field['value'];

				} else {

					$field['value'] = array( $field['value'] );

				}
			}

			?>
      
			<select style="<?php if ( $field['options']['min-width'] ) { echo 'min-width:' . $field['options']['min-width'] . ';'; } ?><?php if ( $field['options']['height'] ) { echo 'height:' . $field['options']['height'] . ';'; } ?><?php if ( $field['options']['style'] ) { echo $field['options']['style']; } ?>" name="<?php echo $field['id']; ?><?php if ( $field['options']['multiple'] ) { echo '[]'; } ?>" id="<?php echo $field['id']; ?>" style="<?php if( $field['fullwidth'] ) { echo 'width:100%'; } ?>" class="wp-field-value meta-field" <?php if ( $field['options']['multiple'] ) { echo 'multiple'; } ?>>
				
        <?php if ( $field['options']['empty'] ) { ?>

        	<option autocorrect="off" autocomplete="off" spellcheck="false" value="">No Animation</option>

        <?php } ?>

				<?php 
        	
        	if ( $field['options']['data'] ) { 
  					foreach ( $field['options']['data'] as $select_key => $select ) { 
  					
            	if ( isset ( $select['optgroup'] ) && $select['optgroup'] ) {
									
                echo '<optgroup label="' . $select['title'] . '">';
                
  								foreach ( $select['optgroup'] as $optgroup_key => $optgroup ) { 
                		
                    $selected = '';
										if ( $field['value'] && in_array( $optgroup['value'], $field['value'] ) ) $selected = 'selected="selected"';
        						echo '<option autocorrect="off" autocomplete="off" spellcheck="false" value="' . $optgroup['value'] . '" ' . $selected . ' >' . $optgroup['title'] . '</option>';      
						 
                  }
                
                echo '</optgroup>';
                
            	} else {
  
								$selected = '';
								if ( $field['value'] && in_array( $select['value'], $field['value'] ) ) $selected = 'selected="selected"';
        				echo '<option autocorrect="off" autocomplete="off" spellcheck="false" value="' . $select['value'] . '" ' . $selected . ' >' . $select['title'] . '</option>';      
						 
            	} 
        
            }
            
					} 
        
        ?>

			</select>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

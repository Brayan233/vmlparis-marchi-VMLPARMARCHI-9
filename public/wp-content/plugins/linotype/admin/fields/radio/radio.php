<?php

$default_options = array(
    "data" => array(),
    "display" => 'inline',
    "style" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-radio <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">
	
	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>	
	<?php } ?>
	
	<div class="field-content">
		
		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="field-input" >

			<?php if ( $field['options']['data'] ) { ?>

				<?php

				if ( isset( $field['options']['display'] ) && $field['options']['display'] == 'list' ) { 

					$display = '<br/>';
				
				} else {
					
					$display = '';
				
				}

				?>

				<?php foreach ( $field['options']['data'] as $data_key => $data ) { ?>
					
					<?php

					if ( $data['value'] == $field['value'] ) { 

						$checked = 'checked="checked"';
					
					} else {
						
						$checked = '';
					
					}

					// if ( ! $field['value'] && $data_key == 0 ){

					// 	$checked = 'checked="checked"';

					// }

					?>

					<label style="<?php if ( $field['options']['style'] ) { echo $field['options']['style']; } ?>" title="<?php echo $data['title']; ?>"><input name="<?php echo $field['id']; ?>" value="<?php echo $data['value']; ?>" <?php echo $checked; ?> type="radio" autocorrect="off" autocomplete="off" spellcheck="false" > <?php if ( isset( $data['img'] ) ) { echo '<img style="margin-bottom:10px;margin-right:10px;border:1px solid #DDD;" src="' . $data['img'] . '">'; } ?><span><?php echo $data['title']; ?></span></label><?php echo $display; ?>
	          
				<?php } ?>

			<?php } ?>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>
	
	</div>

</li>
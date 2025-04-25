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

$image_sizes = get_intermediate_image_sizes();
if ( $image_sizes ) {
  foreach( $image_sizes as $image_size ){
    array_push( $field['options']['data'], array( "value"=> $image_size, "title"=> $image_size ) );
  }
}

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

			<select style="width:100%;<?php if ( $field['options']['min-width'] ) { echo 'min-width:' . $field['options']['min-width'] . ';'; } ?><?php if ( $field['options']['height'] ) { echo 'height:' . $field['options']['height'] . ';'; } ?><?php if ( $field['options']['style'] ) { echo $field['options']['style']; } ?>" name="<?php echo $field['id']; ?><?php if ( $field['options']['multiple'] ) { echo '[]'; } ?>" id="<?php echo $field['id']; ?>" style="<?php if( $field['fullwidth'] ) { echo 'width:100%'; } ?>" class="wp-field-value meta-field" <?php if ( $field['options']['multiple'] ) { echo 'multiple'; } ?>>
				
        <?php if ( $field['options']['empty'] ) { ?>

        	<option autocorrect="off" autocomplete="off" spellcheck="false" value="">-</option>

        <?php } ?>

				<?php if ( $field['options']['data'] ) { ?>
					<?php foreach ( $field['options']['data'] as $select_key => $select ) { ?>

						<?php
						$selected = '';
						if ( $field['value'] && in_array( $select['value'], $field['value'] ) ) $selected = 'selected="selected"';
						?>
        
						<option autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $select['value']; ?>" <?php echo $selected; ?> ><?php echo $select['title']; ?></option>

					<?php } ?>
				<?php } ?>

			</select>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

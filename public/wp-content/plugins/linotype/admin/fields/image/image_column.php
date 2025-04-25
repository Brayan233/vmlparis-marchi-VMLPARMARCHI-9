<?php 

$default_options = array(
    "output" => "url",
    "height" => "",
    "wrapstyle" => "",
    "imgstyle" => "",
    "input" => false,
    "placeholder" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<?php if ( isset($field['options']['output']) && $field['options']['output'] == "url" ) { ?>
	
	<?php if ( $field['value'] ) { ?>
	
		<img width="100%" src="<?php echo $field['value']; ?>"/>
	
	<?php } ?>
	
<?php } else { ?>
	
	<?php $image_src = wp_get_attachment_image_src( $field['value'], 'full' ); ?>

	<?php if ( $image_src ) { ?>

		<img width="100%" src="<?php echo $image_src[0]; ?>" />

	<?php } ?>

<?php } ?>
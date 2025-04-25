<?php

if ( ! class_exists('linoadmin_repeater') ) include dirname( __FILE__ ) . '/linoadmin_repeater.class.php';

$default_options = array(
	"templates" => array(),
);

$field['options'] = handypress_helper::parse_args( $field['options'], $default_options );

$path = array( 
	'dir' =>  dirname( __FILE__ ),
	'url' => str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ )  ),
);

_HANDYLOG('$path',$path);

$linoadmin_repeater = new linoadmin_repeater( $field['options'], $path );

?>

<li class="wp-field wp-field-repeater" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>	
	<?php } ?>

	<div class="field-content" style="width: 100%;max-width: 100%;">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<?php echo $linoadmin_repeater->get(); ?>

		<div class="field-input" style="display:block;" >
			<textarea style="display: block;height:100px;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value"><?php echo stripslashes( $field['value'] ); ?></textarea>
		</div>

		<br/>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>
	
	</div>

</li>

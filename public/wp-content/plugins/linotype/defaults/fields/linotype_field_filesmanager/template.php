<?php

wp_enqueue_style( 'jstree', '//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/themes/default/style.min.css', false, false, 'screen' );
wp_enqueue_style( 'wp-field-filesmanager', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );

wp_enqueue_script( 'jstree', '//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.3/jstree.min.js', array( 'jquery' ), false, true );
wp_enqueue_script( 'wp-field-filesmanager', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array( 'jquery' ), false, true );


$default_options = array(
    "dir" => "/",
    "url" => "/",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( substr( $field['options']['dir'], 0, 1 ) !== '/') $field['options']['dir'] = ABSPATH . $field['options']['dir'];

if ( is_array( $field['value'] ) ) $field['value'] =  json_encode( $field['value'] );
if ( $field['value'] ) $field['value'] =  stripslashes( $field['value'] );

?>

<li class="wp-field wp-field-filesmanager <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>
    
    
    
    <div id="container" role="main" >
			<div id="tree" data-dir="<?php echo $field['options']['dir']; ?>"></div>
			<div id="data">
				<div class="content code" style="display:none;"><textarea id="code" readonly="readonly"></textarea></div>
				<div class="content folder" style="display:none;"></div>
				<div class="content image" style="display:none; position:relative;"><img src="" alt="" style="display:block; position:absolute; left:50%; top:50%; padding:0; max-height:90%; max-width:90%;" /></div>
				<div class="content default" style="text-align:center;">Select a file from the tree.</div>
			</div>
		</div>
    
    

		<div class="field-input" style="display:none;">
			<textarea style="border: 1px solid #ddd;box-shadow: inset 0 1px 2px rgba(0,0,0,.07);background-color: #fff;color: #32373c;outline: 0;transition: 50ms border-color ease-in-out;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" /><?php echo stripslashes( $field['value'] ); ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

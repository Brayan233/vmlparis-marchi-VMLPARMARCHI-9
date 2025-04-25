<?php

wp_enqueue_style( 'codemirror-css', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/lib/codemirror.css', false, false, 'screen' );
wp_enqueue_script('codemirror-codemirror', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/lib/codemirror.js', array('jquery'), '1.0', true );

wp_enqueue_script('codemirror-search', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/addon/search/search.js', array('jquery'), '1.0', true );
wp_enqueue_script('codemirror-searchcursor', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/addon/search/searchcursor.js', array('jquery'), '1.0', true );
wp_enqueue_script('codemirror-jump-to-line', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/addon/search/jump-to-line.js', array('jquery'), '1.0', true );
wp_enqueue_script('codemirror-dialog', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/addon/dialog/dialog.js', array('jquery'), '1.0', true );
wp_enqueue_style( 'codemirror-dialog', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/addon/dialog/dialog.css', false, false, 'screen' );

wp_enqueue_script('codemirror-mode-xml', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/mode/xml/xml.js', array('jquery'), '1.0', true );
wp_enqueue_script('codemirror-mode-javascript', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/mode/javascript/javascript.js', array('jquery'), '1.0', true );
wp_enqueue_script('codemirror-mode-css', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/mode/css/css.js', array('jquery'), '1.0', true );
wp_enqueue_script('codemirror-mode-clike', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/mode/clike/clike.js', array('jquery'), '1.0', true );
wp_enqueue_script('codemirror-mode-php', LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/mode/php/php.js', array('jquery'), '1.0', true );

wp_enqueue_style('codemirror-theme' , LINOTYPE_plugin::$plugin['url'] . 'lib/codemirror/theme/material.css', false, false, 'screen' );

wp_enqueue_style( 'wp-field-code', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style.css', false, false, 'screen' );

wp_enqueue_script( 'wp-field-code', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/script.js', array( 'jquery' ), false, true );


$default_options = array(
	"type" => "html",
	"fullscreen" => false,
    "height"  => ""
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-code  <?php if( $field['fullscreen'] || $field['options']['fullscreen'] ) { echo 'fullscreen'; } ?>" style="display: block;height:<?php echo $field['options']['height']; ?>;padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="panels">

			<div class="panel panel--one">

				<div class="panel__header handle handle--one">

					<div class="panel__toolbar">

						<div class="panel__toolbar_left">
							<span><?php echo $field['options']['type']; ?></span>
							
						</div>

						<div class="panel__toolbar_right">
									
						</div>

					</div>
				
				</div>

				<div class="linotype-editor-code" style="visibility:hidden">

					<div class="linotype-editor-code-sidebar"></div>

					<textarea class="wp-field-code_content code-<?php echo $field['options']['type']; ?>" data-language="<?php echo $field['options']['type']; ?>" name="<?php echo $field['id']; ?>"><?php echo $field['value']; ?></textarea>
					
				</div>

			</div>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

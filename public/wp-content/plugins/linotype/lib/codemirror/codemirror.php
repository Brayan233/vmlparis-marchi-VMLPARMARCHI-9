<?php

wp_enqueue_style( 'codemirror-lib-css', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/lib/codemirror.css', false, false, 'screen' );
wp_enqueue_script('codemirror-lib-codemirror', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/lib/codemirror.js', array('jquery'), '1.0', true );

$default_options = array(
		"mode" 			=> 'html',
    "theme" 		=> "material",
    "overlay" 		=> true,
    "closetag" 		=> true,
    "fullscreen" 	=> true,
    "autoheight" 	=> true,
    "autocomplete" 	=> true,
    "fullscreen" 	=> true,
    "txtstyle" 		=> "",
    "placeholder" 	=> "",
    "height" 		=> "300px",
    "shadow"		=> true,
    "file"			=> null,
    "toolbar" 		=> true,
    "readOnly" 		=> false,
		"message" => "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

//$field['value'] = stripslashes( $field['value'] );

if ( $field['options']['file'] && wp_mkdir_p( dirname( $field['options']['file'] ) ) ) {

	if ( $field['value'] ) {

		if ( $field['options']['mode'] == 'scss' ){

			file_put_contents( str_replace( '.css', '.scss', $field['options']['file'] ), $field['value'] );

			require_once( dirname( __FILE__ ) . '/scssphp/scss.inc.php' );

			try {

				$scss = new scssc();
				$css = $scss->compile( $field['value'] );

				file_put_contents( $field['options']['file'], $css );

			} catch (Exception $e) {

				$field['options']['message'] = '<div class="message error"> Error : scss parse </div>';

			}

		} else {

			file_put_contents( $field['options']['file'], $field['value'] );

		}

	} else if ( file_exists( $field['options']['file'] ) ) {

		unlink( $field['options']['file'] );

	}


	if ( $field['options']['mode'] == 'scss' ) $field['options']['mode'] = 'css';
	if ( $field['options']['mode'] == 'js' ) $field['options']['mode'] = 'javascript';

}

$field['value'] = htmlspecialchars( $field['value'] );

foreach ( $field['options'] as $option_key => $option ) {

	if ( isset( $option ) ) {

		switch ( $option_key ) {

			case 'mode':

				if ( $option == 'html' ) {

					wp_enqueue_script('codemirror-mode-xml', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/xml/xml.js', array('jquery'), '1.0', true );
					wp_enqueue_script('codemirror-mode-javascript', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/javascript/javascript.js', array('jquery'), '1.0', true );
					wp_enqueue_script('codemirror-mode-css', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/css/css.js', array('jquery'), '1.0', true );
					wp_enqueue_script('codemirror-mode-htmlmixed', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/htmlmixed/htmlmixed.js', array('jquery'), '1.0', true );

				}

				if ( $option == 'php' ) {

					wp_enqueue_script('codemirror-mode-xml', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/xml/xml.js', array('jquery'), '1.0', true );
					wp_enqueue_script('codemirror-mode-javascript', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/javascript/javascript.js', array('jquery'), '1.0', true );
					wp_enqueue_script('codemirror-mode-css', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/css/css.js', array('jquery'), '1.0', true );
					wp_enqueue_script('codemirror-mode-clike', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/clike/clike.js', array('jquery'), '1.0', true );
					wp_enqueue_script('codemirror-mode-php', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/php/php.js', array('jquery'), '1.0', true );


				}

				if ( $option == 'javascript' || $option == 'json' ) {

					wp_enqueue_script('codemirror-mode-javascript', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/javascript/javascript.js', array('jquery'), '1.0', true );

				}

				if ( $option == 'css' ) {

					wp_enqueue_script('codemirror-mode-css', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/mode/css/css.js', array('jquery'), '1.0', true );

				}


			break;

			case 'theme':

				if ( $option ) {

					wp_enqueue_style(	'codemirror-theme' , str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/theme/'. $option .'.css', false, false, 'screen' );

				}

			break;

			case 'autocomplete':

		 		if ( $option ) {

		 			wp_enqueue_style(	'codemirror-show-hint' , str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/addon/hint/show-hint.css', false, false, 'screen' );
					wp_enqueue_script( 	'codemirror-show-hint' , str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/addon/hint/show-hint.js', array('jquery'), '1.0', true );
					wp_enqueue_script( 	'codemirror-anyword-hint' , str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/addon/hint/anyword-hint.js', array('jquery'), '1.0', true );

				}

			break;

			case 'closetag':

		 		if ( $option ) {

					wp_enqueue_script( 	'codemirror-xml-fold' , str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/addon/fold/xml-fold.js', array('jquery'), '1.0', true );
					wp_enqueue_script( 	'codemirror-closetag' , str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/addon/edit/closetag.js', array('jquery'), '1.0', true );

				}

			break;

			case 'overlay':

		 		if ( $option ) {

					wp_enqueue_script( 	'codemirror-overlay' , str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/addon/mode/overlay.js', array('jquery'), '1.0', true );

				}

			break;

			case 'fullscreen':

				if ( $option ) {

					wp_enqueue_style(	'codemirror-fullscreen' , str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/addon/display/fullscreen.css', false, false, 'screen' );
					wp_enqueue_script( 	'codemirror-fullscreen' , str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/addon/display/fullscreen.js', array('jquery'), '1.0', true );

				}

			break;

			case 'placeholder':

				if ( $option ) {

					wp_enqueue_script( 	'codemirror-placeholder' , str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/CodeMirror/addon/display/placeholder.js', array('jquery'), '1.0', true );

				}

			break;

		}

	}

}

wp_enqueue_style( 'field-codemirror', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/codemirror.css', false, false, 'screen' );
wp_enqueue_script('field-codemirror', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/codemirror.js', array('jquery'), '1.0', true );

?>

<li class="wp-field wp-field-codemirror <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?> <?php if( $field['padding'] == "0px" ) { echo 'zero-border'; } ?>" wp-field-id="<?php echo $field['id']; ?>" wp-field-option-mode="<?php echo $field['options']['mode']; ?>" wp-field-option-theme="<?php echo $field['options']['theme']; ?>" wp-field-option-overlay="<?php if( $field['options']['overlay'] ) { echo "true"; } ?>" wp-field-option-closetag="<?php if( $field['options']['closetag'] ) { echo "true"; } ?>" wp-field-option-fullscreen="<?php if( $field['options']['fullscreen'] ) { echo "true"; } ?>" wp-field-option-autoheight="<?php if( $field['options']['autoheight'] ) { echo "true"; } ?>" wp-field-option-autocomplete="<?php if( $field['options']['autocomplete'] ) { echo "true"; } ?>" wp-field-option-readonly="<?php if( $field['options']['readOnly'] ) { echo "true"; } ?>"  style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['options']['readOnly'] ) {
	echo '<div style="position: absolute; top: 36px; left: 0px; bottom: 0px; bottom: 0px; right: 0px; z-index: 20; background-color: rgba(255, 255, 255, 0.80);">' . $field['options']['readOnly'] . '</div>';
	 } ?>

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<span class="spinner"></span>

	<div class="field-content" style="visibility:hidden;">

		<?php if ( $field['options']['autoheight'] ) {

			echo '<style type="text/css">';

				echo '.wp-field-codemirror[wp-field-id="' . $field['id'] . '"] .CodeMirror {';

					echo 'height:auto!important;';

					if ( $field['options']['height'] ) echo 'min-height:' . $field['options']['height'] . '!important;';

				echo '}';

				echo '.wp-field-codemirror[wp-field-id="' . $field['id'] . '"] .CodeMirror .CodeMirror-scroll {';

					if ( $field['options']['height'] ) echo 'min-height:' . $field['options']['height'] . '!important;';

				echo '}';

			echo '</style>';

		} else {

			echo '<style type="text/css">';

				echo '.wp-field-codemirror[wp-field-id="' . $field['id'] . '"] .CodeMirror {';

					if ( $field['options']['height'] ) echo 'height:' . $field['options']['height'] . '!important;';

				echo '}';

			echo '</style>';

		}

		?>

		<div class="field-input">
			<div class="<?php if ( $field['options']['shadow'] ) echo 'field-shadow'; ?>">

				<?php if ( $field['options']['toolbar'] ) { ?>
					<div class="CodeMirror-toolbar"><?php if( $field['options']['mode'] ) { echo $field['options']['mode']; } ?></div>
				<?php } ?>

				<?php if( $field['options']['message'] ) { echo $field['options']['message']; } ?>

				<div class="codemirror-scroll-x">

					<textarea style="<?php if( $field['options']['txtstyle'] ) { echo $field['options']['txtstyle']; } ?>" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" <?php if( $field['options']['placeholder'] ) { echo 'placeholder="' . $field['options']['placeholder'] . '"'; } ?> ><?php echo $field['value']; ?></textarea>

				</div>

			</div>
		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

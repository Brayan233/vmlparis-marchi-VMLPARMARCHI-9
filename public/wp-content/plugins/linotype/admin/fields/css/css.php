<?php

//wp_enqueue_script('json-2', LINOADMIN::$ADMIN['url'] . '/fields/js/json2/json2.js', array('jquery'), '1.0', true );

wp_enqueue_style( 'field-css', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/css.css', false, false, 'screen' );
wp_enqueue_script('field-css', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/css.js', array('jquery'), '1.0', true );

if( is_string($field['value']) && ( is_object( json_decode($field['value']) ) || is_array( json_decode($field['value']) ) ) ) {

	$VALUE = json_decode( $field['value'], true);
	$VALUE = $VALUE[0];

} else {

	$VALUE = array();
	$VALUE_css = explode(';', $field['value'] );

	foreach ( $VALUE_css as $key => $css) {

		$css_part = explode(':', $css,2);

		$VALUE[trim($css_part[0])] = trim($css_part[1]);

	}

}

$CSS = $field['css'];

if( ! isset($CSS) ) {

	$CSS = array(
		'size','margin','padding','position','display','float','clear','visibility','overflow','z-index',
		'background','shadow','opacity','border','radius',
		'color','font-size','line-height','vertical-align','font-style','font-weight','text-decoration','font-variant','text-transform','font-family','text-align','text-indent','letter-spacing','word-spacing','white-space','text-shadow'
	);

}

?>

<li class="wp-field wp-field-css <?php echo $field['column']; ?>" wp-field-id="<?php echo $field['id']; ?>" wp-field-output="<?php echo $field['output']; ?>" style="display: none;">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content" style="visibility:visible;">

		<div class="css-panel">

			<div class="css-tab">

				<ul><!--
					--><li class="css-layout-bt" target=".css-layout-content"><span>layout</span></li><!--
					--><li class="css-decoration-bt" target=".css-decoration-content"><span>decoration</span></li><!--
					--><li class="css-text-bt" target=".css-text-content"><span>text</span></li><!--
				--></ul>

			</div>

			<div class="css-tab-content">

				<ul class="css-layout-content hidden"><!--

					--><?php if ( array_search('size', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Size</div><!--
						--><div class="width col-50"><div class="css-input"><input class="value-css" css="width" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['width']; ?>"></div></div><!--
						--><div class="height col-50"><div class="css-input"><input class="value-css" css="height" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['height']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('margin', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Margin</div><!--
						--><div class="margin-left col-25"><div class="css-input"><input class="value-css" css="margin-left" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['margin-left']; ?>"></div></div><!--
						--><div class="margin-right col-25"><div class="css-input"><input class="value-css" css="margin-right" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['margin-right']; ?>"></div></div><!--
						--><div class="margin-top col-25"><div class="css-input"><input class="value-css" css="margin-top" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['margin-top']; ?>"></div></div><!--
						--><div class="margin-bottom col-25"><div class="css-input"><input class="value-css" css="margin-bottom" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['margin-bottom']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('padding', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Padding</div><!--
						--><div class="padding-left col-25"><div class="css-input"><input class="value-css" css="padding-left" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['padding-left']; ?>"></div></div><!--
						--><div class="padding-right col-25"><div class="css-input"><input class="value-css" css="padding-right" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['padding-right']; ?>"></div></div><!--
						--><div class="padding-top col-25"><div class="css-input"><input class="value-css" css="padding-top" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['padding-top']; ?>"></div></div><!--
						--><div class="padding-bottom col-25"><div class="css-input"><input class="value-css" css="padding-bottom" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['padding-bottom']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('position', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Position</div><!--
						--><div class="position col-100"><div class="css-input"><input class="value-css" css="position" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['position']; ?>"></div></div><!--
						--><div class="left col-50"><div class="css-input"><input class="value-css" css="left" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['left']; ?>"></div></div><!--
						--><div class="right col-50"><div class="css-input"><input class="value-css" css="right" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['right']; ?>"></div></div><!--
						--><div class="top col-50"><div class="css-input"><input class="value-css" css="top" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['top']; ?>"></div></div><!--
						--><div class="bottom col-50"><div class="css-input"><input class="value-css" css="bottom" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['bottom']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('display', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Display</div><!--
						--><div class="display col-100"><div class="css-input"><input class="value-css" css="display" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['display']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('float', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Float</div><!--
						--><div class="float col-100"><div class="css-input"><input class="value-css" css="float" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['float']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('clear', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Clear</div><!--
						--><div class="clear col-100"><div class="css-input"><input class="value-css" css="clear" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['clear']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('visibility', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Visibility</div><!--
						--><div class="visibility col-100"><div class="css-input"><input class="value-css" css="visibility" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['visibility']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('overflow', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Overflow</div><!--
						--><div class="overflow col-100"><div class="css-input"><input class="value-css" css="overflow" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['overflow']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('z-index', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Z-Index</div><!--
						--><div class="z-index col-100"><div class="css-input"><input class="value-css" css="z-index" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['z-index']; ?>"></div></div><!--
					--></li><?php } ?><!--

				--></ul>

				<ul class="css-decoration-content hidden"><!--

					--><?php if ( array_search('background', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Background</div><!--
						--><div class="background-image col-50"><div class="css-input"><input class="value-css" css="background-image" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['background-image']; ?>"></div></div><!--
						--><div class="background-color col-50"><div class="css-input"><input class="value-css" css="background-color" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['background-color']; ?>"></div></div><!--
						--><div class="background-position-x col-50"><div class="css-input"><input class="value-css" css="background-position-x" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['background-position-x']; ?>"></div></div><!--
						--><div class="background-position-y col-50"><div class="css-input"><input class="value-css" css="background-position-y" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['background-position-y']; ?>"></div></div><!--
						--><div class="background-repeat col-50"><div class="css-input"><input class="value-css" css="background-repeat" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['background-repeat']; ?>"></div></div><!--
						--><div class="background-attachment col-50"><div class="css-input"><input class="value-css" css="background-attachment" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['background-attachment']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('shadow', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Shadow</div><!--
						--><div class="shadow-color col-50"><div class="css-input"><input class="value-css" css="shadow-color" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['shadow-color']; ?>"></div></div><!--
						--><div class="shadow-blur col-50"><div class="css-input"><input class="value-css" css="shadow-blur" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['shadow-blur']; ?>"></div></div><!--
						--><div class="shadow-offset-x col-50"><div class="css-input"><input class="value-css" css="shadow-offset-x" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['shadow-offset-x']; ?>"></div></div><!--
						--><div class="shadow-offset-y col-50"><div class="css-input"><input class="value-css" css="shadow-offset-y" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['shadow-offset-y']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('opacity', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Opacity</div><!--
						--><div class="opacity col-100"><div class="css-input"><input class="value-css" css="opacity" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['opacity']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('border', $CSS ) !== false ) { ?><li><!--

						--><div class="label">Border left</div><!--
						--><div class="border-left-style col-33"><div class="css-input"><input class="value-css" css="border-left-style" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-left-style']; ?>"></div></div><!--
						--><div class="border-left-color col-33"><div class="css-input"><input class="value-css" css="border-left-color" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-left-color']; ?>"></div></div><!--
						--><div class="border-left-width col-33 last"><div class="css-input"><input class="value-css" css="border-left-width" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-left-width']; ?>"></div></div><!--

						--><div class="label">Border right</div><!--
						--><div class="border-right-style col-33"><div class="css-input"><input class="value-css" css="border-right-style" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-right-style']; ?>"></div></div><!--
						--><div class="border-right-color col-33"><div class="css-input"><input class="value-css" css="border-right-color" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-right-color']; ?>"></div></div><!--
						--><div class="border-right-width col-33 last"><div class="css-input"><input class="value-css" css="border-right-width" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-right-width']; ?>"></div></div><!--

						--><div class="label">Border top</div><!--
						--><div class="border-top-style col-33"><div class="css-input"><input class="value-css" css="border-top-style" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-top-style']; ?>"></div></div><!--
						--><div class="border-top-color col-33"><div class="css-input"><input class="value-css" css="border-top-color" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-top-color']; ?>"></div></div><!--
						--><div class="border-top-width col-33 last"><div class="css-input"><input class="value-css" css="border-top-width" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-top-width']; ?>"></div></div><!--

						--><div class="label">Border bottom</div><!--
						--><div class="border-bottom-style col-33"><div class="css-input"><input class="value-css" css="border-bottom-style" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-bottom-style']; ?>"></div></div><!--
						--><div class="border-bottom-color col-33"><div class="css-input"><input class="value-css" css="border-bottom-color" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-bottom-color']; ?>"></div></div><!--
						--><div class="border-bottom-width col-33 last"><div class="css-input"><input class="value-css" css="border-bottom-width" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-bottom-width']; ?>"></div></div><!--

					--></li><?php } ?><!--

					--><?php if ( array_search('radius', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Radius</div><!--
						--><div class="border-top-left-radius col-50"><div class="css-input"><input class="value-css" css="border-top-left-radius" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-top-left-radius']; ?>"></div></div><!--
						--><div class="border-top-right-radius col-50"><div class="css-input"><input class="value-css" css="border-top-right-radius" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-top-right-radius']; ?>"></div></div><!--
						--><div class="border-bottom-left-radius col-50"><div class="css-input"><input class="value-css" css="border-bottom-left-radius" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-bottom-left-radius']; ?>"></div></div><!--
						--><div class="border-bottom-right-radius col-50"><div class="css-input"><input class="value-css" css="border-bottom-right-radius" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['border-bottom-right-radius']; ?>"></div></div><!--
					--></li><?php } ?><!--

				--></ul>

				<ul class="css-text-content hidden"><!--

					--><?php if ( array_search('color', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Color</div><!--
						--><div class="color col-100"><div class="css-input"><input class="value-css" css="color" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['color']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('font-size', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Font Size</div><!--
						--><div class="font-size col-100"><div class="css-input"><input class="value-css" css="font-size" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['font-size']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('line-height', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Line Height</div><!--
						--><div class="line-height col-100"><div class="css-input"><input class="value-css" css="line-height" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['line-height']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('vertical-align', $CSS ) !== false ) { ?><li><!--
						--><div class="label">vertical-align</div><!--
						--><div class="vertical-align col-100"><div class="css-input"><input class="value-css" css="vertical-align" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['vertical-align']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('font-family', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Font Family</div><!--
						--><div class="font-family col-100"><div class="css-input"><input class="value-css" css="font-family" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['font-family']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('font-style', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Font Style</div><!--
						--><div class="font-style col-100"><div class="css-input"><input class="value-css" css="font-style" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['font-style']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('font-weight', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Font Weight</div><!--
						--><div class="font-weight col-100"><div class="css-input"><input class="value-css" css="font-weight" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['font-weight']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('text-decoration', $CSS ) !== false ) { ?><li><!--
						--><div class="label">Text Decoration</div><!--
						--><div class="text-decoration col-100"><div class="css-input"><input class="value-css" css="text-decoration" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['text-decoration']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('font-variant', $CSS ) !== false ) { ?><li><!--
						--><div class="label">font-variant</div><!--
						--><div class="font-variant col-100"><div class="css-input"><input class="value-css" css="font-variant" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['font-variant']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('text-transform', $CSS ) !== false ) { ?><li><!--
						--><div class="label">text-transform</div><!--
						--><div class="text-transform col-100"><div class="css-input"><input class="value-css" css="text-transform" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['text-transform']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('text-align', $CSS ) !== false ) { ?><li><!--
						--><div class="label">text-align</div><!--
						--><div class="text-align col-100"><div class="css-input"><input class="value-css" css="text-align" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['text-align']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('text-indent', $CSS ) !== false ) { ?><li class="col-25"><!--
						--><div class="label">text-indent</div><!--
						--><div class="text-indent col-100"><div class="css-input"><input class="value-css" css="text-indent" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['text-indent']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('letter-spacing', $CSS ) !== false ) { ?><li class="col-25"><!--
						--><div class="label">letter-spacing</div><!--
						--><div class="letter-spacing"><div class="css-input"><input class="value-css" css="letter-spacing" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['letter-spacing']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('word-spacing', $CSS ) !== false ) { ?><li class="col-25"><!--
						--><div class="label">word-spacing</div><!--
						--><div class="word-spacing"><div class="css-input"><input class="value-css" css="word-spacing" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['word-spacing']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('white-space', $CSS ) !== false ) { ?><li class="col-25"><!--
						--><div class="label">white-space</div><!--
						--><div class="white-space"><div class="css-input"><input class="value-css" css="white-space" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['white-space']; ?>"></div></div><!--
					--></li><?php } ?><!--

					--><?php if ( array_search('text-shadow', $CSS ) !== false ) { ?><li class="col-100"><!--
						--><div class="label">Shadow</div><!--
						--><div class="text-shadow-color col-25"><div class="css-input"><input class="value-css" css="text-shadow-color" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['text-shadow-color']; ?>"></div></div><!--
						--><div class="text-shadow-blur col-25"><div class="css-input"><input class="value-css" css="text-shadow-blur" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['text-shadow-blur']; ?>"></div></div><!--
						--><div class="text-shadow-offset-x col-25"><div class="css-input"><input class="value-css" css="text-shadow-offset-x" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['text-shadow-offset-x']; ?>"></div></div><!--
						--><div class="text-shadow-offset-y col-25"><div class="css-input"><input class="value-css" css="text-shadow-offset-y" type="text" autocorrect="off" autocomplete="off" spellcheck="false" value="<?php echo $VALUE['text-shadow-offset-y']; ?>"></div></div><!--
					--></li><?php } ?><!--

				--></ul>

			</div>

			<div class="wp-field-css-header">

				<div class="wp-field-css-header-title"></div>
				<div class="wp-field-css-value-bt"></div>

				<div class="field-input wp-field-css-value">
					<textarea style="display: block;height:<?php echo $field['height']; ?>;font-size: <?php echo $field['font-size']; ?>;" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" autocorrect="off" autocomplete="off" spellcheck="false" ><?php echo $field['value']; ?></textarea>
				</div>

			</div>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

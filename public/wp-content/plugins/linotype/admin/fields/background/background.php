<?php

wp_enqueue_media();

wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script( 'wp-color-picker-alpha', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.2', true );

wp_enqueue_style( 'field-background', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/background.css', false, false, 'screen' );
wp_enqueue_script('field-background', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/background.js', array('jquery'), '1.0', true );

$default_options = array();

$field['options'] = wp_parse_args( $field['options'], $default_options );

?>

<li class="wp-field wp-field-background <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php

		$bg_color = '';
		$bg_image = '';
		$bg_repeat = '';
		$bg_posx = '';
		$bg_posy = '';
		$bg_size = '';
		$bg_attachment = '';
		$bg_origin = '';
		$css = '';

		if ( $field['value'] ) {

			$field['value'] = stripslashes( $field['value'] );

			$VALUE = json_decode( $field['value'], true );

			if ( isset( $VALUE[0]['background-color'] ) ) $bg_color = $VALUE[0]['background-color'];
			if ( isset( $VALUE[0]['background-image'] ) ) $bg_image = $VALUE[0]['background-image'];
			if ( isset( $VALUE[0]['background-repeat'] ) ) $bg_repeat = $VALUE[0]['background-repeat'];
			if ( isset( $VALUE[0]['background-posx'] ) ) $bg_posx = $VALUE[0]['background-posx'];
			if ( isset( $VALUE[0]['background-posy'] ) ) $bg_posy = $VALUE[0]['background-posy'];
			if ( isset( $VALUE[0]['background-size'] ) ) $bg_size = $VALUE[0]['background-size'];
			if ( isset( $VALUE[0]['background-attachment'] ) ) $bg_attachment = $VALUE[0]['background-attachment'];
			if ( isset( $VALUE[0]['background-origin'] ) ) $bg_origin = $VALUE[0]['background-origin'];

			$css = $VALUE[0]['css'];

		}

		?>

		<div class="field-background">

			<div class="field-background-preview-bg"><div class="field-background-preview" style="<?php echo $css; ?>"></div></div>

			<div class="field-background-panel" style="width:300px">

				<div class="background-params-field">
					<span>color</span>
					<div class="field-background-action">
						<input data-alpha="true" data-alpha="true" type="text" class="background-params background-color color-picker" value="<?php echo $bg_color; ?>" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="">
					</div>
				</div>

				<div class="background-params-field">
					<span>image</span>
					<div class="field-background-action">
						<div class="field-background-setter field-background-setter-image button fullwidth button-small" data-set="">select</div>
						<div class="field-background-setter field-background-setter-dummy button fullwidth button-small button-primary button-primary <?php if( !$bg_image ){ echo 'hide'; } ?>" data-set=""><?php echo basename($bg_image); ?> x</div>
					</div>
					<input type="text" class="background-params background-image hide" value="<?php echo $bg_image; ?>" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="">
				</div>

				<div class="background-params-field">
					<span>repeat</span>
					<div class="field-background-action button-grouped grouped-4">
						<div class="field-background-setter button button-small <?php if( $bg_repeat == "repeat" ){ echo 'button-primary'; } ?>" data-set="repeat">repeat</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_repeat == "repeat-x" ){ echo 'button-primary'; } ?>" data-set="repeat-x">repeat-x</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_repeat == "repeat-y" ){ echo 'button-primary'; } ?>" data-set="repeat-y">repeat-y</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_repeat == "no-repeat" ){ echo 'button-primary'; } ?>" data-set="no-repeat">no-repeat</div>
					</div>
					<input type="text" class="background-params background-repeat hide" value="<?php echo $bg_repeat; ?>" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="">
				</div>

				<div class="background-params-field">
					<span>position X</span>
					<div class="field-background-action button-grouped grouped-4">
						<div class="field-background-setter button button-small <?php if( $bg_posx == "left" ){ echo 'button-primary'; } ?>" data-set="left">left</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_posx == "center" ){ echo 'button-primary'; } ?>" data-set="center">center</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_posx == "right" ){ echo 'button-primary'; } ?>" data-set="right">right</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_posx && ( $bg_posx !== "left" && $bg_posx !== "center" && $bg_posx !== "right" ) ){ echo 'button-primary'; } ?>" data-set="">custom</div>
					</div>
					<input type="text" class="background-params background-posx <?php if( $bg_posx == "" || ( $bg_posx == "left" || $bg_posx == "center" || $bg_posx == "right" ) ){ echo 'hide'; } ?>" value="<?php echo $bg_posx; ?>" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="">
				</div>

				<div class="background-params-field">
					<span>position Y</span>
					<div class="field-background-action button-grouped grouped-4">
						<div class="field-background-setter button button-small <?php if( $bg_posy == "top" ){ echo 'button-primary'; } ?>" data-set="top">top</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_posy == "center" ){ echo 'button-primary'; } ?>" data-set="center">center</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_posy == "bottom" ){ echo 'button-primary'; } ?>" data-set="bottom">bottom</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_posy && ( $bg_posy !== "top" && $bg_posy !== "center" && $bg_posy !== "bottom" ) ){ echo 'button-primary'; } ?>" data-set="">custom</div>
					</div>
					<input type="text" class="background-params background-posy <?php if( $bg_posy == "" || ( $bg_posy == "top" || $bg_posy == "center" || $bg_posy == "bottom" ) ){ echo 'hide'; } ?>" value="<?php echo $bg_posy; ?>" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="">
				</div>

				<div class="background-params-field">
					<span>size</span>
					<div class="field-background-action button-grouped grouped-4">
						<div class="field-background-setter button button-small <?php if( $bg_size == "auto" ){ echo 'button-primary'; } ?>" data-set="auto">auto</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_size == "cover" ){ echo 'button-primary'; } ?>" data-set="cover">cover</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_size == "contain" ){ echo 'button-primary'; } ?>" data-set="contain">contain</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_size && ( $bg_size !== "auto" && $bg_size !== "cover" && $bg_size !== "contain" ) ){ echo 'button-primary'; } ?>" data-set="">custom</div>
					</div>
					<input type="text" class="background-params background-size <?php if( $bg_size == "" || ( $bg_size == "auto" || $bg_size == "cover" || $bg_size == "contain" ) ){ echo 'hide'; } ?>" value="<?php echo $bg_size; ?>" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="">
				</div>

				<div class="background-params-field">
					<span>attachment</span>
					<div class="field-background-action button-grouped grouped-3">
						<div class="field-background-setter button button-small <?php if( $bg_attachment == "scroll" ){ echo 'button-primary'; } ?>" data-set="scroll">scroll</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_attachment == "fixed" ){ echo 'button-primary'; } ?>" data-set="fixed">fixed</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_attachment == "local" ){ echo 'button-primary'; } ?>" data-set="local">local</div>
					</div>
					<input type="text" class="background-params background-attachment hide" value="<?php echo $bg_attachment; ?>" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="">
				</div>

				<?php
				/*
				<div class="background-params-field">
					<span>origin</span>
					<div class="field-background-action button-grouped grouped-4">
						<div class="field-background-setter button button-small <?php if( $bg_origin == "inherit" ){ echo 'button-primary'; } ?>" data-set="inherit">inherit</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_origin == "border-box" ){ echo 'button-primary'; } ?>" data-set="border-box">border-box</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_origin == "padding-box" ){ echo 'button-primary'; } ?>" data-set="padding-box">padding-box</div><!--
						--><div class="field-background-setter button button-small <?php if( $bg_origin == "content-box" ){ echo 'button-primary'; } ?>" data-set="content-box">content-box</div>
					</div>
					<input type="text" class="background-params background-origin hide" value="<?php echo $bg_origin; ?>" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="">
				</div>
				*/
				?>

			</div>

			<textarea style="display:none;width:100%;" type="text" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" <?php if(!isset($field['id_multiple'])) echo 'name="' . $field['id'] . '"'; ?> autocorrect="off" autocomplete="off" spellcheck="false" ><?php echo $field['value']; ?></textarea>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

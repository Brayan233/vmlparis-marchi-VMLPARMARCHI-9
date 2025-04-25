<?php

wp_enqueue_style( 'field-html', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/html.css', false, false, 'screen' );

?>

<li class="wp-field wp-field-html <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>"  wp-field-id="<?php echo $field['id']; ?>" wp-field-output="<?php if ( isset( $field['options'] ) && isset( $field['options']['output'] ) ) { echo $field['options']['output']; } ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if ( $field['title'] ) { ?>
	<div class="field-title"><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">

		<?php if( $field['info'] ) { ?>
			<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<?php

		global $post_id;
		$field['post_id'] = $post_id;

		if ( isset( $field['options']['title'] ) ) {

			echo '<div class="html-title">' . $field['options']['title'] . '</div>';

		}

		if ( isset( $field['options']['html'] ) ) {
			if ( is_callable( $field['options']['html'] ) ) {

				$html = $field['options']['html']( $field );

				echo $html;

			} else {

				echo $field['options']['html'];

			}
		}

		if ( isset( $field['options']['content'] ) ) {
			if ( is_callable( $field['options']['content'] ) ) {

				$html = $field['options']['content']( $field );

				echo $html;

			} else {

				echo $field['options']['content'];

			}
		}

		if ( isset( $field['options']['data'] ) ) {
			if ( is_callable( $field['options']['data'] ) ) {

				$html = $field['options']['data']( $field );

				echo $html;

			} else {

				echo $field['options']['data'];

			}
		}

		?>

		<?php if ( $field['desc'] ) { ?>
		<div class="field-desc"><?php echo $field['desc']; ?></div>
		<?php } ?>

	<div>

</li>

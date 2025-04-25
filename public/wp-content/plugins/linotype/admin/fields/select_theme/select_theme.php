<?php

wp_enqueue_style( 'field-select-theme', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/select_theme.css', false, false, 'screen' );
wp_enqueue_script('field-select-theme', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/select_theme.js', array('jquery'), '1.0', true );

?>

<li class="wp-field wp-field-select-theme <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" style="display: block;padding:<?php echo $field['padding']; ?>">

	<div class="themes-php">

		<?php if( $field['title'] ) { ?>
		<h1 style="margin-bottom: 15px;"><?php echo $field['title']; ?><span class="title-count theme-count"><?php echo count( $field['options']['data'] ); ?></span><?php if ( $field['options']['add_title'] ) { ?><a href="<?php echo $field['options']['add_link']; ?>" class="hide-if-no-js page-title-action"><?php echo $field['options']['add_title']; ?></a><?php } ?></h1>
		<?php } ?>

		<?php if( $field['info'] ) { ?>
		<div class="field-info" ><?php echo $field['info']; ?></div>
		<?php } ?>

		<div class="theme-browser rendered">

			<div class="themes <?php if ( $field['options']['multiple'] ) { echo 'multiple'; } ?>">

				<?php

				if ( $field['options']['multiple'] ) {

					$field['value_arr'] = json_decode( $field['value'], true );

				} else {

					$field['value_arr'] = array( $field['value'] );

				}

				?>

				<?php
				if ( $field['options']['data'] ) {
				foreach ( $field['options']['data'] as $item_key => $item ) {

					$active = false;
					if ( in_array( $item['value'], $field['value_arr'] ) ) $active = 'active';

				?>

				<div class="theme <?php echo $active; ?>" data-value="<?php echo $item['value'] ?>" tabindex="0" >

					<div class="theme-screenshot <?php if ( ! $item['image'] ) { echo 'blank'; } ?>">
						<?php if ( $item['image'] ) { ?>
						<img src="<?php echo $item['image']; ?>" alt="">
						<?php } ?>
					</div>

					<?php if ( $item['desc'] ) { ?>
					<span class="more-details"><?php echo $item['desc'] ?></span>
					<?php } ?>

					<h3 class="theme-name"><?php if ( $active ) { echo '<span>' . __('Active') . ': </span>'; } ?><?php echo $item['title'] ?></h3>

					<div class="theme-actions">

						<?php if ( $field['options']['multiple'] ) { ?>
						<a class="button button-primary action-desactivate" style="margin-left:3px;margin-right:0px;" ><?php _e('Desactivate'); ?></a>
						<?php } ?>

						<a class="button button-secondary action-activate" style="margin-left:3px;margin-right:0px;" ><?php _e('Activate'); ?></a>

						<?php if ( $item['edit_link'] ) { ?>
						<a class="button button-primary action-edit" style="margin-left:3px;margin-right:0px;" href="<?php echo $item['edit_link'] ?>" ><?php _e('Edit'); ?></a>
						<?php } ?>

					</div>

				</div>

				<?php } } ?>

				<?php if ( $field['options']['add_title'] ) { ?>

				<div class="theme add-new-theme">
					<a href="<?php echo $field['options']['add_link']; ?>">
						<div class="theme-screenshot">
							<span></span>
						</div>
						<h3 class="theme-name"><?php echo $field['options']['add_title']; ?></h3>
					</a>
				</div>

				<?php } ?>

			</div>

			<br class="clear">

		</div>

		<div class="field-input" style="display:none;">
			<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false"><?php echo $field['value']; ?></textarea>
		</div>

		<?php if( $field['desc'] ) { ?>
		<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

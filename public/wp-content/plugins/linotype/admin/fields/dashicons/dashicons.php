<?php

/**
 *
 * LINOADMIN
 * Option Page builder
 * v1.0.0
 * by Yannick Armspach
 * http://www.handypress.io
 *
 * @FIELD : wp-field-dashicons
 *
 * $array
 * - id             string    global unique field id for db save
 * - id_multiple    string    unique id for repeater field
 * - title          string    the field title
 * - desc           string    the field description
 * - fullscreen     boolean   if true force full width field
 * - options        array     specific option array for this field
 *   - style     string    css style for text
 *
**/

?>

<?php

wp_enqueue_style( 'field-dashicons', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/dashicons.css', false, false, 'screen' );
wp_enqueue_script('field-dashicons', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/dashicons.js', array('jquery'), '1.0', true );

?>

<li class="wp-field wp-field-dashicons <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?>" wp-field-id="<?php echo $field['id']; ?>" style="display: block;padding:<?php echo $field['padding']; ?>">


	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<div class="field-content">


		<div class="button dashicons-toogle">
			<span class="dashicons-preview <?php echo $field['value']; ?>"></span>
		</div>

		<div class="field-input" style="display:inline-block;width:inherit;">
			<input style="" type="text" id="<?php echo $field['id']; ?>" class="wp-field-value meta-field" name="<?php echo $field['id']; ?>" value="<?php echo $field['value']; ?>" />
		</div>

		<ul class="dashicons-list"><!--
			--><li><span class="dashicons-menu"></span></li><!--
			--><li><span class="dashicons-admin-site"></span></li><!--
			--><li><span class="dashicons-dashboard"></span></li><!--
			--><li><span class="dashicons-admin-post"></span></li><!--
			--><li><span class="dashicons-admin-media"></span></li><!--
			--><li><span class="dashicons-admin-links"></span></li><!--
			--><li><span class="dashicons-admin-page"></span></li><!--
			--><li><span class="dashicons-admin-comments"></span></li><!--
			--><li><span class="dashicons-admin-appearance"></span></li><!--
			--><li><span class="dashicons-admin-plugins"></span></li><!--
			--><li><span class="dashicons-admin-users"></span></li><!--
			--><li><span class="dashicons-admin-tools"></span></li><!--
			--><li><span class="dashicons-admin-settings"></span></li><!--
			--><li><span class="dashicons-admin-network"></span></li><!--
			--><li><span class="dashicons-admin-home"></span></li><!--
			--><li><span class="dashicons-admin-generic"></span></li><!--
			--><li><span class="dashicons-admin-collapse"></span></li><!--
			--><li><span class="dashicons-welcome-write-blog"></span></li><!--
			--><li><span class="dashicons-welcome-add-page"></span></li><!--
			--><li><span class="dashicons-welcome-view-site"></span></li><!--
			--><li><span class="dashicons-welcome-widgets-menus"></span></li><!--
			--><li><span class="dashicons-welcome-comments"></span></li><!--
			--><li><span class="dashicons-welcome-learn-more"></span></li><!--
			--><li><span class="dashicons-format-aside"></span></li><!--
			--><li><span class="dashicons-format-image"></span></li><!--
			--><li><span class="dashicons-format-gallery"></span></li><!--
			--><li><span class="dashicons-format-video"></span></li><!--
			--><li><span class="dashicons-format-status"></span></li><!--
			--><li><span class="dashicons-format-quote"></span></li><!--
			--><li><span class="dashicons-format-chat"></span></li><!--
			--><li><span class="dashicons-format-audio"></span></li><!--
			--><li><span class="dashicons-camera"></span></li><!--
			--><li><span class="dashicons-images-alt"></span></li><!--
			--><li><span class="dashicons-images-alt2"></span></li><!--
			--><li><span class="dashicons-video-alt"></span></li><!--
			--><li><span class="dashicons-video-alt2"></span></li><!--
			--><li><span class="dashicons-video-alt3"></span></li><!--
			--><li><span class="dashicons-image-crop"></span></li><!--
			--><li><span class="dashicons-image-rotate-left"></span></li><!--
			--><li><span class="dashicons-image-rotate-right"></span></li><!--
			--><li><span class="dashicons-image-flip-vertical"></span></li><!--
			--><li><span class="dashicons-image-flip-horizontal"></span></li><!--
			--><li><span class="dashicons-undo"></span></li><!--
			--><li><span class="dashicons-redo"></span></li><!--
			--><li><span class="dashicons-editor-bold"></span></li><!--
			--><li><span class="dashicons-editor-italic"></span></li><!--
			--><li><span class="dashicons-editor-ul"></span></li><!--
			--><li><span class="dashicons-editor-ol"></span></li><!--
			--><li><span class="dashicons-editor-quote"></span></li><!--
			--><li><span class="dashicons-editor-alignleft"></span></li><!--
			--><li><span class="dashicons-editor-aligncenter"></span></li><!--
			--><li><span class="dashicons-editor-alignright"></span></li><!--
			--><li><span class="dashicons-editor-insertmore"></span></li><!--
			--><li><span class="dashicons-editor-spellcheck"></span></li><!--
			--><li><span class="dashicons-editor-distractionfree"></span></li><!--
			--><li><span class="dashicons-editor-kitchensink"></span></li><!--
			--><li><span class="dashicons-editor-underline"></span></li><!--
			--><li><span class="dashicons-editor-justify"></span></li><!--
			--><li><span class="dashicons-editor-textcolor"></span></li><!--
			--><li><span class="dashicons-editor-paste-word"></span></li><!--
			--><li><span class="dashicons-editor-paste-text"></span></li><!--
			--><li><span class="dashicons-editor-removeformatting"></span></li><!--
			--><li><span class="dashicons-editor-video"></span></li><!--
			--><li><span class="dashicons-editor-customchar"></span></li><!--
			--><li><span class="dashicons-editor-outdent"></span></li><!--
			--><li><span class="dashicons-editor-indent"></span></li><!--
			--><li><span class="dashicons-editor-help"></span></li><!--
			--><li><span class="dashicons-editor-strikethrough"></span></li><!--
			--><li><span class="dashicons-editor-unlink"></span></li><!--
			--><li><span class="dashicons-editor-rtl"></span></li><!--
			--><li><span class="dashicons-align-left"></span></li><!--
			--><li><span class="dashicons-align-right"></span></li><!--
			--><li><span class="dashicons-align-center"></span></li><!--
			--><li><span class="dashicons-align-none"></span></li><!--
			--><li><span class="dashicons-lock"></span></li><!--
			--><li><span class="dashicons-calendar"></span></li><!--
			--><li><span class="dashicons-visibility"></span></li><!--
			--><li><span class="dashicons-post-status"></span></li><!--
			--><li><span class="dashicons-edit"></span></li><!--
			--><li><span class="dashicons-trash"></span></li><!--
			--><li><span class="dashicons-arrow-up"></span></li><!--
			--><li><span class="dashicons-arrow-down"></span></li><!--
			--><li><span class="dashicons-arrow-right"></span></li><!--
			--><li><span class="dashicons-arrow-left"></span></li><!--
			--><li><span class="dashicons-arrow-up-alt"></span></li><!--
			--><li><span class="dashicons-arrow-down-alt"></span></li><!--
			--><li><span class="dashicons-arrow-right-alt"></span></li><!--
			--><li><span class="dashicons-arrow-left-alt"></span></li><!--
			--><li><span class="dashicons-arrow-up-alt2"></span></li><!--
			--><li><span class="dashicons-arrow-down-alt2"></span></li><!--
			--><li><span class="dashicons-arrow-right-alt2"></span></li><!--
			--><li><span class="dashicons-arrow-left-alt2"></span></li><!--
			--><li><span class="dashicons-sort"></span></li><!--
			--><li><span class="dashicons-leftright"></span></li><!--
			--><li><span class="dashicons-list-view"></span></li><!--
			--><li><span class="dashicons-exerpt-view"></span></li><!--
			--><li><span class="dashicons-share"></span></li><!--
			--><li><span class="dashicons-share-alt"></span></li><!--
			--><li><span class="dashicons-share-alt2"></span></li><!--
			--><li><span class="dashicons-twitter"></span></li><!--
			--><li><span class="dashicons-rss"></span></li><!--
			--><li><span class="dashicons-facebook"></span></li><!--
			--><li><span class="dashicons-facebook-alt"></span></li><!--
			--><li><span class="dashicons-googleplus"></span></li><!--
			--><li><span class="dashicons-networking"></span></li><!--
			--><li><span class="dashicons-hammer"></span></li><!--
			--><li><span class="dashicons-art"></span></li><!--
			--><li><span class="dashicons-migrate"></span></li><!--
			--><li><span class="dashicons-performance"></span></li><!--
			--><li><span class="dashicons-wordpress"></span></li><!--
			--><li><span class="dashicons-wordpress-alt"></span></li><!--
			--><li><span class="dashicons-pressthis"></span></li><!--
			--><li><span class="dashicons-update"></span></li><!--
			--><li><span class="dashicons-screenoptions"></span></li><!--
			--><li><span class="dashicons-info"></span></li><!--
			--><li><span class="dashicons-cart"></span></li><!--
			--><li><span class="dashicons-feedback"></span></li><!--
			--><li><span class="dashicons-cloud"></span></li><!--
			--><li><span class="dashicons-translation"></span></li><!--
			--><li><span class="dashicons-tag"></span></li><!--
			--><li><span class="dashicons-category"></span></li><!--
			--><li><span class="dashicons-yes"></span></li><!--
			--><li><span class="dashicons-no"></span></li><!--
			--><li><span class="dashicons-no-alt"></span></li><!--
			--><li><span class="dashicons-plus"></span></li><!--
			--><li><span class="dashicons-minus"></span></li><!--
			--><li><span class="dashicons-dismiss"></span></li><!--
			--><li><span class="dashicons-marker"></span></li><!--
			--><li><span class="dashicons-star-filled"></span></li><!--
			--><li><span class="dashicons-star-half"></span></li><!--
			--><li><span class="dashicons-star-empty"></span></li><!--
			--><li><span class="dashicons-flag"></span></li><!--
			--><li><span class="dashicons-location"></span></li><!--
			--><li><span class="dashicons-location-alt"></span></li><!--
			--><li><span class="dashicons-vault"></span></li><!--
			--><li><span class="dashicons-shield"></span></li><!--
			--><li><span class="dashicons-shield-alt"></span></li><!--
			--><li><span class="dashicons-search"></span></li><!--
			--><li><span class="dashicons-slides"></span></li><!--
			--><li><span class="dashicons-analytics"></span></li><!--
			--><li><span class="dashicons-chart-pie"></span></li><!--
			--><li><span class="dashicons-chart-bar"></span></li><!--
			--><li><span class="dashicons-chart-line"></span></li><!--
			--><li><span class="dashicons-chart-area"></span></li><!--
			--><li><span class="dashicons-groups"></span></li><!--
			--><li><span class="dashicons-businessman"></span></li><!--
			--><li><span class="dashicons-id"></span></li><!--
			--><li><span class="dashicons-id-alt"></span></li><!--
			--><li><span class="dashicons-products"></span></li><!--
			--><li><span class="dashicons-awards"></span></li><!--
			--><li><span class="dashicons-forms"></span></li><!--
			--><li><span class="dashicons-portfolio"></span></li><!--
			--><li><span class="dashicons-book"></span></li><!--
			--><li><span class="dashicons-book-alt"></span></li><!--
			--><li><span class="dashicons-download"></span></li><!--
			--><li><span class="dashicons-upload"></span></li><!--
			--><li><span class="dashicons-backup"></span></li><!--
			--><li><span class="dashicons-lightbulb"></span></li><!--
			--><li><span class="dashicons-smiley"></span></li><!--
		--></ul>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>


</li>

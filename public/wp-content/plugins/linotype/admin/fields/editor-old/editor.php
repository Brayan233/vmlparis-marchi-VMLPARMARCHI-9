<?php

/**
 *
 * LINOADMIN
 * Option Page builder
 * v1.0.0
 * by Yannick Armspach
 * http://www.handypress.io
 *
 * @FIELD : wp-field-image
 *
 * $array
 * - id          string    global unique field id for db save
 * - id_multiple string    unique id for repeater field
 * - title       string    the field title
 * - desc        string    the field description
 * - fullscreen  boolean   if true force full width field
 * - options     array     specific option array for this field
 *   - xxx       string    xxxxxxx
 *
**/

?>

<?php

//load script
wp_enqueue_media();
wp_enqueue_style( 'field-editor', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/editor.css', false, false, 'screen' );

$default_options = array(
	"hide_tools" 		=> false,
    "hide_toolbar" 		=> false,
    "media_buttons" 	=> true,
    "height" 			=> "",
    "toolbar" 			=> array(),
    "show_menubar" 		=> false,
    "hide_statusbar" 	=> false,
    "autoheight" 		=> true,
    "placeholder" 		=> "",
);

$field['options'] = wp_parse_args( $field['options'], $default_options );

if ( ! class_exists( 'LINOADMIN_editor' ) ) {

Class LINOADMIN_editor {

	function __construct(){

		add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ), 999 );

	}

	public function mce_external_plugins( $mce_plugins ) {

		$custom_plugins = array('advlist', 'anchor', 'code', 'codemirror', 'contextmenu', 'emoticons', 'importcss', 'insertdatetime', 'nonbreaking', 'placeholder', 'print', 'searchreplace', 'table', 'textpattern', 'visualblocks', 'visualchars', 'wptadv');

		foreach ( $custom_plugins as $tool ) {

			$file_dir = dirname( __FILE__ ) . '/mce/' . trim($tool) . '/plugin.js';
			$file_uri = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/mce/' . trim($tool) . '/plugin.js';

			if ( file_exists( $file_dir ) ) $mce_plugins[trim($tool)] = $file_uri;

		}

		return $mce_plugins;

	}


}

$LINOADMIN_editor = new LINOADMIN_editor();

}

?>

<li class="wp-field wp-field-editor <?php if( $field['fullwidth'] ) { echo 'fullwidth'; } ?> <?php if( $field['padding'] == "0px" ) { echo 'zero-border'; } ?> <?php if( $field['options']['hide_tools'] ) { echo 'hide-tools'; } ?> <?php if( $field['options']['hide_toolbar'] ) { echo 'hide-toolbar'; } ?>" wp-field-id="<?php echo $field['id']; ?>" style="padding:<?php echo $field['padding']; ?>">

	<?php if( $field['title'] ) { ?>
		<div class="field-title" ><?php echo $field['title']; ?></div>
	<?php } ?>

	<!-- <span class="spinner"></span> -->

	<div class="field-content" style="visibility:visible;">

		<div class="field-input" >

			<?php

			$editor = array();

			$editor['editor_class'] = 'meta-field';

			$editor['textarea_name'] = $field['id'];

			//Whether to display media insert/upload buttons
			if ( $field['options']['media_buttons'] == false ) $editor['media_buttons'] = false;

			//The height to set the editor in pixels. If set, will be used instead of textarea_rows. (since WordPress 3.5)
			if ( $field['options']['height'] ) $editor['editor_height'] = str_replace( 'px', '', $field['options']['height'] );

			//Whether to use wpautop for adding in paragraphs. Note that the paragraphs are added automatically when wpautop is false.
			//$editor['wpautop'] = true;

			//Enable Drag & Drop Upload Support (since WordPress 3.9)
			$editor['drag_drop_upload'] = true;

			//Load TinyMCE, can be used to pass settings directly to TinyMCE using an array
			$editor['tinymce'] = array();

			if ( $field['options']['toolbar'] ) {

				foreach ( $field['options']['toolbar'] as $toolbar_key => $toolbar) {

					$editor['tinymce']['toolbar' . ( $toolbar_key + 1 ) ] = implode(',', $toolbar );

				}

			}

			if ( $field['options']['show_menubar'] ) $editor['tinymce']['menubar'] = true;

			if ( $field['options']['hide_statusbar'] ) $editor['tinymce']['statusbar'] = false;

			if ( $field['options']['autoheight'] ) $editor['tinymce']['resize'] = false; $editor['tinymce']['wp_autoresize_on'] = true;

			//if ( $field['options']['style'] == 'dark' ) $editor['tinymce']['content_css'] = get_stylesheet_directory_uri() . '/editor-styles.css' . ',' . str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/style/dark.css';

			//if ( $field['options']['style'] == 'none' ) $editor['tinymce']['content_css'] = null;

			/*
			$editor['tinymce']['codemirror'] = array();
			$editor['tinymce']['codemirror']['indentOnInit'] = true;
			$editor['tinymce']['codemirror']['path'] = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/mce/codemirror/codemirror-4.8';
			$editor['tinymce']['codemirror']['config'] = array();
			$editor['tinymce']['codemirror']['config']['mode'] = 'application/x-httpd-php';
			$editor['tinymce']['codemirror']['config']['lineNumbers'] = true;
			$editor['tinymce']['codemirror']['jsFiles'] = array( 'mode/clike/clike.js', 'mode/php/php.js' );
			*/

			//list not plugins
			//$not_plugins = array( 'bold', 'italic', 'underline', 'strikethrough', 'alignleft', 'aligncenter', 'alignright', 'alignjustify', 'styleselect', 'formatselect', 'fontselect', 'fontsizeselect', 'cut', 'copy', 'paste', 'bullist', 'numlist', 'outdent', 'indent', 'blockquote', 'undo', 'redo', 'removeformat', 'subscript', 'superscript', 'hr', 'link', 'unlink', 'image', 'charmap', 'pastetext', 'wp_code', 'fullscreen', 'media', 'ltr', 'rtl', 'wp_more', 'wp_page', 'wp_help' );

			/* enable plugins */

			//default wp plugins
			$wp_core_plugins = array('charmap','colorpicker','compat3x','directionality','fullscreen','hr','image','lists','media','paste','tabfocus','textcolor','wordpress','wpautoresize','wpdialogs','wpeditimage','wpemoji','wpfullscreen','wpgallery','wplink','wpview');
			//custom plugins
			$custom_plugins = array('advlist', 'anchor', 'code', 'codemirror', 'contextmenu', 'emoticons', 'importcss', 'insertdatetime', 'nonbreaking', 'placeholder', 'print', 'searchreplace', 'table', 'textpattern', 'visualblocks', 'visualchars', 'wptadv');
			//merge all plugins
			$all_plugins = array_merge( $wp_core_plugins, $custom_plugins );

			//HANDYLOG( '$all_plugins', $all_plugins );

			//list pre activate plugins
			$tools_pre_active = array( 'advlist', 'contextmenu', 'importcss', 'placeholder', 'textpattern', 'wptadv' );
			//merge toolbars ant pre activate plugins

			$tools_active = $tools_pre_active;

			if ( isset ( $field['options']['toolbar'][0] ) ) $tools_active = array_merge( $tools_active, $field['options']['toolbar'][0] );
			if ( isset ( $field['options']['toolbar'][1] ) ) $tools_active = array_merge( $tools_active, $field['options']['toolbar'][1] );
			if ( isset ( $field['options']['toolbar'][2] ) ) $tools_active = array_merge( $tools_active, $field['options']['toolbar'][2] );
			if ( isset ( $field['options']['toolbar'][3] ) ) $tools_active = array_merge( $tools_active, $field['options']['toolbar'][3] );

			//HANDYLOG( '$tools_active', $tools_active );

			//remove not plugin from tools array
			$plugins_active = array();
			foreach ( $tools_active as $tool_key => $tool ) {

				if ( in_array( $tool, $all_plugins ) ) $plugins_active[$tool_key] = $tool;

			}


			//HANDYLOG( 'tinymce plugins', $plugins_active );

			//add plugins to the editor
			//$editor['tinymce']['plugins'] =  implode(',', $plugins_active );


			/* setup tinymce */

			// $editor['tinymce']['setup'] = 'function(ed) {
			//
			//
  		// 		jQuery( "#" + ed.id ).attr("placeholder", "' . $field["options"]["placeholder"] . '");
			//
			// 	ed.on("init", function(e) {
			//
			// 		//show editor
			// 		jQuery(".wp-field-editor[wp-field-id=" + ed.id + "] .field-content").css("visibility","visible");
			// 		jQuery(".wp-field-editor[wp-field-id=" + ed.id + "] .spinner").css("display","none");
			//
	    //     	});
			//
			// 	ed.on("change", function(e) {
			//
			// 			if(ed.isDirty()) ed.save();
			//
			// 			jQuery("#'.$field['id'].'").change();
			//
			// 	})
			//
			// }';

			wp_editor( stripcslashes( $field['value'] ), $field['id'], $editor );

			?>

		</div>

		<?php if( $field['desc'] ) { ?>
			<div class="field-description" ><?php echo $field['desc']; ?></div>
		<?php } ?>

	</div>

</li>

<?php

if ( ! class_exists('handypress_list') ) {

class handypress_list {

	public $list_id;

	public $list_data;

	public $list_items;

	public $list_settings;

	static $item_uniq = 0;



	function __construct( $id, $elements, $options ) {

		$this->list_id = $id;

		$this->list_settings = $this->get_settings( $options );

		$this->list_items = $elements;

		$this->load_assets();

	}

	public function load_assets() {

		wp_enqueue_script('jquery-ui-sortable');

		wp_enqueue_style( 'handypress-list', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/list.css', false, false, 'screen' );
		wp_enqueue_script('handypress-list', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL,  dirname( __FILE__ ) ) . '/list.js', array('jquery'), '1.0', true );

		wp_localize_script( 'handypress-list', 'handypress_list_settings', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

	}

	public function load( $list_data ) {

		$this->list_data = $list_data;

	}

	static function get_the_item( $item ) {

		$the_item = $item;

 		$the_item['title'] = '';
		if ( isset( $item['title'] ) ) {
			$the_item['title'] = strip_tags( $item['title'] );
		} else if ( isset( $item['type'] ) ) {
			$the_item['title'] = strip_tags( $item['type'] );
		}

		if ( isset( $item['icon'] ) && $item['icon'] ) {
			$the_item['icon'] = $item['icon'];
		} else {
			$the_item['icon'] = 'no-icon';
		}

		return $the_item;

	}

	public function editor() {

		?>

			<div class="list-editor" list-id="<?php echo $this->list_id; ?>" style="<?php if ( $this->list_settings['options']['border'] == true ) echo 'border: 1px solid #ddd;'; ?>">

				<div class="list-editor-scroll">

					<?php if ( $this->list_settings['options']['toolbar'] ) { ?>

					<div class="list-toolbar-top">

					  <div class="list-toolbar-left">
							 <?php if( $this->list_settings['options']['title'] ) echo '<div class="list-title">'.$this->list_settings['options']['title'].'</div>'; ?>
							<!-- <span class="list-action-undo list-bt fa fa-undo"></span> -->
					  	<!-- <span class="list-action-repeat list-bt fa fa-repeat"></span> -->
					  	<!-- <span class="list-action-add list-bt fa fa-plus"></span> -->
					  </div>

					  <div class="list-toolbar-right">
					  	<span class="list-action-source list-bt fa fa-code"></span>
					  	<span class="list-action-debug list-bt fa fa-bug"></span>
					  	<span class="list-action-fullscreen list-bt fa fa-arrows-alt"></span>
					  </div>

					</div>

					<?php } ?>

					<div class="list-layout">

						<div class="list-column">

							<div id="" class="list-items" style="<?php //echo $the_column['inner']['style']; ?>">

								<div class="list-item-empty"></div>

								<?php if ( isset( $this->list_data ) && $this->list_data ) { ?>

									<?php foreach ( $this->list_data as $item_key => $item ) { ?>

										<?php $the_item = self::get_the_item( $item ); ?>

										<div class="list-item" list-item-type="<?php echo  $the_item['type']; ?>">

											<div class="list-item-move list-item-handlebar"></div>

											<div class="list-item-header">
													<span class="list-item-icon list-bt <?php echo $the_item['icon']; ?>"></span>
													<span class="list-item-title-type"><?php echo $this->list_items[$the_item['type']]['title']; ?> | </span><span class="list-item-title"><?php echo $the_item['title']; ?></span>
											</div>

											<div class="list-item-tools">
										    <?php if( in_array( 'edit', $this->list_settings['options']['actions'] ) ) echo '<div class="list-item-edit list-bt fa fa-pencil"></div>'; ?>
										    <?php if( in_array( 'clone', $this->list_settings['options']['actions'] ) ) echo '<div class="list-item-clone list-bt fa fa-clone"></div>'; ?>
										    <?php if( in_array( 'delete', $this->list_settings['options']['actions'] ) ) echo '<div class="list-item-delete list-bt fa fa-trash-o"></div>'; ?>
										    <?php if( in_array( 'sort', $this->list_settings['options']['actions'] ) ) echo '<div class="list-item-move list-bt fa fa-arrows ui-sortable-handle"></div>'; ?>
										  </div>

											<textarea style="display:none" class="list-item-value"><?php echo  json_encode( $the_item, JSON_UNESCAPED_UNICODE ); ?></textarea>

										</div>

									<?php } ?>

								<?php } ?>

							</div>

							<div class="list-column-tools-bottom">

								<?php if( in_array( 'add', $this->list_settings['options']['actions'] ) ) echo '<span class="list-modal-add-bt list-bt fa fa-plus"></span>'; ?>

							</div>

						</div>

					</div>

					<?php $this->modal(); ?>

					<?php $this->items(); ?>

					<div class="list-source" style="">
						<textarea name="<?php echo $this->list_id; ?>" id="<?php echo $this->list_id; ?>" class="wp-field-value meta-field" type="textarea" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="" /><?php echo json_encode( $this->list_data, JSON_UNESCAPED_UNICODE ); ?></textarea>
					</div>

					<textarea style="display: none;" class="wp-field-options" /><?php echo json_encode( $this->list_settings['options'], JSON_UNESCAPED_UNICODE ); ?></textarea>

					<script class="list-template-item" type="text/template"><?php include dirname( __FILE__ ) . '/list-item.template.php'; ?></script>

				</div>

			</div>

		<?php

	}

	public function modal() {

		echo '<div class="list-modal-edit list-modal">';

			echo '<div class="list-modal-bg list-modal-close"></div>';

		 	echo '<div class="list-modal-container">';

		    	// echo '<div class="list-modal-toolbar-top">';
					//
		      // 		echo '<div class="list-modal-toolbar-left">';
					//
		      // 			echo '<span class="list-modal-toolbar-title">Title</span>';
					//
		      // 		echo '</div>';
					//
		      // 		echo '<div class="list-modal-toolbar-right">';
					//
		      // 			//echo '<span class="list-modal-close list-bt dashicons dashicons-no"></span>';
					//
		      // 		echo '</div>';
					//
		    	// echo '</div>';

		    	echo '<div class="list-modal-content">';

					echo '<iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" width="100%" src=""></iframe>';

					echo '<span class="spinner spinner-center"></span>';

				echo '</div>';

					// echo '<div class="list-modal-toolbar-bottom">';
					//
		      // 		echo '<div class="list-modal-toolbar-left">';
					//
		      // 		echo '</div>';
					//
		      // 		echo '<div class="list-modal-toolbar-right">';
					//
		      // 			echo '<div class="list-modal-close button button-large button-error">Cancel</div> <div class="list-item-save button button-large button-success">Validate</div>';
					//
		      // 		echo '</div>';
					//
		    	// echo '</div>';

			echo '</div>';

		echo '</div>';

	}

	public function items() {

		echo '<div class="list-modal-add list-modal">';

			echo '<div class="list-modal-bg list-modal-close"></div>';

		 	echo '<div class="list-modal-container">';

		    	// echo '<div class="list-modal-toolbar-top">';
					//
		      // 		echo '<div class="list-modal-toolbar-left">';
					//
		      // 			echo '<span class="list-modal-toolbar-title">Title</span>';
					//
		      // 		echo '</div>';
					//
		      // 		echo '<div class="list-modal-toolbar-right">';
					//
		      // 			//echo '<span class="list-modal-close list-bt dashicons dashicons-no"></span>';
					//
		      // 		echo '</div>';
					//
		    	// echo '</div>';

		    	echo '<div class="list-modal-content">';

					if ( $this->list_items ){

						echo '<ul class="list-add-items">';

						foreach ( $this->list_items as $item_type => $item ) {

							echo '<li class="list-add-item">';

								echo '<h3>' . $item['title'] . '</h3>';
								echo '<p>' . $item['desc'] . '</p>';
								echo '<div class="list-item-add button">ADD</div>';

								$item_default = array( 'type' => $item_type );

								if ( $item['settings'] ){
									foreach ( $item['settings'] as $settings_id => $settings ) {

										$item_default[$settings_id] = $settings['default'];

									}
								}

								echo '<textarea class="list-item-value" style="display:none">' . json_encode( $item_default, JSON_UNESCAPED_UNICODE ) . '</textarea>';

							echo '</li>';

						}

						echo '</ul>';

					}

				echo '</div>';

			// 	echo '<div class="list-modal-toolbar-bottom">';
			//
		  //     		echo '<div class="list-modal-toolbar-left">';
			//
		  //     		echo '</div>';
			//
		  //     		echo '<div class="list-modal-toolbar-right">';
			//
		  //     			echo '<div class="list-modal-close button button-large button-error">Cancel</div>';
			//
		  //     		echo '</div>';
			//
		  //   	echo '</div>';

			 echo '</div>';

		echo '</div>';

	}

	public function preview() {

		echo '<div class="list-preview"><iframe sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-top-navigation" width="100%" src="' . admin_url( 'admin-ajax.php' ) . '?action=handypress_list_preview&list_id=' . $this->list_id . '"></iframe></div>';

	}

	public function layout( $list_data ) {


		?>

			<?php if ( $list_data ) { foreach ( $list_data as $row_key => $row ) { ?>

				<?php $the_row = self::get_the_row( $row['settings'] ); ?>

				<div class="<?php echo $the_row['wrapper']['class']; ?>" style="<?php echo $the_row['wrapper']['style']; ?>">

					<div class="<?php echo $the_row['container']['class']; ?>" style="<?php echo $the_row['container']['style']; ?>">

						<div class="<?php echo $the_row['row']['class']; ?>" style="<?php echo $the_row['row']['style']; ?>">

							<?php foreach ( $row['content'] as $col_key => $col ) { ?>

								<?php  $the_column = self::get_the_column( $col['settings'] ); ?>

								<div class="<?php echo $the_column['column']['class']; ?> col-id-<?php echo $row_key; ?>-<?php echo $col_key; ?>" style="<?php echo $the_column['column']['style']; ?>">

									<div class="col-inner" style="<?php //echo $the_column['inner']['style']; ?>">

										<?php
										if ( $col['content'] ) {

											foreach ( $col['content'] as $item_key => $item ) {

												$item['_item_uniq'] = "item_" . self::$item_uniq;

												if( is_callable( $this->list_items[ $item['type'] ]['render'] ) ) {

													//echo $item['type'] . ' function';

													call_user_func( $this->list_items[ $item['type'] ]['render'], $item );

												} else {

													//echo $item['type'] . ' function not found';

												}

												self::$item_uniq++;

											}

										}
										?>

									</div>

								</div>

								<?php echo $clearfix; ?>

							<?php } ?>

						</div>

					</div>

				</div>

			<?php } } ?>

		<?php

	}



	public function get_settings( $options ) {

		$list_settings = array(

			"options" => $options,

			"row_settings" => array(

				"general" => array(
					"type" => array(
						"title" => "Type",
						"desc" => "standard, fluid, fullwidth",
						"default" => "standard",
					),
					"gutters" => array(
						"title" => "Gutters",
						"desc" => "",
						"default" => "true",
					),
					"justify" => array(
						"title" => "Justify",
						"desc" => "start, center, end, around, between",
						"default" => "start",
					),
					"align" => array(
						"title" => "Align",
						"desc" => "start, center, end",
						"default" => "start",
					),
				),

				"display" => array(
					"hidden-up" => array(
						"title" => "Hidden up to",
						"desc" => "xs,sm,md,lg,xl",
						"default" => "xl",
					),
					"hidden-down" => array(
						"title" => "Hidden down to",
						"desc" => "xs,sm,md,lg,xl",
						"default" => "xs",
					),
					"display" => array(
						"title" => "Display",
						"desc" => "",
						"default" => "true",
					),
				),

				"row-style" => array(
					"id" => array(
						"title" => "id",
						"desc" => "",
						"default" => "#myid",
					),
					"class" => array(
						"title" => "class",
						"desc" => "",
						"default" => ".myclass",
					),
					"max-width" => array(
						"title" => "max-Width",
						"desc" => "",
						"default" => "inherit",
					),
					"background-color" => array(
						"title" => "Background-color",
						"desc" => "",
						"default" => "inherit",
					),
					"background-image" => array(
						"title" => "Background-image",
						"desc" => "",
						"default" => "none",
					),
					"margin" => array(
						"title" => "Margin",
						"desc" => "",
						"default" => "0px 0px 0px 0px",
					),
					"padding" => array(
						"title" => "Padding",
						"desc" => "",
						"default" => "0px 0px 0px 0px",
					),
				),

				"container-style" => array(
					"container-bg-color" => array(
						"title" => "Background-color",
						"desc" => "",
						"default" => "inherit",
					),
				),

				"wrapper-style" => array(
					"wrapper-bg-color" => array(
						"title" => "Background-color",
						"desc" => "",
						"default" => "inherit",
					),
				),
			),

			"col_settings" => array(

				"general" => array(
					"col" => array(
						"title" => "Column",
						"desc" => "",
						"default" => "auto",
					),
					"col-sm" => array(
						"title" => "Column small",
						"desc" => "",
						"default" => "auto",
					),
					"col-md" => array(
						"title" => "Column medium",
						"desc" => "",
						"default" => "auto",
					),
					"col-lg" => array(
						"title" => "Column large",
						"desc" => "",
						"default" => "auto",
					),
					"col-xl" => array(
						"title" => "Column extra",
						"desc" => "",
						"default" => "auto",
					),
				),

				"options" => array(
					"clearfix" => array(
						"title" => "clearfix",
						"desc" => "insert just after column",
						"default" => "no",
					),
					"align" => array(
						"title" => "Align",
						"desc" => "start, center, end",
						"default" => "start",
					),
					"offset" => array(
						"title" => "Offset",
						"desc" => "",
						"default" => "0",
					),

				),

				"display" => array(
					"display" => array(
						"title" => "display",
						"desc" => "",
						"default" => "true",
					),
					"hidden-up" => array(
						"title" => "Hidden up to",
						"desc" => "xs,sm,md,lg,xl",
						"default" => "xl",
					),
					"hidden-down" => array(
						"title" => "Hidden down to",
						"desc" => "xs,sm,md,lg,xl",
						"default" => "xs",
					),
				),

				"col-style" => array(
					"id" => array(
						"title" => "id",
						"desc" => "",
						"default" => "#myid",
					),
					"class" => array(
						"title" => "class",
						"desc" => "",
						"default" => ".myclass",
					),
					"max-width" => array(
						"title" => "max-Width",
						"desc" => "",
						"default" => "inherit",
					),
					"background-color" => array(
						"title" => "Background-color",
						"desc" => "",
						"default" => "inherit",
					),
					"background-image" => array(
						"title" => "Background-image",
						"desc" => "",
						"default" => "none",
					),
					"margin" => array(
						"title" => "Margin",
						"desc" => "",
						"default" => "0px 0px 0px 0px",
					),
					"padding" => array(
						"title" => "Padding",
						"desc" => "",
						"default" => "0px 0px 0px 0px",
					),
				),

			),
		);

		return $list_settings;

	}

}

}



if ( ! class_exists('bs4Navwalker') ) {
/**
 * Class Name: bs4Navwalker
 * GitHub URI: https://github.com/dominicbusinaro/bs4navwalker
 * Description: A custom WordPress nav walker class for Bootstrap 4 (v4.0.0-alpha.1) nav menus in a custom theme using the WordPress built in menu manager
 * Version: 0.1
 * Author: Dominic Businaro - @dominicbusinaro
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
class bs4Navwalker extends Walker_Nav_Menu
{
    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class=\"dropdown-menu\">\n";
    }
    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</div>\n";
    }
    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        // New
        $class_names .= ' nav-item';

        if (in_array('menu-item-has-children', $classes)) {
            $class_names .= ' dropdown';
        }
        if (in_array('current-menu-item', $classes)) {
            $class_names .= ' active';
        }
        //
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        // print_r($class_names);
        /**
         * Filter the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        // New
        if ($depth === 0) {
            $output .= $indent . '<li' . $id . $class_names .'>';
        }
        //
        // $output .= $indent . '<li' . $id . $class_names .'>';
        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
        // New
        if ($depth === 0) {
            $atts['class'] = 'nav-link';
        }
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $atts['class']       .= ' dropdown-toggle';
            $atts['data-toggle']  = 'dropdown';
        }
        if ($depth > 0) {
            $atts['class'] = 'dropdown-item';
        }
        if (in_array('current-menu-item', $item->classes)) {
            $atts['class'] .= ' active';
        }
        // print_r($item);
        //
        /**
         * Filter the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param object $item  The current menu item.
         * @param array  $args  An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $item_output = $args->before;
        // New
        /*
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $item_output .= '<a class="nav-link dropdown-toggle"' . $attributes .'data-toggle="dropdown">';
        } elseif ($depth === 0) {
            $item_output .= '<a class="nav-link"' . $attributes .'>';
        } else {
            $item_output .= '<a class="dropdown-item"' . $attributes .'>';
        }
        */
        //
        $item_output .= '<a'. $attributes .'>';
        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of {@see wp_nav_menu()} arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    /**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        if (isset($args->has_children) && $depth === 0) {
            $output .= "</li>\n";
        }
    }
}

}

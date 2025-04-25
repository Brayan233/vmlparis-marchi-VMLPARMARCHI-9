<?php

/**
 * LINOADMIN
 *
 * handy fields builder
 *
 * @package WordPress
 * @subpackage LINOADMIN
 * @version 1.0.0
 * @author Yannick Armspach
 * @link http://www.handypress.io
 *
 */


/**
 * Class to create the meta content.
 *
 * @since 1.0.0
 *
 */
class LINOADMIN_CUSTOMPOST extends LINOADMIN {

  /**
   * __construct
   *
   * @since 1.0
   *
   * @param array  $META  the meta params
   *
   */
  function __construct( $PAGE_ID, $PAGE, $SETTINGS ) {

    $this->PAGE_ID = $PAGE_ID;
    $this->PAGE = $PAGE;
    $this->SETTINGS = $SETTINGS;

    $this->add_custom_post();

  }

  public function add_custom_post() {

    $args = array(
      'label'                 => 'Items',
      'labels'                => array(
          'name'                  => 'Item',
          'singular_name'         => 'Item',
          'menu_name'             => 'Items',
          'name_admin_bar'        => 'Item',
          'parent_item_colon'     => 'Parent:',
          'all_items'             => 'View All',
          'add_new_item'          => 'Add New',
          'add_new'               => 'Add New',
          'new_item'              => 'New',
          'edit_item'             => 'Edit',
          'update_item'           => 'Update',
          'view_item'             => 'View',
          'search_items'          => 'Search',
          'not_found'             => 'Not found',
          'not_found_in_trash'    => 'Not found in Trash',
          'items_list'            => 'list',
          'items_list_navigation' => 'list navigation',
          'filter_items_list'     => 'Filter list',
        ),
      //'supports'              => array("title","thumbnail"),
      'hierarchical'          => false,
      'public'                => false,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => null,
      'menu_icon'             => null,
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,   
      'exclude_from_search'   => true,
      'publicly_queryable'    => false,
      'rewrite'               => false,
      'capability_type'       => 'page',
    );
    
//     'title'
// 'editor' (content)
// 'author'
// 'thumbnail' (featured image) (current theme must also support Post Thumbnails)
// 'excerpt'
// 'trackbacks'
// 'custom-fields'
// 'comments' (also will see comment count balloon on edit screen)
// 'revisions' (will store revisions)
// 'page-attributes' (template and menu order) (hierarchical must be true)
// 'post-formats' removes post formats, see Post Formats

    //replace with default value
    if ( $this->PAGE['hidden'] ) $args['show_in_menu'] = $args['show_in_admin_bar'] = $args['show_in_nav_menus'] = false;
    
    if ( $this->PAGE['submenu'] ) $args['show_in_menu'] = $this->PAGE['submenu'];

    if ( $this->PAGE['name'] ) $args['labels']['menu_name'] = $this->PAGE['name'];
    if ( $this->PAGE['title'] ) $args['labels']['name'] = $this->PAGE['title'];
    if ( $this->PAGE['title'] ) $args['labels']['singular_name'] = $this->PAGE['title'];
    if ( $this->PAGE['subtitle'] ) $args['labels']['all_items'] = $this->PAGE['subtitle'];

    if ( $this->PAGE['icon'] ) $args['menu_icon'] = $this->PAGE['icon'];

    if ( $this->PAGE['order'] ) $args['menu_position'] = $this->PAGE['order'];

    //merge array with custom args
    if ( is_array( $this->PAGE['custompost'] ) ) {

      $page_labels = $this->PAGE['custompost']['labels'];
      $labels = array_merge( $args['labels'],$page_labels );
      $args = array_merge( $args, $this->PAGE['custompost'] );
      $args['labels'] = $labels;
    
    }

    register_post_type( $this->PAGE_ID, $args );

  }

}

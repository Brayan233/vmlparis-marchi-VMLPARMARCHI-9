<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class LINOTYPE_custompost {

  function __construct( $settings ){

    $this->settings = $settings;

    $this->post_type();

    $this->taxonomies();

    $this->terms_meta();

  }

  public function post_type() {

    $labels = array(
      'name'                  => __( $this->settings['name'] ),
      'singular_name'         => __( $this->settings['singular_name'] ),
      'menu_name'             => __( $this->settings['name'] ),
      'name_admin_bar'        => __( $this->settings['name'] ),
      'archives'              => __( $this->settings['singular_name'] . ' Archives' ),
      'attributes'            => __( $this->settings['singular_name'] . ' Attributes' ),
      'parent_item_colon'     => __( 'Parent ' . $this->settings['singular_name'] . ':' ),
      'all_items'             => __( 'All ' . $this->settings['name'] ),
      'add_new_item'          => __( 'Add New ' . $this->settings['singular_name'] ),
      'add_new'               => __( 'Add New' ),
      'new_item'              => __( 'New ' . $this->settings['singular_name'] ),
      'edit_item'             => __( 'Edit ' . $this->settings['singular_name'] ),
      'update_item'           => __( 'Update ' . $this->settings['singular_name'] ),
      'view_item'             => __( 'View ' . $this->settings['singular_name'] ),
      'view_items'            => __( 'View ' . $this->settings['name'] ),
      'search_items'          => __( 'Search ' . $this->settings['singular_name'] ),
      'not_found'             => __( 'Not found' ),
      'not_found_in_trash'    => __( 'Not found in Trash' ),
      'featured_image'        => __( 'Featured Image' ),
      'set_featured_image'    => __( 'Set featured image' ),
      'remove_featured_image' => __( 'Remove featured image' ),
      'use_featured_image'    => __( 'Use as featured image' ),
      'insert_into_item'      => __( 'Insert into ' . $this->settings['name'] ),
      'uploaded_to_this_item' => __( 'Uploaded to this ' . $this->settings['name'] ),
      'items_list'            => __( $this->settings['singular_name'] . ' list' ),
      'items_list_navigation' => __( $this->settings['singular_name'] . ' list navigation' ),
      'filter_items_list'     => __( 'Filter ' . $this->settings['name'] . ' list' ),
    );

    if ( isset( $this->settings['slug'] ) && $this->settings['slug'] ) {
      $rewrite = array(
        'slug'                  => $this->settings['slug'],
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
      );
    } else {
      $rewrite = array(
        'slug'                  => $this->settings['id'],
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
      );
    }
    $capabilities = array(
      'edit_post'             => 'edit_post',
      'read_post'             => 'read_post',
      'delete_post'           => 'delete_post',
      'edit_posts'            => 'edit_posts',
      'edit_others_posts'     => 'edit_others_posts',
      'publish_posts'         => 'publish_posts',
      'read_private_posts'    => 'read_private_posts',
    );
    $args = array(
      'label'                 => __( $this->settings['singular_name'] ),
      'description'           => __( $this->settings['singular_name'] . ' Description' ),
      'labels'                => $labels,
      'supports'              => array( 'title', 'editor', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats' ),
      //'taxonomies'            => array( 'category', 'post_tag' ),
      'hierarchical'          => true,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => $this->settings['has_archive'],
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'query_var'             => $this->settings['id'],
      'rewrite'               => $rewrite,
      //'capabilities'          => $capabilities,
      'show_in_rest'          => true,
    );

    register_post_type( $this->settings['id'], $args );

  }

  public function taxonomies() {

    if( isset( $this->settings['taxonomies'] ) ) {
      foreach( $this->settings['taxonomies'] as $taxonomy ) {

        $labels = array(
            'name' => __( $taxonomy['name'] ),
            'singular_name' => __( $taxonomy['singular_name'] ),
            'search_items' => __('Search ' . $taxonomy['singular_name'] ),
            'popular_items' => __('Common ' . $taxonomy['name'] ),
            'all_items' => __('All ' . $taxonomy['name'] ),
            'edit_item' => __('Edit ' . $taxonomy['singular_name'] ),
            'update_item' => __('Update ' . $taxonomy['singular_name'] ),
            'add_new_item' => __('Add new ' . $taxonomy['singular_name'] ),
            'new_item_name' => __('New ' . $taxonomy['singular_name'] . ':' ),
            'add_or_remove_items' => __('Remove ' . $taxonomy['singular_name'] ),
            'choose_from_most_used' => __('Choose from common ' . $taxonomy['singular_name'] ),
            'not_found' => __('No ' . $taxonomy['singular_name'] . ' found.' ),
            'menu_name' => __( $taxonomy['name'] ),
        );

        $args = array(
          'labels' => $labels,
          'hierarchical'               => true,
          'public'                     => true,
          'show_admin_column'          => true,
          'show_in_nav_menus'          => true,
          'show_tagcloud'              => true,
          // 'capabilities'               => array(
          //   'manage_terms'               => 'manage_categories',
          //   'edit_terms'                 => 'manage_categories',
          //   'delete_terms'               => 'manage_categories',
          //   'assign_terms'               => 'edit_posts',
          // )
        );

        if ( isset( $taxonomy['slug'] ) && $taxonomy['slug'] ) {
          $args['rewrite'] = array(
            'slug' => $taxonomy['slug'],
            'with_front' => false
          );
        } else {
          $args['rewrite'] = array(
            'slug' => $taxonomy['id'],
            'with_front' => false
          );
        }

        register_taxonomy( $taxonomy['id'], array( $this->settings['id'] ), $args);

        add_filter( 'manage_edit-' . $this->settings['id'] . '_columns', function( $columns ) use ( $taxonomy ) {

          $new = array();

          foreach($columns as $key => $title) {

            $new[$key] = $title;
            if ($key=='title') $new[ $taxonomy['id'] ] = __( $taxonomy['singular_name'] );

          }

          return $new;

        } );

        add_filter('manage_posts_custom_column', function ( $column, $post_id ) use ( $taxonomy ) {

          if( $column === $taxonomy['id'] ) {

            $terms = get_the_terms( $post_id, $taxonomy['id'] );

            if( $terms ) {

              foreach( $terms as $term ) {

                echo $term->name;

              }

            }

          }


        }, 10, 3 );

        add_action( 'restrict_manage_posts', function( $post_type, $which ) use ( $taxonomy ) {

          if ( $this->settings['id'] !== $post_type ) return;

          $taxonomy_slug = $taxonomy['id'];

          $taxonomy_obj = get_taxonomy( $taxonomy_slug );
          $taxonomy_name = $taxonomy_obj->labels->name;

          $terms = get_terms( $taxonomy_slug );

          echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
          echo '<option value="">' . sprintf( esc_html__( 'Show All %s', 'text_domain' ), $taxonomy_name ) . '</option>';
          foreach ( $terms as $term ) {
              printf(
                  '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                  $term->slug,
                  ( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
                  $term->name,
                  $term->count
              );
          }
          echo '</select>';

        } , 99, 2);

      }
    }

  }

  public function terms_meta() {

    if( isset( $this->settings['taxonomies'] ) ) {
      foreach( $this->settings['taxonomies'] as $taxonomy ) {

        if( isset( $taxonomy['metas'] ) ) {
          foreach( $taxonomy['metas'] as $meta ) {

            add_action( $taxonomy['id'] . '_add_form_fields', function( $taxonomy ) use ( $meta ) {

              ?>

              <div class="form-field term-group">
                  <label for="<?php echo $meta['id']; ?>"><?php echo $meta['name']; ?></label>

                  <?php LINOTYPE::$FIELDS->display( $meta['type'], $meta ); ?>

              </div>

              <?php

            }, 10, 2 );

            add_action( $taxonomy['id'] . '_edit_form_fields', function( $term, $taxonomy ) use ( $meta ) {

              $value = get_term_meta( $term->term_id, $meta['id'], true );

              ?>
              <tr class="form-field term-group-wrap">
                  <th scope="row"><label for="<?php echo $meta['id']; ?>"><?php echo $meta['name']; ?></label></th>
                  <td>
                    <?php

                    $meta['value'] = $value;

                    LINOTYPE::$FIELDS->display( $meta['type'], $meta );

                    ?>
                  </td>
              </tr>
              <?php

            }, 10, 2 );

            add_action( 'created_' . $taxonomy['id'], function( $term_id, $tt_id ) use ( $meta ) {

              if( isset( $_POST[ $meta['id'] ] ) && $_POST[ $meta['id'] ] ){

                $value = $_POST[ $meta['id'] ];

                if ( $value ) {

                  add_term_meta( $term_id, $meta['id'], $value, true );

                }

              }

            }, 10, 2 );

            add_action( 'edited_' . $taxonomy['id'], function( $term_id, $tt_id ) use ( $meta ) {

              if( isset( $_POST[ $meta['id'] ] ) ){

                $value = $_POST[ $meta['id'] ];

                if ( get_term_meta( $term_id, $meta['id'], true ) !== false ) {

                  update_term_meta( $term_id, $meta['id'], $value );

                } else {

                  add_term_meta( $term_id, $meta['id'], $value, true );

                }

              }

            }, 10, 2 );

            add_filter( 'manage_edit-' . $taxonomy['id'] . '_columns', function( $columns ) use ( $meta ) {


                if ( $columns ) {

                  $new = array();

                  foreach($columns as $key => $title) {

                    $new[$key] = $title;
                    if ($key=='name') $new[ $meta['id'] ] = __( $meta['name'] );

                  }

                return $new;

              } else {

                return $columns;

              }

            } );

            add_filter('manage_' . $taxonomy['id'] . '_custom_column', function ( $content, $column_name, $term_id ) use ( $meta ) {

              if( $column_name === $meta['id'] ) {

                $value = get_term_meta( absint( $term_id ), $meta['id'], true );

                if( $value ) {

                    LINOTYPE::$FIELDS->preview( $meta['type'], array(
                      'id' => $meta['id'],
                      'value' => $value,
                      'options' => array(
                      ),
                    ) );

                }

              }

            }, 10, 3 );

          }
        }

      }
    }

	}


}

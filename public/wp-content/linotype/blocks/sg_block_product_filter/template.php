<?php 

$shop_category_slug = get_query_var('shop_category');
$category = false;
if ($shop_category_slug) {
    $category = get_term_by('slug', $shop_category_slug, 'product_cat');
}


block('header', $settings );

  $block = new sg_block_product_filter( $options );

  echo '<div class="container">';

    echo $block->start();
    
      echo '<div class="product-list">';

        if ($category) {
            echo '<h2 class="lvl2-title title">' . esc_html($category->name) . '</h2>';
            echo '<p class="lvl2-desc">' . esc_html($category->description) . '</p>';
        } else {
            echo '<h2 class="lvl2-title title">' . esc_html($options['title']) . '</h2>';
            echo '<p class="lvl2-desc"></p>';
        }

        echo $block->get('search');

        echo '<div class="filters">';
          echo '<button type="button" class="btn btn-filter">' . linotrad('Filters') . '</button>';
          echo '<div class="filter-list">';
            echo '<div class="filter-dialog">';
              echo '<div class="filter-content">';
                echo '<p class="filter-title desktop-hide">' . linotrad('Filters') . '</p>';
                echo '<button type="button" class="btn-unstyled btn-reset" data-action="reset-filters">' . linotrad('Reset filters') . '</button>';
                echo '<button type="button" class="btn-unstyled btn-close desktop-hide" data-action="close-filters" aria-label="' . linotrad('Close') . '"></button>';
                echo $block->get_filter( 'sort_by', 'radio', linotrad('Sort by') );
                echo $block->get_filter( 'tax_pa_colors', 'checkbox', linotrad('Colors') );
                echo $block->get_filter( 'tax_pa_materials', 'checkbox', linotrad('Materials') );
                echo $block->get_filter( 'tax_pa_sizes', 'checkbox', linotrad('Size') );
              echo '</div>';
            echo '<button type="button" class="btn btn-block desktop-hide" data-action="close-filters">' . linotrad('Apply filters') . '</button>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
        
        echo '<div class="categories">';
          echo '<button type="button" class="btn btn-filter">' . linotrad('Categories') . '</button>';
          echo '<div class="filter-list">';
            echo '<div class="filter-dialog">';
              echo '<div class="filter-content">';
                echo '<p class="filter-title desktop-hide">' . linotrad('Categories') . '</p>';
                echo '<button type="button" class="btn-unstyled btn-reset" data-action="reset-categories">' . linotrad('Reset categories') . '</button>';
                echo '<button type="button" class="btn-unstyled btn-close desktop-hide" data-action="close-filters" aria-label="' . linotrad('Close') . '"></button>';
                echo $block->get_filter( 'wc_product_cat', 'tree', '' );
              echo '</div>';
              echo '<button type="button" class="btn btn-block desktop-hide" data-action="close-filters">' . linotrad('Apply categories') . '</button>';
            echo '</div>';
          echo '</div>';
        echo '</div>';

        echo '<div class="product-grid">';

          echo $block->get_loop('start');

            echo '<li><a href="' . $block->get_item('wp_link') . '">';

              echo '<picture class="linotype_field_image ratio" style="padding-top: 100%;">';
                echo '<source type="image/jpeg" srcset="' . $block->get_item('wp_image') . ' 1x, ' . $block->get_item('wp_image_retina') . ' 2x" >';
                echo '<img class="linotype_field_image-img" src="' . $block->get_item('wp_image') . '" alt="' . $block->get_item('wp_title') . '">';
              echo '</picture>';

              echo '<div class="product-info">';
                echo '<div>';
                  echo '<p class="product-category">' . $block->get_item('wc_product_cat_single') . '</p>';
                  echo '<h4 class="product-title">' . $block->get_item('wp_title') . '</h4>';
                  echo '<p class="product-price">';
                    echo $block->get_item('wc_price');
                  echo '</p>';
                echo '</div>';
                echo '<div>';
                  echo '<div class="product-colors">' . $block->get_item('tax_color_pa_colors') . '</div>';
                  echo '<div class="product-sizes">' . $block->get_item('tax_label_pa_sizes') . '</div>';
                echo '</div>';
              echo '</div>';
            echo '</a></li>';

          echo $block->get_loop('end');

        echo '</div>';

      echo '</div>';

      echo '<div class="pagination-tools">';

        echo $block->get('pagination');
        echo $block->get('per_page');

      echo '</div>';

    echo $block->end();

  echo '</div>';

block('footer', $settings );

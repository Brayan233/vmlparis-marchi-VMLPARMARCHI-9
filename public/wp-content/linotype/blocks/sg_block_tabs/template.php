<?php 

$block = new sg_block_tabs( $options, $contents );

block( 'header', $settings );

?>

  <div class="container">

    <div class="block-heading">
      <?php
      echo '<h2 class="lvl2-title">' . $options['title'] . '</h2>';
      if ( $link = get_linotype_field_link( $options['link'] ) ) {
        $noopener = '';
        if ( $link['target'] == '_blank' ) $noopener = 'rel="noopener"';
        echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '" ' . $noopener . ' class="btn btn-primary btn-arrow block-heading-link desktop-hide">' . $link['title'] . '</a>';
      }
      ?>
    </div>

    <div class="tab-grid">
      <?php
        echo '<div class="col-tabs">';
          echo '<div class="sg_block_tabs-links" role="tablist">';

            foreach ( $block->get_links() as $link_key => $link ) {

              if ( $link['id'] ) {

                echo '<div class="' . $link['class'] . '">';

                  echo '<a href="' . $link['href'] . '" class="tab-link" role="tab">' . $link['title'] . '</a>';

                echo '</div>';

              }

            }

          echo '</div>';
          if ( $link = get_linotype_field_link( $options['link'] ) ) {
            $noopener = '';
            if ( $link['target'] == '_blank' ) $noopener = 'rel="noopener"';
            echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '" ' . $noopener . ' class="btn btn-primary btn-arrow link-all mobile-hide">' . $link['title'] . '</a>';
          }
        echo '</div>';

        echo '<div class="col-tab-contents">';

        foreach ( $block->get_contents() as $content_key => $content ) {

          echo '<div class="' . $content['class'] . '" id="' . $content['id'] . '">';

            echo LINOTYPE_composer::render( $content['data'], $elements, false, false );

          echo '</div>';
          
        }

        echo '</div>';
      ?>

    </div>
  </div>

<?php

block( 'footer', $settings );

<?php 

block('header', $settings );

    $link = get_linotype_field_link( $options['link'] );

    echo '<div>';

        echo '<div class="block-heading">';
        
            echo '<h2 class="lvl2-title title">' . $options['title'] . '</h2>';
            if ( $link['url'] ) {
                if ( $link['target'] == '_blank' ) {
                    echo '<a href="' . $link['url'] . '" class="btn btn-primary btn-arrow link" target="_blank" rel="noopener">' . $link['title'] . '</a>';
                } else {
                    echo '<a href="' . $link['url'] . '" class="btn btn-primary btn-arrow link">' . $link['title'] . '</a>';
                }
            }

        echo '</div>';

        echo '<div class="gallery">';

            foreach ( $options['links'] as $item ) {
                
                echo '<div class="post">';
               
                    echo '<div class="sg_block_gallery-item inner">';
                        
                        echo get_linotype_field_image( array(
                            'alt' => '',
                            'class' => 'sg_block_gallery-item-image',
                            'sources' => array(
                                array(
                                    'id' => $item['img'],
                                    'break' => 992,
                                    'crop' => false,
                                    'x' => 400,
                                    'y' => 0,
                                ),
                                array(
                                    'id' => $item['img'],
                                    'break' => 0,
                                    'crop' => false,
                                    'x' => 200,
                                    'y' => 0,
                                )
                            ),
                            'compress' => $options['compress'],
                            'lazyload' => false,
                            'srcsetname' => 'srcset',
                            'srcname' => 'lazy',
                            'ratio' => false,
                            'webp' => true,
                        ));

                        if ( $item['desc'] ) {
                            echo '<p class="legend">';
                                if ( $item['link'] ) {
                                    echo '<a class="btn btn-primary btn-arrow media-link" href="' . $item['link'] . '">' . $item['desc'] . '</a>';
                                } else {
                                    echo $item['desc'];
                                }
                            echo '</p>';
                        }
                        
                    echo '</div>';

                echo '</div>';
                
            }

        echo '</div>';

    echo '</div>';

    echo '<div class="sg_block_gallery-popin">';

        echo '<div class="sg_block_gallery-close-wrapper">';
            echo '<div class="container">';
            echo '<button type="button" class="btn-unstyled tap-expand-before sg_block_gallery-close" aria-label="Fermer"></button>';
            echo '</div>';
        echo '</div>';
        
        echo '<div class="sg_block_gallery-container">';
            echo '<div class="sg_block_gallery-content">';
            echo 'sg_block_gallery';
            echo '</div>';
        echo '</div>';

    echo '</div>';

block('footer', $settings );

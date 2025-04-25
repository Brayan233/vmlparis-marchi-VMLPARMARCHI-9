<?php

echo '<div class="row">';

    echo '<div class="column-100 padding-50">';

        echo '<h6>Title:</h6>';
        echo '<p class="value" style="font-size: 24px;">' . $options['title'] . '</p>';
        
        echo '<h6>Categories:</h6>';
        echo '<ul>';
            echo '<li>' . get_term( $options['cat_term_1'] )->name . '</li>';
            echo '<li>' . get_term( $options['cat_term_2'] )->name . '</li>';
            echo '<li>' . get_term( $options['cat_term_3'] )->name . '</li>';
        echo '</ul>';

        echo '<h6>Link:</h6>';
        if ( $link = get_linotype_field_link( $options['link'] ) ) {
            
            echo '<p class="hero-link">';
                echo $link['title'] . ' (' . $link['url'] . ')';
            echo '</p>';

        }
        
    echo '</div>';

echo '</div>';

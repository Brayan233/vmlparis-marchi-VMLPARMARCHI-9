<?php

echo '<div class="row">';

    echo '<div class="column-100">';

        echo '<h6>Quote:</h6>';
        echo '<p class="value" style="font-size: 24px;">' . $options['quote'] . '</p>';

        echo '<h6>Link:</h6>';
        if ( $link = get_linotype_field_link( $options['link'] ) ) {
          
            echo '<p class="hero-link">';
                echo $link['title'] . ' (' . $link['url'] . ')';
            echo '</p>';
  
        }
        
    echo '</div>';

echo '</div>';

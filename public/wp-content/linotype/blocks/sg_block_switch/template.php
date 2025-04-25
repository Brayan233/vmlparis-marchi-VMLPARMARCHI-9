<?php 

block('header', $settings ); 

$switch = new sg_block_switch( $options );

  echo '<div class="container">';

    echo '<div class="dropdown">';

      echo '<button type="button" class="btn-unstyled dropdown-toggle">';

        $current = $switch->current();
        echo $current['title'];

      echo '</button>'; 

      echo '<div class="dropdown-menu">';

        echo '<div class="inner">';

          foreach( $switch->items() as $item ) {

            if ($item !== $current) {
              echo '<a href="' . $item['url'] . '" class="dropdown-link">' . $item['title'] . '</a>';
            }

          }

        echo '</div>';
      echo '</div>';

    echo '</div>';
  echo '</div>';

 block('footer', $settings );

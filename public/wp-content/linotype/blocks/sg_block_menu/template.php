<?php block('header', $settings ); ?>

  <nav class="menu-content">
    <?php 
      echo linotype_field_wp_menu( $options['menu'], array(
        "level_1" => array(
          "div" => "",
          "ul" => "nav", 
          "li" => "nav-item",
          "a" => "nav-link",
          "a_child" => "nav-link",
          "before" => "",
          "after" => ""
        ),
        "level_2" => array(
          "div" => "",
          "ul" => "nav sub-nav", 
          "li" => "nav-item",
          "a" => "nav-link",
          "before" => "",
          "after" => ""
        )
      ));
    ?>
  </nav>

	<?php

 block('footer', $settings );

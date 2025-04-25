<?php block('header', $settings, array( 'class' => array( $options['absolute'], $options['light'] ) ) );

  $lang = sg_block_nav_lang();
  
  ?>

  <header class="container navbar">
    <button type="button" class="btn-unstyled menu-button" id="menuButton" aria-label="<?php echo linotrad('Menu'); ?>">
      <span class="navicon"></span>
    </button>
    <nav class="site-nav" id="mainMenu">
      <div class="menu">
        <?php 
        echo linotype_field_wp_menu( $options['menu'], array(
          "level_1" => array(
            "div" => "",
            "ul" => "nav main-nav",
            "li" => "nav-item",
            "a" => "nav-link",
            "a_child" => "nav-link sub-nav-toggle",
            "before" => "",
            "after" => ""
          ),
          "level_2" => array(
            "div" => "sub-nav-wrap",
            "ul" => "nav sub-nav",
            "li" => "nav-item",
            "a" => "nav-link",
            "a_child" => "nav-link sub-nav-toggle",
            "before" => "",
            "after" => ""
          ),
          "level_3" => array(
            "div" => "sub-nav-wrap",
            "ul" => "nav sub-nav",
            "li" => "nav-item",
            "a" => "nav-link",
            "a_child" => "",
            "before" => "",
            "after" => ""
          )
        ));
        ?>
        <div class="nav-item desktop-tablet-hide">
          <a class="panel-toggle-login nav-link"><?php echo linotrad('Account'); ?></a>
        </div>
        <div class="nav-item desktop-tablet-hide">
          <div class="dropdown language-switcher">
            <button class="btn-unstyled dropdown-toggle" aria-label="<?php echo linotrad('Language'); ?>"><?php echo $lang['current']['code']; ?></button>
              <ul class="dropdown-menu">
                <li class="list-item current"><a href="<?php echo $lang['current']['link']; ?>" class="list-link"><?php echo $lang['current']['name']; ?></a></li>
                <?php foreach( $lang['items'] as $lang_item ) { echo '<li class="list-item"><a href="' . $lang_item['link'] . '" class="list-link">' . $lang_item['name'] . '</a></li>'; } ?>
              </ul>
            </div>
        </div>
      </div>
    </nav>
    <h1 class="logo">
      <a href="<?php if ( function_exists('pll_home_url') ) { echo pll_home_url(); } else { echo site_url(); } ?>">
        <?php 
        echo get_linotype_field_image( array(
          'alt' => 'Caroline de marchi',
          'class' => 'logo-img',
          'sources' => array(
            array(
              'id' => $options['logo'],
              'break' => 0,
              'crop' => true,
              'x' => 218,
              'y' => 25,
            ),
          ),
          'ratio' => false,
          'lazyload' => false,
          'webp' => true,
        ));
        echo get_linotype_field_image( array(
          'alt' => 'Caroline de marchi',
          'class' => 'logo-img-light',
          'sources' => array(
            array(
              'id' => $options['logo_light'],
              'break' => 0,
              'crop' => true,
              'x' => 218,
              'y' => 25,
            ),
          ),
          'ratio' => false,
          'lazyload' => false,
          'webp' => true,
        ));
        echo get_linotype_field_image( array(
          'alt' => 'Caroline de marchi',
          'class' => 'logo-img-sticky',
          'sources' => array(
            array(
              'id' => $options['logo_sticky'],
              'break' => 0,
              'crop' => true,
              'x' => 218,
              'y' => 14,
            ),
          ),
          'ratio' => false,
          'lazyload' => false,
          'webp' => true,
        ));
        ?>
      </a>
    </h1>
    <nav class="nav-right">
      <ul class="nav">
        <li class="nav-item mobile-tablet-hide">
          <div class="dropdown language-switcher">
          <button class="btn-unstyled dropdown-toggle" aria-label="<?php echo linotrad('Language'); ?>"><?php echo $lang['current']['code']; ?></button>
            <ul class="list-unstyled dropdown-menu">
              <?php foreach( $lang['items'] as $lang_item ) { echo '<li class="list-item"><a href="' . $lang_item['link'] . '" class="list-link">' . $lang_item['code'] . '</a></li>'; } ?>
            </ul>
          </div>
        </li>
        <li class="nav-item mobile-tablet-hide">
          <a class="panel-toggle-login nav-link"><?php echo linotrad('Account'); ?></a>
        </li>
        <li class="nav-item">
          <a class="panel-toggle-cart nav-link link-cart" role="button" aria-label="<?php echo linotrad('Cart'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g transform="translate(4 1)" stroke="#000" fill="none" fill-rule="evenodd"><rect y="6" width="16" height="16" rx="1"/><path d="M12 10.111V4a4 4 0 10-8 0v6.111"/></g></svg>
            <?php  ?>
            <?php do_action('wc_block_cart_mini_count'); ?>
          </a>
        </li>
      </ul>
    </nav>
  </header>

	<?php

 block('footer', $settings );

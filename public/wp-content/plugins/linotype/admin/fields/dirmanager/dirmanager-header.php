<?php

    @header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
  if ( ! defined( 'WP_ADMIN' ) )
    require_once( dirname( __FILE__ ) . '/admin.php' );

  /**
   * In case admin-header.php is included in a function.
   *
   * @global string    $title
   * @global string    $hook_suffix
   * @global WP_Screen $current_screen
   * @global WP_Locale $wp_locale
   * @global string    $pagenow
   * @global string    $update_title
   * @global int       $total_update_count
   * @global string    $parent_file
   */
  global $title, $hook_suffix, $current_screen, $wp_locale, $pagenow,
    $update_title, $total_update_count, $parent_file;

  // Catch plugins that include admin-header.php before admin.php completes.
  if ( empty( $current_screen ) )
    set_current_screen();

  get_admin_page_title();
  $title = esc_html( strip_tags( $title ) );

  if ( is_network_admin() ) {
    /* translators: Network admin screen title. 1: Network name */
    $admin_title = sprintf( __( 'Network Admin: %s' ), esc_html( get_network()->site_name ) );
  } elseif ( is_user_admin() ) {
    /* translators: User dashboard screen title. 1: Network name */
    $admin_title = sprintf( __( 'User Dashboard: %s' ), esc_html( get_network()->site_name ) );
  } else {
    $admin_title = get_bloginfo( 'name' );
  }

  if ( $admin_title == $title ) {
    /* translators: Admin screen title. 1: Admin screen name */
    $admin_title = sprintf( __( '%1$s &#8212; WordPress' ), $title );
  } else {
    /* translators: Admin screen title. 1: Admin screen name, 2: Network or site name */
    $admin_title = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress' ), $title, $admin_title );
  }

  /**
   * Filters the title tag content for an admin page.
   *
   * @since 3.1.0
   *
   * @param string $admin_title The page title, with extra context added.
   * @param string $title       The original page title.
   */
  $admin_title = apply_filters( 'admin_title', $admin_title, $title );

  wp_user_settings();

  _wp_admin_html_begin();
  ?>
  <title><?php echo $admin_title; ?></title>
  <?php

  wp_enqueue_style( 'colors' );
  wp_enqueue_style( 'ie' );
  wp_enqueue_script('utils');
  wp_enqueue_script( 'svg-painter' );

  $admin_body_class = preg_replace('/[^a-z0-9_-]+/i', '-', $hook_suffix);
  ?>
  <script type="text/javascript">
  addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
  var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>',
    pagenow = '<?php echo $current_screen->id; ?>',
    typenow = '<?php echo $current_screen->post_type; ?>',
    adminpage = '<?php echo $admin_body_class; ?>',
    thousandsSeparator = '<?php echo addslashes( $wp_locale->number_format['thousands_sep'] ); ?>',
    decimalPoint = '<?php echo addslashes( $wp_locale->number_format['decimal_point'] ); ?>',
    isRtl = <?php echo (int) is_rtl(); ?>;
  </script>
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <?php
  do_action( 'admin_enqueue_scripts', $hook_suffix );

  do_action( "admin_print_styles-{$hook_suffix}" );

  do_action( 'admin_print_styles' );

  do_action( "admin_print_scripts-{$hook_suffix}" );

  do_action( 'admin_print_scripts' );

  do_action( "admin_head-{$hook_suffix}" );

  do_action( 'admin_head' );

  ?>
  </head>
  <?php
  $admin_body_classes = apply_filters( 'admin_body_class', '' );
  ?>
  <body class="wp-admin wp-core-ui no-js <?php echo $admin_body_classes ; ?>">
  <script type="text/javascript">
    document.body.className = document.body.className.replace('no-js','js');
  </script>

  <div id="wpwrap">
  <?php //require(ABSPATH . 'wp-admin/menu-header.php'); ?>
  <div id="wpcontent">

  <div id="wpbody" role="main">


  <div id="wpbody-content" aria-label="<?php esc_attr_e('Main content'); ?>" tabindex="0">
  <?php

  //wp_enqueue_style( 'composer-iframe', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) . '/composer-iframe.css', false, false, 'screen' );

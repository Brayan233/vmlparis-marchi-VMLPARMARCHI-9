<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo wp_get_attachment_image_url( linoption('logo_app'), 'full', false ); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo wp_get_attachment_image_url( linoption('logo_fav'), 'full', false ); ?>">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <meta name="facebook-domain-verification" content="lqm23y0tf54f2kphp3j4vwkhbwldt5">
    <?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php endif; ?>
    <script data-cookieconsent="ignore">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KH9CKLS');</script>
    <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="b4cd5757-6223-4995-85be-33c55572ec2e" type="text/javascript" async></script>
    <?php wp_head(); ?>
</head>
  <body <?php body_class(); ?>>
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KH9CKLS" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
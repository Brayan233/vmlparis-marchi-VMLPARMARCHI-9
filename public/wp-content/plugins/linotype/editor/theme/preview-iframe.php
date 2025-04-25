<?php
/**
 * blocks preview
 *
 * @package WordPress
 * @subpackage blocks
 * @since 1.0.0
 */

require_once( dirname(dirname(dirname(dirname(dirname(dirname( __FILE__ )))))) . '/wp-load.php' );

add_filter('show_admin_bar', '__return_false');

if ( ! isset( $_GET['id'] ) && ! $_GET['id'] ) die('<h1>Sorry,</h1><p>you are not allowed to customize this block</p>' );

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php endif; ?>
    <?php wp_head(); ?>
    <style>
    html {
    margin: 0px;
    padding: 45px 20px;
    height: 100%;
    overflow: hidden;
    background-color: transparent!important;
    }
    body {
    margin: 0px;
    padding: 0px;
    width: 100%;
    height: 100%;
    background-color: transparent!important;
    }
    body {
    display: flex;
    justify-content: center;
    align-items: start;
    }
    #linotype---preview{
    display:inline-block;
    position: relative;
    z-index: 1;
    max-width: 100%;
    max-height: 100%;
    }
    #linotype---preview-ruler-left{
    display: block;
    position: absolute;
    z-index: 1;
    width: 0px;
    height: 10000px;
    top: -5000px;
    bottom: 0px;
    left: 0px;
    border-right: 1px dashed rgba(0, 0, 0, 0.25);
    }
    #linotype---preview-ruler-right{
    display: block;
    position: absolute;
    z-index: 1;
    width: 0px;
    height: 10000px;
    top: -5000px;
    bottom: 0px;
    right: 0px;
    border-right: 1px dashed rgba(0, 0, 0, 0.25);
    }
    #linotype---preview-ruler-top{
    display: block;
    position: absolute;
    z-index: 1;
    width: 10000px;
    height: 0px;
    left: -5000px;
    top: 0px;
    right: 0px;
    border-top: 1px dashed rgba(0, 0, 0, 0.25);
    }
    #linotype---preview-ruler-bottom{
    display: block;
    position: absolute;
    z-index: 1;
    width: 10000px;
    height: 0px;
    left: -5000px;
    bottom: 0px;
    right: 0px;
    border-top: 1px dashed rgba(0, 0, 0, 0.25);
    }
    </style>
</head>
<body <?php body_class(); ?>>

<div id="linotype---preview">

    <div id="linotype---preview-ruler-top"></div>
    <div id="linotype---preview-ruler-right"></div>
    <div id="linotype---preview-ruler-bottom"></div>
    <div id="linotype---preview-ruler-left"></div>

    <?php
    $DATA = LINOTYPE::$TEMPLATES->get($_GET['id']);
    include LINOTYPE_plugin::$plugin['dir'] . 'admin/fields/composer/composer.class.php';
    LINOTYPE_composer::render( $DATA['template'], LINOTYPE::$BLOCKS->get(), false, true );
    ?>

</div>

<?php wp_footer(); ?>
</body>
</html>

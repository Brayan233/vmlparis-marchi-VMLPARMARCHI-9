<?php

add_action('admin_menu', function() {
    global $submenu;
    $url = LINOTYPE_plugin::$plugin['url'] . 'editor/index.php';
    $submenu['linotype'][0] = array('Editor', 'manage_options', $url);
}, 9999999999999999 );
 

self::$admin->addLocation( 'linotype', array(
    "type" => 'option',
    "name"=>'LINOTYPE',
    "subname"=>'Editor',
    "title"=>'',
    'order' => 66,
    'margin' => '20px',
    'icon' => 'data:image/svg+xml;base64,' . base64_encode( file_get_contents( self::$plugin['dir'] .'assets/icons/blocks-menu.svg' ) ),
    "bt_save" => false
));

self::$admin->addMetabox( 'linotype_metabox', array(
"name"=>'List',
"context"=>'normal',
"priority"=>'default',
"force_state" => "open",
"hide_box_style" => true,
"hide_handle" => true,
"disable_switch" => true,
"disable_sortable" => true,
"remove_padding" => true,
));

self::$admin->addMeta('linotype_redirect', array(
    "title"=>'',
    "type"=> 'html',
    "options" => array(
        "data" => function(){
            echo '<a class="button" href="' . LINOTYPE_plugin::$plugin['url'] . 'editor/index.php">GO</a>';
        },
    ),
    "default"=>'',
    "help" => false,
    "padding" => "0px",
    "fullwidth" => true,
));

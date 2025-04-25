<?php

self::$admin->addLocation( 'linotype_full', array(
    "type" => 'option',
    "capability" => 'linotype_admin',
    "name"=>'Licence',
    "title"=>'',
    // 'order' => 10,
    "submenu" => "linotype",
));

self::$admin->addMetabox( 'metabox_linotype_full', array(
    "name" => "Settings",
    "context" => "normal",
    "priority" => "high",
    "force_state" => "open",
    "hide_box_style" => true,
    "hide_handle" => true,
    "disable_switch" => true,
    "disable_sortable" => true,
    "remove_padding" => true,
));

self::$admin->addMeta('linotype_licence', array(
    "title"=>'Licence old',
    "type"=> 'text',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "placeholder" => 'XXX-XXX-XXX-XXX-XXX',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
));

self::$admin->addMeta('linotype_repo', array(
    "title"=>'Licence ID',
    "type"=> 'text',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "placeholder" => 'https://test.wp-blocks.com/',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
));

self::$admin->addMeta('linotype_client_id', array(
    "title"=>'Licence ID',
    "type"=> 'text',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "placeholder" => 'xxxxxxxxxxxxxxxxxxxxx',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
));
self::$admin->addMeta('linotype_client_secret', array(
    "title"=>'Licence SECRET',
    "type"=> 'text',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "placeholder" => '*********************',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
));

$TOKEN = get_option('linotype_token', false );

if ( $TOKEN == false ) $TOKEN = 'no token';

self::$admin->addMeta('linotype_token', array(
    "title"=>'Licence TOKEN',
    "type"=> 'html',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "content" => $TOKEN
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
));


self::$admin->addMeta('linotype_token_actions', array(
    "title"=>'Update',
    "type"=> 'html',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
      "content" => '<a class="button" href="' . get_bloginfo('url') . '/wp-content/plugins/linotype/api/index.php' . '">Update</a><a class="button" href="' . get_bloginfo('url') . '/wp-content/plugins/linotype/api/index.php?reset=token' . '">Reset Token</a>',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
));

self::$admin->addMeta('linotype_sync', array(
    "title"=>'Sync',
    "type"=> 'textarea',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
      "height" => '800px',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
));

?>

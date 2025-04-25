<?php

$field = array(
'id' => 'map',
"title"=> 'Templates Mapping',
"desc"=> '',
"info"=> 'You can define different template for each type off page and define conditional rules to overide template in some case.',
"type"=> 'theme',
"value"=> $LINOTYPE_EDITOR['data']['map'],
"options" => array(
"map" => 'LINOTYPE_themes::get_map',
"default" => get_option('linotype_template_default'),
),
"help" => false,
'padding' => '15px 20px',
"fullwidth" => true,
);
include LINOTYPE_plugin::$plugin['dir'] . 'admin/fields/theme/theme.php';

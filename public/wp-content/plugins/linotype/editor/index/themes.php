<?php

$BLOCKS_DATA_FORMAT = array();

$BLOCKS_DATA = LINOTYPE::$THEMES->get();

if ( $BLOCKS_DATA ) {
    foreach ( $BLOCKS_DATA as $item_key => $item ) {

        if ( isset( $item['dir'] ) && $item['dir'] ) {
                
            if ( isset( $item['source'] ) ) {
                $BLOCKS_DATA_FORMAT[$item_key]['source'] = $item['source'];
            } else {
                $BLOCKS_DATA_FORMAT[$item_key]['source'] = 'custom';
            }
            if ( isset( $item['editor_link'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['editor_link'] = $item['editor_link'];
            if ( isset( $item['icon'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['icon'] = $item['icon'];
            if ( isset( $item['title'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['title'] = $item['title'];
            if ( isset( $item['desc'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['desc'] = $item['desc'];
            if ( isset( $item['preview'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['preview'] = $item['preview'];
            if ( isset( $item['sync_status'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['sync_status'] = $item['sync_status'];
            if ( isset( $item['sync_title'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['sync_title'] = $item['sync_title'];
            if ( isset( $item['category'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['category'] = $item['category'];
            if ( isset( $item['target'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['target'] = $item['target'];
            if ( isset( $item['version'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['version'] = $item['version'];
            if ( isset( $item['update'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['update'] = $item['update'];
            if ( isset( $item['author'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['author'] = $item['author'];
            if ( isset( $item['tags'] ) ) $BLOCKS_DATA_FORMAT[$item_key]['tags'] = $item['tags'];
        
        } else {

            linolog( 'themes errors : ' . $item_key, $item );

        }

    }
}

$field = array(
    'id' => 'list_themes',
    "title"=>'',
    "info" => "",
    "desc" => "",
    "type"=> 'list',
    "options" => array(
        "items" => $BLOCKS_DATA_FORMAT,
        'col' => 'col-md-3',
        'height' => '100px',
        'preview' => false,
        'as_list' => false,
        'toolbar' => function(){

            echo '<div style="editor-toolbar">

                <a class="button" href="' . LINOTYPE_plugin::$plugin['url'] . 'editor/index.php?type=fetch&target=theme#themes" id="linotype-fetch">Fetch</a>
                <a class="button"  href="' . LINOTYPE_plugin::$plugin['url'] . 'editor/index.php?type=import&target=theme" class="page-title-action">Import</a>
                <a class="button button-primary"  href="' . LINOTYPE_plugin::$plugin['url'] . 'editor/index.php?type=add&target=theme" class="page-title-action">Add Theme</a>
                
            </div>';

        }
    ),
    "default"=>'',
    "help" => false,
    "padding" => "0px",
    "fullwidth" => true,
);

include LINOTYPE_plugin::$plugin['dir'] . 'admin/fields/list/list.php';


?>
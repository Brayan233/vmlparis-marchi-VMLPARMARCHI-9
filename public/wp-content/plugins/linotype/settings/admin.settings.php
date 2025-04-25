<?php

self::$admin->addLocation( 'linotype_settings', array(
    "type" => 'option',
    "capability" => 'linotype_admin',
    "name"=>'Settings',
    "title"=>'',
    // 'order' => 6,
    "submenu" => "linotype",
));

self::$admin->addMetabox( 'metabox_blocks', array(
    "name" => "Settings",
    "context" => "normal",
    "priority" => "high",
    "force_state" => "open",
    "hide_box_style" => true,
    "hide_handle" => true,
    "disable_switch" => true,
    "disable_sortable" => true,
    "remove_padding" => true,
    "tabs_style"=>"nav",
));

self::$admin->addMeta('linotype_engine', array(
    "title"=>'Linotype Engine Type',
    "type"=> 'select',
    "info"=> '',
    "desc"=> '',
    "options" => array(
        "data" => array(
            array('title'=>"Full Linotype system",'value'=>"full"),
            array('title'=>"Only Linotype and templates system",'value'=>"templates"),
            array('title'=>"Only Linotype system",'value'=>"blocks"),
        ),
        "maxItems" => 1,
        "placeholder" => ""
    ),
    "default"=>'full',
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Engine",
));

self::$admin->addMeta( "linotype_content_post_types", array( 
    "title"=> 'Enable Linotype in Content',
    "type"=>'text',
    "options" => array(
        "placeholder" => 'All',
        "data" => function(){ return handypress_helper::all_post_types(); },
    ),
    "default" => "",
    "info" => "",
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Engine",
));

self::$admin->addMeta( "linotype_content_by_default", array( 
    "title"=> 'Linotype in Content by default',
    "type"=>'checkbox',
    "options" => array(
        "label" => 'Yes',
    ),
    "info" => "",
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Engine",
));

self::$admin->addMeta('linotype_theme', array(
    "title"=>'Current theme',
    "type"=> 'select',
    "info"=> '',
    "desc"=> '',
    "options" => array(
        "data" => LINOTYPE::$THEMES->get_select_data(),
        "maxItems" => 1,
        "plugins" => array('clear_button'),
        "placeholder" => "Select theme"
    ),
    "default"=>'linotype_theme_blockstarter',
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Theme",
));

self::$admin->addMeta('linotype_helper', array(
    "title"=>'Helper Panel',
    "type"=> 'checkbox',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "label" => 'Display the frontend helper panel',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Helper",
));

self::$admin->addMeta('linotype_editor_dark', array(
    "title"=>'Editor theme',
    "type"=> 'checkbox',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "label" => 'Activate editor dark theme',
        "value" => "blocks-editor-dark",
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Editor",
));

self::$admin->addMeta('linotype_editor_expert', array(
    "title"=>'Expert Mode',
    "type"=> 'checkbox',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "label" => 'Activate expert Linotype editor',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Editor",
));

self::$admin->addMeta('linotype_debug', array(
    "title"=>'Debug Mode',
    "type"=> 'checkbox',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "label" => 'Enable error log',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Admin",
));

self::$admin->addMeta('linotype_welcome', array(
    "title"=>'Hide Welcome',
    "type"=> 'checkbox',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "label" => 'Hide Welcome panel',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Admin",
));

self::$admin->addMeta('linotype_disable_wp_updates', array(
    "title"=>'disable Wordpress updates',
    "type"=> 'checkbox',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "label" => 'Yes',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Admin",
));
self::$admin->addMeta('linotype_disable_wp_adminbar', array(
    "title"=>'disable Admin bar',
    "type"=> 'checkbox',
    "info"=> '',
    "desc"=> '',
    "default"=>'',
    "options" => array(
        "label" => 'Yes',
    ),
    "help" => false,
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => false,
    //"tab" => "Admin",
));





self::$admin->addLocation( 'linotype_cache', array(
    "type" => 'option',
    "capability" => 'linotype_admin',
    "name"=>'Cache',
    "title"=>'',
    // 'order' => 10,
    // "add_header" => self::$plugin['dir'] .'settings/parts.sync.php',
    "submenu" => "linotype",
));



self::$admin->addMetabox( 'linotype_setting_metabox', array( 
    "name" => "Settings",
    "context" => "normal",
    "priority" => "high",
    "force_state" => "open", 
    "hide_box_style" => true,
    "hide_handle" => true,
    "disable_switch" => true,
    "disable_sortable" => true,
    "remove_padding" => true,
    "tabs_style"=>"nav",
));


self::$admin->addMeta('linotype_cache', array(
    "title"=> '',
    "desc"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Enable cache',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Cache',
));

self::$admin->addMeta( 'linotype_cache_reset', array(
    "title"=>'',
    "desc"=>'timestamp:' . LINOTYPE::$SETTINGS['cache']['timestamp'],
    "type" => "button-action",
    "options" => array(
        "label" => 'Clear All Cache',
        "desc" => '',
        "class" => 'button button-delete',
        "action" => function() { 
            LINOTYPE_cache::cache_html_empty();
        },
    ),
    "fullwidth" => true,
    "value_default" => '',
    'tab' => 'Cache'
    ));

self::$admin->addMeta('linotype_cache_list', array(
    "title"=> '',
    "desc"=> '',
    "type"=> 'html',
    "options" => array(
        "html" => function(){ 
            
            echo '<ul>';
            foreach( LINOTYPE_cache::get_cache_files() as $dir_key => $dir ) {
                
                echo '<li><h3>' . $dir_key . '</h3>';

                    echo '<ul style="margin-left: 10px;">';

                        foreach( $dir as $type_key => $type ) {

                            echo '<li><h3>' . $type_key . '</h3>';

                                echo '<ul>';

                                    foreach( $type as $file_key => $file ) {

                                        $url = base64_decode( str_replace( LINOTYPE::$SETTINGS['cache']['timestamp'] . '-', '', basename( $file ) ) );
                                        $url_cache = str_replace( ABSPATH, get_bloginfo('url') . '/', $file );

                                        echo '<li><i style="font-size: 11px; background: #ddd; color: #888; padding: 3px;">' . gmdate("Y-m-d H:i", filemtime( $file ) ).  '</i> <a target="_blank" href="' . $url_cache . '">' . base64_decode( str_replace( LINOTYPE::$SETTINGS['cache']['timestamp'] . '-', '', basename( $file ) ) ) . '</a> <a style="color: #de445a;" href="admin.php?page=linotype_cache&cache_delete=' . $dir_key . '/' . $type_key . '/' . basename( basename( $file ) ) . '">delete</a></li>';
                                    
                                    }
                                
                                echo '<ul>';

                            echo '<li>';

                        }

                    echo '<ul>';

                echo '<li>';
            
            }
            echo '</ul>';

        },
    ),
    "help" => false,
    "padding" => "0px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Cache',
));

self::$admin->addMeta('linotype_cache_max_age', array(
    "title"=> 'Cache Max age in hours',
    "desc"=> '720 = 30 days',
    "type"=> 'text',
    "options" => array(
        "placeholder" => '720',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Cache',
));

self::$admin->addMeta('linotype_cache_exception', array(
    "title"=> 'Cache exception',
    "desc"=> 'e.g. "/cart" or "/account/*" and one per line',
    "type"=> 'textarea',
    "options" => array(
        "placeholder" => '',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Cache',
));




self::$admin->addMeta('linotype_lazyload', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Lazy Load',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Minify',
));

self::$admin->addMeta('linotype_minify_html', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Minify Html',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Minify',
));

self::$admin->addMeta('linotype_minify_css', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Minify CSS',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Minify',
));
self::$admin->addMeta('linotype_minify_js', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Minify JS',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Minify',
));
self::$admin->addMeta('linotype_minify_plugins', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Minify plugins scripts and styles',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Plugins',
));

self::$admin->addMeta( 'linotype_minify_plugins_scripts', array(
    "title"=> 'Plugins Scripts',
    "info"=>'',
    "type" => "select-list",
    "options" => array(
        "data" => LINOTYPE::$SETTINGS['cache']['all_assets']['scripts'],
        'data_map' => array( 'title' => 'src', 'value' => 'handle' ),
        'add_title' => '',
        'add_link' => '',
        "multiple" => true,
        "label_select" => "Add",
        "label_unselect" => "Remove",
        "height" => '500px',
        "min-width" => '100%',
    ),
    "fullwidth" => true,
    "value_default" => '',
    'tab' => 'Plugins'
));

self::$admin->addMeta( 'linotype_minify_plugins_styles', array(
    "title"=> 'Plugins Styles',
    "info"=>'',
    "type" => "select-list",
    "options" => array(
        "data" => LINOTYPE::$SETTINGS['cache']['all_assets']['styles'],
        'data_map' => array( 'title' => 'src', 'value' => 'handle' ),
        'add_title' => '',
        'add_link' => '',
        "multiple" => true,
        "label_select" => "Add",
        "label_unselect" => "Remove",
        "height" => '500px',
        "min-width" => '100%',
    ),
    "fullwidth" => true,
    "value_default" => '',
    'tab' => 'Plugins'
));

// self::$admin->addMeta( 'linotype_minify_plugins_create', array(
//     "title"=>'',
//     "desc"=>'',
//     "type" => "button-action",
//     "options" => array(
//         "label" => 'Rebuilt cache scripts and styles file',
//         "desc" => '',
//         "class" => 'button button-delete',
//         "action" => function() { 
//             LINOTYPE_cache::cache_plugins_create();
//         },
//     ),
//     "fullwidth" => true,
//     "value_default" => '',
//     'tab' => 'Plugins'
// ));

self::$admin->addMeta( 'linotype_minify_plugins_reset', array(
"title"=>'',
"desc"=>'',
"type" => "button-action",
"options" => array(
    "label" => 'Reset plugins scripts and styles index',
    "desc" => '',
    "class" => 'button button-delete',
    "action" => function() { 
        LINOTYPE_cache::cache_plugins_index_reset();
    },
),
"fullwidth" => true,
"value_default" => '',
'tab' => 'Plugins'
));



self::$admin->addMeta('linotype_remove_css_js_version', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Remove JS CSS version',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Sanitize',
));
self::$admin->addMeta('linotype_add_jquery_defer_attribute', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Add jquery Defer Attribute',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Sanitize',
));
self::$admin->addMeta('linotype_add_defer_attribute', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Add script Defer Attribute',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Sanitize',
));


self::$admin->addMeta('linotype_dequeue_jquery_migrate', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Dequeue jquery migrate',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Sanitize',
));
self::$admin->addMeta('linotype_disable_emojis', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Disable emojis',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Sanitize',
));
self::$admin->addMeta('linotype_disable_embeds_code', array(
    "title"=> '',
    "info"=> '',
    "type"=> 'checkbox',
    "options" => array(
        "label" => 'Disable embeds code',
    ),
    "help" => false,
    "padding" => "20px 20px 0px 20px",
    "fullwidth" => true,
    'tab' => 'Sanitize',
));





self::$admin->addLocation( 'linotype_sync', array(
    "type" => 'option',
    "capability" => 'linotype_admin',
    "name"=>'Sync',
    "title"=>'',
    // 'order' => 10,
    // "add_header" => self::$plugin['dir'] .'settings/parts.sync.php',
    "submenu" => "linotype",
));



self::$admin->addMetabox( 'linotype_setting_metabox', array( 
    "name" => "Settings",
    "context" => "normal",
    "priority" => "high",
    "force_state" => "open", 
    "hide_box_style" => true,
    "hide_handle" => true,
    "disable_switch" => true,
    "disable_sortable" => true,
    "remove_padding" => true,
    "tabs_style"=>"nav",
));

self::$admin->addMeta( "linotype_sync_github_key", array( 
    "title" => "Github Consumer Key",
    "info" => '',
    "desc" => '',
    "options" => array(
        "placeholder" => "******************"
    ),
    "type" => "text",
    "fullwidth" => true,
    "padding" => "10px 20px 0px 20px",
    // "tab" => "settings"
));
self::$admin->addMeta( "linotype_sync_github_user", array( 
    "title" => "Github User",
    "info" => '',
    "desc" => '',
    "options" => array(
        "placeholder" => "username"
    ),
    "type" => "text",
    "fullwidth" => true,
    "padding" => "10px 20px 0px 20px",
    // "tab" => "settings",
    // "bt_save" => true,
));

self::$admin->addMeta( "linotype_sync_github_repo", array( 
    "title" => "Github Repo",
    "info" => '',
    "desc" => '',
    "options" => array(
        "placeholder" => "repo"
    ),
    "type" => "text",
    "fullwidth" => true,
    "padding" => "10px 20px 0px 20px",
    // "tab" => "settings",
    // "bt_save" => true,
));


?>
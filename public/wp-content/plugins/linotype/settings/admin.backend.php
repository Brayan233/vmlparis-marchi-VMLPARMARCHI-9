<?php

self::$admin->addLocation( 'wp_backend', array(
	"name"=>'Backend',
	"subname" => "Customize",
	"title"=>'Backend Customizer',
	"icon" => "dashicons-layout",
	// "submenu"=>'options-general.php',
));

self::$admin->addMetabox( 'wp_backend_metabox', array(
"name"=>'Template',
"context"=>'normal',
"priority"=>'over',
"force_state" => "open",
"hide_box_style" => true,
"hide_handle" => true,
"disable_switch" => true,
"disable_sortable" => true,
"remove_padding" => true,
"tabs_style"=>"nav",
"tabs_contents" => array(

	"toolbar" => array( "label"=> 'Toolbar', "title"=>'', "desc"=>'', "icon"=>'', "enable"=> true ),
	"sidebar" => array( "label"=> 'Sidebar', "title"=>'', "desc"=>'', "icon"=>'', "enable"=>true ),
	"header" => array( "label"=> 'Header', "title"=>'', "desc"=>'', "icon"=>'', "enable"=>true ),
	"body" => array( "label"=> 'Body', "title"=>'', "desc"=>'', "icon"=>'', "enable"=>true ),
	"footer" => array( "label"=> 'Footer', "title"=>'', "desc"=>'', "icon"=>'', "enable"=>true ),
	"login" => array( "label"=> 'Login', "title"=>'', "desc"=>'', "icon"=>'', "enable"=>true ),
	"extra" => array( "label"=> 'Extra', "title"=>'', "desc"=>'', "icon"=>'', "enable"=>true ),
	"vc" => array( "label"=> 'VisualComposer', "title"=>'', "desc"=>'', "icon"=>'', "enable"=>true ),
	"wc" => array( "label"=> 'WooCommerce', "title"=>'', "desc"=>'', "icon"=>'', "enable"=>true ),
	//"preset" => array( "label"=> 'Preset', "title"=>'', "desc"=>'', "icon"=>'', "enable"=>true ),

),
));

/*
 *
 * LOGIN
 *
 */

self::$admin->addMeta('WPBACKEND_login_redirect_admin', array(
	"title"=>'Login Redirect for admins',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

self::$admin->addMeta('WPBACKEND_login_redirect_notadmin', array(
	"title"=>'Login Redirect for users',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

self::$admin->addMeta('WPBACKEND_login_auto_remember', array(
	"title"=>'Remember',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Auto check "Remember me"',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

self::$admin->addMeta('WPBACKEND_login_hide_h1', array(
	"title"=>'Hide H1',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Hide H1',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

// self::$admin->addMeta('WPBACKEND_login_header_logo', array(
// 	"title"=>'Header Logo',
// 	"type"=>'image',
    // "options" => array(
    //     "output"=>"url"
    // ),
// 	"options" => array(
// 	),
// 	"padding" => "20px 0px 0px 0px",
// 	"tab" => "login"
// ));

self::$admin->addMeta('WPBACKEND_login_hide_message', array(
	"title"=>'Hide message',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Hide all login message error and shake animation',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

self::$admin->addMeta('WPBACKEND_login_hide_back', array(
	"title"=>'Back button',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Hide back to website button',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

self::$admin->addMeta('WPBACKEND_login_hide_nav', array(
	"title"=>'Hide nav',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Hide nav button',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

self::$admin->addMeta('WPBACKEND_logout_go_home', array(
	"title"=>'Logout Go Home',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Force logout to home page',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

self::$admin->addMeta('WPBACKEND_login_theme', array(
	"title"=>'Login theme',
	"type"=>'select',
	"options" => array(
        "data" => array(
        	array( "title"=> "dark", "value" => ""),
        	array( "title"=> "light", "value" => "light"),
        ),
        "multiple" => false,
    ),
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

self::$admin->addMeta('WPBACKEND_login_panel_color', array(
	"title"=>'Panel color',
	"type"=>'color',
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));
self::$admin->addMeta('WPBACKEND_login_panel_padding', array(
	"title"=>'Panel padding',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));
self::$admin->addMeta('WPBACKEND_login_panel_margin', array(
	"title"=>'Panel margin',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));
self::$admin->addMeta('WPBACKEND_login_panel_max_width', array(
	"title"=>'Panel width',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

self::$admin->addMeta('WPBACKEND_login_bg', array(
	"title"=>'login Background',
	"type"=>'background',
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));

self::$admin->addMeta('WPBACKEND_login_logo', array(
	"title"=>'Logo',
	"type"=>'background',
	"padding" => "20px 0px 0px 0px",
	"tab" => "login"
));


/*
 *
 * LAYOUT
 *
 */
// self::$admin->addMeta('WPBACKEND_preset', array(
// 	"title"=>'',
// 	"type"=>'radio',
// 	"options" => array(
// 		"data" => array(
//             array( "title" => "", "img" => WPBACKEND_URL . 'img/layouts/layout1.png', "value" => "" ),
//             array( "title" => "", "img" => WPBACKEND_URL . 'img/layouts/layout2.png', "value" => "wp-backend-layout-2" ),
//             array( "title" => "", "img" => WPBACKEND_URL . 'img/layouts/layout3.png', "value" => "wp-backend-layout-3" ),
//             array( "title" => "", "img" => WPBACKEND_URL . 'img/layouts/layout4.png', "value" => "wp-backend-layout-4" ),
//             array( "title" => "", "img" => WPBACKEND_URL . 'img/layouts/layout5.png', "value" => "wp-backend-layout-5" ),
//             array( "title" => "", "img" => WPBACKEND_URL . 'img/layouts/layout6.png', "value" => "wp-backend-layout-6" ),
//             array( "title" => "", "img" => WPBACKEND_URL . 'img/layouts/layout7.png', "value" => "wp-backend-layout-7" ),
//             array( "title" => "", "img" => WPBACKEND_URL . 'img/layouts/layout8.png', "value" => "wp-backend-layout-8" ),
//         ),
// 	),
// 	"fullwidth" => true,
// 	"padding" => "20px 0px 0px 0px",
// 	"tab" => "preset"
// ));




/*
 *
 * HEADER
 *
 */
//self::$admin->addMeta('WPBACKEND_title_block', array( "title"=>'Header', "desc"=>'', "type"=>'title', "padding" => "20px 0px 0px 0px", "tab" => "header" ));

self::$admin->addMeta('WPBACKEND_header', array(
	"title"=>'Header',
	"type"=>'radio',
	"options" => array(
		"data" => array(
            array( "title" => "No Custom Header", "value" => "" ),
            array( "title" => "Before Toolbar", "value" => "before_toolbar" ),
            array( "title" => "After Toolbar", "value" => "after_toolbar" ),
            array( "title" => "Before Content", "value" => "before_content" ),
        ),
        //"display" => "list",
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "header"
));

self::$admin->addMeta('WPBACKEND_header_maxwidth', array(
	"title"=>'Header Max Width',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "header"
));
self::$admin->addMeta('WPBACKEND_header_height', array(
	"title"=>'Header Height',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "header"
));
self::$admin->addMeta('WPBACKEND_header_logo', array(
	"title"=>'Header Logo',
    "type"=>'image',
    "options" => array(
        "output"=>"url"
    ),
	"padding" => "20px 0px 0px 0px",
	"tab" => "header"
));
self::$admin->addMeta('WPBACKEND_header_logo_width', array(
	"title"=>'Header Logo Width',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "header"
));
self::$admin->addMeta('WPBACKEND_header_logo_padding', array(
	"title"=>'Header Logo Padding',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "header"
));
self::$admin->addMeta('WPBACKEND_header_bg', array(
	"title"=>'Header Background',
	"type"=>'background',
	"padding" => "20px 0px 0px 0px",
	"tab" => "header"
));
self::$admin->addMeta('WPBACKEND_header_content_bg', array(
	"title"=>'Header Content Background',
	"type"=>'background',
	"padding" => "20px 0px 0px 0px",
	"tab" => "header"
));
self::$admin->addMeta('WPBACKEND_header_php', array(
	"title"=>'Header Content',
	"type"=>'codemirror',
	"options" => array(
		"mode" => "php",
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "header"
));

/*
 *
 * TOOLBAR
 *
 */
//self::$admin->addMeta('WPBACKEND_title_toolbar', array( "title"=>'Toolbar', "type"=>'title', "padding" => "20px 0px 0px 0px", "tab" => "toolbar" ));

self::$admin->addMeta('WPBACKEND_toolbar_position', array(
	"title"=>'Toolbar Position',
	"type"=>'radio',
	"options" => array(
		"data" => array(
            array( "title" => "Fixed (default)", "value" => "" ),
            array( "title" => "Fixed in wrap", "value" => "WPBACKEND_toolbar_fixed_inwrap" ),
            array( "title" => "Relative", "value" => "WPBACKEND_toolbar_relative" ),
            array( "title" => "Relative in wrap", "value" => "WPBACKEND_toolbar_relative_inwrap" ),
            array( "title" => "Fixed Bottom", "value" => "WPBACKEND_toolbar_fixed_bottom" ),
            array( "title" => "Hide", "value" => "WPBACKEND_toolbar_hide" ),
        ),
        //"display" => "list",
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));
// self::$admin->addMeta( 'WPBACKEND_toolbar_options', array(
//     "title" => "Options",
//     "type" => "panel-setting",
//     "desc" => "",
//     "fullwidth" => false,
//     "options" => array(

//     	"items" => array(
// 			array(
// 			    "title" => "Toolbar Max Width",
// 			    "id" => "maxwidth",
// 			    "type" => "button",
// 			    "options" => array(
// 			    	"" => "Full Width",
// 			    	"1500" => "1500px",
// 			    	"1300" => "1300px",
// 			    	"1200" => "1200px",
// 			    ),
// 			    "custom" => true,
// 			),
// 			array(
// 			    "title" => "light Toolbar",
// 			    "id" => "light",
// 			    "type" => "button",
// 			    "options" => array(
// 			    	"" => "Default",
// 			    	"light" => "light",
// 			    ),
// 			    "custom" => false,
// 			),
// 			array(
// 			    "title" => "large Toolbar",
// 			    "id" => "large",
// 			    "type" => "button",
// 			    "options" => array(
// 			    	"" => "Small",
// 			    	"large" => "Large",
// 			    ),
// 			    "custom" => false,
// 			),

// 		),

//     ),
//     "padding" => "20px 0px 0px 0px",
//     "tab" => "toolbar"
// ));

self::$admin->addMeta('WPBACKEND_toolbar_maxwidth', array(
	"title"=>'Toolbar Max Width',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));
self::$admin->addMeta('WPBACKEND_toolbar_light', array(
	"title"=>'light Toolbar',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Force Light shame of the toolbar',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));
self::$admin->addMeta('WPBACKEND_toolbar_large', array(
	"title"=>'Large Toolbar',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Enlarge the toolbar',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));
self::$admin->addMeta('WPBACKEND_toolbar_shadow', array(
	"title"=>'Toolbar shadow',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Add toolbar shadow',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));
self::$admin->addMeta('WPBACKEND_toolbar_margin_left_right', array(
	"title"=>'Toolbar margin',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Add left right margin',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));
self::$admin->addMeta('WPBACKEND_toolbar_border_bottom', array(
	"title"=>'Toolbar border',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Add border bottom',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));

$WPBACKEND_default_toolbar = get_option('WPBACKEND_default_toolbar_tree');

if( $WPBACKEND_default_toolbar ) {
	foreach ( $WPBACKEND_default_toolbar as $key => $value ) {

		$item = array();

		$item["title"] = $key;
		if ( isset( $value->title ) ) $item["title"] = $value->title;
		$item["value"] = $key;

		$WPBACKEND_toolbar_remove_default[] = $item;

	}
}

self::$admin->addMeta('WPBACKEND_toolbar_remove_default', array(
	"title"=>'Remove Default items',
	"type"=>'selectize',
	"options" => array(
        "data" => $WPBACKEND_toolbar_remove_default,
        "multiple" => true,
        "custom" => true,
        "placeholder" => "",
    ),
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));

self::$admin->addMeta('WPBACKEND_toolbar_enable', array(
	"title"=>'Use custom toolbar menu',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Enable the toolbar menu customizer',
	),
	"padding" => "20px 0px 0px 0px",
	//"fullwidth" => true,
	"tab" => "toolbar"
));

self::$admin->addMeta('WPBACKEND_toolbar_admin_hide', array(
	"title"=>'Hide toolbar menu in the backend',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Remove from admin',
	),
	"padding" => "20px 0px 0px 0px",
	//"fullwidth" => true,
	"tab" => "toolbar"
));

self::$admin->addMeta('WPBACKEND_toolbar_roles', array(
	"title"=>'Create custom menu for',
	"type"=>'selectize',
	"options" => array(
        "data" => array(),//$WPBACKEND->all_roles,
        "placeholder" => "Select roles to create a new custom menu for each",
    ),
	"padding" => "20px 0px 0px 0px",
	// "fullwidth" => true,
	"tab" => "toolbar"
));

self::$admin->addMeta('WPBACKEND_toolbar_logo', array(
	"title"=>'Toolbar Logo',
	"type"=>'image',
    "options" => array(
        "output"=>"url"
    ),
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));
self::$admin->addMeta('WPBACKEND_toolbar_logo_width', array(
	"title"=>'Toolbar Logo Width',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));
self::$admin->addMeta('WPBACKEND_toolbar_logo_padding', array(
	"title"=>'Toolbar Logo Padding',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));

self::$admin->addMeta('WPBACKEND_toolbar_custom', array(
	"title"=>'Custom Toolbar',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'replace default toolbar with niche css only menu',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar"
));

self::$admin->addMeta('WPBACKEND_toolbar_custom_css', array(
	"title"=>'Custom Toolbar CSS',
	"type"=>'textarea',
	"padding" => "20px 0px 0px 0px",
	"tab" => "toolbar",
	"default" => "toolbar"
));



/*
 *
 * SIDEBAR
 *
 */
//self::$admin->addMeta('WPBACKEND_title_sidebar', array( "title"=>'Sidebar', "type"=>'title', "padding" => "20px 0px 0px 0px", "tab" => "sidebar" ));

self::$admin->addMeta('WPBACKEND_sidebar_position', array(
	"title"=>'Sidebar Position',
	"type"=>'radio',
	"options" => array(
		"data" => array(
            array( "title" => "Left (default)", "value" => "" ),
            array( "title" => "Right", "value" => "WPBACKEND_sidebar_right" ),
            array( "title" => "Hide", "value" => "WPBACKEND_sidebar_hide" ),
        ),
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "sidebar"
));
self::$admin->addMeta('WPBACKEND_sidebar_scroll', array(
	"title"=>'Scroll Debug',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Debug sidebar scroll',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "sidebar"
));
self::$admin->addMeta('WPBACKEND_sidebar_hidecorner', array(
	"title"=>'Hide Corner',
	"desc"=>'',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Hide Sidebar Corner',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "sidebar"
));
self::$admin->addMeta('WPBACKEND_sidebar_enable', array(
	"title"=>'Customize Sidebar',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Enable the sidebar customizer',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "sidebar"
));

if ( get_option('WPBACKEND_sidebar_enable') ) {

	//edit sidebar content
	self::$admin->addMeta('WPBACKEND_custom_menu', array(
		"title"=>'Side Menu',
		"type"=>'nestable',
		"info" => "Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.",
		"options" => array(
			"data" => get_option( 'WPBACKEND_default_menu' ),
			"collapsed" => true,
		),
		"padding" => "20px 0px 0px 0px",
		"tab" => "sidebar"
	));

}


/*
 *
 * BODY
 *
 */
//self::$admin->addMeta('WPBACKEND_title_body', array( "title"=>'Body', "type"=>'title', "padding" => "20px 0px 0px 0px", "tab" => "body" ));

self::$admin->addMeta('WPBACKEND_body_maxwidth', array(
	"title"=>'Body Max Width',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "body"
));
self::$admin->addMeta('WPBACKEND_body_bg', array(
	"title"=>'Body Background',
	"type"=>'background',
	"padding" => "20px 0px 0px 0px",
	"tab" => "body"
));
self::$admin->addMeta('WPBACKEND_body_content_bg', array(
	"title"=>'Body Content Background',
	"type"=>'background',
	"padding" => "20px 0px 0px 0px",
	"tab" => "body"
));

/*
 *
 * FOOTER
 *
 */
//self::$admin->addMeta('WPBACKEND_title_block', array( "title"=>'Footer', "desc"=>'', "type"=>'title', "padding" => "20px 0px 0px 0px", "tab" => "footer" ));

self::$admin->addMeta('WPBACKEND_footer', array(
	"title"=>'Footer',
	"type"=>'radio',
	"options" => array(
		"data" => array(
            array( "title" => "No Custom Footer", "value" => "" ),
            array( "title" => "Before Content", "value" => "before_content" ),
            array( "title" => "Before Content in wrap", "value" => "before_content_inwrap" ),
            array( "title" => "Width Footer", "value" => "with_footer" ),
            array( "title" => "Width Footer in wrap", "value" => "with_footer_inwrap" ),
        ),
        //"display" => "list",
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "footer"
));

self::$admin->addMeta('WPBACKEND_footer_default', array(
	"title"=>'Footer',
	"type"=>'radio',
	"options" => array(
		"data" => array(
            array( "title" => "Default", "value" => "" ),
            array( "title" => "Hide Infos", "value" => "hide_infos" ),
            array( "title" => "Hide Version", "value" => "hide_version" ),
            array( "title" => "Hide All", "value" => "hide_all" ),
        ),
        //"display" => "list",
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "footer"
));

self::$admin->addMeta('WPBACKEND_footer_custom_txt', array(
	"title"=>'Footer Custom Infos',
	"type"=>'textarea',
	"padding" => "20px 0px 0px 0px",
	"tab" => "footer"
));

self::$admin->addMeta('WPBACKEND_footer_maxwidth', array(
	"title"=>'Footer Max Width',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "footer"
));
self::$admin->addMeta('WPBACKEND_footer_height', array(
	"title"=>'Footer Height',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "footer"
));
self::$admin->addMeta('WPBACKEND_footer_bg', array(
	"title"=>'Footer Background',
	"type"=>'background',
	"padding" => "20px 0px 0px 0px",
	"tab" => "footer"
));
self::$admin->addMeta('WPBACKEND_footer_content_bg', array(
	"title"=>'Footer Content Background',
	"type"=>'background',
	"padding" => "20px 0px 0px 0px",
	"tab" => "footer"
));
self::$admin->addMeta('WPBACKEND_footer_php', array(
	"title"=>'Footer Content',
	"type"=>'codemirror',
	"options" => array(
		"mode" => "php",
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "footer"
));


/*
 *
 * VISUALCOMPOSER
 *
 */
self::$admin->addMeta('WPBACKEND_vc_relative', array(
	"title"=>'Fixed toolbar',
	"desc"=>'',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Disable the fixed toolbar (keep it in the metabox)',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "vc"
));
self::$admin->addMeta('WPBACKEND_vc_fixed_editor', array(
	"title"=>'Fullscreen edit',
	"desc"=>'',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Enable fullscreen editor (never move or resize the editor)',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "vc"
));
self::$admin->addMeta('WPBACKEND_vc_boxed_row', array(
	"title"=>'Boxed',
	"desc"=>'',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Enable the plain boxed style for rows',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "vc"
));
self::$admin->addMeta('WPBACKEND_tiny_switch', array(
	"title"=>'Editor Switch',
	"desc"=>'',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Enable tiny switch button',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "vc"
));

// self::$admin->addMeta('WPBACKEND_vc_custom_style', array(
// 	"title"=>'Clean style',
// 	"desc"=>'',
// 	"type"=>'checkbox',
// 	"options" => array(
// 		"label"=>'Enable the clean style (can be buggy with custom component)',
// 	),
// 	"padding" => "20px 0px 0px 0px",
// 	"tab" => "vc"
// ));
/*
 *
 * WOOCOMMERCE
 *
 */
self::$admin->addMeta('WPBACKEND_wc_disable_notif', array(
	"title"=>'Updater Notif',
	"desc"=>'',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Disable Updater Notif',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "wc"
));
/*
 *
 * EXTRA
 *
 */
//self::$admin->addMeta('WPBACKEND_title_extra', array( "title"=>'Extra', "type"=>'title', "padding" => "20px 0px 0px 0px", "tab" => "extra" ));

self::$admin->addMeta('WPBACKEND_post_side_column_width', array(
	"title"=>'Post Side Column Width',
	"desc"=>'default: 280px',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "extra"
));
self::$admin->addMeta('WPBACKEND_title_absolute', array(
	"title"=>'Absolute title',
	"desc"=>'Move the page title in absolute',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "extra"
));
self::$admin->addMeta('WPBACKEND_notice_fixed', array(
	"title"=>'Fixed notice',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Fix the notice up right',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "extra"
));
self::$admin->addMeta('WPBACKEND_editor_expand_debug', array(
	"title"=>'Expend editor',
	"desc"=>'',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Force expend editor on top',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "extra"
));
self::$admin->addMeta('WPBACKEND_screenmeta_fixed', array(
	"title"=>'Screen metas',
	"desc"=>'',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Force fixed top',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "extra"
));

self::$admin->addMeta('WPBACKEND_hide_admin_bar', array(
	"title"=>'Admin Bar',
	"desc"=>'',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Hide Frontend Admin Bar',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "extra"
));





/*
*
* TOOLBAR MENU
*
*/
self::$admin->addLocation( 'wp_backend_toolbar', array(
	"name"=>'Toolbars',
	"title"=>'Toolbars',
	"submenu"=>'wp_backend',
));

self::$admin->addMetabox( 'wp_backend_toolbar_metabox', array(
"name"=>'Toolbar Menu',
"context"=>'normal',
"priority"=>'over',
"force_state" => "open",
"hide_box_style" => true,
"hide_handle" => true,
"disable_switch" => true,
"disable_sortable" => true,
"remove_padding" => true,
"tabs_style"=>"nav",
"tabs_contents" => array(
	"default" => array( "label"=> 'Default' ),
),
));

// if ( get_option('WPBACKEND_toolbar_enable') ) {

	$toolbar_menu_params = array(
		"title"=>'',
		"type"=>'nestable',
		//"info" => "Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.",
		"options" => array(
			"data" => get_option( 'WPBACKEND_default_menu' ),
			"collapsed" => true,
			"data_custom" => array(
				array(
					"id" => "default",
					"title" => "Default Link",
					"button" => "Add Menu",
					"desc" => "Add custom page to the buddypress menu.",
					"data" => get_option('WPBACKEND_default_menu'),
					"fields" => array(
						"id" => "",
						"title" => "",
						"icon" => "",
						"role" => "",
						"href" => "",
						"class" => "",
						"target" => "",
					),
				),
				array(
					"id" => "link",
					"title" => "Link",
					"button" => "Add Link",
					"desc" => "Add custom link to the menu.",
					"fields" => array(
						"id" => "Link",
						"title" => "Link",
						"icon" => "",
						"role" => "read",
						"href" => "http://",
						"class" => "",
						"target" => "",
					),
				),
				array(
					"id" => "logout",
					"title" => "Logout",
					"button" => "Add Logout",
					"desc" => "Add logout link",
					"fields" => array(
						"id" => "logout",
						"title" => "Logout",
						"icon" => "dashicons dashicons-migrate",
						"role" => "read",
						// "href" => wp_logout_url( home_url() ),
						"class" => "",
						"target" => "",
					),
				),
				array(
					"id" => "separator",
					"title" => "Separator",
					"button" => "Add Separator",
					"desc" => "Add separator to the menu.",
					"fields" => array(
						"id" => "Separator",
						"role" => "read",
					),
				),
			),
		),
		"padding" => "0px 0px 20px 0px",
		"fullwidth" => true,
		"tab" => "default"
	);

	self::$admin->addMeta('WPBACKEND_custom_toolbar', $toolbar_menu_params );

	if ( get_option( 'WPBACKEND_toolbar_roles' ) ) {
		$custom_menu_roles_enable = explode( ',', get_option( 'WPBACKEND_toolbar_roles' ) );
		foreach ( $custom_menu_roles_enable as $role ) {

			$toolbar_menu_params_role = $toolbar_menu_params;

			$toolbar_menu_params_role['tab'] = $role;

			self::$admin->addMeta('WPBACKEND_custom_toolbar_' . $role, $toolbar_menu_params_role );

		}
	}

	//all in toolbar parent menu
	// self::$admin->addMeta('WPBACKEND_toolbar_parent', array(
	// 	"title"=>'Single toolbar menu',
	// 	"type"=>'checkbox',
	// 	"options" => array(
	// 		"label"=>'Display All toolbar menus in single parent menu',
	// 	),
	// 	"padding" => "20px 0px 0px 0px",
	// 	"tab" => "toolbar"
	// ));

// }


/*
*
* SETTINGS MENU
*
*/
self::$admin->addLocation( 'wp_backend_notifs', array(
	"name"=>'Notifications',
	"title"=>'Notifications',
	"submenu"=>'wp_backend',
	"bt_save" => false,
));

self::$admin->addMetabox( 'wp_backend_toolbar_metabox', array(
"name"=>'Toolbar Menu',
"context"=>'normal',
"priority"=>'over',
"force_state" => "open",
"hide_box_style" => true,
"hide_handle" => true,
"disable_switch" => true,
"disable_sortable" => true,
"remove_padding" => true,
"tabs_style"=>"nav",
"tabs_contents" => array(
	"default" => array( "label"=> 'Default' ),
),
));


/*
*
* DASHBOARD
*
*/
self::$admin->addLocation( 'wp_backend_dashboard', array(
	"name"=>'Dashboard',
	"title"=>'Dashboard',
	"submenu"=>'wp_backend',
));

self::$admin->addMetabox( 'wp_backend_metabox', array(
"name"=>'Toolbar Menu',
"context"=>'normal',
"priority"=>'over',
"force_state" => "open",
"hide_box_style" => true,
"hide_handle" => true,
"disable_switch" => true,
"disable_sortable" => true,
"remove_padding" => true,

));

// self::$admin->addMeta('wp_backend_dashboard', array(
//     "title"=>'',
//     "type"=>'composer',
//     // "help" => false,

//     "options" => array(
//     	"border" => true,
//     	"collapsed" => true,
//     	'toolbar_pos' => 'top',
//     	'maxDepth' => 2,
//     	"row" => 3,
//     	"column" => 3,
//         "data_custom" => array(
//         	array(
//                 "id" => "group",
//                 "title" => "",
//                 "desc" => "",
//                 "button" => "Add Group",
//                 "fields" => array(
//                     "id" => "group",
//                     "title" => "Group title",
//                     "icon" => "",
//                     "desc" => "",
//                 ),
//             ),
//             array(
//                 "id" => "image",
//                 "title" => "",
//                 "desc" => "",
//                 "button" => "Add Image",
//                 "fields" => array(
//                     "id" => "image",
//                     "title" => "Image",
//                     "icon" => "dashicons dashicons-format-image",
//                     "src" => "",
//                     "desc" => "",
//                 ),
//             ),
//             array(
//                 "id" => "video",
//                 "title" => "",
//                 "desc" => "",
//                 "button" => "Add Video",
//                 "fields" => array(
//                     "id" => "video",
//                     "title" => "New Video",
//                     "icon" => "dashicons dashicons-format-video",
//                     "desc" => "",
//                     "link" => "",
//                     "player" => "mp4",
//                 ),
//             ),
//         	array(
//                 "id" => "file",
//                 "title" => "",
//                 "desc" => "",
//                 "button" => "Add File",
//                 "fields" => array(
//                     "id" => "file",
//                     "title" => "New File",
//                     "icon" => "dashicons dashicons-media-archive",
//                     "desc" => "",
//                     "link" => "",
//                     "target" => "_blank",
//                 ),
//             ),
//             array(
//                 "id" => "link",
//                 "title" => "",
//                 "desc" => "",
//                 "button" => "Add Link",
//                 "fields" => array(
//                     "id" => "link",
//                     "title" => "New Link",
//                     "icon" => "dashicons dashicons-admin-links",
//                     "desc" => "",
//                     "link" => "",
//                     "target" => "_blank",
//                 ),
//             ),


//         ),
//     ),
//     "padding" => "20px 0px 0px 0px",
//     "fullwidth" => true,
//     // "tab" => "Resources"
// ));


self::$admin->addMetabox( 'wp_backend_dashboard_metabox', array(
"name"=>'webmaster',
"context"=>'normal',
"priority"=>'over',
"force_state" => "open",
"hide_box_style" => true,
"hide_handle" => true,
"disable_switch" => true,
"disable_sortable" => true,
"remove_padding" => true,

));

/*
*
* TOOLBAR MENU
*
*/
self::$admin->addLocation( 'wp_backend_webmaster', array(
	"name"=>'Helpdesk',
	"title"=>'Helpdesk',
	"submenu"=>'wp_backend',
));

self::$admin->addMetabox( 'wp_backend_webmaster_metabox', array(
"name"=>'webmaster',
"context"=>'normal',
"priority"=>'over',
"force_state" => "open",
"hide_box_style" => true,
"hide_handle" => true,
"disable_switch" => true,
"disable_sortable" => true,
"remove_padding" => true,
"tabs_style"=>"nav"
));

self::$admin->addMeta('wp_backend_helpdesk_enable', array(
	"title"=>'Enable helpdesk',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Enable the helpdesk page for your customer',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "General"
));

self::$admin->addMeta('wp_backend_helpdesk_title', array(
	"title"=>'Helpdesk title',
	"type"=>'text',
	"options" => array(),
	"padding" => "20px 0px 0px 0px",
	"tab" => "General"
));

self::$admin->addMeta('wp_backend_helpdesk_desc', array(
	"title"=>'Helpdesk description',
	"type"=>'editor',
	"options" => array(),
	"padding" => "20px 0px 0px 0px",
	"tab" => "General"
));

self::$admin->addMeta('wp_backend_helpdesk_name', array(
	"title"=>'Helpdesk menu name',
	"type"=>'text',
	"options" => array(),
	"padding" => "20px 0px 0px 0px",
	"tab" => "General"
));
self::$admin->addMeta('wp_backend_helpdesk_icon', array(
	"title"=>'Helpdesk icon',
	"type"=>'text',
	"options" => array(),
	"padding" => "20px 0px 0px 0px",
	"tab" => "General"
));
self::$admin->addMeta('wp_backend_helpdesk_pos', array(
	"title"=>'Helpdesk sidebar pos',
	"type"=>'text',
	"options" => array(),
	"padding" => "20px 0px 0px 0px",
	"tab" => "General"
));


self::$admin->addMeta('wp_backend_helpdesk_resources', array(
    "title"=>'Resources',
    "type"=>'repeater',
    // "help" => true,
    "options" => array(
    	"border" => true,
    	"collapsed" => false,
    	'maxDepth' => 2,
    	'toolbar_pos' => 'top',
        "data_custom" => array(
        	array(
                "id" => "group",
                "title" => "",
                "desc" => "",
                "button" => "Add Group",
                "fields" => array(
                    "id" => "group",
                    "title" => "Group title",
                    "icon" => "",
                    "desc" => "",
                ),
            ),
            array(
                "id" => "video",
                "title" => "",
                "desc" => "",
                "button" => "Add Video",
                "fields" => array(
                    "id" => "video",
                    "title" => "New Video",
                    "icon" => "dashicons dashicons-format-video",
                    "desc" => "",
                    "link" => "",
                    "player" => "mp4",
                ),
            ),
        	array(
                "id" => "file",
                "title" => "",
                "desc" => "",
                "button" => "Add File",
                "fields" => array(
                    "id" => "file",
                    "title" => "New File",
                    "icon" => "dashicons dashicons-media-archive",
                    "desc" => "",
                    "link" => "",
                    "target" => "_blank",
                ),
            ),
            array(
                "id" => "link",
                "title" => "",
                "desc" => "",
                "button" => "Add Link",
                "fields" => array(
                    "id" => "link",
                    "title" => "New Link",
                    "icon" => "dashicons dashicons-admin-links",
                    "desc" => "",
                    "link" => "",
                    "target" => "_blank",
                ),
            ),


        ),
    ),
    "padding" => "20px 0px 0px 0px",
    "fullwidth" => true,
    "tab" => "Resources"
));


self::$admin->addMeta('wp_backend_webmaster_enable', array(
	"title"=>'Enable webmaster',
	"type"=>'checkbox',
	"options" => array(
		"label"=>'Enable the webmaster widget',
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "Webmaster"
));

self::$admin->addMeta('wp_backend_webmaster_name', array(
	"title"=>'Webmaster Name',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "Webmaster"
));
self::$admin->addMeta('wp_backend_webmaster_phone', array(
	"title"=>'Webmaster Phone',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "Webmaster"
));
self::$admin->addMeta('wp_backend_webmaster_email', array(
	"title"=>'Webmaster Email',
	"type"=>'text',
	"padding" => "20px 0px 0px 0px",
	"tab" => "Webmaster"
));
self::$admin->addMeta('wp_backend_webmaster_avatar', array(
	"title"=>'Webmaster Avatar',
	"type"=>'image',
    "options" => array(
        "output"=>"url"
    ),
	// "help" => true,
	"options" => array(
		"input" => true,
	),
	"padding" => "20px 0px 0px 0px",
	"tab" => "Webmaster"
));
self::$admin->addMeta('wp_backend_helpdesk_quote', array(
	"title"=>'Webmaster quote',
	"type"=>'editor',
	"options" => array(),
	"padding" => "20px 0px 0px 0px",
	"tab" => "Webmaster"
));







/*
*
* SETTINGS MENU
*
*/
self::$admin->addLocation( 'wp_backend_settings', array(
	"name"=>'Settings',
	"title"=>'Settings',
	"submenu"=>'wp_backend',
	"bt_save" => false,
));

self::$admin->addMetabox( 'wp_backend_settings_metabox', array(
"name"=>'Settings',
"context"=>'normal',
"priority"=>'over',
"force_state" => "open",
"hide_box_style" => true,
"hide_handle" => true,
"disable_switch" => true,
"disable_sortable" => true,
"remove_padding" => true,
// "tabs_style"=>"nav",
// "tabs_contents" => array(
// 	"default" => array( "label"=> 'Default' ),
// ),
));

self::$admin->addMeta('WPBACKEND_preset', array(
	"title"=>'',
	"type"=>'presets',
	"options" => array(
	),
	"fullwidth" => true,
	"padding" => "0px 0px 0px 0px",
	// "tab" => "preset"
));

?>

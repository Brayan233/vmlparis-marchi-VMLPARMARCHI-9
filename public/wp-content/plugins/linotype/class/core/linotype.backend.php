<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
Plugin Name: Backend
Plugin URI: http://www.handypress.io
Description: Customize the Wordpress backend.
Version: 1.1.0
Author: Yannick Armspach
Author Email: Yannick Armspach <yannick.armspach@gmail.com>
*/

/**
*
* WPBACKEND CLASS
*
**/
class WPBACKEND {

/**
*
* CONSTRUCT
*
* @desc wordpress action and filter
*
**/
function __construct() {

	$this->get_settings();

	//init menu
	$this->init_menu();

	//init toolbar
	$this->init_toolbar();

	//init header
	$this->init_header();

	//init footer
	$this->init_footer();

	//Update
	add_action( 'init', array( $this, 'update' ) );

	//init settings
	add_action( 'init', array( $this, 'init_plugin' ) );

	//admin script n style
	add_action( 'admin_enqueue_scripts', array( $this, 'admin_script_n_style' ), 20, 1);

	//set admin body class
	add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

	//add custom style
	add_action( 'admin_head', array( $this, 'admin_custom_css') );

	//login screen style
	add_action( 'login_head', array( $this, 'my_custom_login' ) );
	// add_action( 'login_header', array( $this, 'my_custom_login_header' ) );

	add_filter( 'login_redirect', array( $this, "admin_login_redirect" ), 10, 3);
	add_action( 'wp_logout', array( $this, 'go_home' ) );
	add_filter( 'login_footer', array( $this, 'rememberme_checked' ) );


	//remove frontend adminbar
	if ( get_option('WPBACKEND_hide_admin_bar') ) add_filter( 'show_admin_bar', '__return_false' );

	if ( get_option('WPBACKEND_wc_disable_notif') ) remove_action('admin_notices', 'woothemes_updater_notice');

	//adminbar site style
	//add_action( 'wp_enqueue_scripts', array( $this, 'adminbar_site' ), 999999 );

	//force remove front 32px
	//add_filter( 'language_attributes', array( $this, 'disable_margin_32px') );

	//ob start
	//add_action( 'admin_head', array( $this, 'head_ob_start') );

	//ob end
	//add_action( 'admin_footer', array( $this, 'footer_ob_end') );

	//Save profile
	//add_action('personal_options_update', array( $this, 'update_extra_profile_fields' ) );

	//Save profile on user edit
	//add_action('edit_user_profile_update', array( $this, 'update_extra_profile_fields' ) );

	//remove body class
	// add_filter( 'body_class', array( $this, 'class_body') );

	//textdomain
	//add_action( 'init', array( $this, 'textDomain' ) );

	//activation
	//register_activation_hook( WPBACKEND_FILE, array( $this, 'activate' ) );

	//deactivate
	//register_deactivation_hook( WPBACKEND_FILE, array( $this, 'deactivate' ) );

	//uninstall
	//register_uninstall_hook( WPBACKEND_FILE, array( $this, 'uninstall' ) );

}

public function get_settings() {

	$this->classes = ' ';

	if( get_option( 'WPBACKEND_toolbar_position' ) == 'WPBACKEND_toolbar_fixed_inwrap' ) {
		$this->classes .= ' wp-backend-toolbar-fixed-inwrap';
	}
	if( get_option( 'WPBACKEND_toolbar_position' ) == 'WPBACKEND_toolbar_relative' ) {
		$this->classes .= ' wp-backend-toolbar-relative';
	}
	if( get_option( 'WPBACKEND_toolbar_position' ) == 'WPBACKEND_toolbar_relative_inwrap' ) {
		$this->classes .= ' wp-backend-toolbar-relative';
	}
	if( get_option( 'WPBACKEND_toolbar_position' ) == 'WPBACKEND_toolbar_fixed_bottom' ) {
		$this->classes .= ' wp-backend-toolbar-fixed-bottom';
	}
	if( get_option( 'WPBACKEND_toolbar_position' ) == 'WPBACKEND_toolbar_hide' ) {
		$this->classes .= ' wp-backend-toolbar-hide';
	}
	if( get_option('WPBACKEND_toolbar_large') == true ) {
		$this->classes .= ' wp-backend-toolbar-large';
	}
	if( get_option('WPBACKEND_toolbar_border_bottom') == true ) {
		$this->classes .= ' wp-backend-toolbar-border-bottom';
	}
	if( get_option('WPBACKEND_toolbar_margin_left_right') == true ) {
		$this->classes .= ' wp-backend-toolbar-margin-left-right';
	}
	if( get_option('WPBACKEND_toolbar_shadow') == true ) {
		$this->classes .= ' wp-backend-toolbar-shadow';
	}
	if( get_option('WPBACKEND_toolbar_home_icon') == true ) {
		$this->classes .= ' wp-backend-toolbar-home-icon';
	}
	if( get_option('WPBACKEND_toolbar_light') == true ) {
		$this->classes .= ' wp-backend-toolbar-light';
	}
	if( get_option('WPBACKEND_sidebar_position') == 'WPBACKEND_sidebar_right' ) {
		$this->classes .= ' wp-backend-sidebar-right';
	}
	if( get_option('WPBACKEND_sidebar_position') == 'WPBACKEND_sidebar_hide' ) {
		$this->classes .= ' wp-backend-sidebar-hide';
	}
	if( get_option('WPBACKEND_sidebar_hidecorner') ) {
		$this->classes .= ' wp-backend-sidebar-hidecorner';
	}

	if( get_option('WPBACKEND_sidebar_scroll') == true ) {
		$this->classes .= ' wp-backend-sidebar-scroll';
	}
	if( get_option('WPBACKEND_notice_fixed') == true ) {
		$this->classes .= ' wp-backend-notice-fixed';
	}
	if( get_option('WPBACKEND_screenmeta_fixed') == true ) {
		$this->classes .= ' wp-backend-screenmeta-fixed';
	}
	if ( get_option('WPBACKEND_body_maxwidth') ) {
		$this->classes .= ' wp-backend-body-max-width';
	}

	if ( get_option('WPBACKEND_footer') == 'with_footer' || get_option('WPBACKEND_footer') == 'with_footer_inwrap' ) {
		$this->classes .= ' wp-backend-footer-with';
	}

}

/**
*
* init
*
* @desc init plugin
*
**/
public function init_plugin(){


	//get all cap
	$this->all_roles = handypress_helper::get_all_roles();


}


/**
*
* init hook
*
* @desc customize hook to change global menu
* //init_hook
**/
function init_menu(){

	$class = new ReflectionClass( get_class($this) );

	$methods = $class->getMethods();

	$this->methodename = $methods;

	foreach ( $methods as $method ){

		if ( strpos($method->name, 'hook_' ) === 0 ) {

			$hook = substr($method->name, 5);

			add_filter( $hook, array(&$this, $method->name), 99999, $method->getNumberOfParameters() );

		}

	}

	unset($class);

}


/**
*
* hook_admin_menu
*
* @desc replace menu and restore menu for user role
*
**/
function hook_admin_menu(){

	global $menu, $submenu;

	add_filter('parent_file', array($this, 'replace_wp_menu'));
	add_action('adminmenu', array($this, 'restore_wp_menu'));

}

/**
*
* menu_replace
*
* @desc replace menu by the custom one
*
**/
public function replace_wp_menu( $parent_file = '' ) {

	global $menu, $submenu, $pagenow;

	$url = '/';
	if ( isset( $_SERVER['REQUEST_URI'] ) ) $url = $_SERVER['REQUEST_URI'];
	$tokens = explode('/', $url);
	$pagenow = end($tokens);

	$this->old_menu = $menu;
	$this->old_submenu = $submenu;

	$handymenu = $this->get_custom_menu( $menu, $submenu, $pagenow );

	if ( get_option( 'WPBACKEND_sidebar_enable' ) ) {
		$menu = $handymenu[0];
		$submenu = $handymenu[1];
	}

	return $parent_file;

}

/**
*
* menu_restore
*
* @desc restore default menu
*
**/
public function restore_wp_menu() {

	global $menu, $submenu;

	$menu = $this->old_menu;
	$submenu = $this->old_submenu;

}

/**
*
* get_custom_menu
*
* @desc
*
**/
public function get_custom_menu( $menu, $submenu, $pagenow ) {

	ksort($menu);

	$this->WPBACKEND_MENU = array();

	if ( !empty($menu) ){
		foreach ( $menu as $key => $item_menu) {

			//default link
			$href_menu = $item_menu[2];

			//if no .php extention
			if ( strpos( $href_menu, '.php' ) === false ) $href_menu = 'admin.php?page=' . $href_menu;

			//woocommerce fix
			if ( isset( $submenu['woocommerce'][0][2] ) && $href_menu === 'admin.php?page=woocommerce' ) $href_menu = $submenu['woocommerce'][0][2];

			if ( isset( $item_menu[3] ) ) {
			$get_counter = preg_match('/(<span.+?>.+?<\/span>)/is', $item_menu[3], $matches );
			if ( isset( $matches[0] ) ) $item_menu[3] = strip_tags( str_replace( $matches[0], '', $item_menu[3] ) );
			}

			if ( isset( $item_menu[0] ) ) {
			$get_counter = preg_match('/(<span.+?>.+?<\/span>)/is', $item_menu[0], $matches );
			if ( isset( $matches[0] ) ) $item_menu[0] = strip_tags( str_replace( $matches[0], '', $item_menu[0] ) );
			}

			//if separator
			$item_title = '';
			if ( ! strstr($item_menu[2], 'separator' ) ) $item_title = $item_menu[0];

			//create menu arg
			$arg_menu = array();
			if ( isset( $item_menu[0] ) ) $arg_menu['id'] = $item_menu[0];
			if ( isset( $item_title ) ) $arg_menu['title'] = $item_title;
			// if ( isset( $item_title ) ) $arg_menu['name'] = $item_title;

			if ( isset( $item_menu[6] ) && $item_menu[6] !== 'div' ) {

				$arg_menu['icon'] = $item_menu[6];

				if ( strpos( $arg_menu['icon'], 'dashicons') !== false ) $arg_menu['icon'] = 'dashicons ' . $arg_menu['icon'];

			} else {

				$arg_menu['icon'] = '';

			}

			if ( isset( $item_menu[1] ) ) $arg_menu['role'] = $item_menu[1];
			if ( isset( $href_menu ) ) $arg_menu['href'] = $href_menu;
			// if ( isset( $item_menu[2] ) ) $arg_menu['ref'] = $item_menu[2];
			if ( isset( $item_menu[4] ) ) $arg_menu['class'] = $item_menu[4];
			$arg_menu['parent'] = 'top';

			//push item
			$this->WPBACKEND_MENU[$key] = $arg_menu;

			//add submenu
			if ( !empty($submenu[$item_menu[2]]) ){
				foreach ( $submenu[$item_menu[2]] as $key_submenu => $item_submenu) {

					//default link
					$href_submenu = $item_submenu[2];

					//if top level page get first subpage link
					if ( strpos( $href_submenu, '.php' ) === false ) $href_submenu = 'admin.php?page=' . $href_submenu;

					if ( isset( $item_submenu[3] ) ) {
					$get_counter = preg_match('/(<span.+?>.+?<\/span>)/is', $item_submenu[3], $matches );
					if ( isset( $matches[0] ) ) $item_submenu[3] = strip_tags( str_replace( $matches[0], '', $item_submenu[3] ) );
					}

					if ( isset( $item_submenu[0] ) ) {
					$get_counter = preg_match('/(<span.+?>.+?<\/span>)/is', $item_submenu[0], $matches );
					if ( isset( $matches[0] ) ) $item_submenu[0] = strip_tags( str_replace( $matches[0], '', $item_submenu[0] ) );
					}

					//create submenu arg
					$arg_submenu = array();
					if ( isset( $item_submenu[0] ) ) $arg_submenu['id'] = $item_submenu[0];
					if ( isset( $item_submenu[0] ) ) $arg_submenu['title'] = $item_submenu[0];
					// if ( isset( $item_submenu[0] ) ) $arg_submenu['name'] = $item_submenu[0];
					if ( isset( $item_submenu[6] ) ) {
						$arg_submenu['icon'] = $item_submenu[6];
					} else {
						$arg_submenu['icon'] = '';
					}
					if ( isset( $item_submenu[1] ) ) $arg_submenu['role'] = $item_submenu[1];
					if ( isset( $href_submenu ) ) $arg_submenu['href'] = $href_submenu;
					// if ( isset( $item_submenu[2] ) ) $arg_submenu['ref'] = $item_submenu[2];
					if ( isset( $item_submenu[4] ) ) $arg_submenu['class'] = $item_submenu[4];
					if ( isset( $item_menu[5] ) ) $arg_submenu['parent'] = $item_menu[5];

					//push item
					$this->WPBACKEND_MENU[$key]['children'][$arg_submenu['id']] = $arg_submenu;

				}
			}
		}
	}

	//save menu
	update_option( 'WPBACKEND_default_menu', $this->WPBACKEND_MENU );

	//new admin side menu
	$new_menu = $this->WPBACKEND_MENU;

	$custom_menu = get_option('WPBACKEND_custom_menu');
	if ( $custom_menu ) $new_menu = json_decode( stripslashes( $custom_menu ) , true);

	$reset_menu = array();
	$reset_submenu = array();

	if ( isset( $new_menu ) ){
		foreach ( $new_menu as $key_item => $item ) {

			if ( ! isset( $item['ref'] ) ) $item['ref'] = "";

			if ( ! strstr($item['ref'], 'separator' ) ) {

				$reset_menu[$key_item][0] = $item['title'];
				$reset_menu[$key_item][1] = $item['role'];
				$reset_menu[$key_item][2] = $item['href'];
				if ( $item['ref'] == 'woocommerce' ) $reset_menu[$key_item][2] = $item['ref'];
				// $reset_menu[$key_item][3] = $item['name'];
				$reset_menu[$key_item][4] = $item['class'] . ' menu-top';
				$reset_menu[$key_item][5] = $item['id'];
				$reset_menu[$key_item][6] = str_replace('dashicons ', '', $item['icon'] );

			} else {

				$reset_menu[$key_item][0] = '';
				$reset_menu[$key_item][1] = $item['role'];
				$reset_menu[$key_item][2] = $item['ref'];
				$reset_menu[$key_item][3] = '';
				$reset_menu[$key_item][4] = $item['class'];

			}

			if ( isset( $item['children'] ) && ( count( $item['children'] ) > 1 ) ) {

				foreach ( $item['children'] as $key_sub => $sub) {

					$html_subsub = '';

					// //create subsub
					if ( isset( $sub['children'] ) ){

						$html_subsub = '<span>+</span></a><div class="admin-menu-subsub">';

						foreach ( $sub['children'] as $key_subsub => $subsub) {

							//if ( isset( $subsub['icon'] ) ) $subsub['title'] = '<span class="dashicons dashicons-before dashicons-menu '.$subsub['icon'].'"></span>' . $subsub['title'];

							$html_subsub .= '<div><a href="' . $subsub['href'] . '"> - ' . $subsub['title'] . '</a></div>';

						}

						$html_subsub .= '</div><a class="admin-menu-dumy">';

					}

					//create sub an include subsub
					//if ( isset( $sub['icon'] ) ) $sub['title'] = '<span class="dashicons dashicons-before dashicons-menu '.$sub['icon'].'"></span> ' . $sub['title'];

					if ( ! isset( $sub['title'] ) ) $sub['title'] = "";
					if ( ! isset( $sub['role'] ) ) $sub['role'] = "";
					if ( ! isset( $sub['href'] ) ) $sub['href'] = "";
					// if ( ! isset( $sub['name'] ) ) $sub['name'] = "";

					$reset_submenu[$item['ref']][] = array(
						$sub['title'] . $html_subsub,
						$sub['role'],
						$sub['href'],
						// $sub['name'],
					);

					//create subsub
					// if ( isset( $sub['children'] ) ){

					// 	foreach ( $sub['children'] as $key_subsub => $subsub) {

					// 		//if ( $key_subsub == 0 ) $reset_submenu[$item['ref']][] = array( '</a></li><div class="admin-menu-subsub">', $subsub['role'], '#', 'subsub-open' . $key_subsub );
					// 		//if ( isset( $subsub['icon'] ) ) $subsub['title'] = '<span class="dashicons dashicons-before dashicons-menu '.$subsub['icon'].'"></span>' . $subsub['title'];

					// 		// $reset_submenu[$item['ref']][] = array(
					// 		// 	'- ' . $subsub['title'],
					// 		// 	$subsub['role'],
					// 		// 	$subsub['href'],
					// 		// 	$subsub['name'],
					// 		// );

					// 	}

					// 	//if ( $key_subsub == count($sub['children']) -1 ) $reset_submenu[$item['ref']][] = array( '</a></li></div>', $subsub['role'], '#', 'subsub-close' . $key_subsub );

					// }

					//create subsub
					// if ( isset( $sub['children'] ) ){

					// 	foreach ( $sub['children'] as $key_subsub => $subsub) {

					// 		if ( $key_subsub == 0 ) $reset_submenu[$item['ref']][] = array( '</a></li><div class="admin-menu-subsub">', $subsub['role'], '#', 'subsub-open' . $key_subsub );
					// 		//if ( isset( $subsub['icon'] ) ) $subsub['title'] = '<span class="dashicons dashicons-before dashicons-menu '.$subsub['icon'].'"></span>' . $subsub['title'];

					// 		$reset_submenu[$item['ref']][] = array(
					// 			'- ' . $subsub['title'],
					// 			$subsub['role'],
					// 			$subsub['href'],
					// 			$subsub['name'],
					// 		);

					// 	}

					// 	if ( $key_subsub == count($sub['children']) -1 ) $reset_submenu[$item['ref']][] = array( '</a></li></div>', $subsub['role'], '#', 'subsub-close' . $key_subsub );

					// }



				}

			}

		}
	}

	//reset new menu order
	$menu = $reset_menu;
	$submenu = $reset_submenu;

	return array( $menu, $submenu );

}


public function init_toolbar() {

	if ( get_option('WPBACKEND_toolbar_position') == 'WPBACKEND_toolbar_hide' ) {

		add_filter( 'init', array( $this, 'hide_toolbar' ), 9 );

	} else {

		//get toolbar
		if ( is_admin() ) add_action( 'admin_bar_menu', array( $this, 'get_toolbar' ) , 111111 );

		//init toolbar
		add_action( 'admin_bar_menu', array( $this,'remove_default_toolbar' ) , 888888 );
		

		if ( get_option('WPBACKEND_toolbar_enable') ) {

			//add new toolbar
			add_action( 'admin_bar_menu', array( $this,'create_toolbar' ) , 999999 );

			//replace howdy
			add_filter( 'admin_bar_menu', array( $this, 'replace_howdy' ), 25 );

		}

	}

}

public function hide_toolbar() {

	add_filter( 'show_admin_bar', '__return_false' );
	// add_filter( 'wp_admin_bar_class', '__return_false' );

}

function get_toolbar( $wp_admin_bar ) {

	$this->all_toolbar_nodes = $wp_admin_bar->get_nodes();

	update_option( 'WPBACKEND_default_toolbar', $this->all_toolbar_nodes );

	$this->WPBACKEND_TOOLBAR = array();

	//get level 1
	if ( isset( $this->all_toolbar_nodes ) ){

		foreach ( $this->all_toolbar_nodes as $key => $value ) {

			$arg_toolbar = array();
		 	if ( isset( $value->id ) ) $arg_toolbar['id'] = $value->id;
		 	if ( isset( $value->title ) ) $arg_toolbar['title'] = $value->title;
		 	if ( isset( $value->href ) ) $arg_toolbar['href'] = $value->href;
		 	if ( isset( $value->group ) ) $arg_toolbar['group'] = $value->group;
		 	if ( isset( $value->meta->title ) ) $arg_toolbar['meta-title'] = $value->meta->title;
		 	if ( isset( $value->meta->class ) ) $arg_toolbar['meta-class'] = $value->meta->class;

		 	if( empty( $value->parent ) ) {
		 		$this->WPBACKEND_TOOLBAR[$key] = $arg_toolbar;
				unset($this->all_toolbar_nodes[$key]);
			}
		}


		if ( isset( $this->WPBACKEND_TOOLBAR ) ){

			//get level 2
			foreach ( $this->WPBACKEND_TOOLBAR as $parent_key => $level1 ) {

				foreach ( $this->all_toolbar_nodes as $key => $value ) {

					if ( $level1['id'] == $value->parent ) {

					 	$arg_toolbar = array();
					 	if ( isset( $value->id ) ) $arg_toolbar['id'] = $value->id;
					 	if ( isset( $value->title ) ) $arg_toolbar['title'] = $value->title;
					 	if ( isset( $value->href ) ) $arg_toolbar['href'] = $value->href;
					 	if ( isset( $value->group ) ) $arg_toolbar['group'] = $value->group;
					 	if ( isset( $value->meta->title ) ) $arg_toolbar['meta-title'] = $value->meta->title;
					 	if ( isset( $value->meta->class ) ) $arg_toolbar['meta-class'] = $value->meta->class;

			 			$this->WPBACKEND_TOOLBAR[$value->parent]['children'][$key] = $arg_toolbar;
			 			unset($this->all_toolbar_nodes[$key]);
			 		}

				}

			}

			//get level 3
			foreach ( $this->WPBACKEND_TOOLBAR as $level1_key => $level1 ) {

				if ( isset( $level1['children'] ) ){
					foreach ( $level1['children'] as $level2_key => $level2 ) {

						foreach ( $this->all_toolbar_nodes as $key => $value ) {

							if ( $level2['id'] == $value->parent ) {

							 	$arg_toolbar = array();
							 	if ( isset( $value->id ) ) $arg_toolbar['id'] = $value->id;
							 	if ( isset( $value->title ) ) $arg_toolbar['title'] = $value->title;
							 	if ( isset( $value->href ) ) $arg_toolbar['href'] = $value->href;
							 	if ( isset( $value->group ) ) $arg_toolbar['group'] = $value->group;
							 	if ( isset( $value->meta->title ) ) $arg_toolbar['meta-title'] = $value->meta->title;
							 	if ( isset( $value->meta->class ) ) $arg_toolbar['meta-class'] = $value->meta->class;


					 			$this->WPBACKEND_TOOLBAR[$level1_key]['children'][$value->parent]['children'][$key] = $arg_toolbar;
					 			unset($this->all_toolbar_nodes[$key]);
					 		}

						}

					}
				}

			}

			//get level 4
			foreach ( $this->WPBACKEND_TOOLBAR as $level1_key => $level1 ) {

				if ( isset( $level1['children'] ) ){
					foreach ( $level1['children'] as $level2_key => $level2 ) {

						if ( isset( $level2['children'] ) ){
							foreach ( $level2['children'] as $level3_key => $level3 ) {

								foreach ( $this->all_toolbar_nodes as $key => $value ) {

									if ( $level3['id'] == $value->parent ) {

									 	$arg_toolbar = array();
									 	if ( isset( $value->id ) ) $arg_toolbar['id'] = $value->id;
									 	if ( isset( $value->title ) ) $arg_toolbar['title'] = $value->title;
									 	if ( isset( $value->href ) ) $arg_toolbar['href'] = $value->href;
									 	if ( isset( $value->group ) ) $arg_toolbar['group'] = $value->group;
									 	if ( isset( $value->meta->title ) ) $arg_toolbar['meta-title'] = $value->meta->title;
									 	if ( isset( $value->meta->class ) ) $arg_toolbar['meta-class'] = $value->meta->class;



							 			$this->WPBACKEND_TOOLBAR[$level1_key]['children'][$level2_key]['children'][$value->parent]['children'][$key] = $arg_toolbar;
							 			unset($this->all_toolbar_nodes[$key]);
							 		}

								}

							}
						}

					}
				}

			}

		}

	}

	//save menu
	update_option( 'WPBACKEND_default_toolbar_tree', $this->WPBACKEND_TOOLBAR );

}

function remove_default_toolbar( $wp_admin_bar ) {

	$all_toolbar_nodes = $wp_admin_bar->get_nodes();

	$revove_keys = explode(',', get_option('WPBACKEND_toolbar_remove_default') );

	//remove default toolbar
	if ( isset( $revove_keys ) && $revove_keys ){
		foreach ( $revove_keys as $key => $value ) {
			$wp_admin_bar->remove_node($value);
		}
	}

}

public function create_toolbar( $wp_admin_bar ) {

	$WPBACKEND_toolbar_parent = '';

	//create master parent menu if enable
	if( get_option('WPBACKEND_toolbar_parent') ) {

		$WPBACKEND_toolbar_parent = 'top';

		$arg_menu = array(
			'id'    => 'top',
			'title' => '<span class="ab-icon dashicons-menu"></span><span class="ab-label">MENU</span>',
			'href'  => '#',
		);

		$wp_admin_bar->add_node( $arg_menu );

	}

	//get default menu array
	$handymenu =  get_option('WPBACKEND_default_menu');

	//get custom menu
	$custom_menu = get_option('WPBACKEND_custom_toolbar');

	//if current user custom menu
	global $current_user;
	$user_roles = $current_user->roles;
	$current_role = array_shift($user_roles);

	if ( get_option('WPBACKEND_custom_toolbar_' . $current_role ) ) $custom_menu = get_option('WPBACKEND_custom_toolbar_' . $current_role );

	//if custom menu exist replace default menu
	if ( $custom_menu ) $handymenu = json_decode( stripslashes( $custom_menu ) , true);

	if ( isset( $handymenu ) ){
		foreach ( $handymenu as $key => $item_menu) {

			//if ( isset( $item_menu['icon'] ) ) $item_menu['title'] = '<span class="dashicons dashicons-before dashicons-toolbar '.$item_menu['icon'].'"></span> ' . $item_menu['title'];
			if ( isset( $item_menu['icon'] ) && $item_menu['icon'] ) {

				if ( strpos( $item_menu['icon'], 'data:image' ) !== false ) {

					$item_menu['svg'] = base64_decode( str_replace('data:image/svg+xml;base64,', '', $item_menu['icon'] ) );

					$item_menu['title'] = '<span class="ab-icon wp-menu-image">'.$item_menu['svg'].'</span><span class="ab-label">'.$item_menu['title'].'</span>';

				} else {

					$item_menu['title'] = '<span class="ab-icon '.$item_menu['icon'].'"></span><span class="ab-label">'.$item_menu['title'].'</span>';

				}

			}

			$arg_menu = array();
			if ( isset( $item_menu['id'] ) ) $arg_menu['id'] = $item_menu['id'] . '-' . $key;
			if ( isset( $item_menu['title'] ) ) $arg_menu['title'] = $item_menu['title'];
			if ( isset( $item_menu['group'] ) ) $arg_menu['group'] = $item_menu['group'];
			if ( isset( $item_menu['href'] ) ) $arg_menu['href'] = $item_menu['href'];
			if ( isset( $item_menu['parent'] ) ) $arg_menu['parent'] = $WPBACKEND_toolbar_parent;

			if ( current_user_can( $item_menu['role'] ) ) $wp_admin_bar->add_node( $arg_menu );

			if ( isset( $item_menu['children'] ) && is_array( $item_menu['children'] ) ){
				foreach ( $item_menu['children'] as $key_children => $item_children) {

					$title = '';
					if ( isset( $item_children['icon'] ) && $item_children['icon'] ) $title .= '<span class="ab-icon '.$item_children['icon'].'"></span>';
					if ( isset( $item_children['title'] ) ) $title .= '<span class="ab-label">'.$item_children['title'].'</span>';
					$item_children['title'] = $title;

					$arg_submenu = array();
					if ( isset( $item_children['id'] ) ) $arg_submenu['id'] = $item_children['id'] . '-' . $key . '-' . $key_children;
					if ( isset( $item_children['title'] ) ) $arg_submenu['title'] = $item_children['title'];
					if ( isset( $item_children['group'] ) ) $arg_submenu['group'] = $item_children['group'];
					if ( isset( $item_children['href'] ) ) $arg_submenu['href'] = $item_children['href'];
					if ( isset( $item_menu['id'] ) ) $arg_submenu['parent'] = $item_menu['id'] . '-' . $key;

					if ( current_user_can( $item_children['role'] ) ) $wp_admin_bar->add_node( $arg_submenu );

					if ( isset( $item_children['children'] ) && is_array( $item_children['children'] ) ){
						foreach ( $item_children['children'] as $key_childrenchildren => $item_childrenchildren) {

							$title = '';
							if ( isset( $item_childrenchildren['icon'] ) && $item_childrenchildren['icon'] ) $title .= '<span class="ab-icon '.$item_childrenchildren['icon'].'"></span>';
							if ( isset( $item_childrenchildren['title'] ) ) $title .= '<span class="ab-label">'.$item_childrenchildren['title'].'</span>';
							$item_childrenchildren['title'] = $title;

							$arg_childrenchildren = array();
							if ( isset( $item_childrenchildren['id'] ) ) $arg_childrenchildren['id'] = $item_childrenchildren['id'] . '-' . $key . '-' . $key_children . '-' . $key_childrenchildren;
							if ( isset( $item_childrenchildren['title'] ) ) $arg_childrenchildren['title'] = $item_childrenchildren['title'];
							if ( isset( $item_childrenchildren['group'] ) ) $arg_childrenchildren['group'] = $item_childrenchildren['group'];
							if ( isset( $item_childrenchildren['href'] ) ) $arg_childrenchildren['href'] = $item_childrenchildren['href'];
							if ( isset( $item_children['id'] ) ) $arg_childrenchildren['parent'] = $item_children['id'] . '-' . $key . '-' . $key_children;

							if ( current_user_can( $item_childrenchildren['role'] ) ) $wp_admin_bar->add_node( $arg_childrenchildren );

							if ( isset( $item_childrenchildren['children'] ) && is_array( $item_childrenchildren['children'] ) ){
								foreach ( $item_childrenchildren['children'] as $key_childrenchildrenchildren => $item_childrenchildrenchildren) {

									$title = '';
									if ( isset( $item_childrenchildrenchildren['icon'] ) && $item_childrenchildrenchildren['icon'] ) $title .= '<span class="ab-icon '.$item_childrenchildrenchildren['icon'].'"></span>';
									if ( isset( $item_childrenchildrenchildren['title'] ) ) $title .= '<span class="ab-label">'.$item_childrenchildrenchildren['title'].'</span>';
									$item_childrenchildrenchildren['title'] = $title;

									$arg_childrenchildrenchildren = array();
									if ( isset( $item_childrenchildrenchildren['id'] ) ) $arg_childrenchildrenchildren['id'] = $item_childrenchildrenchildren['id'] . '-' . $key . '-' . $key_children . '-' . $key_childrenchildren;
									if ( isset( $item_childrenchildrenchildren['title'] ) ) $arg_childrenchildrenchildren['title'] = $item_childrenchildrenchildren['title'];
									if ( isset( $item_childrenchildrenchildren['group'] ) ) $arg_childrenchildrenchildren['group'] = $item_childrenchildrenchildren['group'];
									if ( isset( $item_childrenchildrenchildren['href'] ) ) $arg_childrenchildrenchildren['href'] = $item_childrenchildrenchildren['href'];
									if ( isset( $item_children['id'] ) ) $arg_childrenchildrenchildren['parent'] = $item_children['id'] . '-' . $key . '-' . $key_children;

									if ( current_user_can( $item_childrenchildrenchildren['role'] ) ) $wp_admin_bar->add_node( $arg_childrenchildrenchildren );


								}
							}

						}
					}

				}
			}

		}
	}

}


/**
 * Check if the current user has the specified capability.
 * If the Pro version installed, you can use special syntax to perform complex capability checks.
 *
 */
private function current_user_can($capability) {
	//WP core uses a special "do_not_allow" capability in a dozen or so places to explicitly deny access.
	//Even multisite super admins do not have this cap. We can return early here.
	if ( $capability === 'do_not_allow' ) {
		return false;
	}

	if ( $this->user_cap_cache_enabled && isset($this->cached_user_caps[$capability]) ) {
		return $this->cached_user_caps[$capability];
	}

	$user_can = apply_filters('admin_menu_editor-current_user_can', current_user_can($capability), $capability);
	$this->cached_user_caps[$capability] = $user_can;
	return $user_can;
}


/**
*
* custom_header
*
* @desc load custom header
*
**/

public function create_backend_menu(){

	//get default menu array
	$handymenu =  get_option('WPBACKEND_default_menu');

	//get custom menu
	$custom_menu = get_option('WPBACKEND_custom_toolbar');

	//if current user custom menu
	global $current_user;
	$user_roles = $current_user->roles;
	$current_role = array_shift($user_roles);

	if ( get_option('WPBACKEND_custom_toolbar_' . $current_role ) ) $custom_menu = get_option('WPBACKEND_custom_toolbar_' . $current_role );

	//if custom menu exist replace default menu
	if ( $custom_menu ) $handymenu = json_decode( stripslashes( $custom_menu ) , true);

	// _HANDYLOG($handymenu);
?>

<?php if ( isset( $handymenu ) ){ ?>

	<nav class="wp-backend-menu">

		<div class="wp-backend-menu-wrapper" style="max-width:<?php echo get_option('WPBACKEND_toolbar_maxwidth' ); ?>">

			<?php
			if( get_option('WPBACKEND_toolbar_logo') ) {

				echo '<div id="logo"><a style="display: block;padding:' . get_option("WPBACKEND_toolbar_logo_padding") . ';width: ' . get_option("WPBACKEND_toolbar_logo_width") . ';" href="' . get_site_url() . '" target="_blank">
								<img style="width:100%;padding:0px;margin:0px;display: block;" src="' . get_option("WPBACKEND_toolbar_logo") . '"/>
							</a></div>';

			}
			?>

	  	<label for="drop" class="menu-toggle toggle"><span class="dashicons dashicons-menu"></span></label>

	  	<input type="checkbox" id="drop" />

	    <ul class="menu">

				<?php foreach ( $handymenu as $item_menu_l0_key => $item_menu_l0 ) { ?>

					<?php if ( $item_menu_l0['id'] == 'logout' ) $item_menu_l0['href'] = wp_logout_url( home_url() ); ?>

	        			<li class="<?php if ( isset( $item_menu_l0['children'] ) && is_array( $item_menu_l0['children'] ) ) echo 'has_child'; ?>">

						<?php if ( isset( $item_menu_l0['children'] ) ){ ?> <label for="drop-<?php echo $item_menu_l0_key; ?>" class="toggle"><?php if ( $item_menu_l0['icon'] ) echo '<span class="menu-icon ' . $item_menu_l0['icon'] . '"></span>'; ?><?php echo $item_menu_l0['title']; ?></label> <?php } ?>

						<a href="<?php echo $item_menu_l0['href']; ?>"><?php if ( $item_menu_l0['icon'] ) echo '<span class="menu-icon ' . $item_menu_l0['icon'] . '"></span>'; ?><?php echo $item_menu_l0['title']; ?></a>

						<?php if ( isset( $item_menu_l0['children'] ) ){ ?> <input type="checkbox" id="drop-<?php echo $item_menu_l0_key; ?>"/> <?php } ?>

						<?php if ( isset( $item_menu_l0['children'] ) ){ ?>

							<ul>

							<?php if ( isset( $item_menu_l0['children'] ) && is_array( $item_menu_l0['children'] ) ) { foreach ( $item_menu_l0['children'] as $item_menu_l1_key => $item_menu_l1 ) { ?>

								<?php if ( $item_menu_l1['id'] == 'logout' ) $item_menu_l1['href'] = wp_logout_url( home_url() ); ?>

	              					<li class="<?php if ( isset( $item_menu_l1['children'] ) ) echo 'has_child'; ?>">

									<?php if ( isset( $item_menu_l1['children'] ) ){ ?> <label for="drop-<?php echo $item_menu_l1_key; ?>-<?php echo $item_menu_l1_key; ?>" class="toggle"><?php if ( $item_menu_l1['icon'] ) echo '<span class="menu-icon ' . $item_menu_l1['icon'] . '"></span>'; ?><?php echo $item_menu_l1['title']; ?></label> <?php } ?>

									<a href="<?php echo $item_menu_l1['href']; ?>"><?php if ( $item_menu_l1['icon'] ) echo '<span class="menu-icon ' . $item_menu_l1['icon'] . '"></span>'; ?><?php echo $item_menu_l1['title']; ?></a>

									<?php if ( isset( $item_menu_l1['children'] ) ){ ?> <input type="checkbox" id="drop-<?php echo $item_menu_l1_key; ?>-<?php echo $item_menu_l1_key; ?>"/> <?php } ?>

									<?php if ( isset( $item_menu_l1['children'] ) ){ ?>

										<ul>

										<?php if ( $item_menu_l1['children'] ) { foreach ( $item_menu_l1['children'] as $item_menu_l2_key => $item_menu_l2 ) { ?>

											<?php if ( $item_menu_l2['id'] == 'logout' ) $item_menu_l2['href'] = wp_logout_url( home_url() ); ?>

				              				<li class="<?php if ( isset( $item_menu_l2['children'] ) ) echo 'has_child'; ?>">

												<a href="<?php echo $item_menu_l2['href']; ?>"><?php if ( $item_menu_l2['icon'] ) echo '<span class="menu-icon ' . $item_menu_l2['icon'] . '"></span>'; ?><?php echo $item_menu_l2['title']; ?></a>

											</li>

										<?php } } ?>

				          				</ul>

									<?php } ?>

								</li>

							<?php } } ?>

	          			</ul>

						<?php } ?>

					</li>

				<?php } ?>

			</ul>

		</div>

	</nav>

<?php } ?>



		<?php

	}


public function init_header() {

	if ( get_option('WPBACKEND_toolbar_custom') ) {

	add_action( 'in_admin_header', array( $this, 'create_backend_menu' ) );

	if ( get_option('WPBACKEND_toolbar_admin_hide') ) remove_action( 'in_admin_header', 'wp_admin_bar_render', 0);

	} else {

		switch ( get_option('WPBACKEND_header') ) {
			case 'before_toolbar':
				if ( get_option('WPBACKEND_toolbar_position') == 'WPBACKEND_toolbar_hide' ) {
					add_action( 'in_admin_header', array( $this, 'custom_header_content' ) );
				} else {
					add_action( 'wp_before_admin_bar_render', array( $this, 'custom_header_content' ) );
				}
			break;
			case 'after_toolbar':
				if ( get_option('WPBACKEND_toolbar_position') == 'WPBACKEND_toolbar_hide' ) {
					add_action( 'in_admin_header', array( $this, 'custom_header_content' ) );
				} else {
					add_action( 'wp_after_admin_bar_render', array( $this, 'custom_header_content' ) );
				}
			break;
			case 'before_content':
				add_action( 'admin_notices', array( $this, 'custom_header_content' ), 1 );
			break;
		}

	}

}

/**
*
* custom_header_content
*
* @desc print custom header content
*
**/
public function custom_header_content(){

	echo '<div id="admin-custom-header"><div id="admin-custom-header-content">';

		$content = '';

		if( get_option('WPBACKEND_header_logo') ) {

			$content = '<a style="display: block;padding:' . get_option("WPBACKEND_header_logo_padding") . ';width: ' . get_option("WPBACKEND_header_logo_width") . ';" href="' . get_site_url() . '" target="_blank">
							<img style="width:100%;padding:0px;margin:0px;display: block;" src="' . get_option("WPBACKEND_header_logo") . '"/>
						</a>';

		}

		if( get_option('WPBACKEND_header_php') ) {

			/*
			ob_start();

			$php = stripcslashes( get_option('WPBACKEND_header_php') );

			if ( $php ) eval("?>$php");

			$content = ob_get_contents();

			ob_end_clean();
			*/

		}

		echo $content;

	echo '</div></div>';

}

/**
*
* custom_footer
*
* @desc load custom footer
*
**/
public function init_footer(){

	switch ( get_option('WPBACKEND_footer') ) {
		case 'before_content':
		case 'before_content_inwrap':
			add_action( 'admin_footer', array( $this, 'custom_footer_content' ) );
		break;
		case 'with_footer':
		case 'with_footer_inwrap':
			add_action( 'in_admin_footer', array( $this, 'custom_footer_content' ), 9999999 );
		break;
	}

	switch ( get_option('WPBACKEND_footer_default') ) {
		case 'hide_version':
			add_filter( 'update_footer', '__return_empty_string', 11 );
		break;
		case 'hide_infos':
			add_filter( 'admin_footer_text', '__return_empty_string', 11 );
		break;
		case 'hide_all':
			add_filter( 'admin_footer_text', '__return_empty_string', 11 );
			add_filter( 'update_footer', '__return_empty_string', 11 );
		break;
	}

	if ( get_option('WPBACKEND_footer_custom_txt') ) add_filter( 'admin_footer_text', function(){ return get_option('WPBACKEND_footer_custom_txt'); }, 10 );

}

/**
*
* custom_footer_content
*
* @desc print custom footer content
*
**/
public function custom_footer_content(){

	echo '<div id="admin-custom-footer"><div id="admin-custom-footer-content">';

		$php = stripcslashes( get_option('WPBACKEND_footer_php') );

		if ( $php ) eval("?>$php");

	echo '</div></div>';


}

/**
*
* admin_body_class
*
* @desc add class to admin body
*
**/
public function admin_body_class( $classes ) {

	global $submenu, $menu, $pagenow;

	if ( $this->classes ) $classes = $this->classes;

	return $classes;

}

public function admin_custom_css() {

	$style = '';

	$style .= '<style type="text/css">';

		//$style .= '@media only screen and (min-width:850px){';
		//$style .= '}';
			//TOOLBAR

			if ( get_option('WPBACKEND_login_bg') ) {
			$style .= 'body #wp-auth-check-wrap #wp-auth-check-bg {';
				$bg = json_decode( stripslashes( get_option('WPBACKEND_login_bg') ), true );
				$style .= $bg[0]['css'];
			$style .= '}';
			}

			//remove 32px if need
			if ( get_option('WPBACKEND_toolbar_admin_hide', '') || in_array( get_option('WPBACKEND_toolbar_position', ''), array( "WPBACKEND_toolbar_fixed_inwrap", "WPBACKEND_toolbar_hide", "WPBACKEND_toolbar_relative", "WPBACKEND_toolbar_relative_inwrap", "WPBACKEND_toolbar_fixed_bottom") ) ){
				$style .= 'html.wp-toolbar,body.admin-bar{padding-top: 0px!important;margin-top: 0px!important;}';
			}

			//add 72px to html if toolbar enlarge and fixed
			if ( get_option('WPBACKEND_toolbar_position') == "" && get_option('WPBACKEND_toolbar_large') ) {
				$style .= 'html.wp-toolbar{padding-top: 72px!important;}';
			}

			//add 32px to wpbody if toolbar fixed in wrap and no header
			if ( get_option('WPBACKEND_header') == "" && get_option('WPBACKEND_toolbar_position') == "WPBACKEND_toolbar_fixed_inwrap" ) {
				$style .= '#wpbody{padding-top: 32px!important;}';
			}

			//add 72px to wpbody if toolbar enlarge and fixed in wrap and no header
			if ( get_option('WPBACKEND_header') == "" && get_option('WPBACKEND_toolbar_position') == "WPBACKEND_toolbar_fixed_inwrap" && get_option('WPBACKEND_toolbar_large') ) {
				$style .= '#wpbody{padding-top: 72px!important;}';
			}

			//set max with of toolbar
			if ( get_option('WPBACKEND_toolbar_maxwidth') ) {
				$maxwidth = filter_var( get_option('WPBACKEND_toolbar_maxwidth'), FILTER_SANITIZE_NUMBER_INT );
				$style .= '#wp-toolbar{max-width:' . $maxwidth . 'px!important;margin-left:auto!important;margin-right:auto!important;left:0px!important;right:0px!important;position:relative!important;float:none!important;}';
			}

			//force toolbar relative position from body max width
			if ( get_option('WPBACKEND_toolbar_position') == "WPBACKEND_toolbar_relative_inwrap" && get_option('WPBACKEND_body_maxwidth') ) {
				$maxwidth = filter_var( get_option('WPBACKEND_body_maxwidth'), FILTER_SANITIZE_NUMBER_INT );
				$style .= '#wpadminbar{max-width:' . $maxwidth . 'px!important;margin-left:auto!important;margin-right:auto!important;float:none!important;}';
			}

			//force toolbar fixed position from body max width
			if ( get_option('WPBACKEND_toolbar_position') == "WPBACKEND_toolbar_fixed_inwrap" && get_option('WPBACKEND_body_maxwidth') ) {
				$maxwidth = filter_var( get_option('WPBACKEND_body_maxwidth'), FILTER_SANITIZE_NUMBER_INT );
				$style .= '#wpadminbar{max-width:' . $maxwidth . 'px!important;margin-left:auto!important;margin-right:auto!important;float:none!important;}';
			}

			//HEADER

			//header max width
			if ( get_option('WPBACKEND_header_maxwidth') ) {
				$maxwidth = filter_var( get_option('WPBACKEND_header_maxwidth'), FILTER_SANITIZE_NUMBER_INT );
				$style .= '#admin-custom-header #admin-custom-header-content{max-width:' . $maxwidth . 'px!important;margin-left:auto!important;margin-right:auto!important;left:0px!important;right:0px!important;position:relative!important;float:none!important;}';
			}

			//header height
			if ( get_option('WPBACKEND_header_height') ) {
				$height = filter_var( get_option('WPBACKEND_header_height'), FILTER_SANITIZE_NUMBER_INT );
				$style .= '#admin-custom-header #admin-custom-header-content{height:' . $height . 'px!important;}';
			}

			//header style
        	if ( get_option('WPBACKEND_header_bg') ) {
				$style .= '#admin-custom-header{';
					$bg = json_decode( stripslashes( get_option('WPBACKEND_header_bg') ), true );
					$style .= $bg[0]['css'];
				$style .= '}';
			}
			if ( get_option('WPBACKEND_header_content_bg') ) {
				$style .= '#admin-custom-header-content{';
					$bg = json_decode( stripslashes( get_option('WPBACKEND_header_content_bg') ), true );
					$style .= $bg[0]['css'];
				$style .= '}';
			}

	        //FOOTER

	        //footer max width
			if ( get_option('WPBACKEND_footer_maxwidth') ) {
				$maxwidth = filter_var( get_option('WPBACKEND_footer_maxwidth'), FILTER_SANITIZE_NUMBER_INT );
				$style .= '#admin-custom-footer #admin-custom-footer-content{max-width:' . $maxwidth . 'px!important;margin-left:auto!important;margin-right:auto!important;left:0px!important;right:0px!important;position:relative!important;float:none!important;}';
			}

			//footer height
			if ( get_option('WPBACKEND_footer_height') ) {
				$height = filter_var( get_option('WPBACKEND_footer_height'), FILTER_SANITIZE_NUMBER_INT );
				$style .= '#admin-custom-footer #admin-custom-footer-content{height:' . $height . 'px!important;}';
			}

			//add margin if footer in wpfooter
	       	if ( get_option('WPBACKEND_footer') == "with_footer" || get_option('WPBACKEND_footer') == "with_footer_inwrap" ) {
	       		$style .= '#wpbody-content { margin-bottom: ' . filter_var( get_option('WPBACKEND_footer_height'), FILTER_SANITIZE_NUMBER_INT ) . 'px;padding-bottom: 0px; }';
	       	}

	       	//force toolbar fixed position from body max width
			if ( ( get_option('WPBACKEND_footer') == "with_footer_inwrap" || get_option('WPBACKEND_footer') == "before_content_inwrap" ) && get_option('WPBACKEND_body_maxwidth') ) {
				$maxwidth = filter_var( get_option('WPBACKEND_body_maxwidth'), FILTER_SANITIZE_NUMBER_INT ) - 40;
				$style .= '#wpfooter{max-width:' . $maxwidth . 'px!important;margin-left:auto!important;margin-right:auto!important;float:none!important;}';
			}

			//footer style
			if ( get_option('WPBACKEND_footer_bg') ) {
				$style .= '#admin-custom-footer{';
					$bg = json_decode( stripslashes( get_option('WPBACKEND_footer_bg') ), true );
					$style .= $bg[0]['css'];
				$style .= '}';
			}
			if ( get_option('WPBACKEND_footer_content_bg') ) {
				$style .= '#admin-custom-footer-content{';
					$bg = json_decode( stripslashes( get_option('WPBACKEND_footer_content_bg') ), true );
					$style .= $bg[0]['css'];
				$style .= '}';
			}

			//add 72px to wpwrap if toolbar enlarge and fixed in wrap and no header
			if ( get_option('WPBACKEND_toolbar_position') == "WPBACKEND_toolbar_fixed_bottom" && get_option('WPBACKEND_toolbar_large') ) {
				$style .= '#wpwrap{margin-bottom: 72px!important;}';
			}



			//BODY

			//body max width
			if ( get_option('WPBACKEND_body_maxwidth') ) {
				$maxwidth = filter_var( get_option('WPBACKEND_body_maxwidth'), FILTER_SANITIZE_NUMBER_INT );
				$style .= '#wpbody{max-width:' . $maxwidth . 'px!important;margin-left:auto!important;margin-right:auto!important;}';
			}

			//body style
	       	if ( get_option('WPBACKEND_body_bg') ) {
				$style .= 'html{';
					$bg = json_decode( stripslashes( get_option('WPBACKEND_body_bg') ), true );
					$style .= $bg[0]['css'];
				$style .= '}';
			}
			if ( get_option('WPBACKEND_body_content_bg') ) {
				$style .= '#wpbody{';
					$bg = json_decode( stripslashes( get_option('WPBACKEND_body_content_bg') ), true );
					$style .= $bg[0]['css'];
				$style .= '}';
			}

			//EXTRA

			$style .= '@media only screen and (min-width:850px){';

				//resize the side column
				if ( get_option('WPBACKEND_post_side_column_width') ) {
					$width = filter_var( get_option('WPBACKEND_post_side_column_width'), FILTER_SANITIZE_NUMBER_INT );
					$style .= '#poststuff #post-body.columns-2{margin-right:' . $width . 'px;}#post-body.columns-2 #postbox-container-1{float:right;margin-right:-' . $width . 'px;width: ' . ($width-20) . 'px;}#poststuff #post-body.columns-2 #side-sortables{width:' . ($width-20) . 'px;}';
		        }

	        $style .= '}';

	        //move the title in absolute
	        if ( get_option('WPBACKEND_title_absolute') ) {
				$style .= '.wrap h1:first-child{position: absolute!important;top: '.get_option('WPBACKEND_title_absolute').'!important;}';
	        }

	        if ( get_option('WPBACKEND_vc_relative') ) {
				$style .= '
				.vc_subnav-fixed {
					top: 0px!important;
				}
				body:not(.vc_fullscreen) .vc_subnav-fixed {
					position: relative!important;
					padding-left: 0px!important;
					top: 0px!important;
				}
				body:not(.vc_fullscreen) .vc_subnav-fixed .vc_save-backend {
					display: none!important;
				}
				body:not(.vc_fullscreen) .vc_fullscreen .vc_navbar {
					z-index: 999999999!important;
					top: 0px!important;
				}
				body.vc_fullscreen #wpadminbar {
				    display: none;
				}
				.vc_navbar-header a#vc_logo,
				.vc_navbar-header a#vc_logo:hover,
				.vc_navbar-header a#vc_logo:active {
					text-decoration:none!important;
				}
				';

	        }

	        if ( get_option('WPBACKEND_vc_fixed_editor') ) {

				$style .= '

				/* fixed edit panel */
				.vc_ui-panel {
					display: none;
				}
				.vc_ui-panel-content {
				    margin-top: inherit!important;
				}
				#vc_ui-panel-templates,
				#vc_ui-panel-add-element,
				#vc_ui-panel-edit-element {
					top: 0px!important;
				    bottom: 0px!important;
				    left: 0px!important;
				    right: 0px!important;
				    width: 100%!important;
				    height: 100%!important;
				    max-height: inherit!important;
				    overflow-y: auto!important;
				    position: fixed!important;
				    padding: 0px!important;
				    background: rgba(0,0,0,0.70)!important;
				}
				.vc_ui-panel-window-inner{
					margin: 5%!important;
				}
				';

	        }

	        if ( get_option('WPBACKEND_vc_boxed_row') ) {

				$style .= '

					/* full row handlebar */
					.vc_controls-row{
						background-color: #EEE;
						border-top-left-radius: 3px;
	    				border-top-right-radius: 3px;
					}
					.vc_controls-row .vc_row_layouts,
					.vc_controls-row .vc_control {
						background-color: #EEE!important;
						border-top-left-radius: 3px;
	    				border-top-right-radius: 3px;
					}
					.vc_controls-row .vc_control-set-column.custom_columns {
						border-bottom: none!important;
					}
					.wpb_vc_row > .wpb_element_wrapper > .vc_row {
					    border-bottom-left-radius: 3px;
					    border-bottom-right-radius: 3px;
					}
					.wpb_element_wrapper {
					    background-color: #FFF;
					}

					#visual_composer_content {
					    overflow:hidden;
					}
					.wpb_vc_row,
					.wpb_vc_row_inner {
					    overflow:hidden;
					}
					.vc_row{
					  background-color:#F5F5F5;
					  margin-bottom:20px;

					}
					/*tab bg bug*/
					.vc_tta-tabs-container {
					    background-color:#F5F5F5;
					    margin-right:0px!important;
					}

				';

	        }


	        if ( get_option('WPBACKEND_tiny_switch') ) {

				$style .= '

					body:not(.vc_fullscreen) .composer-switch {
					position: absolute!important;
					right: 1px!important;
					z-index: 100!important;
					top: -3px!important;
					height:35px;
					border-radius: 0px!important;
					border-top-right-radius:0px!important;
					border-bottom-right-radius:0px!important;
					background: rgba(255,255,255, .8)!important;
					overflow:hidden;
				}
				body:not(.vc_fullscreen) .composer-switch .logo-icon,
				body:not(.vc_fullscreen) .composer-switch .vc_spacer {
					*display:none!important;
					background-color:transparent;
					padding-right:0px;
				}
				body:not(.vc_fullscreen) .composer-switch .wpb_switch-to-composer{
					border-radius: 0px!important;
					background: transparent!important;
					color: #999!important;
				}

				.composer-switch a.wpb_switch-to-front-composer {
				    background-color:transparent!important;
				    color:#999!important;
				    display:none!important;
				}

				';

	        }



	        if ( get_option('WPBACKEND_vc_custom_style') ) {

				$style .= '

				/* solid column */

				.wpb_vc_column {
					padding-left: 0px!important;
					padding-right: 0px !important;
				}
				.wpb_vc_row > .wpb_element_wrapper > .vc_row {
					margin-left: 0px;
					margin-right: 0px;
					background-color: #F5F5F5;
					margin-bottom: 15px;
				}
				a.vc_control.column_add.vc_column-add:last-child {
					display: none;
				}

				/* hide column edit */
				.wpb_row_container>.wpb_vc_column>.vc_controls {
				    visibility: hidden;
				}
				.wpb_row_container>.wpb_vc_column:hover>.vc_controls {
				    visibility: visible;
				}

				/*full selector*/
				.vc_controls > .vc_controls-cc {
					top: 0px!important;
					left: 0px!important;
					transform: none!important;
					width: 100%!important;
					height: 100%!important;
					background-color: rgba(175, 175, 175, 0 )!important;
					text-align: right!important;
					line-height: 52px!important;
				}
				.wpb_content_element:hover .wpb_element_wrapper {
				    border: 1px solid #999;
				}
				.vc_control-btn-edit .icon, .vc_control-btn-edit .vc_icon {
				   background-position: -16px -32px!important;
				}
				.vc_control-btn-clone .icon, .vc_control-btn-clone .vc_icon {
				   background-position: -16px -48px!important;
				}
				.vc_control-btn-delete .icon, .vc_control-btn-delete .vc_icon {
				   background-position: -16px -64px!important;
				}
				.vc_controls > .vc_controls-cc a {
					z-index:1;
				}
				a.vc_control-btn.vc_control-btn-edit,
				a.vc_control-btn.vc_control-btn-clone,
				a.vc_control-btn.vc_control-btn-delete {
				    background-color: rgba(255, 255, 255, 0.95);
				    top: 1px;
				    bottom: 1px;
				    position: absolute;
				}
				a.vc_control-btn.vc_control-btn-edit { right:61px; border-left: 1px solid #999!important; }
				a.vc_control-btn.vc_control-btn-clone { right:31px }
				a.vc_control-btn.vc_control-btn-delete { right:1px }
				.vc_controls > .vc_controls-cc .vc_element-move,
				.vc_controls > .vc_controls-cc .vc_element-move span {
					position: absolute;
					left: 0px;
					top: 0px;
					right: 0px;
					bottom: 0px;
					width: 100%;
					opacity: 0;
					z-index:-1;
				}
				.vc_controls > .vc_controls-cc .vc_control-btn .vc_btn-content:hover{
					background-color: inherit!important;
				}


				';

	        }


        //$style .= '}';

				$style .= stripslashes( get_option('WPBACKEND_toolbar_custom_css') );

	$style .= '</style>';

	echo $style;

}

public function my_custom_login() {

	add_filter( 'login_headerurl', array( $this, 'my_login_logo_url' ) );
	// add_filter( 'login_headertitle', array( $this, 'my_login_logo_url_title' ) );

	$style = '';

	$style .= '<style type="text/css">';

	$style .= '.login form { margin-top: 0px; }';

	if ( get_option('WPBACKEND_login_bg') ) {
		$style .= 'body.login:not(.interim-login) {';
			$bg = json_decode( stripslashes( get_option('WPBACKEND_login_bg') ), true );
			$style .= $bg[0]['css'];
		$style .= '}';
	}

	if ( get_option('WPBACKEND_login_logo') ) {

		$style .= '.login form { position: relative; margin-top: 0px; } .login h1, .login h1 a { margin: 0px; padding: 0px; width: 100%; background-size: inherit; cursor:default; -webkit-box-shadow: none; box-shadow: none; }';
		$style .= '.login h1 a { ';
			//$style .= '.login h1 a { position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px; height: inherit;';
			$bg = json_decode( stripslashes( get_option('WPBACKEND_login_logo') ), true );
			$style .= $bg[0]['css'];
		$style .= '}';

	} else {

		$style .= '.login h1 { display:none; }';

	}

	if ( get_option('WPBACKEND_login_hide_h1') ) {

		$style .= '#login h1 {display: none!important;}';

	}

	if ( get_option('WPBACKEND_login_panel_color') ) {
		$style .= '.login #backtoblog a, .login #nav a { color: '.get_option('WPBACKEND_login_panel_color').'; }';
	}

	if ( get_option('WPBACKEND_login_hide_nav') ) {

		$style .= 'p#nav {display: none;}';

	}

	if ( get_option('WPBACKEND_login_hide_back') ) {

		$style .= 'p#backtoblog {display: none;}';

	}

	if ( get_option('WPBACKEND_login_hide_message') ) {

		$style .= '#login_error, .login .message {display: none!important;}';

		add_filter( 'login_errors', array( $this, 'login_error_override' ) );
		remove_action('login_head', 'wp_shake_js', 12);

	}

	if ( get_option('WPBACKEND_login_panel_max_width') ) $style .= '#login { width: 100%; max-width: ' . get_option('WPBACKEND_login_panel_max_width') . ';}';
	if ( get_option('WPBACKEND_login_panel_padding') ) $style .= '#login, .interim-login #login { padding: ' . get_option('WPBACKEND_login_panel_padding') . ';}';
	if ( get_option('WPBACKEND_login_panel_margin') ) $style .= '#login, .interim-login #login { margin: ' . get_option('WPBACKEND_login_panel_margin') . ';}';

	$style .= '.login form .input, .login form input[type=checkbox], .login input[type=text] { background: #fbfbfb!important; border-color: #DDDDDD; -webkit-box-shadow: none; box-shadow: none; }';

	if ( get_option('WPBACKEND_login_panel_color') ) $style .= '.login form { background-color: '.get_option('WPBACKEND_login_panel_color').'; border: none; -webkit-box-shadow: none; box-shadow: none; }';

	if ( get_option('WPBACKEND_login_theme') == "light" ) {
		$style .= '.login label { color: #FFF; }';
		$style .= '.login #wp-submit.button-primary { background: #FFF; border: none!important; -webkit-box-shadow: none!important; box-shadow: none!important; color: #333; text-decoration: none; text-shadow: none; }';
	} else {
		$style .= '.login label { color: #333;}';
		$style .= '.login #wp-submit.button-primary { background: #333; border: none!important; -webkit-box-shadow: none!important; box-shadow: none!important; color: #FFF; text-decoration: none; text-shadow: none; }';
	}

	$style .= 'body.interim-login{ background:transparent!important; }';

	$style .= '</style>';

	echo $style;

}
public function my_custom_login_header() {

	if ( get_option('WPBACKEND_login_header_logo') ) {

		echo '<img class="custom-login-logo" src="' . get_option('WPBACKEND_login_header_logo') . '" />';

	}


}
public function rememberme_checked() {

	if ( get_option('WPBACKEND_login_auto_remember') ) {

		$script = "<script>document.getElementById('rememberme').checked = true;</script>";

		echo $script;

	}

}

public function my_login_logo_url() {

return '#';

}

public function my_login_logo_url_title() {

return '';

}

public function login_error_override() {

    return '';

}

public function go_home(){

  if ( get_option('WPBACKEND_logout_go_home') ) {

  		wp_redirect( home_url() );
 		exit();

	}

}

function admin_login_redirect( $redirect_to, $request, $user ) {

	global $user;

	if( isset( $user->roles ) && is_array( $user->roles ) ) {

		if( in_array( "administrator", $user->roles ) ) {

			if ( get_option('WPBACKEND_login_redirect_admin') ) {

		  		return get_option('WPBACKEND_login_redirect_admin');

			} else {

				return $redirect_to;

			}

		} else {

			if ( get_option('WPBACKEND_login_redirect_notadmin') ) {

		  		return get_option('WPBACKEND_login_redirect_notadmin');

			} else {

				return $redirect_to;

			}


		}

	} else {

		return $redirect_to;

	}

}





/**
*
* replace_howdy
*
* @desc remove the howdy
*
**/
public function replace_howdy( $wp_admin_bar ) {

	$my_account=$wp_admin_bar->get_node('my-account');
	$newtitle = str_replace( 'Howdy,', '', $my_account->title );
	$wp_admin_bar->add_node( array(
		'id' => 'my-account',
		'title' => $newtitle,
	) );

}


/**
*
* disable_margin_32px
*
* @desc add class to force html 32px height
*
**/
// function disable_margin_32px( $output ) {

// 	$output .= ' class="wp-backend"';

// 	return $output;

// }

/**
*
* class_body
*
* @desc remove front end body class 'admin-bar' to prevent extra admin bar style from theme (e.g. style.css)
*
**/
// public function class_body( $wp_classes ) {

// 	global $current_user_can_access_admin_page;

// 	if ( is_admin_bar_showing() && get_user_meta( $current_user->ID, 'toolbar_tiny', true ) == "checked" ) {

// 		$arr_id = array_search('admin-bar',$wp_classes);

// 		unset( $wp_classes[$arr_id] );

// 		$wp_classes[] = 'toolbar-' . get_user_meta($current_user->ID, 'toolbar_pos',true);

// 	}

// 	return $wp_classes;

// }


/**
*
* admin_script
*
* @desc load css only if admin bar display
*
**/
public function admin_script_n_style() {

	global $pagenow, $post_type ;

	//add admin script n style
	wp_enqueue_style( 'HANDYPRESS_WPBACKEND_ADMIN', LINOTYPE_plugin::$plugin['url'] . '/assets/css/backend.css', array( 'admin-bar' ), false, 'all' );
	wp_enqueue_script('HANDYPRESS_WPBACKEND_ADMIN', LINOTYPE_plugin::$plugin['url'] . '/assets/js/backend.js', array('jquery'), null, true );

	//debug expend editor
	if ( get_option( 'WPBACKEND_editor_expand_debug' ) ) {
		wp_deregister_script( 'editor-expand' );
		wp_enqueue_script('custom-editor-expand', LINOTYPE_plugin::$plugin['url'] . '/assets/js/custom-editor-expand.min.js', array('jquery'), null, true );
	}

	//menu highlight debug
	if( get_option('WPBACKEND_sidebar_position') && get_option('WPBACKEND_sidebar_position') !== 'WPBACKEND_sidebar_hide' ) {

		wp_enqueue_script('WPBACKEND-menu-highlight-fix', LINOTYPE_plugin::$plugin['url'] . '/assets/js/menu-highlight-fix.js', array('jquery'), null, true );

		wp_localize_script( 'WPBACKEND-menu-highlight-fix', 'js_wp_backend', array(
			"pagenow" => $pagenow,
			"post_type" => $post_type,
		));

	}

}


/**
*
* adminbar_site
*
* @desc only if tiny checked
*
**/
// public function adminbar_site() {

// 	global $current_user;

// 	if ( is_admin_bar_showing() && get_user_meta( $current_user->ID, 'toolbar_tiny', true ) == "checked" ) {

// 		//wp_enqueue_style( 'HANDYPRESS_WPBACKEND_SITE', WPBACKEND_URL . 'css/WPBACKEND-SITE.css', array( 'admin-bar' ), false, 'all' );

// 	}

// }

/**
*
* head_ob_start
*
* @desc ob on profile page to insert tiny row under toolbar settings
*
**/
// public function head_ob_start() {

//   global $pagenow;

//   if ( $pagenow == 'profile.php' || $pagenow == 'user-edit.php' ){
// 			ob_start( array( $this, 'add_personal_options' ) );
// 	}

// }

/**
*
* add_personal_options
*
* @desc create setting row
*
**/
public function add_personal_options( $subject ) {

	global $pagenow;

	if ( isset($subject) && ( $pagenow == 'profile.php' || $pagenow == 'user-edit.php' ) ) {

		global $current_user;

		$subject = WPBACKEND_str_get_html($subject);

		if ( $pagenow == 'user-edit.php' ){

			$the_user_ID = $_GET['user_id'];

			$plugin_version = '1.0.0';

			if( get_user_meta( $the_user_ID, 'WPBACKEND_plugin_version', true ) !== $plugin_version ) {

				add_user_meta( $the_user_ID, 'toolbar_tiny', "checked" );
				add_user_meta( $the_user_ID, 'toolbar_pos', "topLeft" );

				update_user_meta( $the_user_ID, 'toolbar_tiny', "checked" );
				update_user_meta( $the_user_ID, 'toolbar_pos', "topLeft" );

				add_user_meta( $the_user_ID, 'WPBACKEND_plugin_version', $plugin_version );
				update_user_meta( $the_user_ID, 'WPBACKEND_plugin_version', $plugin_version );

			}

		} else {

			$the_user_ID = $current_user->ID;

		}

		$toolbar_tiny = get_user_meta( $the_user_ID, 'toolbar_tiny', true );

		$toolbar_pos = get_user_meta( $the_user_ID, 'toolbar_pos', true );

		$toolbar_pos_topLeft = '';
		$toolbar_pos_topRight = '';
		$toolbar_pos_bottomLeft = '';
		$toolbar_pos_bottomRight = '';
		$toolbar_pos_default = '';

		switch ($toolbar_pos) {
			case 'topLeft':
				$toolbar_pos_topLeft = 'checked';
			break;
			case 'topRight':
				$toolbar_pos_topRight = "checked";
			break;
			case 'bottomLeft':
				$toolbar_pos_bottomLeft = "checked";
			break;
			case 'bottomRight':
				$toolbar_pos_bottomRight = "checked";
			break;
			default:
				$toolbar_pos_default = "checked";
			break;
		}

		$row = '';

		$row .= '<tr>';

			$row .= '<th scope="row">Toolbar Tiny</th>';

				$row .= '<td>';
					$row .= '<label for="toolbar_tiny"><input name="toolbar_tiny" id="toolbar_tiny" type="checkbox" '.$toolbar_tiny.'> Enable Tiny Toolbar when viewing site</label>  ';
					$row .= '<input type="radio" name="toolbar_pos" value="topLeft" '.$toolbar_pos_topLeft.' style="margin-left:10px;">Top Left</input>';
					$row .= '<input type="radio" name="toolbar_pos" value="topRight" '.$toolbar_pos_topRight.' style="margin-left:10px;">Top Right</input>';

				$row .= '</td>';

		$row .= '</tr>';

		$subject->find('#admin_bar_front', 0)->parent->parent->parent->parent->innertext = $subject->find('#admin_bar_front', 0)->parent->parent->parent->parent->innertext . $row;

	}

	return $subject;

}

/**
*
* footer_ob_end
*
* @desc end ob
*
**/
// public function footer_ob_end() {

// 	global $pagenow;

// 	if ( $pagenow == 'profile.php' || $pagenow == 'user-edit.php' ){
// 		ob_end_flush();
// 	}

// }

/**
*
* update_extra_profile_fields
*
* @desc save extra profile meta
*
**/
public function update_extra_profile_fields( $user_id ) {

	if ( $_POST['toolbar_tiny'] ){
		$toolbar_tiny = "checked";
	}else{
		$toolbar_tiny = "";
	}

	update_user_meta( $user_id, 'toolbar_tiny', $toolbar_tiny );

	update_user_meta( $user_id, 'toolbar_pos', $_POST['toolbar_pos'] );

}


/**
**
** TEXT DOMAINE
**
** Set language
**
*/
public function textDomain() {

	$domain = 'WPBACKEND';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

  load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );

  load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( WPBACKEND_FILE ) ) . '/lang/' );

}


/**
**
** ACTIVATE
**
** @desc Check Wordpress version on plugin activation
**
*/
public function activate( $network_wide ) {

	if ( version_compare( get_bloginfo( 'version' ), '3.0', '<' ) ) {

   	 	deactivate_plugins( WPBACKEND_FILE  );

    	wp_die( __('WordPress 3.0 and higher required. The plugin has now disabled itself. Upgrade!','WPBACKEND') );

	}

}

/**
**
** UPDATE
**
** @desc Check plugin version
**
*/
public function update(){

	global $current_user;

	$plugin_version = '1.0.0';

	if( get_user_meta( $current_user->ID, 'WPBACKEND_plugin_version', true ) !== $plugin_version ) {

		add_user_meta( $current_user->ID, 'toolbar_tiny', "checked" );
		add_user_meta( $current_user->ID, 'toolbar_pos', "topLeft" );

		update_user_meta( $current_user->ID, 'toolbar_tiny', "checked" );
		update_user_meta( $current_user->ID, 'toolbar_pos', "topLeft" );

		add_user_meta( $current_user->ID, 'WPBACKEND_plugin_version', $plugin_version );
		update_user_meta( $current_user->ID, 'WPBACKEND_plugin_version', $plugin_version );

	}

}

/**
*
* DESACTIVATE PLUGIN
*
**/
public function deactivate( $network_wide ) {

}

/**
*
* UNINSTALL PLUGIN
*
**/
public function uninstall( $network_wide ) {

}


}

$WPBACKEND = new WPBACKEND();


?>

<?php

if ( ! class_exists('handypress_plugin') ) {
	
class handypress_plugin {

	static $settings;

	function __construct( $settings = array() ) {

		$settings_default = array(
			'name' => 'HANDYPRESS_plugin_starter',
			'version' => '1.0.0',
			'basename' => null,
			'dir' => null,
			'url' => null,
			'domain' => 'handypress-plugin-starter',
			'activate_function' => null,
			'update_function' => null,
			'links' => array(),
		);

		self::$settings = array_merge( $settings_default, $settings );

		if ( self::$settings['basename'] == null || self::$settings['dir'] == null || self::$settings['url'] == null ) {

			wp_die( 'Plugin initialisation require basename, plugin directory path and url path', 'init', self::$settings );

		}

		//text Domain
	    add_action( 'plugins_loaded', array( $this, 'textDomain' ) );

	    //quick link
	    add_filter( 'plugin_action_links_' . self::$settings['basename'], array( $this, 'quick_links' ) );

	    //activation
	    add_action( 'activated_plugin', array( $this, 'activate' ) );

	    //update
	    add_action( 'init', array( $this, 'update' ) );

	    //reset
	    add_action( 'init', array( $this, 'reset' ) );

	}

	static function get(){

		return self::$settings;

	}

	/*
	 *
	 * textDomain
	 *
	 */
	public function textDomain() {

		$locale = apply_filters( 'plugin_locale', get_locale(), self::$settings['domain'] );

		if ( $loaded = load_textdomain( self::$settings['domain'], trailingslashit( WP_LANG_DIR ) . self::$settings['domain'] . '/' . self::$settings['domain'] . '-' . $locale . '.mo' ) ) {

			return $loaded;

		} else {

			load_plugin_textdomain( self::$settings['domain'], FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );

		}

	}

	/*
	 *
	 * quick_links
	 *
	 */
	public function quick_links( $links ) {

		$plugin_links = array();

		if ( self::$settings['links'] ){
			foreach ( self::$settings['links'] as $plugin_link_key => $plugin_link ) {

				$plugin_link = array_merge( array( 'url' => '', 'class' => '', 'title' => '' ), $plugin_link );

				$new_link = '<a href="' . $plugin_link['url'] . '" class="' . $plugin_link['class'] . '">' . $plugin_link['title'] . '</a>';

				array_push( $plugin_links, $new_link );

			}
		}

	  	return array_merge( $plugin_links, $links );

	}

	/*
	 *
	 * activate
	 *
	 */
	public function activate( $plugin ) {

	    if( is_callable( self::$settings['activate_function'] ) && $plugin == self::$settings['basename'] ) {

	        self::$settings['activate_function']();

	    }

	}

	/*
	 *
	 * update
	 *
	 */
	public function update(){

	  	if( is_callable( self::$settings['update_function'] ) && get_option( self::$settings['name'] . '_plugin_version' ) !== self::$settings['version'] ) {

	        self::$settings['update_function']();

	        update_option( self::$settings['name'] . '_plugin_version', self::$settings['version'] );

	    }

	}

	/*
	 *
	 * reset
	 *
	 */
	public function reset(){

		/* TODO Create a reset system
		if( is_callable( self::$settings['reset_function'] ) && get_option( self::$settings['name'] . '_reset' ) ) {

			update_option( self::$settings['name'] . '_reset', "" );

	        self::$settings['reset_function']();

	    }
	    */

	}

}
}

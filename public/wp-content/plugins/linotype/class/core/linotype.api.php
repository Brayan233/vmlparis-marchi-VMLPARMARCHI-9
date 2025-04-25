<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class LINOTYPE_api extends WP_REST_Controller {
 
  private $api_namespace;
	private $base;
	private $api_version;
	private $required_capability;
	
	public function __construct() {
    
    $this->api_namespace = 'linotype/v';
		$this->api_version = '1';
		$this->required_capability = 'read';
		
    $this->init();
    
  }
  
  public function init(){

    add_action( 'rest_api_init', array( $this, 'register_routes' ) );
  
  }

  public function permission( WP_REST_Request $request ){
    
    ////////dev
    return true;
    ////////

    $creds = array();
		$headers = getallheaders();

		if ( array_key_exists( 'user', $headers ) && array_key_exists( 'pass', $headers ) ) {

			$creds['user_login'] = $headers["user"];
			$creds['user_password'] =  $headers["pass"];
			$creds['remember'] = false;
			$user = wp_signon( $creds, false );
			
			if ( is_wp_error($user) ) {
				return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
			}
			
			wp_set_current_user( $user->ID, $user->user_login );
			
			if ( ! current_user_can( $this->required_capability ) ) {

				return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
      
      }

      return true;

    } else {
    
      return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
    
    }

  }

  public function register_routes() {

    

    register_rest_route( $this->api_namespace . $this->api_version, '/block(?:/(?P<id>[^/]+))', array(
      array(
          'methods'  => WP_REST_Server::READABLE,
          'permission_callback'   => array( $this, 'permission' ),
          'callback' => array( $this, 'get_block' ),
          'args' => array(
            'id' => array(
              'required' => true,
              'type' => 'string',
              'description' => 'the block id',
            ),
          ),
      )
    ));

    register_rest_route( $this->api_namespace . $this->api_version, '/blocks', array(
      array(
          'methods'  => WP_REST_Server::READABLE,
          'permission_callback'   => array( $this, 'permission' ),
          'callback' => array( $this, 'list_blocks' )
      )
    ));


    register_rest_route( $this->api_namespace . $this->api_version, '/template(?:/(?P<id>[^/]+))', array(
      array(
          'methods'  => WP_REST_Server::READABLE,
          'permission_callback'   => array( $this, 'permission' ),
          'callback' => array( $this, 'get_template' ),
          'args' => array(
            'id' => array(
              'required' => true,
              'type' => 'string',
              'description' => 'the template id',
            ),
          ),
      )
    ));

    register_rest_route( $this->api_namespace . $this->api_version, '/templates', array(
      array(
          'methods'  => WP_REST_Server::READABLE,
          'permission_callback'   => array( $this, 'permission' ),
          'callback' => array( $this, 'list_templates' )
      )
    ));


  }

  public function list_blocks( WP_REST_Request $request ){
    
    $DATA = array();
    $ITEMS = LINOTYPE::$BLOCKS->get();
    
    if ( isset ( $ITEMS ) ) {
      foreach ( $ITEMS as $item_id => $item ) {

        $DATA[ $item['id'] ] = $item['update'];

      }
    }

    return $DATA;

  }

  public function list_templates( WP_REST_Request $request ){
    
    $DATA = array();
    $ITEMS = LINOTYPE::$TEMPLATES->get();
    
    if ( isset ( $ITEMS ) ) {
      foreach ( $ITEMS as $item_id => $item ) {

        $DATA[ $item['id'] ] = $item['update'];

      }
    }

    return $DATA;

  }

  public function get_block( WP_REST_Request $request ){
    
    $DATA = array();
    $ITEMS = LINOTYPE::$BLOCKS->get();
    $ID = $request->get_param( 'id' );

    if ( $ID ) {

      $DATA = $ITEMS[$ID];

    }

    return $DATA;

  }
  
  public function get_template( WP_REST_Request $request ){
    
    $DATA = array();
    $ITEMS = LINOTYPE::$TEMPLATES->get();
    $ID = $request->get_param( 'id' );

    $STYLES = "";
    $SCRIPTS = "";

    if ( $ID ) {

      $template = $ITEMS[$ID]['template'];

      //get all require libraries asset
      foreach( $template as $item_key => $item ) {

        $block = LINOTYPE::$BLOCKS->get( $item['type'] ); 

        if( $block['libraries'] ) {
          foreach( $block['libraries'] as $library_id ) {
            
            $library = LINOTYPE::$LIBRARIES->get( $library_id ); 

            $STYLES  .= file_get_contents( $library['dir'] . '/style.css' ) . PHP_EOL;
            $SCRIPTS .= file_get_contents( $library['dir'] . '/script.js' ) . PHP_EOL;

          }
        }

      }

      //get all blocks data and assets
      foreach( $template as $item_key => $item ) {

        $block = LINOTYPE::$BLOCKS->get( $item['type'] ); 

        $template[ $item_key ]['id'] = handypress_helper::getUniqueID();
        $template[ $item_key ]['tpl'] = file_get_contents( $block['dir'] . '/template.php' );
        $template[ $item_key ]['url'] = $block['url'];

        $STYLES  .= file_get_contents( $block['dir'] . '/style.css' ) . PHP_EOL;
        $SCRIPTS .= file_get_contents( $block['dir'] . '/script.js' ) . PHP_EOL;

      }

      LINOTYPE_helpers::file_save( LINOTYPE::$SETTINGS['dir_cache'] . '/public/api/templates/' . $ID . '/template.json', json_encode( $DATA ) );
      LINOTYPE_helpers::file_save( LINOTYPE::$SETTINGS['dir_cache'] . '/public/api/templates/' . $ID . '/styles.css', $STYLES );
      LINOTYPE_helpers::file_save( LINOTYPE::$SETTINGS['dir_cache'] . '/public/api/templates/' . $ID . '/scripts.js', $SCRIPTS );
      
      $DATA = array(
        'template' => array_values( $template ),
        'settings' => array(
          'styles' => LINOTYPE::$SETTINGS['url_cache'] . '/public/api/templates/' . $ID . '/styles.css',
          'scripts' => LINOTYPE::$SETTINGS['url_cache'] . '/public/api/templates/' . $ID . '/scripts.js',
        ),
      );

    }
    
    return $DATA;

  }

  
 
}

$LINOTYPE_api = new LINOTYPE_api();


function add_linotype_api_cache( $allowed_endpoints ) {
  
  $allowed_endpoints[ 'linotype/v1' ] = array(
    'templates',
    'template',
    'blocks',
    'block'
  );

  return $allowed_endpoints;

}

add_filter( 'wp_rest_cache/allowed_endpoints', 'add_linotype_api_cache', 10, 1);


//change prefix
function rest_url_prefix( ) {
  return 'api';
}
//add_filter( 'rest_url_prefix', 'rest_url_prefix' );
 
//disable default api
function remove_default_endpoints( $endpoints ) {

  foreach ( $endpoints as $endpoint_key => $endpoint ) {

    unset( $endpoints['/'] );
    if ( substr( $endpoint_key, 0, 7 ) === "/oembed" ) unset( $endpoints[ $endpoint_key ] );
    if ( substr( $endpoint_key, 0, 3 ) === "/wp" ) unset( $endpoints[ $endpoint_key ] );

  }
  return $endpoints ;
}
//add_filter( 'rest_endpoints', 'remove_default_endpoints' );


function restrict_rest_api_to_localhost() {

  $whitelist = [ '127.0.0.1', "::1" ];

  if( ! in_array($_SERVER['REMOTE_ADDR'], $whitelist ) ){
      die( 'REST API is disabled.' );
  }

}
//add_action( 'rest_api_init', 'restrict_rest_api_to_localhost', 0 );
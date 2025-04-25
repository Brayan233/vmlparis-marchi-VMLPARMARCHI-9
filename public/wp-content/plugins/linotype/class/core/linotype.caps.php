<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*
* LINOTYPE_caps
*
*
**/
class LINOTYPE_caps {

  function __construct(){

    add_action( 'init',  array( $this, 'add_caps' ), 0 );

  }

  public function add_caps() {

    $administrator = get_role( 'administrator' );
    if ( $this->run_once('linotype_admin_admin') ) $administrator->add_cap( 'linotype_admin' );
    if ( $this->run_once('linotype_admin_edit') )  $administrator->add_cap( 'linotype_edit' );
    if ( $this->run_once('linotype_admin_save') )  $administrator->add_cap( 'linotype_save' );

    $editor = get_role( 'editor' );
    if ( $this->run_once('linotype_editor_admin') ) $editor->add_cap( 'linotype_admin' );
    if ( $this->run_once('linotype_editor_edit') ) $editor->add_cap( 'linotype_edit' );
    if ( $this->run_once('linotype_editor_save') ) $editor->add_cap( 'linotype_save' );

    $author = get_role( 'author' );
    if ( $this->run_once('linotype_author_edit') ) $author->add_cap( 'linotype_edit' );


    if ( ! is_main_site() ) {
      $administrator->remove_cap( 'linotype_save' );
      $editor->remove_cap( 'linotype_save' );
      $author->remove_cap( 'linotype_save' );
    }

  
  }

  public function run_once($key) {

    $test_case = get_option('linotype_caps_run_once');

    if (isset($test_case[$key]) && $test_case[$key]){

        return false;

    } else {

        $test_case[$key] = true;
        update_option('linotype_caps_run_once',$test_case);
        return true;

    }

  }

}

new LINOTYPE_caps();

?>
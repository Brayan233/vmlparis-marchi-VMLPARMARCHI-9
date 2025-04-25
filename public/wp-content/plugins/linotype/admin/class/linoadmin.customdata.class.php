<?php

/**
 * LINOADMIN_CUSTOMDATA
 *
 * Init the Custom data display
 *
 * require
 * - handypress_db
 * - handypress_helper
 * - handypress_table
 *
 * @package WordPress
 * @subpackage LINOADMIN
 * @version 1.0.0
 * @author Yannick Armspach
 * @link http://www.handypress.io
 *
 */
class LINOADMIN_CUSTOMDATA extends LINOADMIN {

  public $PAGE = null;

  public $db = null;

  public $columns = null;

  /**
   * __construct
   *
   * @since 1.0
   *
   * @param array  $META  the meta params
   *
   */
  function __construct( $PAGE ) {

    $this->PAGE = $PAGE;

    $this->sql_columns = $this->create_sql_columns();

    $this->table_columns = $this->create_table_columns();

    //create Database
    $this->db = new handypress_db( array( "id" => $this->PAGE['id'], "columns" => $this->sql_columns ) );

  }

  public function db() {

    return $this->db;

  }

  public function create_sql_columns(){

    $columns = array();

    //define all meta as sql columns
    if ( isset( $this->PAGE["metabox"] ) ) {
    foreach ( $this->PAGE["metabox"] as $METABOX_ID => $METABOX ) {

      if ( isset( $METABOX['tabs_contents'] ) ) {
      foreach ( $METABOX['tabs_contents'] as $TAB_ID => $TAB ) {

        if ( isset( $TAB['meta'] ) ) {
        foreach ( $TAB['meta'] as $META_ID => $META ) {

          $db_label =  $META_ID;
          $columns[$db_label] = array(
            'row' => $db_label . " varchar(255) NOT NULL default ''",
            'format' => '%s'
          );

        }
        }

      }
      }

    }
    }

    //if special column exist
    if ( isset( $this->PAGE['customdata']['sql_columns'] ) ) {

      //check if full custom value
      if ( handypress_helper::isArrayAssoc( $this->PAGE['customdata']['sql_columns'] ) ) {

        //overide with this custom column
        $columns = $this->PAGE['customdata']['sql_columns'];

      } else {

        //or if simple order columns, remap the default columns
        $columns_map = array_fill_keys( $this->PAGE['customdata']['sql_columns'], null );

        foreach ( $columns_map as $key => $value ) {
          $columns_map[$key] = $columns[$key];
        }

        $columns = $columns_map;

      }

    }

    return $columns;

  }

  public function create_table_columns(){

    $columns = array();

    //define all meta as custom table column
    if ( isset( $this->PAGE["metabox"] ) ) {
    foreach ( $this->PAGE["metabox"] as $METABOX_ID => $METABOX ) {

      if ( isset( $METABOX['tabs_contents'] ) ) {
      foreach ( $METABOX['tabs_contents'] as $TAB_ID => $TAB ) {

        if ( isset( $TAB['meta'] ) ) {
        foreach ( $TAB['meta'] as $META_ID => $META ) {

          $columns[ $META_ID ] = $META;

        }
        }

      }
      }

    }
    }

    //if special column exist, remap the table column
    if ( isset( $this->PAGE['customdata']['table_columns'] ) ) {

      $columns_map = array_fill_keys( $this->PAGE['customdata']['table_columns'], null );

      foreach ( $columns_map as $key => $value ) {
        $columns_map[$key] = $columns[$key];
      }

      $columns = $columns_map;

    }

    return $columns;

  }



  public function display() {

    echo '<div class="wrap">';

    if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "add-new" ) {

      $this->display_add();

    } else if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "edit" && isset( $_REQUEST['post'] ) && $_REQUEST['post'] ) {

      $this->display_edit();

    } else {

      $this->display_list();

    }

    echo '</div>';

  }

  public function display_add() {

    if ( $this->PAGE["title"] ) echo '<h1>';
    if ( $this->PAGE["title_icon"] ) echo '<span style="width: 32px;height: 32px;font-size: 32px;" class="dashicons ' . $this->PAGE["title_icon"] . '"></span> ';
    if ( $this->PAGE["title"] ) echo __('Add') . ' ' . $this->PAGE["title"] . '<a href="' . admin_url( 'admin.php?page=' . $this->PAGE["id"] ) . '" class="page-title-action">' . __('Cancel') . '</a>';
    if ( $this->PAGE["title"] ) echo '</h1>';

    ?>

    <div class="clear"></div>

    <form id="wpbody-form" method="post">

      <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

          <div id="postbox-container-1" class="postbox-container">
            <?php do_meta_boxes('','side',null);  ?>
            <?php do_meta_boxes('','core',null);  ?>
          </div>

          <div id="postbox-container-2" class="postbox-container">
            <?php do_meta_boxes('','normal',null);  ?>
            <?php do_meta_boxes('','default',null);  ?>
            <?php do_meta_boxes('','low',null);  ?>

          </div>

        </div>

      </div>

    </form>

    <div class="clear"></div>

    <?php

  }

  public function display_edit() {

    if ( $this->PAGE["title"] ) echo '<h1>';
    if ( $this->PAGE["title_icon"] ) echo '<span style="width: 32px;height: 32px;font-size: 32px;" class="dashicons ' . $this->PAGE["title_icon"] . '"></span> ';
    if ( $this->PAGE["title"] ) echo __('Edit') . ' ' . $this->PAGE["title"] ;
    if ( $this->PAGE["title"] ) echo '</h1>';

    ?>

    <div class="clear"></div>

    <form id="wpbody-form" method="post">

      <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-<?php echo $this->PAGE["column"]; ?>">

          <?php if ( $this->PAGE["column"] == 1 ) { ?>

            <div id="postbox-container-2" class="postbox-container">
              <?php do_meta_boxes('','normal',null);  ?>
              <?php do_meta_boxes('','default',null);  ?>
              <?php do_meta_boxes('','low',null);  ?>
              <?php do_meta_boxes('','side',null);  ?>
              <?php do_meta_boxes('','core',null);  ?>
            </div>

          <?php } else { ?>

            <div id="postbox-container-1" class="postbox-container">
              <?php do_meta_boxes('','side',null);  ?>
              <?php do_meta_boxes('','core',null);  ?>
            </div>

            <div id="postbox-container-2" class="postbox-container">
              <?php do_meta_boxes('','normal',null);  ?>
              <?php do_meta_boxes('','default',null);  ?>
              <?php do_meta_boxes('','low',null);  ?>
            </div>

          <?php } ?>

        </div>

      </div>

      <div class="clear"></div>

      <input type="hidden" name="post" id="post" autocorrect="off" autocomplete="off" spellcheck="false" placeholder="" value="<?php echo $_REQUEST['post']; ?>">

    </form>

    <div class="clear"></div>

    <?php

  }

  public function display_list() {

    if ( $this->PAGE["title"] ) echo '<h1>';
    if ( $this->PAGE["title_icon"] ) echo '<span style="width: 32px;height: 32px;font-size: 32px;" class="dashicons ' . $this->PAGE["title_icon"] . '"></span> ';
    if ( $this->PAGE["title"] ) echo $this->PAGE["title"] . '<a href="' . admin_url( 'admin.php?page=' . $this->PAGE["id"] . '&action=add-new' ) . '" class="page-title-action">' . __('Add') . '</a>';
    if ( $this->PAGE["title"] ) echo '</h1>';

    $data_table = new handypress_table();

    $data_table->init_table( $this->PAGE, $this->db, $this->table_columns );

    $data_table->prepare_items();

    $data_table->views();

    echo '<form id="data-table-filter" method="get">';

    $data_table->search_box( 'search', 'search_id' );

    $data_table->display();

    if ( isset( $_REQUEST['page'] ) ) echo '<input type="hidden" name="page" value="' . $_REQUEST['page'] . '" />';
    if ( isset( $_REQUEST['paged'] ) ) echo '<input type="hidden" name="paged" value="' . $_REQUEST['paged'] . '" />';

    echo '</form>';

    echo '<div class="clear"></div>';

  }

}

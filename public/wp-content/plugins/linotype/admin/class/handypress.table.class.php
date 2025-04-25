<?php 

if ( ! class_exists('WP_List_Table') ) require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

if ( ! class_exists( 'handypress_table' ) ) {

class handypress_table extends WP_List_Table {
    
    public $PAGE;

    public $db;

    public $table_columns;

    public $total_items;

    /**
    *
    * __construct
    *
    */
    function __construct(){

        global $status, $page;

        //Set parent
        parent::__construct( array( 'singular' => 'item', 'plural' => 'items', 'ajax' => false ) );
        
    }

    /**
    *
    * init_table
    *
    */
    public function init_table( $PAGE = array(), $db = null, $table_columns = null ){
        
        $this->PAGE = $PAGE;

        $this->db = $db;

        $this->table_columns = $table_columns;

    }
    

    /**
    *
    * column_default
    *
    */
    public function column_default( $item, $column_name ) {

        switch ( $column_name ) {

            case 'ID':
            
                return $item[$column_name];
            
            break;

            case 'user_id':
            
                return $item[$column_name];
            
            break;

            case 'create_date':
                
                $date = $item[$column_name];

                $date = date(get_option('date_format'), strtotime($date) );

                return $date;
            
            break;

            case 'update_date':
                
                $date = $item[$column_name];
                
                $date = date(get_option('date_format'), strtotime($date) );

                return $date;
            
            break;

            default:

                $field = $this->table_columns[ $column_name ];
                $field['value'] = $item[$column_name];

                //check if custom field
                if ( is_dir( $field['type'] ) ) {

                    $type_name = explode('/', $field['type'] );
                    $type_name = end( $type_name );

                    if ( file_exists( $field['type'] . '/'.$field['type'].'_column.php' ) ) {

                        ob_start();
                        include $field['type'] . '/'.$field['type'].'_column.php';
                        $table_columns_html = ob_get_contents();
                        ob_end_clean();

                        return $table_columns_html;

                    }

                } else {

                    if ( file_exists( LINOADMIN::$ADMIN['dir'] . '/fields/'.$field['type'].'/'.$field['type'].'_column.php' ) ) {

                        //load template field
                        ob_start();
                        include LINOADMIN::$ADMIN['dir'] . '/fields/' . $field['type'] . '/'.$field['type'].'_column.php';
                        $table_columns_html = ob_get_contents();
                        ob_end_clean();

                        return $table_columns_html;

                    }

                }
                                
                return $field['value'];

            break;

        }

    }

    /**
    *
    * column_title
    *
    */
    public function column_title( $item ){

        $actions = array(
            'edit'          => '<a href="admin.php?page=' . $this->PAGE['id'] . '&action=' . 'edit'     . '&post='.$item['ID'].'">Edit</a>',
            //'send'          => '<a href="admin.php?page=' . $this->PAGE['id'] . '&action=' . 'send'     . '&post='.$item['ID'].'">Send</a>',
            //'download'      => '<a href="admin.php?page=' . $this->PAGE['id'] . '&action=' . 'download' . '&post='.$item['ID'].'">Download</a>',
            'delete'        => '<a href="admin.php?page=' . $this->PAGE['id'] . '&action=' . 'delete'   . '&post='.$item['ID'].'">Delete</a>',
        );

        $field = $this->table_columns[ 'title' ];

        $table_columns_html = stripcslashes( $item['title'] );

        if ( file_exists( $field['type'] . '/'. $field['type'] . '_column.php' ) ) {

            ob_start();
            include $field['type'] . '/'.$field['type'].'_column.php';
            $table_columns_html = ob_get_contents();
            ob_end_clean();

        }

        $table_content = $table_columns_html;

        $table_content = '<a href="admin.php?page=' . $this->PAGE['id'] . '&action=' . 'edit'     . '&post='.$item['ID'].'">' . $table_content . '</a>';

        $table_content .= $this->row_actions($actions);

        return $table_content;

    }

    /**
    *
    * column_cb
    *
    */
    public function column_cb( $item ) {

        return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item['ID'] );
    
    }

    /**
    *
    * column_url
    *
    */
    // public function column_url ( $item ) {
        
    //     return '<img width=100%" src="' . $item['url'] . '"/>';

    // }

    /**
    *
    * get_columns
    *
    */
    public function get_columns(){
        
        $columns['cb'] = '<input type="checkbox" />';
        
        if ( $this->table_columns ) {
            foreach ( $this->table_columns as $key => $value) {
                
                if ( $value['title'] !== "" ){
                    $columns[$key] = $value['title'];
                } else {
                    $columns[$key] = ucwords( $key );
                }    
                
            }
        }

        $columns['create_date'] = __('Create');
        $columns['update_date'] = __('Update');
        
        return $columns;

    }

    /**
    *
    * get_sortable_columns
    *
    */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',false),
            'create_date'    => array('create_date',false),
            'update_date'    => array('update_date',false),
            'status'    => array('status',false),
        );
        return $sortable_columns;
    }

    /**
    *
    * get_bulk_actions
    *
    */
    public function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }

    /**
    *
    * process_bulk_action
    *
    */
    public function process_bulk_action() {
        
        global $wpdb;
        $table_name = $this->db->table_ID;
        
        if( 'delete' === $this->current_action() ) {

            if( isset( $_REQUEST['item'] ) ) {
            
                $items = $_REQUEST['item'];
                if ( gettype( $_REQUEST['item'] ) == "string" ) $items = array( $items );

            } else if( isset( $_REQUEST['post'] ) ) {

                $items = array( $_REQUEST['post'] );

            }

            $ids = implode( ',', $items );

            if ( ! empty( $ids ) ) {
        
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
        
            }

        }

    }
    
    /**
    *
    * prepare_items
    *
    */
    public function prepare_items() {

        global $wpdb; 

        $this->process_bulk_action();

        $data = $this->db->get_data();

        if ( ! $data ) $data = array();

        $per_page = 15;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        //usort($data, 'usort_reorder');

        $current_page = $this->get_pagenum();
        
        $total_items = count($data);

        $this->total_items = $total_items;

        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
    
        $this->items = $data;
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );

    }

    /**
    *
    * get_first_item
    *
    */
    public function get_first_item(){
      
      return $this->items[0]['ID'];
      
    }

    /**
    *
    * get_total_item
    *
    */
    public function get_total_item(){
      
      return $this->total_items;
      
    }

    /**
    *
    * views
    *
    */
    public function views() {
        
        $views = array( 
          "all" => '<a href="#" class="current">' . __('All'). ' <span class="count">('. $this->total_items .')</span></a>', 
          //"uploaded" => '<a href="#">Uploaded <span class="count">(50)</span></a>', 
          //"controled" => '<a href="#">Controled <span class="count">(20)</span></a>'
        );

        if ( empty( $views ) )
            return;

        $this->screen->render_screen_reader_content( 'heading_views' );

        echo "<ul class='subsubsub'>\n";
        foreach ( $views as $class => $view ) {
            $views[ $class ] = "\t<li class='$class'>$view";
        }
        echo implode( " |</li>\n", $views ) . "</li>\n";
        echo "</ul>";
    }

    /**
    *
    * display
    *
    */
    public function display() {
    
        $singular = $this->_args['singular'];

        // echo '<br/>';

        $this->display_tablenav( 'top' );

        $this->screen->render_screen_reader_content( 'heading_list' );
        ?>
        <table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
            <thead>
            <tr>
                <?php $this->print_column_headers(); ?>
            </tr>
            </thead>

            <tbody id="the-list"<?php
                if ( $singular ) {
                    echo " data-wp-lists='list:$singular'";
                } ?>>
                <?php $this->display_rows_or_placeholder(); ?>
            </tbody>

            <tfoot>
            <tr>
                <?php $this->print_column_headers( false ); ?>
            </tr>
            </tfoot>

        </table>
        <?php

        $this->display_tablenav( 'bottom' );
    
    }

}

}

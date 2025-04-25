<?php 

class sg_block_switch {

    private $options;

    private $post_type;

    public $links;
    
    function __construct( $options = array() ) {
        
        $this->post_type = $options['post_type'];

        $this->links = array();

        $this->init();
    
    }
    
    private function init() {
    
        $args = array(  
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1, 
            'orderby' => 'menu_order',
        );
    
        $loop = new WP_Query( $args ); 
            
        while ( $loop->have_posts() ) : $loop->the_post(); 

            $class = 'switch-id-' . get_the_ID();

            if ( get_the_ID() == get_queried_object_id() ) {

                $class .= ' current';

                $this->links['current'] = array(
                    "id" => get_the_ID(),
                    "class" => $class,
                    "title" => get_the_title(),
                    "url" => get_the_permalink(),
                    "image" => intval( get_post_thumbnail_id() ),
                );
                
            }

            $this->links['items'][] = array(
                "id" => get_the_ID(),
                "class" => $class,
                "title" => get_the_title(),
                "url" => get_the_permalink(),
                "image" => intval( get_post_thumbnail_id() ),
            );
             
        endwhile;
    
        wp_reset_postdata(); 
        
    }

    public function items() {
        
        return $this->links['items'];
        
    }

    public function current() {
    
        return $this->links['current'];
        
    }

    public function is_current() {
    
        return false; //$this->links['current'];
        
    }

}
<?php 

class sg_block_product_grid {

    private $options;

    private $ids;

    public $products;
    
    function __construct( $options = array() ) {
        
        $this->ids = $options['post_ids'];

        $this->products = array();

        $this->init();
    
    }
    
    private function init() {
    
        $this->ids = explode( ',', $this->ids );
        
        foreach( $this->ids as $id ) {

            $product = wc_get_product( $id );

            if ( $product ) {

                $category = "";
                $tsf = function_exists( 'the_seo_framework' ) ? the_seo_framework() : null;
                if ( $tsf ) {
                    $primary_term = $tsf->get_primary_term( $id, 'product_cat' );
                    if ( $primary_term ) $category = $primary_term->name;
                } else {
                    $terms = get_the_terms ( $id, 'product_cat' );
                    if ( isset( $terms[0]->term_id ) ) $category = $terms[0]->name;
                }
                
                //colors
                $pa_colors = wp_get_post_terms( $id, 'pa_colors' );
                $colors = '';
                if ( $pa_colors ) {
                    foreach ( $pa_colors as $key => $value ) {
                        $color = get_term_meta( $value->term_id, 'color', true );
                        if ( $color ) $colors .= '<span class="tax-item" style="background:' . $color . '"></span>';
                    }
                }
                $colors = '<div class="tax-list tax-color-' . 'pa_colors' . '">' . $colors . '</div>';

                //sizes
                $pa_sizes = wp_get_post_terms( $id, 'pa_sizes' );
                $sizes = '';
                if ( $pa_sizes ) {
                    foreach ( $pa_sizes as $key => $value ) {
                        $label = get_term_meta( $value->term_id, 'label', true );
                        if ( $label ) {
                            $sizes .= '<span class="tax-item">' . $label . '</span>';
                        } else {
                            $sizes .= '<span class="tax-item tax-label-' . 'pa_sizes' . '">' . $value->name . '</span>';
                        }
                    }
                }
                $sizes = '<div class="tax-list">' . $sizes . '</div>';
                
                $this->products[] = array(
                    "title" => get_the_title( $id ),
                    "url" => get_the_permalink( $id ),
                    "category" => $category,
                    "image" => intval( get_post_thumbnail_id( $id ) ),
                    "price" => $product->get_price_html(),
                    "colors" => $colors,
                    "sizes" => $sizes,
                );

            }
            
        }
        
    }

    public function loop() {
        
        return $this->products;
        
    }

    private function get_product() {
    
        $this->ids = explode( ',', $ids );
        
    }

}

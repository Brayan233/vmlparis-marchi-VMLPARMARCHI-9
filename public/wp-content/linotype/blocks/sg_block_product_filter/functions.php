<?php 

/**
    * 
    * @overide_product_variations
    * 
    * force default attribute by url params
    * 
    * @param  array   $selected_attributes  default attribute value 
    *
    * @return array   new selected value 
*/
function overide_product_variations( $args = array() ){

    global $wp_query, $product;
    
    $available_variations = $product->get_variation_attributes();
    
    if ( 'no' == 'yes' ) {
        
        $rewrite = $wp_query->query_vars[ 'xxx' ];
        
        $variations = explode('/', $rewrite );

        foreach( $variations as $variation ) {

            $variation_data = explode('=', $variation );

            if ( $args['attribute'] == 'pa_' . $variation_data[0] ) {

                $args['selected'] = $variation_data[1];
                
            }
            
        }

    } else {
        
        foreach ( $available_variations as $key => $variations ) {
        
            if ( $args['attribute'] == $key && isset( $_GET[ $key ] ) && $_GET[ $key ] ) {

                $args['selected'] = $_GET[ $key ];

            }

        }
        
    }
   
  
    return $args;

}
// add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'overide_product_variations', 10, 1 );


//rewrite end point url for url params on single product page
function add_attribute_rewrite(){
		
    add_rewrite_endpoint( 'xxx', EP_PERMALINK );

    flush_rewrite_rules();

}

// add_action( 'init', 'add_attribute_rewrite' );

class sg_block_product_filter {

    private $options;

    private $data;

    function __construct( $options = array() ) {
        
        $defaults = array(
            "id" => "1",
            "title" => "shop",
            "filter_types" => "product",
            "filter_status" => "",
            "filter_product_types" => "",
            "filter_terms" => "",
            "filter_metas" => "wp_title,wc_product_cat,wc_product_cat_single,wc_price,wp_link,tax_pa_colors,tax_color_pa_colors,tax_pa_materials,tax_pa_sizes,tax_label_pa_sizes,wp_image,wp_image_retina",
            "filter_template_preset" => "",
            "filter_template" => "",
            "filter_settings" => "",
            "filter_options" => array(
                "per_page" => "1000",
                "visible_page" => "5",
            ),
            "filter_posttypes" => "",
            "filter_postmetas" => "",
            "filter_update" => "",
            "user_id" => "1",
            "update_date" => "2020-03-18 17:10:35",
            "create_date" => "2020-03-18 12:21:12",
        );

        $this->options = array_merge( $defaults, $options );
        
        // --- Pre-filter by category from clean URL ---
        $shop_category_slug = get_query_var('shop_category');
        if ( $shop_category_slug ) {
            $term = get_term_by('slug', $shop_category_slug, 'product_cat');
            if ( $term ) {
                // Set filter_terms to the term_id for filtering
                $this->options['filter_terms'] = $term->term_id;
            }
        }

        $this->current_tax = get_queried_object()->taxonomy;
        $this->current_term_id = get_queried_object()->term_id;
        $this->current_term_name = get_queried_object()->name;

        $this->init();
    
    }
    
    private function init() {
        
        $settings = array();
        $settings['post_types'] = "product";
        $settings['post_status'] = "";
        // Use filter_terms if set (from clean URL)
        $settings['terms'] = isset($this->options['filter_terms']) ? $this->options['filter_terms'] : "";
        $settings['products_variations'] = false;
        $settings['metas'] = $this->options['filter_metas'];
        $settings['batch'] = 1000;
        $settings['loop'] = 1;
      
        
        // if( $current_term_id ) $settings['terms'] = $current_term_id;

        $this->data = $this->get_items( $settings );

        add_action( 'wp_footer', array( $this, 'inline_data' ) );
        
    }

    public function inline_data() {
        echo '<script type="text/javascript"> var sg_block_product_filter_data = "' . base64_encode( gzdeflate( json_encode( $this->data['items'] ), 9 ) ) . '"; </script>';
    }

    private function get_items( $settings ) {

        if ( ! $settings['post_types'] ) return false;
        if ( ! $settings['post_status'] ) $settings['post_status'] = 'publish';
        if ( ! $settings['batch'] ) $settings['batch'] = 10;
        if ( ! $settings['loop'] ) $settings['loop'] = 1;
        
        $items = array();
        $filters = array();
        
        $count = 0;
        $items_count = 0;
        $variations_count = 0;
        
        $arg =array(
            'post_type' => explode(',', $settings['post_types'] ),
            'post_status' => $settings['post_status'],
            'posts_per_page' => $settings['batch'],
            'paged' => $settings['loop'],
            'orderby' => 'menu_order',
            'order' => 'ASC',
        );
        
        if ( $settings['terms'] ) {
        
            $arg['tax_query'] = array(
        
            'relation' => 'AND',
        
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => explode( ',', $settings['terms'] ),
                'include_children' => false,
                'operator' => 'IN',
            ),
        
            );
        
        }
        
        $loop = new WP_Query( $arg );
        
        //loop items
        if ( $loop->have_posts() ) {
            while ( $loop->have_posts() ) : $loop->the_post();
        
            $item_id = get_the_ID();
        
            if ( $settings['products_variations'] ) $variations = $this->get_variations( $item_id, -1 );
        
            //loop variation
            if ( isset( $variations ) && $variations ) {
        
                foreach ( $variations as $variation_key => $variation ) {
        
                //loop attribute
                $variation_slug = "";
                $variation_rewrite = "";
                $variation_data = array();
                $variation_title = "";
                $first_loop = true;
        
                if ( $variation['attributes'] ) {
                    foreach ( $variation['attributes'] as $attribute => $term ) {
        
                        $attribute_data = wc_attribute_label(  str_replace( 'attribute_', '', $attribute ) );
                        $term_data = get_term_by( 'slug', $term, str_replace( 'attribute_', '', $attribute ) );
                        
                        if ( $term_data ) {

                            $map_variation = array(
                                '{{variation_label}}' => $attribute_data,
                                '{{variation_value}}' => $term_data->name,
                            );
                            $variation_title .= str_replace( array_keys($map_variation), array_values($map_variation), '{{variation_value}}' );
                
                            

                            if ( $first_loop ) {
                
                                $variation_slug .= "?" . str_replace( 'attribute_', '', $attribute ) . "=" . $term;
                                $variation_rewrite = 'xxx' . "/" . $term;
                
                                $first_loop = false;
                
                            } else {
                
                                $variation_slug .= "&" . str_replace( 'attribute_', '', $attribute ) . "=" . $term;
                                $variation_rewrite .= "+" . $term;
                
                            }

                        }
        
                    }
                }
        
                $item_data = array( "id" => $item_id . '-' . $variation['variation_id'] );
        
                $metas = explode( ',', $settings['metas'] );
        
                if ( $metas ){
                    foreach ($metas as $metas_key => $meta ) {
        
                    switch ( $meta ) {
        
                        //default
                        case 'wp_title':
                            $item_data[$meta] = get_the_title();
                            // $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wp_link':
                            // $item_data[$meta] = get_the_permalink() . $variation_rewrite;
                            $item_data[$meta] = get_the_permalink() . $variation_slug;
                            // $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wp_content':
                            $item_data[$meta] = get_the_content();
                            // $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wp_excerpt':
                            $item_data[$meta] = get_the_excerpt();
                            // $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wp_date':
                            $item_data[$meta] = get_the_date();
                            $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wp_author':
                            $item_data[$meta] = get_the_author();
                            $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wp_avatar':
                            $item_data[$meta] = get_avatar_url( get_the_author(), 96 );
                            $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wp_image':
                            $item_data[$meta] = wp_get_attachment_image_src( get_post_thumbnail_id( $variation['variation_id'] ), 'medium' );
                            if ( $item_data[$meta] ) { $item_data[$meta] = $item_data[$meta][0]; } else { $item_data[$meta] = ''; }
                            $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wp_image_retina':
                            $item_data[$meta] = wp_get_attachment_image_src( get_post_thumbnail_id( $variation['variation_id'] ), 'large' );
                            if ( $item_data[$meta] ) { $item_data[$meta] = $item_data[$meta][0]; } else { $item_data[$meta] = ''; }
                            $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wp_image_full':
                            $item_data[$meta] = wp_get_attachment_image_src( get_post_thumbnail_id( $variation['variation_id'] ), 'full' );
                            if ( $item_data[$meta] ) { $item_data[$meta] = $item_data[$meta][0]; } else { $item_data[$meta] = ''; }
                            $filters[$meta][] = $item_data[$meta];
                        break;
        
                        //woocommerce
                        case 'wc_price':
                            if ( $variation['price'] !== $variation['price_regular'] ) {
                                $price = '<del>' . wc_price( $variation['price_regular'] ) . '</del><ins>' . wc_price( $variation['price'] ) . '</ins>';
                            } else {
                                $price =  wc_price( $variation['price'] );
                            }
                            $item_data[$meta] = $price;
                            $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wc_price_regular':
                            $item_data[$meta] = $variation['price_regular'];
                            $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wc_currency':
                            $item_data[$meta] = get_woocommerce_currency_symbol();
                            $filters[$meta][] = $item_data[$meta];
                        break;
                        case 'wc_product_cat':
                            $the_product = wc_get_product( $item_id );
                            if ( $the_product ) {
                                $item_data[$meta] = wp_get_post_terms( $item_id, 'product_cat', array( 'fields' => 'names' ) );
                            } else {
                                $item_data[$meta] = "";
                            }
                            if ( $item_data[$meta] ) {
                                foreach ( $item_data[$meta] as $key => $value ) {
                                $filters[$meta][] = $value;
                                }
                            }
                        break;

                        case 'wc_product_cat_single':
                            $tsf = function_exists( 'the_seo_framework' ) ? the_seo_framework() : null;
                            $primary_category = "";
                            if ( $tsf ) {
                                $primary_term = $tsf->get_primary_term( $item_id, 'product_cat' );
                                if ( $primary_term ) $primary_category = $primary_term->name;
                            } else {
                                $terms = get_the_terms ( $item_id, 'product_cat' );
                                if ( isset( $terms[0]->term_id ) ) $primary_category = $terms[0]->name;
                            }
                            $item_data[$meta] = $primary_category;
                            $filters[$meta][] = $item_data[$meta];
                        break;
        
                        //custom
                        default:
        
                            if ( substr( $meta, 0, 4 ) === "tax_" ) {
            
                                $meta_id = str_replace('tax_', '', $meta );
                                
                                if ( isset( $variation['attributes']['attribute_' . $meta_id ] ) ) {
                                    $attr_id = $variation['attributes']['attribute_' . $meta_id ];
                                } else {
                                    $attr_id = null;
                                }
                                
                                $term_data = get_term_by( 'slug', $attr_id, str_replace( 'attribute_', '', $attribute ) );

                                if ( $term_data && isset( $attr_id ) ) {
                                    
                                    //is product_attribute
                                    $item_data[$meta][] = $term_data->name;
                
                                    $tax_data = wp_get_post_terms( $item_id, $meta_id, array( 'fields' => 'names' ) );
                
                                    if ( $tax_data ) {
                                        foreach ( $tax_data as $key => $value ) {
                                            $filters[$meta][] = $value;
                                        }
                                    }
                                
                                } else if ( taxonomy_exists( $meta_id ) ) {
            
                                    //is taxonomy
                                    $item_data[$meta] = wp_get_post_terms( $item_id, $meta_id, array( 'fields' => 'names' ) );
            
                                    if ( $item_data[$meta] ) {
                                        foreach ( $item_data[$meta] as $key => $value ) {
                                            $filters[$meta][] = $value;
                                        }
                                    }
            
                                }
            
                                if ( ! $item_data[$meta] ) $item_data[$meta] = ['all'];
            
                            } else {
            
                                //is meta
                                $meta_value = get_post_meta( $item_id, $meta, true );
                                if ( $meta_value ) {
                                $item_data[$meta] = $meta_value;
                                } else {
                                $item_data[$meta] = "";
                                }
                                $filters[$meta][] = $item_data[$meta];
            
                            }
            
                            if ( ! $item_data[$meta] ) $item_data[$meta] = 'all';
        
                        break;
        
                    }
        
                    }
                }
        
                $items[] = $item_data;
        
                $variations_count++;
        
                }
        
            } else {
        
                $item_data = array( "id" => $item_id );
        
                $metas = explode( ',', $settings['metas'] );
        
                if ( $metas ){
                foreach ($metas as $metas_key => $meta ) {
        
                    switch ( $meta ) {
        
                    //default
                    case 'wp_title':
                        $item_data[$meta] = get_the_title();
                        // $filters[$meta][] = $item_data[$meta];
                    break;
                    case 'wp_link':
                        $item_data[$meta] = get_the_permalink();
                        // $filters[$meta][] = $item_data[$meta];
                    break;
                    case 'wp_content':
                        $item_data[$meta] = get_the_content();
                        // $filters[$meta][] = $item_data[$meta];
                    break;
                    case 'wp_excerpt':
                        $item_data[$meta] = get_the_excerpt();
                        // $filters[$meta][] = $item_data[$meta];
                    break;
                    case 'wp_date':
                        $item_data[$meta] = get_the_date();
                        $filters[$meta][] = $item_data[$meta];
                    break;
                    case 'wp_author':
                        $item_data[$meta] = get_the_author();
                        $filters[$meta][] = $item_data[$meta];
                    break;
                    case 'wp_avatar':
                        $item_data[$meta] = get_avatar_url( get_the_author(), 96 );
                        $filters[$meta][] = $item_data[$meta];
                    break;
                    case 'wp_image':
                        $item_data[$meta] = wp_get_attachment_image_src( get_post_thumbnail_id( $item_id ), 'medium' );
                        if ( $item_data[$meta] ) { $item_data[$meta] = $item_data[$meta][0]; } else { $item_data[$meta] = ''; }
                        $filters[$meta][] = $item_data[$meta];
                    break;
                    case 'wp_image_retina':
                        $item_data[$meta] = wp_get_attachment_image_src( get_post_thumbnail_id( $item_id ), 'large' );
                        if ( $item_data[$meta] ) { $item_data[$meta] = $item_data[$meta][0]; } else { $item_data[$meta] = ''; }
                        $filters[$meta][] = $item_data[$meta];
                    break;
                    case 'wp_image_full':
                        $item_data[$meta] = wp_get_attachment_image_src( get_post_thumbnail_id( $item_id ), 'full' );
                        if ( $item_data[$meta] ) { $item_data[$meta] = $item_data[$meta][0]; } else { $item_data[$meta] = ''; }
                        $filters[$meta][] = $item_data[$meta];
                        break;
        
                    //woocommerce
                    case 'wc_price_sale':
                        $the_product = wc_get_product( $item_id );
                        if ( $the_product && $the_product->get_sale_price() ) {
                            $item_data[$meta] = $the_product->get_sale_price();
                        } else if ( $the_product && $the_product->get_regular_price() ) {
                            $item_data[$meta] = $the_product->get_regular_price();
                        } else {
                            $item_data[$meta] = "";
                        }
                        $filters[$meta][] = $item_data[$meta];
                    break;

                    case 'wc_price_regular':
                        $the_product = wc_get_product( $item_id );
                        if ( $the_product ) {
                            $item_data[$meta] = $the_product->get_regular_price();
                        } else {
                            $item_data[$meta] = "";
                        }
                        $filters[$meta][] = $item_data[$meta];
                    break;

                    case 'wc_price':
                        $the_product = wc_get_product( $item_id );
                        // if ( $the_product->is_type( 'variable' ) ) {
                        //     $item_data[$meta] = linotrad('from') . ' ' . wc_price( $the_product->get_price() );
                        // } else {
                        $item_data[$meta] = $the_product->get_price_html();
                        // }
                        $filters[$meta][] = $item_data[$meta];
                        
                        // Add sort_price for clean numerical sorting
                        $raw_price = (float) $the_product->get_price();
                        $item_data['sort_price'] = $raw_price;
                    break;

                    case 'wc_currency':
                        $item_data[$meta] = get_woocommerce_currency_symbol();
                        $filters[$meta][] = $item_data[$meta];
                    break;
                    
                    case 'wc_product_cat':
                        $the_product = wc_get_product( $item_id );
                        if ( $the_product ) {
                            $item_data[$meta] = wp_get_post_terms( $item_id, 'product_cat', array( 'fields' => 'names' ) );
                        } else {
                            $item_data[$meta] = "";
                        }
                        if ( $item_data[$meta] ) {
                            foreach ( $item_data[$meta] as $key => $value ) {
                                $filters[$meta][] = $value;
                            }
                        }
                    break;

                    case 'wc_product_cat_single':
                        $tsf = function_exists( 'the_seo_framework' ) ? the_seo_framework() : null;
                        $primary_category = "";
                        if ( $tsf ) {
                            $primary_term = $tsf->get_primary_term( $item_id, 'product_cat' );
                            if ( $primary_term ) $primary_category = $primary_term->name;
                        } else {
                            $terms = get_the_terms ( $item_id, 'product_cat' );
                            if ( isset( $terms[0]->term_id ) ) $primary_category = $terms[0]->name;
                        }
                        $item_data[$meta] = $primary_category;
                        $filters[$meta][] = $item_data[$meta];
                    break;
        
                    //custom
                    default:
        
                        if ( substr( $meta, 0, 4 ) === "tax_" ) {
                            
                            if ( substr( $meta, 4, 6 ) === "color_" ) {
                                
                                $meta_id = str_replace('tax_color_', '', $meta );
                
                                if ( taxonomy_exists( $meta_id ) ) {
            
                                    //is taxonomy
                                    $item_terms = wp_get_post_terms( $item_id, $meta_id );
                                    
                                    $colors = '';

                                    if ( $item_terms ) {
                                        foreach ( $item_terms as $key => $value ) {

                                            $color = get_term_meta( $value->term_id, 'color', true );

                                            if ( $color ) $colors .= '<span class="tax-item" style="background:' . $color . '"></span>';

                                        }
                                    }

                                    $item_data[$meta] = '<div class="tax-list tax-color-' . $meta_id . '">' . $colors . '</div>';
                
                                }
                
                                if ( ! isset( $item_data[$meta] ) || ! $item_data[$meta] ) $item_data[$meta] = ['all'];
                
                            } else if ( substr( $meta, 4, 6 ) === "label_" ) {
                                
                                $meta_id = str_replace('tax_label_', '', $meta );
                
                                if ( taxonomy_exists( $meta_id ) ) {
            
                                    //is taxonomy
                                    $item_terms = wp_get_post_terms( $item_id, $meta_id );
                                    
                                    $labels = '';

                                    if ( $item_terms ) {
                                        foreach ( $item_terms as $key => $value ) {

                                            $label = get_term_meta( $value->term_id, 'label', true );
                                            
                                            if ( $label ) {
                                                $labels .= '<span class="tax-item">' . $label . '</span>';
                                            } else {
                                                $labels .= '<span class="tax-item">' . $value->name . '</span>';
                                            }

                                        }
                                    }

                                    $item_data[$meta] = '<div class="tax-list tax-label-' . $meta_id . '">' . $labels . '</div>';
                
                                }
                
                                if ( ! isset( $item_data[$meta] ) || ! $item_data[$meta] ) $item_data[$meta] = ['all'];
                
                            } else {

                                $meta_id = str_replace('tax_', '', $meta );
        
                                    if ( taxonomy_exists( $meta_id ) ) {
                    
                                        //is taxonomy
                                        $item_data[$meta] = wp_get_post_terms( $item_id, $meta_id, array( 'fields' => 'names' ) );
                    
                                        if ( $item_data[$meta] ) {
                                            foreach ( $item_data[$meta] as $key => $value ) {
                                                $filters[$meta][] = htmlentities( $value );
                                            }
                                        }
                    
                                    }
                    
                                    if ( ! isset( $item_data[$meta] ) || ! $item_data[$meta] ) $item_data[$meta] = ['all'];
                    
                            }

                        } else {
        
                            //is meta
                            $meta_value = get_post_meta( $item_id, $meta, true );
                            if ( $meta_value ) {
                                $item_data[$meta] = $meta_value;
                            } else {
                                $item_data[$meta] = "";
                            }
                            $filters[$meta][] = $item_data[$meta];
            
                        }
        
                        if ( ! $item_data[$meta] ) $item_data[$meta] = 'all';
        
                    break;
        
                    }
                
                }
                }
        
                $items[] = $item_data;
        
                $items_count++;
        
            }
        
            $count++;
        
            endwhile;
            
            //format filters array
            foreach( $filters as $filter_key => $filter ) {
                $filters[ $filter_key ] = array_unique( $filter );
            }

            //output
            $output = array(
            "items" => $items,
            "filters" => $filters,
            "items_count" => $items_count,
            "variations_count" => $variations_count,
            "count" => $count,
            );
        
        } else {
        
            $output = false;
        
        }
        
        $output["total"] = $loop->found_posts;
        
        wp_reset_postdata();
        
        return $output;
        
        }
        
        /**
         *
        * get_variations
        *
        * @desc
        *
        */
        public function get_variations( $product_id, $num ) {
        
        global $post;
        
        $backup = $post;
        
        $variations = array();
        
        if ( $product_id && $num ) {
        
            $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => $num,
            'post_parent' => $product_id,
            // 'meta_key' => 'total_sales',
            // 'orderby' => 'meta_value_num',
            // 'order' => 'ASC',
            );
        
            $loop = new WP_Query( $args );
        
            if ( $loop->have_posts() ) {
        
            while ( $loop->have_posts() ) : $loop->the_post();
        
                $variation_id = get_the_ID();
        
                $variation = new WC_Product_Variation( $variation_id );
        
                $total_sales = (int) get_post_meta( $variation_id, 'total_sales', true );
                if ( ! $total_sales ) $total_sales = 0;
        
                $variations[ $variation_id ]['title'] = get_the_title();
                $variations[ $variation_id ]['content'] = get_the_content();
                $variations[ $variation_id ]['permalink'] = get_the_permalink();
                $variations[ $variation_id ]['variation_id'] = $variation_id;
                $variations[ $variation_id ]['attributes'] = $variation->get_variation_attributes();
                $variations[ $variation_id ]['price_regular'] = $variation->get_regular_price();
                if ( $variation->get_sale_price() ) {
                    $variations[ $variation_id ]['price'] = $variation->get_sale_price();
                } else {
                    $variations[ $variation_id ]['price'] = $variations[ $variation_id ]['price_regular'];
                }
                $variations[ $variation_id ]['total_sales'] = $total_sales;
                
        
            endwhile;
        
            }
        
            wp_reset_postdata();
        
        }
        
        $post = $backup;
        
        return $variations;
        
    }

    public function start() {
    
        return '<div id="sg_block_product_filter-' . $this->options['id'] . '" class="sg_block_product_filter-instance" data-filter-id="' . $this->options['id'] . '">';
    
    }

    public function get_filter( $id, $field = 'checkbox', $default = "all" ) {

        $template = '';
        
        if ( $id ) {

            switch ( $field ) {
        
                case "select":
            
                    $template .= '<div class="sg_block_product_filter-filter sg_block_product_filter-filter-select" id="' . $id . '_filter_select">';
            
                        $template .= '<select data-field-type="select" style="width:200px;background-color:#88C540" id="' . $id . '_filter" class="sg_block_product_filter-field filter-control-' . $this->options['id'] . '" data-field="' . $id . '">';
                            
                            $template .= '<option value="all">' . $default . '</option>';
                
                            if ( isset( $this->data['filters'][$id] ) && $this->data['filters'][$id] ) {
                                foreach ( $this->data['filters'][$id] as $val ) {
                                    // Count how many products have this filter value
                                    $count = 0;
                                    if (!empty($this->data['items'])) {
                                        foreach ($this->data['items'] as $item) {
                                            if (isset($item[$id]) && $item[$id] == $val) {
                                                $count++;
                                            }
                                        }
                                    }
                                    
                                    $template .= '<option value="' . $val . '">' . $val . ' (' . $count . ')</option>';
                                }
                            }
                
                        $template .= '</select>';
                    
                    $template .= '</div>';
            
                break;
                
                case "radio":
            
                    // Special handling for sort_by filter
                    if ($id === 'sort_by') {
                        $template .= '<div class="sg_block_product_filter-filter sg_block_product_filter-filter-radio" id="' . $id . '_filter_radio">';
                        
                        $template .= '<div class="sg_block_product_filter-filter-selected">' . $default . '</div>';
                        
                        $template .= '<div class="sg_block_product_filter-filter-dropdown">';
                        
                        // Add sort options manually instead of from filters data - without data-field attribute
                        // Make 'relevance' checked by default
                        $template .= '<label class="sg_block_product_filter-filter-item"><input data-field-type="radio" id="' . $id . '_filter" class="sg_block_product_filter-field filter-control-' . $this->options['id'] . '" type="radio" name="' . $id . '" value="relevance" checked><span class="checkmark"></span><span class="label">' . linotrad('Default Order') . '</span></label>';
                        $template .= '<label class="sg_block_product_filter-filter-item"><input data-field-type="radio" id="' . $id . '_filter" class="sg_block_product_filter-field filter-control-' . $this->options['id'] . '" type="radio" name="' . $id . '" value="price_low_high"><span class="checkmark"></span><span class="label">' . linotrad('Price: Low to High') . '</span></label>';
                        $template .= '<label class="sg_block_product_filter-filter-item"><input data-field-type="radio" id="' . $id . '_filter" class="sg_block_product_filter-field filter-control-' . $this->options['id'] . '" type="radio" name="' . $id . '" value="price_high_low"><span class="checkmark"></span><span class="label">' . linotrad('Price: High to Low') . '</span></label>';
                        
                        $template .= '</div>';
                        $template .= '</div>';
                        return $template;
                    }

                    $template .= '<div class="sg_block_product_filter-filter sg_block_product_filter-filter-radio" id="' . $id . '_filter_radio">';
                    
                    $template .= '<div class="sg_block_product_filter-filter-selected">' . $default . '</div>';
                    
                    $template .= '<div class="sg_block_product_filter-filter-dropdown">';
                    
                    if ( isset( $this->data['filters'][$id] ) && $this->data['filters'][$id] ) {
                        foreach ( $this->data['filters'][$id] as $key => $value ) {
                            // Count how many products have this value
                            $count = 0;
                            foreach ( $this->data['items'] as $item ) {
                                if ( isset($item[$id]) ) {
                                    // Handle both array and string values
                                    if ( is_array($item[$id]) && in_array($value, $item[$id]) ) {
                                        $count++;
                                    } elseif ( $item[$id] == $value ) {
                                        $count++;
                                    }
                                }
                            }
                            
                            $init = '';
                            if ( $id == 'wc_' . $this->current_tax && $this->current_term_name == $value ) {
                                $init = ' init';
                            }
                            
                            // Include the count in the label
                            $display_count = ' <span class="count">' . $count . '</span>';
                            $template .= '<label class="sg_block_product_filter-filter-item"><input data-field-type="radio" id="' . $id . '_filter" class="sg_block_product_filter-field filter-control-' . $this->options['id'] . '' . $init . '" data-field="' . $id . '" type="radio" name="' . $id . '" value="' . $value . '"><span class="checkmark"></span><span class="label">' . $value . $display_count . '</span></label>';
                        }
                    }
                    
                    $template .= '<label class="sg_block_product_filter-filter-item"><input data-field-type="radio" id="' . $id . '_filter" class="sg_block_product_filter-field filter-control-' . $this->options['id'] . '" data-field="' . $id . '" type="radio" name="' . $id . '" value="all"><span class="checkmark"></span><span class="label">All</span></label>';
                
                    $template .= '</div>';
            
                break;
                
                case "checkbox":
            
                    $template .= '<div class="sg_block_product_filter-filter sg_block_product_filter-filter-checkbox" id="' . $id . '_filter_checkbox">';
                        
                        $template .= '<div class="sg_block_product_filter-filter-selected">' . $default . '</div>';
                
                        $template .= '<div class="sg_block_product_filter-filter-dropdown">';
                            
                            if ( isset( $this->data['filters'][$id] ) && $this->data['filters'][$id] ) {
                                foreach ( $this->data['filters'][$id] as $key => $value ) {
                                    // Count how many products have this value
                                    $count = 0;
                                    foreach ( $this->data['items'] as $item ) {
                                        if ( isset($item[$id]) ) {
                                            // Handle both array and string values
                                            if ( is_array($item[$id]) && in_array($value, $item[$id]) ) {
                                                $count++;
                                            } elseif ( $item[$id] == $value ) {
                                                $count++;
                                            }
                                        }
                                    }
                                    
                                    // Include the count in the label
                                    $display_count = ' <span class="count">' . $count . '</span>';
                                    $template .= '<label class="sg_block_product_filter-filter-item"><input data-field-type="checkbox" id="' . $id . '_filter" class="sg_block_product_filter-field filter-control-' . $this->options['id'] . '" data-field="' . $id . '" type="checkbox" name="' . $id . '" value="' . $value . '"><span class="checkmark"></span><span class="label">' . $value . $display_count . '</span></label>';
                                }
                            }
                            
                        $template .= '</div>';
            
                    $template .= '</div>';
            
                break;

                case "tree":

                    $type = 'radio';

                    $template .= '<div class="sg_block_product_filter-filter sg_block_product_filter-filter-' . $type . '" id="' . $id . '_filter_' . $type . '">';

                        $template .= "<ul>";

                        // 'All' link
                        $shop_url = get_permalink( wc_get_page_id( 'shop' ) );
                        $is_all_active = empty(get_query_var('shop_category')) ? ' active' : '';
                        $template .= '<li><a href="' . esc_url( $shop_url ) . '" class="sg_block_product_filter-filter-link sg-block-product-filter-link-all' . $is_all_active . '">' . linotrad('All') . '</a></li>';
                        $current_slug = get_query_var('shop_category');
                        $args = array(
                            'taxonomy'      => 'product_cat',
                            'parent'        => 0,
                            'hide_empty'    => true,
                            'orderby'       => 'menu',
                            'order'         => 'ASC',
                            'hierarchical'  => 1,
                            'pad_counts'    => 0
                        );
                        $categories = get_categories( $args );
                        foreach ( $categories as $category ){
                            $template .= '<li>';
                            // Make main category clickable with link instead of just a span
                            $category_url = trailingslashit( $shop_url ) . $category->slug;
                            $is_category_active = ($current_slug === $category->slug) ? ' active' : '';
                            $template .= '<a href="' . esc_url( $category_url ) . '" class="sg_block_product_filter-filter-link' . $is_category_active . '">' . esc_html( $category->name ) . '</a>';
                            // Subcategories
                            $sub_args = array(
                                'taxonomy'      => 'product_cat',
                                'parent'        => $category->term_id,
                                'orderby'       => 'menu',
                                'hide_empty'    => true,
                                'order'         => 'ASC',
                                'hierarchical'  => 1,
                                'pad_counts'    => 0
                            );
                            $sub_categories = get_categories( $sub_args );
                            if (sizeof($sub_categories) > 0) {
                                $template .= '<ul>';
                                foreach ( $sub_categories as $sub_category ){
                                    $sub_category_url = trailingslashit( $shop_url ) . $sub_category->slug;
                                    $is_sub_active = ($current_slug === $sub_category->slug) ? ' active' : '';
                                    $template .= '<li><a href="' . esc_url( $sub_category_url ) . '" class="sg_block_product_filter-filter-link' . $is_sub_active . '">' . esc_html( $sub_category->name ) . '</a></li>';
                                }
                                $template .= '</ul>';
                            }
                            $template .= '</li>';
                        }
                        $template .= '</ul>';
                    $template .= '</div>';
                break;

            }

        }
      
        return $template;
      
    }

    public function get_loop( $type = null ) {
    
        switch ( $type ) {

            case 'start':
                return'<ul id="filter-items-' . $this->options['id'] . '"></ul><script id="filter-item-template-' . $this->options['id'] . '" type="text/html">';
            break;

            case 'end':
                return '</script>';
            break;

        }
        
    }

    public function get_item( $id = null ) {
    
        if ( in_array( $id, explode( ',', $this->options['filter_metas'] ) ) ) {

            return '<%= ' . $id . ' %>';

        }
        
    }

    public function get( $type = null, $id = null ) {
    
        switch ( $type ) {

            case 'search':
                return '<div class="searchfield"><div class="search-icon"></div><input id="filter-search-' . $this->options['id'] . '" type="text" placeholder="' . linotrad('Search') . '" autocomplete="off" /><div class="search-clear"></div></div>';
            break;

            case 'total':
                return '<span id="filter-total-' . $this->options['id'] . '">0</span>';
            break;

            case 'pagination':
                return '<div class="pagination-container" id="filter-pagination-' . $this->options['id'] . '"></div>';
            break;

            case 'per_page':
                return '<div class="per-page-container" id="filter-perpage-' . $this->options['id'] . '" ></div>';
            break;


        }
        
    }

    public function end() {
    
        return '<textarea style="display:none" class="sg_block_product_filter-options">' . json_encode( $this->options['filter_options'] ) . '</textarea></div>';
  
    }

}

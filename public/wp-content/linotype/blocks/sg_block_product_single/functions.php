<?php 

//overwrite woocommerce template parts
block( 'wc_get_template', array(
    'single-product/add-to-cart/variation-add-to-cart-button.php' => dirname( __FILE__ ) . '/single-product/add-to-cart/variation-add-to-cart-button.php',
    'single-product/add-to-cart/simple.php' => dirname( __FILE__ ) . '/single-product/add-to-cart/simple.php',
    'single-product/add-to-cart/variation.php' => dirname( __FILE__ ) . '/single-product/add-to-cart/variation.php',
));

//remove action used by direct function
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

//enable defaut woocommerce lightbox
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

//display only the lowest price for variable product references
add_filter('woocommerce_variable_price_html', function( $price, $product ) { return linotrad('From') . ' ' . wc_price($product->get_price()); }, 10, 2); 

//display only the lowest price for variable product references
add_filter('woocommerce_gallery_full_size', function( $size ) { return '2048x2048'; } ); 

//force variation price on same price
add_filter( 'woocommerce_show_variation_price', function( $show, $parent, $variation ) { return true; }, 99, 3);

//remove (via woocommerce) on apple and chrome pay api
add_filter( 'wc_stripe_payment_request_total_label_suffix', '__return_empty_string' );

//Removing the short description meta box
add_action( 'add_meta_boxes', function() { remove_meta_box( 'postexcerpt', 'product', 'normal' ); }, 50 );

//add ApplePay GooglePay text info
add_action( 'woocommerce_after_add_to_cart_quantity', function() {

    global $post;

    $gateways = WC()->payment_gateways->get_available_payment_gateways();

    if ( ! isset( $gateways['stripe'] ) ) {
        return;
    }

    if ( ! is_cart() && ! is_checkout() && ! is_product() && ! isset( $_GET['pay_for_order'] ) ) {
        return;
    }

    if ( is_checkout() && ! apply_filters( 'wc_stripe_show_payment_request_on_checkout', false, $post ) ) {
        return;
    }

    echo '<p id="wc-stripe-payment-request-button-separator" style="margin-top:1.5em;text-align:center;display:none;">' . linotrad('En utilisant ce service, vous acceptez nos conditions générales de vente') . '</p>';
    
}, 1 );

add_filter( 'woocommerce_get_price_html', 'custom_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'custom_price_format', 10, 2 );
function custom_price_format( $price, $product ) {

    $regular_price = $product->is_type('variable') ? $product->get_variation_regular_price( 'min', true ) : $product->get_regular_price();
    $sale_price = $product->is_type('variable') ? $product->get_variation_sale_price( 'min', true ) : $product->get_sale_price();

    if ( $regular_price !== $sale_price && $product->is_on_sale()) {
       
        $percentage_txt = '';
        $percentage = round( ( $regular_price - $sale_price ) / $regular_price * 100 ).'%';
        $percentage_txt .= ' - ' . $percentage;

        $price = '<del>' . wc_price($regular_price) . '</del> <ins>' . wc_price($sale_price) . $percentage_txt . '</ins>';
    }
    return $price;
}
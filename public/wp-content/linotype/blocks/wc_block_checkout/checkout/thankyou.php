<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="block-heading checkout-heading">
  <ol class="list-unstyled checkout-steps">
      <li class="checkout-step"><span><?php echo linotrad('Cart'); ?></span></li>
      <li class="checkout-step"><span><?php echo linotrad('Checkout'); ?></span></li>
      <li class="checkout-step current"><span><?php echo linotrad('Receipt'); ?></span></li>
  </ol>
</div>

<div class="woocommerce-order col2-set">

    <?php
    if ( $order ) :

      do_action( 'woocommerce_before_thankyou', $order->get_id() );
      ?>

      <?php if ( $order->has_status( 'failed' ) ) : ?>

        <div class="col-1">

          <p class="checkout-fail-notice"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

          <div class="woocommerce-thankyou-actions">
            <a class="btn btn-block" href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
            <?php if ( is_user_logged_in() ) : ?>
              <a href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>" class="btn btn-block btn-block-hollow"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
            <?php endif; ?>
          </div>

        </div>

      <?php else : ?>

        <div class="col-1">

          <h2 class="checkout-lvl1-title"><?php echo linotrad('Thank you.'); ?><br/><?php echo linotrad('Your order has been received.'); ?></h2>

          <div class="woocommerce-thankyou-actions">
            <a class="btn btn-block" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
              <?php echo linotrad( 'Back to the shop' ); ?>
            </a>
            <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() ) { ?>
              <a href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>" class="btn btn-block btn-block-hollow">Mon compte</a>
            <?php } ?>
          </div>

        </div>

        <div class="col-2">

          <?php
          $invoice_link = '';
          if ( class_exists( 'WPO_WCPDF' ) ) {
            if ( is_user_logged_in() ) {
              $pdf_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf&template_type=invoice&order_ids=' . $order->get_id() . '&my-account'), 'generate_wpo_wcpdf' );
            } else {
              $pdf_url = admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf&template_type=invoice&order_ids=' . $order->get_id() . '&order_key=' . $order->get_order_key() );
            }
            $invoice_link = '<a href="' . esc_attr( $pdf_url ) . '" class="btn-unstyled btn-download-receipt" aria-label="' . linotrad( 'Download receipt' ) . '"></a>';
          }
          ?>

          <h3 class="checkout-lvl2-title"><?php echo linotrad( 'Details' ); ?> <?php echo $invoice_link; ?></h3>

          <table class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

            <tr class="woocommerce-order-overview__order order">
              <th><?php esc_html_e( 'Order number:', 'woocommerce' ); ?></th>
              <td><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>

            <tr class="woocommerce-order-overview__date date">
              <th><?php esc_html_e( 'Date:', 'woocommerce' ); ?></th>
              <td><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>

            <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
              <tr class="woocommerce-order-overview__email email">
                <th><?php esc_html_e( 'Email:', 'woocommerce' ); ?></th>
                <td><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
              </tr>
            <?php endif; ?>

            <tr class="woocommerce-order-overview__total total">
              <th><?php esc_html_e( 'Total:', 'woocommerce' ); ?></th>
              <td><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
            </tr>

            <?php if ( $order->get_payment_method_title() ) : ?>
              <tr class="woocommerce-order-overview__payment-method method">
                <th><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?></th>
                <td><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></td>
              </tr>
            <?php endif; ?>

          </table>

          <h3 class="checkout-lvl2-title"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h3>

          <p class="woocommerce-order-overview__billing-address billing-address"><?php echo $order->get_formatted_billing_address(); ?></p>

        </div>

      <?php endif; ?>

      <?php /*
      <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
      <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
      */ ?>

    <?php else : ?>

      <div class="col-1">

        <h2 class="checkout-lvl1-title"><?php echo linotrad('Thank you.'); ?><br/><?php echo linotrad('Your order has been received.'); ?></h2>

        <div class="btn-list woocommerce-thankyou-actions">
          <a class="btn btn-block" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
            <?php echo linotrad( 'Back to shop' ); ?>
          </a>
          <?php if ( is_user_logged_in() ) : ?>
            <a href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>" class="btn btn-block btn-block-hollow"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
          <?php endif; ?>
        </div>

      </div>

    <?php endif; ?>
  
  </div>

</div>

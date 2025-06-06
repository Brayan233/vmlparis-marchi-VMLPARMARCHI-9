<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="block-heading checkout-heading">
		<h2 class="checkout-lvl1-title"><?php echo linotrad('Checkout'); ?></h2>
		<ol class="list-unstyled checkout-steps">
			<li class="checkout-step"><a href="<?php echo wc_get_cart_url() ?>"><?php echo linotrad('Cart'); ?></a></li>
			<li class="checkout-step current"><span><?php echo linotrad('Checkout'); ?></span></li>
			<li class="checkout-step"><span><?php echo linotrad('Receipt'); ?></span></li>
		</ol>
</div>

<div class="before-checkout">

	<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

</div>

<?php // If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<div class="col2-set">

		<div class="col-1">

			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div id="customer_details">
					<div class="billing-details">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>

					<div class="shipping-details">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>
			
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			
			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

		</div>

		<div class="col-2">

			<h3 class="checkout-lvl2-title" id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>

			<div id="order_review" class="woocommerce-checkout-review-order">

				<?php do_action( 'woocommerce_checkout_order_review' ); ?>

			</div>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

		</div>

	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

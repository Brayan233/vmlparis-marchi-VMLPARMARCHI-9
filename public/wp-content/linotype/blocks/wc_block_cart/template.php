<?php block( 'header', $settings ); ?>

<div class="container">

	<div class="block-heading checkout-heading">
		<h2 class="checkout-lvl1-title"><?php echo linotrad('My cart'); ?></h2>
		<ol class="list-unstyled checkout-steps">
			<li class="checkout-step current"><span><?php echo linotrad('Cart'); ?></span></li>
			<li class="checkout-step"><span><?php echo linotrad('Checkout'); ?></span></li>
			<li class="checkout-step"><span><?php echo linotrad('Receipt'); ?></span></li>
		</ol>
	</div>

	<?php do_action( 'woocommerce_before_cart' ); ?>

	<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

		<?php block( 'content', $settings ); ?>

	</form>

	<?php
	/**
	 * Cart collaterals hook.
	 *
	 * @hooked woocommerce_cross_sell_display
	 * @hooked woocommerce_cart_totals - 10
	 */
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
	do_action( 'woocommerce_cart_collaterals' );
	?>

	<?php do_action( 'woocommerce_after_cart' ); ?>

</div>

<?php block( 'footer', $settings ); ?>

<?php

remove_action( 'woocommerce_account_navigation', 'woocommerce_account_navigation' );

block( 'wc_get_template', array(
    'myaccount/dashboard.php' => dirname( __FILE__ ) . '/myaccount/dashboard.php',
    'myaccount/form-edit-account.php' => dirname( __FILE__ ) . '/myaccount/form-edit-account.php',
    'myaccount/form-edit-address.php' => dirname( __FILE__ ) . '/myaccount/form-edit-address.php',
    'myaccount/form-login.php' => dirname( __FILE__ ) . '/myaccount/form-login.php',
    'myaccount/orders.php' => dirname( __FILE__ ) . '/myaccount/orders.php',
    'myaccount/my-address.php' => dirname( __FILE__ ) . '/myaccount/my-address.php',
	'order/order-details.php' => dirname( __FILE__ ) . '/order/order-details.php',
));

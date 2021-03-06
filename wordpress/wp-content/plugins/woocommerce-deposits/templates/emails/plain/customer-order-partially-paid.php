<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

echo $email_heading . "\n\n";

echo __( "Your order has been received and is now being processed. Your order details are shown below for your reference:", 'woocommerce-deposits' ) . "\n\n";

if ( $order->has_status( 'partially-paid' ) && get_option('wc_deposits_remaining_payable', 'yes') === 'yes' ) {
  echo sprintf( __( 'To pay the remaining balance, please visit this link: %s', 'woocommerce-deposits' ), $order->get_checkout_payment_url() ) . "\n\n";
}

echo "****************************************************\n\n";

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text );

echo sprintf( __( 'Order number: %s', 'woocommerce-deposits'), $order->get_order_number() ) . "\n";
echo sprintf( __( 'Order date: %s', 'woocommerce-deposits'), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ) . "\n";

do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text );

echo "\n" . $order->email_order_items_table( $order->is_download_permitted(), true, $order->has_status( 'processing' ), '', '', true );

echo "----------\n\n";

if ( $totals = $order->get_order_item_totals() ) {
  foreach ( $totals as $total ) {
    echo $total['label'] . "\t " . $total['value'] . "\n";
  }
}

echo "\n****************************************************\n\n";

do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text );

do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text );

echo "\n****************************************************\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );

<?php/** * Cart totals * * @version     2.3.6 */if ( ! defined( 'ABSPATH' ) ) {    exit;}$porto_woo_version = porto_get_woo_version_number();if (!porto_is_ajax()) : ?><div class="featured-box align-left">    <div class="box-content"><?php endif; ?>        <div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">            <?php do_action( 'woocommerce_before_cart_totals' ); ?>            <h2><?php _e( 'Cart Totals', 'porto' ); ?></h2>            <table class="shop_table responsive cart-total" cellspacing="0">                <tr class="cart-subtotal">                    <th><?php _e( 'Subtotal', 'porto' ); ?></th>                    <td><?php wc_cart_totals_subtotal_html(); ?></td>                </tr>                <?php                $codes = WC()->cart->get_coupons();                ?>                <?php foreach ( $codes as $code => $coupon ) : ?>                    <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">                        <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>                        <td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>                    </tr>                <?php endforeach; ?>                <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>                    <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>                    <?php wc_cart_totals_shipping_html(); ?>                    <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>                <?php elseif ( WC()->cart->needs_shipping() ) : ?>                    <tr class="shipping hidden">                        <th><?php _e( 'Coleta', 'porto' ); ?></th>                        <td><div style="text_align:left;"><?php                        //woocommerce_shipping_calculator();                        //echo do_shortcode('[shipping-calculator]');                        ?></td>                    </tr>                <?php endif; ?>                <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>                    <tr class="fee">                        <th><?php echo esc_html( $fee->name ); ?></th>                        <td><?php wc_cart_totals_fee_html( $fee ); ?></td>                    </tr>                <?php endforeach; ?>                <?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) :                    $taxable_address = WC()->customer->get_taxable_address();                    $estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()                        ? sprintf( ' <small>(' . __( 'estimated for %s', 'porto' ) . ')</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )                        : '';                    if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>                        <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>                            <tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">                                <th><?php echo esc_html( $tax->label ) . $estimated_text; ?></th>                                <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>                            </tr>                        <?php endforeach; ?>                    <?php else : ?>                        <tr class="tax-total">                            <th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>                            <td><?php wc_cart_totals_taxes_total_html(); ?></td>                        </tr>                    <?php endif; ?>                <?php endif; ?>                <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>                <tr class="order-total">                    <th><?php _e( 'Total', 'porto' ); ?></th>                    <td><?php wc_cart_totals_order_total_html(); ?></td>                </tr>                <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>            </table>            <?php if ( version_compare($porto_woo_version, '2.5', '<') && WC()->cart->get_cart_tax() ) : ?>                <p class="wc-cart-shipping-notice"><small><?php                        $estimated_text = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()                            ? sprintf( ' ' . __( ' (taxes estimated for %s)', 'porto' ), WC()->countries->estimated_for_prefix() . __( WC()->countries->countries[ WC()->countries->get_base_country() ], 'porto' ) )                            : '';                        printf( __( 'Note: Shipping and taxes are estimated%s and will be updated during checkout based on your billing and shipping information.', 'porto' ), $estimated_text );                        ?></small></p>            <?php endif; ?>            <div class="wc-proceed-to-checkout">                <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>            </div>            <?php do_action( 'woocommerce_after_cart_totals' ); ?>        </div><?php if (!porto_is_ajax()) : ?>    </div></div><?php endif; ?>
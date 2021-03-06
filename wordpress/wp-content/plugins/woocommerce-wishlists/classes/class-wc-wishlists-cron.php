<?php

add_action( 'wc_wishlists_cron', 'wc_wishlists_do_cron' );

function wc_wishlists_do_cron() {
	$instance = WC_Wishlists_Cron::instance();
	$instance->send_price_changes();
}

/**
 * @since 1.7.0
 * Class which will send notifications if an item on a wishlist has had a price reduction since it was added to the list.
 * Uses the email configured on the list as the destination. Checks if wishlist settings have Notification enabled and that the list has Notifications enabled.
 * Notifications are enabled by default for all lists.
 */
class WC_Wishlists_Cron {

	private static $instance;

	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new WC_Wishlists_Cron();
		}

		return self::$instance;
	}

	public static function register() {
		self::instance();
		self::$instance->register_jobs();
	}

	public function __construct() {

	}

	public function register_jobs() {
		$gmt       = microtime( true );
		$scheduled = wp_next_scheduled( 'wc_wishlists_cron' );
		$date      = date( 'F j, Y @ h:i A', $scheduled );

		if ( ! $scheduled ) {
			wp_schedule_event( time(), 'twicedaily', 'wc_wishlists_cron' );
		} else {

		}
	}

	public function send_price_changes( $force_resend = false ) {

		if ( WC_Wishlists_Settings::get_setting( 'wc_wishlist_notifications_enabled', 'disabled' ) != 'enabled' ) {
			return;
		}

		$args = array(
			'post_type'   => 'wishlist',
			'post_status' => 'publish',
			'nopaging'    => true
		);

		$posts = get_posts( $args );

		$receivers = array();

		$wishlists_message_histories = array();

		foreach ( $posts as $post ) {
			$post_id = $post->ID;

			$wishlist = new WC_Wishlists_Wishlist( $post_id );

			$notifications = get_post_meta( $wishlist->id, '_wishlist_owner_notifications', true );
			if ( empty( $notifications ) ) {
				$notifications = 'yes';
			}

			if ( $notifications == 'no' ) {
				continue;
			}

			$wishlist_items = WC_Wishlists_Wishlist_Item_Collection::get_items( $post_id );

			$wishlist_owner_email = get_post_meta( $post_id, '_wishlist_email', true );

			//Added validation 2.9.0
			$wishlist_owner_validation = get_post_meta( $post_id, '_wishlist_email_validated', true );
			$is_valid_email            = false;
			if ( $wishlist_owner_validation === '' ) {
				//If the validated email is exactly empty then this list was created prior to 2.9.0
				$is_valid_email = true;
			} elseif ( $wishlist_owner_validation == $wishlist_owner_email ) {
				$is_valid_email = true;
			}

			if ( ! $is_valid_email ) {
				continue;
			}

			$wishlist_message_history = get_post_meta( $post_id, '_wishlist_message_history', true );
			$wishlist_message_history = empty( $wishlist_message_history ) ? array() : $wishlist_message_history;

			if ( ! empty( $wishlist_owner_email ) ) {

				$wishlist_subscribers = array( $wishlist_owner_email );

				$changes = array();
				if ( $wishlist_items ) {

					//Inspect each item in the list for price changes. 
					foreach ( $wishlist_items as $item ) {
						$id = isset( $item['variation_id'] ) && ! empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];


						if ( isset( $item['variation_id'] ) ) {
							$product = wc_get_product( $item['variation_id'] );
						} else {
							$product = wc_get_product( $item['product_id'] );
						}

						if (empty($product) || !$product->exists()) {
							continue;
						}

						$price = wc_get_price_excluding_tax( $product );

						$wl_price = isset( $item['wl_price'] ) ? $item['wl_price'] : $price;

						if ( $wl_price && $wl_price > $price ) {

							//The price which was stored on the list is higher than the current product price. 

							$text = sprintf( __( '<a href="%s">%s</a> has been reduced in price! Was %s, now avaiable for %s ', 'wc_wishlist' ), get_permalink( $product->get_id() ), $product->get_title(), $wl_price, $price );
							$text = apply_filters( 'woocommerce_wishlist_price_change_message', $text, $id, $price, $wl_price );

							//Grab the wishlist array for this products changes. 
							if ( isset( $changes[ $id ] ) ) {
								$inspected_lists = isset( $changes[ $id ]['wishlists'] ) ? $changes[ $id ]['wishlists'] : array( $post_id );
							} else {
								$inspected_lists = array( $post_id );
							}

							$changes[ $id ] = array(
								'title'     => $product->get_title(),
								'old_price' => $wl_price,
								'new_price' => $price,
								'url'       => get_permalink( $product->get_id() ),
								'text'      => $text,
								'wishlists' => $inspected_lists
							);
						}
					}
				}

				if ( $changes && count( $changes ) ) {
					foreach ( $wishlist_subscribers as $receiver ) {
						if ( ! isset( $receivers[ $receiver ] ) ) {
							$receivers[ $receiver ] = array();
						}

						if ( ! isset( $wishlist_message_history[ $receiver ] ) ) {
							$wishlist_message_history[ $receiver ] = array();
						}

						foreach ( $changes as $id => $change ) {

							$send              = false;
							$notification_hash = md5( $change['old_price'] . $change['new_price'] );
							if ( isset( $wishlist_message_history[ $receiver ][ $id ] ) ) {
								if ( $wishlist_message_history[ $receiver ][ $id ] != $notification_hash ) {
									$send = true;
								} else {
									$send = false;  //Price change hash matches the last message we sent.
								}
							} else {
								$send = true;
							}

							if ( $send || $force_resend ) {
								$wishlist_message_history[ $receiver ][ $id ] = $notification_hash;
								$receivers[ $receiver ][ $id ]                = $change;
							}
						}
					}
				}
			}

			$wishlists_message_histories[ $post_id ] = $wishlist_message_history;
		}

		//At this point we have an array of receivers and the assoicated product price change descriptions. 
		if ( $receivers && count( $receivers ) ) {

			foreach ( $receivers as $receiver => $changes ) {
				if ( count( $changes ) ) {
					$html = '';
					ob_start();

					$email_heading = sprintf( __( '%s', 'wc_wishlist' ), get_option( 'blogname' ) );
					woocommerce_wishlists_get_template( 'price-change-email.php', array(
							'changes'       => $changes,
							'email_heading' => $email_heading
						)
					);
					$html = ob_get_clean();

					add_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
					add_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
					add_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );

					$to      = $receiver;
					$subject = apply_filters( 'wc_wishlist_price_change_email_subject', __( 'Price Change Notification', 'wc_wishlist' ) );
					$message = $html;

					$return = wp_mail( $to, $subject, $message );

					remove_filter( 'wp_mail_from', array( $this, 'get_from_address' ) );
					remove_filter( 'wp_mail_from_name', array( $this, 'get_from_name' ) );
					remove_filter( 'wp_mail_content_type', array( $this, 'get_content_type' ) );
				}
			}
		}

		//Finally update all the wishlists with the receivers => messages we sent. 
		foreach ( $wishlists_message_histories as $wishlist_id => $history ) {
			update_post_meta( $wishlist_id, '_wishlist_message_history', $history );
		}

		return true;
	}

	public function get_content_type( $content_type ) {
		return 'text/html';
	}

	/**
	 * Get from name for email.
	 *
	 * @return string
	 */
	public function get_from_name() {
		return wp_specialchars_decode( esc_html( get_option( 'woocommerce_email_from_name' ) ), ENT_QUOTES );
	}

	/**
	 * Get from email address.
	 *
	 * @return string
	 */
	public function get_from_address() {
		return sanitize_email( get_option( 'woocommerce_email_from_address' ) );
	}

}

if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	add_action( 'init', 'wc_wishlist_maybe_send_notifications' );

	function wc_wishlist_maybe_send_notifications() {
		if ( isset( $_GET['force-send-wl-notifications'] ) ) {
			wc_wishlists_do_cron();
		}
	}

}

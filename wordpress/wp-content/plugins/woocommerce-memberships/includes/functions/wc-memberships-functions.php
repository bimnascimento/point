<?php
/**
 * WooCommerce Memberships
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Memberships to newer
 * versions in the future. If you wish to customize WooCommerce Memberships for your
 * needs please refer to https://docs.woocommerce.com/document/woocommerce-memberships/ for more information.
 *
 * @package   WC-Memberships/Classes
 * @author    SkyVerge
 * @copyright Copyright (c) 2014-2017, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

// load helper functions
require_once( wc_memberships()->get_plugin_path() . '/includes/functions/wc-memberships-functions-dates.php' );
require_once( wc_memberships()->get_plugin_path() . '/includes/functions/wc-memberships-functions-misc.php' );
require_once( wc_memberships()->get_plugin_path() . '/includes/functions/wc-memberships-functions-orders.php' );
require_once( wc_memberships()->get_plugin_path() . '/includes/functions/wc-memberships-functions-membership-plans.php' );
require_once( wc_memberships()->get_plugin_path() . '/includes/functions/wc-memberships-functions-user-memberships.php' );

// load frontend-only functions (also while doing AJAX)
if ( ! is_admin() || is_ajax() ) {
	require_once( wc_memberships()->get_plugin_path() . '/includes/functions/wc-memberships-functions-template.php' );
}

// deprecated functions for BC compatibility
require_once( wc_memberships()->get_plugin_path() . '/includes/functions/wc-memberships-functions-deprecated.php' );

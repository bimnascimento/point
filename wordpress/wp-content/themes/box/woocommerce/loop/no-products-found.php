<?php
/**
 * Displayed when no products are found matching the current query
 *
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if(!is_search()){
?>

<script>jQuery(".loading-site").fadeOut(800);</script>
<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce;
if( version_compare( $woocommerce->version, '2.6.3', "<" ) ) {
	require_once('product-image20.php');
}
else {
	require_once('product-image263.php');
}

?>
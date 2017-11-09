
<?php 
if (is_user_logged_in()){
if ( empty( $subscription ) ) {
	global $wp;

	if ( ! isset( $wp->query_vars['view-subscription'] ) || 'shop_subscription' != get_post_type( absint( $wp->query_vars['view-subscription'] ) ) || ! current_user_can( 'view_order', absint( $wp->query_vars['view-subscription'] ) ) ) {
		echo '<div class="woocommerce-error">' . esc_html__( 'Invalid Subscription.', 'woocommerce-subscriptions' ) . ' <a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="wc-forward">'. esc_html__( 'My Account', 'woocommerce-subscriptions' ) .'</a>' . '</div>';
		return;
	}

	$subscriptions = wcs_get_subscription( $wp->query_vars['view-subscription'] );
}

$order=$subscriptions->get_items();
$arr2=array();
$arr3=array();
foreach ( $order as $o=>$items ) {
$post_parent=$items->get_product_id(); 
$res = get_post_meta($post_parent);
}	
$attributes=unserialize($res['_product_attributes'][0]);

if($attributes['type']['value']=="individual"){


		//echo "aqui1";
		include('deliveryIndividual.php');


}elseif($attributes['type']['value']=="corporate"){
		//echo "aqui2";
		include('deliveryCorporate.php');

}

}
?>
<div style="text-align:center;">
<a type="button" href="http://localhost/wordpress/?page_id=651" id="<?php echo 'hugSaveDelivery'.$o;?>" class="single_add_to_cart_button button alt" type="submit">Back</a>
</div>




<?php
if (is_user_logged_in()){
if ( empty( $subscription ) ) {
	global $wp;
global $wpdb;

	if ( ! isset( $wp->query_vars['view-subscription'] ) || 'shop_subscription' != get_post_type( absint( $wp->query_vars['view-subscription'] ) ) || ! current_user_can( 'view_order', absint( $wp->query_vars['view-subscription'] ) ) ) {
		echo '<div class="woocommerce-error">' . esc_html__( 'Invalid Subscription.', 'woocommerce-subscriptions' ) . ' <a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="wc-forward">'. esc_html__( 'My Account', 'woocommerce-subscriptions' ) .'</a>' . '</div>';
		return;
	}

	$subscriptions = wcs_get_subscription( $wp->query_vars['view-subscription'] );
}
$cu = wp_get_current_user();

?>
<b>Enter your address below to check our availability in your area</b>
<div id="locationField"><input id="autocomplete2" type="text" placeholder="Enter your address" /></div>
<table id="address" style="display:none">
<tbody>
<tr>
<td class="label">Street address</td>
<td class="slimField"><input id="street_number" class="field" disabled="disabled" type="text" /></td>
<td class="wideField" colspan="2"><input id="route" class="field" disabled="disabled" type="text" /></td>
</tr>
<tr>
<td class="label">City</td>

<td class="wideField" colspan="3"><input id="locality" class="field" disabled="disabled" type="text" /></td>
</tr>
<tr>
<td class="label">State</td>
<td class="slimField"><input id="administrative_area_level_1" class="field" disabled="disabled" type="text" /></td>
&nbsp;&nbsp;
<td class="label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Zip code</td>
<td class="wideField"><input id="postal_code" class="field" disabled="disabled" type="text" /></td>
</tr>
<tr>
<td class="label">Country</td>
<td class="wideField" colspan="3"><input id="country" class="field" disabled="disabled" type="text" /></td>
</tr>
</tbody>
</table>
<div style="text-align:center">
<div class="row">
<div class="col-lg-4 col-lg-offset-2">
<?php $link="http://localhost/wordpress/?page_id=374&view-subscription=".$subscriptions->id; ?>
<a id="back" href="<?php echo $link; ?>"  class="single_add_to_cart_button button alt" type="button" ">Back</a>
</div> 
<div class="col-lg-2 ">
 <input id="changeAddress" type="button" value="Check">
</div>
</div></div>
<br/><br/>
<div style="margin-top:30px"></div>
<b>Area of distribution</b>
<div id="map"></div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Notification</h4>
      </div>
      <div class="modal-body">
        <p>We have not covered your area yet. But we keep your address to study its inclusion in our distribution route.</p>
      </div>
      
    </div>

  </div>
</div>

<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Notification</h4>
      </div>
      <div class="modal-body">
        <p>Yei!!! We deliver to your area.</p>
      </div>
      
    </div>

  </div>
</div>
<input type="hidden" name="idUserss" id="idUserss"   value="<?php echo $cu->ID; ?>">
<input type="hidden" name="idSubscriptionss" id="idSubscriptionss" value="<?php echo $subscriptions->id; ?>">
<link href="/wordpress/wp-includes/css/bootstrap.css" rel="stylesheet">
<script src="/wordpress/wp-includes/js/bootstrap.js" type="text/javascript"></script>
<?php } ?>

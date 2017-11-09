<?php 
global $wp_session;
global $wpdb;

if (is_user_logged_in()){

$cu = wp_get_current_user();

//echo "mimimi".$wp_session->session_id;
$value = $wp_session['session_id'];
	$query="SELECT * FROM st_SearchedAddresses WHERE IDSession='".$value."' ";
	$result = $wpdb->get_results( $query );

	$query="SELECT * FROM st_AddressUser WHERE IDUser='".$cu->ID."' and IDSubscripcion='".$subscriptions->id."' ";
	$addressUser = $wpdb->get_results( $query );
	if(!empty($result) and empty($addressUser)){
$query="INSERT INTO  st_AddressUser (IDUser,IDSubscripcion,sAddress) VALUES ('".$cu->ID."','".$subscriptions->id."','".$result[0]->sShortAddress."')";
$insert = $wpdb->query($query);	
	}



if($subscriptions->status=="active"){

$query="SELECT * FROM wp_posts as a  WHERE a.post_type='product' and a.post_status='publish'   ";
$products = $wpdb->get_results( $query );

$query="SELECT * FROM wp_posts as a JOIN wp_postmeta as b ON a.ID=b.post_id WHERE a.post_type='product_variation' and a.post_status='publish' and b.meta_key='_price'   ";
$LISTproductsvariations = $wpdb->get_results( $query );

$query="SELECT * FROM wp_woocommerce_order_items as a join wp_woocommerce_order_itemmeta as b on a.order_item_id=b.order_item_id WHERE a.order_id=".$subscriptions->id." and b.meta_key='_product_id'   ";
$parent_id = $wpdb->get_results( $query );

$query="SELECT * FROM wp_woocommerce_order_items as a join wp_woocommerce_order_itemmeta as b on a.order_item_id=b.order_item_id WHERE a.order_id=".$subscriptions->id." and b.meta_key='_variation_id'   ";
$_variation_id = $wpdb->get_results( $query );

$parent=$parent_id[0]->meta_value;

$query="SELECT * FROM wp_posts WHERE post_parent =".$parent." and post_status='publish'  ";
$variations = $wpdb->get_results( $query );


$query="SELECT * FROM wp_woocommerce_order_items WHERE order_id=".$subscriptions->id." ";
$result = $wpdb->get_results( $query );

$arrDays=array(
"0"=> array(
         "name" => "Mon",
	 "value"=> 1
	),
"1"=> array(
         "name" => "Tue",
	 "value"=> 2
	),
"2"=> array(
         "name" => "Wed",
	 "value"=> 3
	),
"3"=> array(
         "name" => "Thu",
	 "value"=> 4
	),
"4"=> array(
         "name" => "Fri",
	 "value"=> 5
	),
);
//echo "<pre>",print_r($arr),"</pre>";die();
$order=$subscriptions->get_items();
?>
<input type="hidden" name="idUser" id="idUser" value="<?php echo $cu->ID;?>">
<input type="hidden" name="idSubscriptions" id="idSubscriptions" value="<?php echo $subscriptions->id;?>">
<div id="errorl"></div>
<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

<thead>
<tr>
<th class="subscription-id order-number"><span class="nobr">Products: </span></th>

</tr>
</thead>
</table>
<br/>
<div id="accordion" role="tablist" aria-multiselectable="true">

<?php
$arr2=array();
$arr3=array();
foreach ( $order as $o=>$items ) {
$post_parent=$items->get_product_id(); 
$res = get_post_meta($post_parent);
	
$attributes=unserialize($res['_product_attributes'][0]);
//echo "<pre>",print_r($attributes),"</pre>";die();
	if($attributes['type']['value']=="individual"){


$arr2[$o]=$post_parent;
$arr3[$o]=$items->get_variation_id();
//echo "<pre>",print_r($arr2),"</pre>";die();
$query="SELECT * FROM wp_posts as a JOIN wp_postmeta as b ON a.ID=b.post_id WHERE a.post_type='product_variation' and a.post_status='publish' and b.meta_key='_price' and  post_parent=".$post_parent." ";
$productsvariations = $wpdb->get_results( $query );

//echo $items->get_order_id()."--".$items->get_product_id();die();
$daysDeliv="SELECT * FROM st_DeliveryDays WHERE IDOrder=".$items->get_order_id()." and IDProduct=".$items->get_variation_id()." and IDUser=".$cu->ID."";
$reultdaysDeliv = $wpdb->get_results( $daysDeliv );

//echo "<pre>",print_r($order),"</pre>";die();
if(isset($reultdaysDeliv) and !empty($reultdaysDeliv)){
//$arr=array();
foreach($reultdaysDeliv as $i=>$item){ 

	$arr[$i]=$item->IDday;

 } 
 }


?>
<div class="card">
    <div class="card-header" role="tab" id="<?php echo 'headingTwo'.$o;?>">
      <h5 class="mb-0">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="<?php echo '#collapseTwo'.$o;?>" aria-expanded="true" aria-controls="<?php echo 'collapseTwo'.$o;?>">
        <?php echo $items->get_name(); ?>
        </a>
      </h5>
    </div>
    <div id="<?php echo 'collapseTwo'.$o;?>" class="collapse" role="tabpanel" aria-labelledby="<?php echo 'headingTwo'.$o;?>">
      <div class="card-block">

<?php
$varname=$items->get_name();
$partname=explode("-",$items->get_name());
$expname=explode("D",$partname[1]);
$namevalidate=trim($expname[0]);
?>
    <input id="<?php echo 'nameValidate'.$o;?>" name="<?php echo 'nameValidate'.$o;?>"  type="hidden" value="<?php echo $namevalidate; ?>" />   
     

	     
	  
<hr>
<div id="<?php echo 'sucDelivery'.$o;?>"></div>

<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

<thead>
<tr>
<th class="subscription-id order-number"><span class="nobr">Delivery dates: </span></th>

</tr>
</thead>

<tbody>
<tr class="order">
<td class="subscription-id order-number" data-title="ID">

<table id="<?php echo 'secCheck'.$o;?>">
<tr>
<?php foreach($arrDays as $item) { ?>
<td>
<input id="<?php echo 'delivery'.$o;?>" name="<?php echo 'delivery'.$o.'[]';?>" type="checkbox" value="<?php echo $item['value'];?>" <?php if(in_array($item['value'], $arr)){ echo " checked ";} ?> />   <?php echo $item['name'];?>

</td>
<?php } ?>
<input type="hidden" name="<?php echo 'product'.$o;?>" id="<?php echo 'product'.$o;?>"  value="<?php echo $items->get_variation_id(); ?>">


</tr>
</table>

</td>

</tr>
</tbody>

</table>
<div style="text-align:center;">
<button id="<?php echo 'hugSaveDelivery'.$o; ?>" class="single_add_to_cart_button button alt" type="submit">Save</button>
</div>
<hr>
<div id="<?php echo 'sucFrecuency'.$o; ?>"></div>

<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

<thead>
<tr>
<th class="subscription-id order-number"><span class="nobr">Frecuency:  </span></th>

</tr>
</thead>

<tbody>
<tr class="order">
<td class="subscription-id order-number" data-title="ID">

<table>
<tr>
<?php foreach($productsvariations as $varts) {
$name=explode("-",$varts->post_title);
if($items->get_variation_id()==$varts->ID){$checked="checked";}else{$checked="";}
?>
<td>

<input id="<?php echo 'frecuencyDelevery'.$o;?>" name="<?php echo 'frecuencyDelevery'.$o;?>" class="changeFreq" type="radio" value="<?php echo $varts->ID;?>" <?php echo $checked; ?>/> <?php echo $name[1]; ?>  x Week

</td>
<?php } ?>

</tr>
</table>

</td>

</tr>
</tbody>

</table>
<div style="text-align:center;">
<button id="<?php echo 'hugSavefrecuencyDelevery'.$o;?>" class="single_add_to_cart_button button alt"  type="submit">Update</button>
</div>	
<hr>
<!-- fin acordion -->
 </div>
    </div>
  </div>
<div id="<?php echo 'hiddens'.$o; ?>"></div>

<script>
( function( $ ) {
document.getElementById("<?php echo 'hugSavefrecuencyDelevery'.$o;?>").addEventListener('click', function() {
		 name="<?php echo 'frecuencyDelevery'.$o;?>";
		var vfrec=$('input:radio[name="'+name+'"]:checked').val();
		idSubscriptions=$('#idSubscriptions').val();
		var arr = [];
                $('.changeFreq').each(function() {

                       if ($(this).is(':checked')==true ) {
				//alert("aqui2");
			
			
			arr.push($('input:radio[name="'+this.id+'"]:checked').val());
			

			}
      		});
	      
		var parametros = {
                
		 "idSubscriptions" : idSubscriptions,
                 "arrProducts" : arr,
		 "action": "changeFrecuency",
                };
		$.ajax({
				 type: 'POST',
				 dataType: 'json',
				 url: '/wordpress/wp-admin/admin-ajax.php', 
				 data: parametros,
				 success: function(data) { 
					$('body,html').animate({scrollTop : 200}, 500);
						$('#errorl').empty();
		$('#errorl').html("<div class='alert alert-success' role='alert'>Record saved successfully</div>");
					setTimeout(location.reload(), 500);
					 },
				error: function(data) { 
					$('body,html').animate({scrollTop : 200}, 500);
							$('#errorl').empty();
		$('#errorl').html("<div class='alert alert-danger' role='alert'>Error saving log contact admin</div>");
					
					 },
 			});


        });
	        
	
	  
        document.getElementById("<?php echo 'hugSaveDelivery'.$o;?>").addEventListener('click', function() {
		error=0;
		 name="<?php echo 'delivery'.$o.'[]';?>";
		 idDelivery="<?php echo 'delivery'.$o;?>";
		 id="<?php echo 'product'.$o;?>";
		 days="<?php echo 'nameValidate'.$o;?>";
                idUser=$('#idUser').val();
		product=$('#'+id).val();
		idSubscriptions=$('#idSubscriptions').val();
	        delivery=JSON.stringify($('[name="'+name+'"]').serializeArray()); 

		days=$('#'+days).val();
		 var day= days.split('D');
		dayValidate=$.trim(day[0]);
		//alert(dayValidate);
		var checkboxes = $("<?php echo '#secCheck'.$o;?> input:checked").length;
		//alert(checkboxes);
		if(checkboxes<dayValidate){
		rest=dayValidate-checkboxes;
		//alert("menos 3");
		
		 error = 1;
		}else if(checkboxes>dayValidate){
		sobr=checkboxes-dayValidate;
		//alert("mas 3");
		//return false;
		 error = 1;
		}


		
		if(error>0){
		$('body,html').animate({scrollTop : 200}, 500);
		$('#errorl').html("<div class='alert alert-danger' role='alert'>You must select the days according to the chosen plan</div>");	
		return false;
		}else{
		var parametros = {
                "idUser" : idUser,
                "idSubscriptions" : idSubscriptions,
		"product" : product,
		"delivery": delivery,
		 "action": "deliveryDays",
                };
		$.ajax({
				 type: 'POST',
				dataType: 'json',
				 url: '/wordpress/wp-admin/admin-ajax.php', 
				 data: parametros,
				 success: function(data) { 
					$('body,html').animate({scrollTop : 200}, 500);
		$('#errorl').empty();			
		$('#errorl').html("<div class='alert alert-success' role='alert'>Record saved successfully</div>");
					
					 },
				error: function(data) { 
					$('body,html').animate({scrollTop : 200}, 500);
		$('#errorl').empty();				
		$('#errorl').html("<div class='alert alert-danger' role='alert'>Error saving log contact admin</div>");
					
					 },
 			});
		}

        });




	} )( jQuery );	
</script>
<?php  } ?>

<?php

}
?>
</div>
<hr>
<!-- CAMBIAR direccion -->
<?php
$query="SELECT * FROM st_AddressUser as a  WHERE a.IDUser='".$cu->ID."' and a.IDSubscripcion='".$subscriptions->id."'   ";
$address = $wpdb->get_results( $query );
//echo "<pre>",print_r($address),"</pre>";die();
?>
<div class="woocommerce_account_subscriptions">
<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

<thead>
<tr>
<th class="subscription-id order-number"><span class="nobr">Address :  </span></th>

</tr>
</thead>

<tbody>
<tr class="order">
<td class="subscription-id order-number" data-title="ID">
<div class="row">
<div class="col-lg-2" style="text-align:centrer">
Receiver 
</div>
<div class="col-lg-8">
<input type="text" name="sReceiver" id="sReceiver" value="<?php echo $address[0]->sReceiver;?>">
</div>
</td>
</tr>
<tr class="order">
<td class="subscription-id order-number" data-title="ID">
<div class="row">
<div class="col-lg-2" style="text-align:centrer">
Other Info 
(Instructions)
</div>
<div class="col-lg-8">
<textarea name="sOtherInfo" id="sOtherInfo" cols="50" rows="5"><?php echo $address[0]->sOtherInfo;?></textarea>
</div>
</td>
</tr>
<tr class="order">
<td class="subscription-id order-number" data-title="ID">
<div class="row">
<div class="col-lg-2" style="text-align:centrer">
Address 
</div>
<div class="col-lg-8">
<input type="text" name="sAddress" id="sAddress" readonly value="<?php echo $address[0]->sAddress;?>">
</div>
<div class="col-lg-2">
<?php $link="http://localhost/wordpress/?page_id=577&view-subscription=".$subscriptions->id; ?>
<a type="button" href="<?php echo $link; ?>" id="hugSearchAddress" class="single_add_to_cart_button button alt" type="submit">Check</a>
</div>
</td>
</tr>
</tbody>

</table>
<div style="text-align: center;"><button id="hugSaveAddressUser" class="single_add_to_cart_button button alt" type="submit">Update</button>
</div>
</div>	









<hr>
<!-- CAMBIAR PRODUCTO -->
<div id="sucFrecuency"></div>
<div class="woocommerce_account_subscriptions">
<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

<thead>
<tr>
<th class="subscription-id order-number"><span class="nobr">Plan :  </span></th>

</tr>
</thead>

<tbody>
<tr class="order">
<td class="subscription-id order-number" data-title="ID">
<div class="row"  >
<table>

<?php foreach($products as $i=>$itemProduct) {
$post_parent=$itemProduct->ID; 
$res = get_post_meta($post_parent);
	
$attributes=unserialize($res['_product_attributes'][0]);
//echo "<pre>",print_r($attributes),"</pre>";die();
	if($attributes['type']['value']=="individual"){
//echo "<pre>",print_r($products),"</pre>";die();
$query="SELECT * FROM wp_posts as a JOIN wp_postmeta as b ON a.ID=b.post_id WHERE a.post_type='product_variation' and a.post_status='publish' and b.meta_key='_price' and  post_parent=".$itemProduct->ID." ";
$listproductsvariations = $wpdb->get_results( $query );
?>
<tr >
<td >
<p style="margin-left:80px;">
<input id="<?php echo 'plan-'.$i; ?>" onchange="showDiv(this.id)" name="<?php echo 'plan-'.$i; ?>" <?php if(in_array($itemProduct->ID, $arr2)){ echo " checked ";} ?> type="checkbox"  value="<?php echo $itemProduct->ID;?>" /> <?php echo $itemProduct->post_title; ?> 
<div id="<?php echo 'div-'.$i; ?>" style="text-align:center">
<?php foreach($listproductsvariations as $varts) {
$name=explode("-",$varts->post_title);

?>

<input id="<?php echo 'changePlans'.$i;?>" name="<?php echo 'changePlans'.$i;?>" class="changePlan" type="radio" value="<?php echo $varts->ID;?>" <?php if(in_array($varts->ID, $arr3)){ echo " checked ";} ?>/> <?php echo $name[1]; ?>  x Week
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php } ?>
</div>
</p>
</td>
</tr>
<script type="application/javascript"> 

jQuery(document).ready(function($){

vtr="<?php echo 'plan-'.$i; ?>";
vtr2="<?php echo 'div-'.$i; ?>";

 if ($('input[name="'+vtr+'"]').is(':checked')==true ) {
			
	$('#'+vtr2).show();		
}else{

$('#'+vtr2).hide();	
}

}); 	
</script>
<?php } ?>
<?php } ?>

</table>
</div>
</td>

</tr>
</tbody>

</table>
<div style="text-align: center;"><button id="hugSavePlan2" class="single_add_to_cart_button button alt" type="submit">Update</button>
</div>
</div>	
<hr>
<input type="hidden" name="idSubscription" id="idSubscription" value="<?php echo $subscriptions->id; ?>" />




<?php

}
?>


<!-- skip  week -->
<?php if($subscriptions->status=="active"){ 
$week=date('W');
$query="SELECT * FROM st_SkipWeek as a  WHERE a.IDUser='".$cu->ID."' and a.IDSubscripcion='".$subscriptions->id."'  and a.iWeek='".$week."' ";
$skip = $wpdb->get_results( $query );


?>
<div id="sucFrecuency"></div>
<div class="woocommerce_account_subscriptions">
<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

<thead>
<tr>
<th class="subscription-id order-number"><span class="nobr">Status :  </span></th>

</tr>
</thead>

<tbody>
<tr class="order">
<td class="subscription-id order-number" data-title="ID">
<div style="text-align: center;"><?php echo ucwords($subscriptions->status); ?><br/>
<small><b>Next Payment: </b><?php echo esc_attr( $subscriptions->get_date_to_display( 'next_payment' ) ); ?>
				</small>
</div>
</td>
<td class="subscription-id order-number" data-title="ID">
<?php if(empty($skip)){ ?>
<div style="text-align: center;"><button id="hugSkipWeek" class="single_add_to_cart_button button alt" type="submit">Skip the next whole week</button>
</div>
<?php }else{ 
echo "<div class='alert alert-warning' role='alert'> *Can only omit the subscription once a week!</div>";
}?>
<div style="text-align: center;margin-top:20px;">
<?php $actions = wcs_get_all_user_actions_for_subscription( $subscriptions, get_current_user_id() ); ?>
	<?php if ( ! empty( $actions ) ) : ?>
		<tr>
			<td><div style="text-align: center;margin-top:20px;"><?php esc_html_e( 'Action', 'woocommerce-subscriptions' ); ?></div></td>
			<td>
				<div style="text-align: center;margin-top:20px;">
				<?php //echo "<pre>",print_r($actions),"</pre>";die();
					foreach ( $actions as $key => $action ) : 

						if($key!="change_payment_method"){
					?>
					
					<a href="<?php echo esc_url( $action['url'] ); ?>" class="button <?php echo sanitize_html_class( $key ) ?>"><?php echo esc_html( $action['name']." Subscription" ); ?></a>
						<?php } ?>
				<?php endforeach; ?>
				</div>
			</td>
		</tr>
	<?php endif; ?>
</div>

							
</td>

</tr>
</tbody>

</table>

</div>
<hr>
<?php } ?>
<?php

} 
?>
<script>

  function showDiv(indice){
( function( $ ) {
 var arr= indice.split('-');
		name=arr[0];
		ids=arr[1];
		

 	 if ($("#"+indice).is(':checked')==true ) {
		$("#div-"+ids).show();
		
	}else{
		//alert("rfrf"+ids);
		$("#div-"+ids).children().prop('checked', false);
		$("#div-"+ids).hide();
		}		
					
		
} )( jQuery );			
}
	( function( $ ) {
	
	document.getElementById('hugSavePlan2').addEventListener('click', function() {
		
		error=0;
		var arr2 = [];
                $('.changePlan').each(function() {

                       if ($(this).is(':checked')==true ) {
				
			arr2.push($('input:radio[name="'+this.id+'"]:checked').val());
			

			}else{
			error++;
			}
      		});
              
		idSubscription=$('#idSubscription').val();
		
		var parametros2 = {
                "plan": arr2,
                "idSubscription" : idSubscription,
 		"action": "changeProduct",
                };
		$.ajax({
				 type: 'POST',
				dataType: 'json',
				 url: '/wordpress/wp-admin/admin-ajax.php', 
				 data: parametros2,
				 success: function(data) { 
					
					setTimeout(location.reload(), 300);
					 },
				error: function(data) { 
					
					
					 },
 			});

		

        });
        } )( jQuery );	
</script>

<link   href="/wordpress/wp-includes/css/bootstrap.css" rel="stylesheet">
 <link  href="/wordpress/wp-includes/css/font-awesome.min.css" rel="stylesheet">
<script src="/wordpress/wp-includes/js/delivery.js" type="text/javascript"></script>
<script src="/wordpress/wp-includes/js/bootstrap.js" type="text/javascript"></script>

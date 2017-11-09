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
$variationslist = $wpdb->get_results( $query );


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

<div id="errorl"></div>

<input type="hidden" name="idUser" id="idUser" value="<?php echo $cu->ID;?>">
<input type="hidden" name="idSubscriptions" id="idSubscriptions" value="<?php echo $subscriptions->id;?>">

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
	if($attributes['type']['value']=="corporate"){


$arrProduct[$o]=$post_parent;
$arr3[$o]=$items->get_variation_id();
//echo "<pre>",print_r($arr2),"</pre>";die();
$query="SELECT * FROM wp_posts as a JOIN wp_postmeta as b ON a.ID=b.post_id WHERE a.post_type='product_variation' and a.post_status='publish' and b.meta_key='_price' and  post_parent=".$post_parent." ";
$productsvariations = $wpdb->get_results( $query );

//echo $items->get_order_id()."--".$items->get_variation_id();die();
$daysDeliv="SELECT * FROM st_DeliveryDays WHERE IDOrder=".$items->get_order_id()." and IDProduct=".$items->get_variation_id()." and IDUser=".$cu->ID."";
$reultdaysDeliv = $wpdb->get_results( $daysDeliv );

//echo "<pre>",print_r($reultdaysDeliv),"</pre>";die();
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
<input id="delivery" name="<?php echo 'delivery'.$o.'[]';?>" type="checkbox"  value="<?php echo $item['value'];?>" <?php if(in_array($item['value'], $arr)){ echo " checked ";} ?> />   <?php echo $item['name'];?>

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
<button id="<?php echo 'hugSaveDelivery'.$o;?>" class="single_add_to_cart_button button alt" type="submit">Save</button>
</div>
<hr>
<div id="<?php echo 'sucFrecuency'.$o;?>"></div>

<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

<thead>
<tr>
<th class="subscription-id order-number"><span class="nobr">Frecuency:  </span></th>

</tr>
</thead>

<tbody>
<tr class="order">
<td class="subscription-id order-number" data-title="ID" style="text-align:center">
<input type="hidden" name="varProduct" id="varProduct" value="<?php echo $items->get_variation_id(); ?>"
<?php  
$chk=0;
foreach($products as $i=>$product) { 
		
	$res = get_post_meta($product->ID);
	
$attributes=unserialize($res['_product_attributes'][0]);

	if($attributes['type']['value']=="corporate"){
$arr=$attributes['frequency']['value'];
$arr2=$attributes['size']['value'];	
if($chk==0){	
	
?>

<?php 
		
			 foreach($variationslist as $variation){ ?>
			<?php if($product->ID==$variation->post_parent) { ?>
			<?php $title=explode(" - ",$variation->post_title); 
			
			?>
			
			
			<input type="hidden"  class="totalpriceCorp"  name="<?php echo $product->post_name; ?>" id="<?php echo $product->ID; ?>" price="<?php echo $variation->meta_value; ?>"  frecuency="<?php echo $title[1]; ?>" value="<?php echo $variation->ID; ?>" /> 
			

				<?php } //echo "<pre>",print_r($arr),"</pre>";die();  } ?>

				<?php } ?>
			<?php $chk++;	 } ?>
		
		<?php } ?>

<?php		
	
	
 } 

?>
<br/>
			
<div class="row" >
<?php

$variations = wc_get_product($items->get_variation_id());
$variation_title_pre =  $variations->get_formatted_name();
$s1=explode("-",$variation_title_pre);
$na=$s1[1];
$vname=explode(",",$na);
$sFreq=trim($vname[0]);
$sSize=trim($vname[1]);
$expSize=explode("(",$sSize);
$sSize=trim($expSize[0]);
$valoresFrecuency=explode("|",$arr);
$valoresSize=explode("|",$arr2);
 foreach($valoresFrecuency as $i=>$value){


$value=trim($value);
//echo $sFreq."--".$sSize."--".$value."--".$items->get_variation_id();die();
if($value==$sFreq){$checked="checked";}else{$checked="";}
?>

<?php if($i<3){ ?>

<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio"  class="calculatePrice" <?php echo $checked; ?>  id="<?php echo $value;?>" name="corFrequency" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }elseif($i>3 and $i<6){ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio" class="calculatePrice" <?php echo $checked; ?>  id="<?php echo $value;?>" name="corFrequency" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }else{ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio"  class="calculatePrice" <?php echo $checked; ?> id="<?php echo $value;?>" name="corFrequency" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php } ?>
<?php } ?>
</div>
</td>
</tr>
</tbody>
</table>

<hr>
<br/>
<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

<thead>
<tr>
<th class="subscription-id order-number"><span class="nobr">Size:  </span></th>

</tr>
</thead>

<tbody>
	
<tr>
<td style="text-align:center">

			<br/>
<div class="row" >
<?php
$valoresFrecuency=explode("|",$arr);
$valoresSize=explode("|",$arr2);
 foreach($valoresSize as $i=>$value){

$value=trim($value);
if($value==$sSize){$checked="checked";}else{$checked="";}
?>

<?php if($i<3){ ?>

<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio" class="calculatePrice" <?php echo $checked; ?> id="<?php echo $value;?>" name="corSize" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }elseif($i>3 and $i<6){ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio" class="calculatePrice" <?php echo $checked; ?>  id="<?php echo $value;?>" name="corSize" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }else{ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio"  class="calculatePrice" <?php echo $checked; ?> id="<?php echo $value;?>" name="corSize" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php } ?>
<?php } ?>
</div>

</td>

</tr>
</tbody>

</table>
<div style="text-align:center;">
<button id="hugSavePlanFrecuencyCorporate" class="single_add_to_cart_button button alt"  type="submit">Update</button>
</div>	
<hr>
<!-- fin acordion -->
 </div>
    </div>
  </div>
<div id="<?php echo 'hiddens'.$o;?>"></div>
<script>
( function( $ ) {

	
	  
        document.getElementById("<?php echo 'hugSaveDelivery'.$o;?>").addEventListener('click', function() {
		error=0;
		 name="<?php echo 'delivery'.$o.'[]';?>";
		 id="<?php echo 'product'.$o;?>";
                idUser=$('#idUser').val();
		product=$('#'+id).val();
		//alert(product);
		idSubscriptions=$('#idSubscriptions').val();
	        delivery=JSON.stringify($('[name="'+name+'"]').serializeArray()); 
		 days="<?php echo 'nameValidate'.$o;?>";
		days=$('#'+days).val();
		 var day= days.split('D');
		dayValidate=$.trim(day[0]);
		alert(dayValidate);
		var checkboxes = $("<?php echo '#secCheck'.$o;?> input:checked").length;
		alert(checkboxes);
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
		
		$('#errorl').html("<div class='alert alert-success' role='alert'>Record saved successfully</div>");
					
					 },
				error: function(data) { 
					$('body,html').animate({scrollTop : 200}, 500);
		
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
<!-- CAMBIAR direccion <span class="nobr" style="font-weight_bold;margin-left:30px;">Frecuency:  </span>-->
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
<input type="text" name="sReceiver" id="sReceiver" value="<?php echo ($address[0]->sReceiver) ?  $address[0]->sReceiver :  "" ;?>">
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
<textarea name="sOtherInfo" id="sOtherInfo" cols="50" rows="5"><?php echo ($address[0]->sOtherInfo) ?  $address[0]->sOtherInfo :  "" ; ?></textarea>
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
<input type="text" name="sAddress" id="sAddress" readonly value="<?php echo ($address[0]->sAddress) ?  $address[0]->sAddress :  "" ;?>">
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
<tr>
<td>

<?php


 foreach($products as $i=>$itemProduct) {
$post_parent=$itemProduct->ID; 
$res = get_post_meta($post_parent);

$attributes=unserialize($res['_product_attributes'][0]);
//echo "<pre>",print_r($attributes),"</pre>";die();
	if($attributes['type']['value']=="corporate"){

//echo "<pre>",print_r($arr2),"</pre>";die();
$query="SELECT * FROM wp_posts as a JOIN wp_postmeta as b ON a.ID=b.post_id WHERE a.post_type='product_variation' and a.post_status='publish' and b.meta_key='_price' and  post_parent=".$itemProduct->ID." ";
$listproductsvariations = $wpdb->get_results( $query );
?>

<p style="margin-left:80px;">
<input id="<?php echo 'plan-'.$i; ?>" onchange="showDiv(this.id)" name="<?php echo 'plan-'.$i; ?>" <?php if(in_array($itemProduct->ID, $arrProduct)){ echo " checked ";} ?> type="checkbox"  value="<?php echo $itemProduct->ID;?>" /> <?php echo $itemProduct->post_title; ?> 
<div id="<?php echo 'div-'.$i; ?>" style="text-align:center">



<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

<thead>
<tr>
<th class="subscription-id order-number" style="margin-left:30px;"><span class="nobr">Frecuency:  </span></th>

</tr>
</thead>

<tbody>
<tr class="order">
<td class="subscription-id order-number" data-title="ID" style="text-align:center">
<input type="hidden" name="varProduct" id="varProduct" value="<?php echo $items->get_variation_id(); ?>">
<?php  
$chk=0;
foreach($products as $i=>$product) { 

	$res = get_post_meta($product->ID);
	
$attributes=unserialize($res['_product_attributes'][0]);

	if($attributes['type']['value']=="corporate"){
$arr=$attributes['frequency']['value'];
$arr2=$attributes['size']['value'];	
if($chk==0){	
	
?>

<?php 
		
			 foreach($variationslist as $variation){ 
//echo $product->ID."--".$variation->post_parent."<br/>";


?>
 
			<?php if($product->ID==$variation->post_parent) { ?>
			<?php $title=explode(" - ",$variation->post_title); 
					//echo "<pre>rrr",print_r($variations),"</pre>";die();
			?>
			
			
			<input type="hidden"  class="totalpriceCorp1"  name="<?php echo $product->post_name; ?>" id="<?php echo $product->ID; ?>" price="<?php echo $variation->meta_value; ?>"  frecuency="<?php echo $title[1]; ?>" value="<?php echo $variation->ID; ?>" /> 
			

				<?php } //echo "<pre>",print_r($arr),"</pre>";die();  } ?>

				<?php } ?>
			<?php $chk++;	 } ?>
		
		<?php } ?>

<?php		
	
	
 } 

?>

			
<div class="row" >
<?php
$variations = wc_get_product($items->get_variation_id());
$variation_title_pre =  $variations->get_formatted_name();
$s1=explode("-",$variation_title_pre);
$na=$s1[1];
$vname=explode(",",$na);
$sFreq=trim($vname[0]);
$sSize=trim($vname[1]);
$expSize=explode("(",$sSize);
$sSize=trim($expSize[0]);
$valoresFrecuency=explode("|",$arr);
$valoresSize=explode("|",$arr2);
 foreach($valoresFrecuency as $i=>$value){
if($value==$sFreq){$checked="checked";}else{$checked="";}
$value=trim($value);
?>

<?php if($i<3){ ?>

<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio"  <?php echo $checked; ?> class="calculatePrice1"  id="<?php echo $value;?>" name="corFrequency1" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }elseif($i>3 and $i<6){ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio" <?php echo $checked; ?> class="calculatePrice1"   id="<?php echo $value;?>" name="corFrequency1" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }else{ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio"  <?php echo $checked; ?> class="calculatePrice1"  id="<?php echo $value;?>" name="corFrequency1" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php } ?>
<?php } ?>
</div>
</td>
</tr>
</tbody>
</table>

<hr>

<table class="shop_table shop_table_responsive my_account_subscriptions my_account_orders">

<thead>
<tr>
<th class="subscription-id order-number" style="margin-left:30px;"><span class="nobr">Size:  </span></th>

</tr>
</thead>

<tbody>
	
<tr>
<td style="text-align:center">

			
<div class="row" >
<?php
$valoresFrecuency=explode("|",$arr);
$valoresSize=explode("|",$arr2);
 foreach($valoresSize as $i=>$value){
if($value==$sSize){$checked="checked";}else{$checked="";}
$value=trim($value);
?>

<?php if($i<3){ ?>

<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio" <?php echo $checked; ?> class="calculatePrice1"  id="<?php echo $value;?>" name="corSize1" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }elseif($i>3 and $i<6){ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio" <?php echo $checked; ?> class="calculatePrice1"   id="<?php echo $value;?>" name="corSize1" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }else{ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio"  <?php echo $checked; ?> class="calculatePrice1"  id="<?php echo $value;?>" name="corSize1" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php } ?>
<?php } ?>
</div>

</td>

</tr>
</tbody>

</table>


<script type="application/javascript"> 
//id="<?php echo "Q-".$valor['IDQuestion']."-".$valor['iTypeData']."-".$valor['nPercentage']; ?>"
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



</tr>
</tbody>
</div>
</div>	
</table>
<div style="text-align: center;"><button id="hugSavePlanCorporate" class="single_add_to_cart_button button alt" type="submit">Update</button>

<hr>
<input type="hidden" name="idSubscription" id="idSubscription" value="<?php echo $subscriptions->id; ?>" />




<?php

}
?>
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
	
</script>
<script>

( function( $ ) {


	        
document.getElementById('hugSavePlanFrecuencyCorporate').addEventListener('click', function() {
		var car=0;
		var error=0;
		var arr=[];
		var arrCar=[];
		var arr2 = [];
		var corSize=$('input:radio[name="corSize"]:checked').val(); 
                var corFrequency=$('input:radio[name="corFrequency"]:checked').val(); 
		
		if ($('input[name="corSize"]:radio').is(':checked')==true && $('input[name="corFrequency"]:radio').is(':checked')==true) {
			
			error=0;
		}else{
			error++;
		}

		
		
	if(error > 0){
		
		$('body,html').animate({scrollTop : 200}, 500);
		$('#errorl').empty();
		$('#errorl').html("<div class='alert alert-danger' role='alert'>You must select at least one product</div>");
		return false;
		}
		
		
		var arrName=[];
		idSubscription=$('#idSubscription').val();

		$('.totalpriceCorp').each(function(i, elem) {
		   var frecuency= elem.getAttribute('frecuency');
		   var price= elem.getAttribute('price');
		   var var_id= elem.getAttribute('id');
		   var product= elem.getAttribute('value');
		   var valor=corFrequency+", "+corSize;
		    if(frecuency==valor){
		       		//alert(product);
				arr2.push(product);
			}
		  });

		

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
					//alert("no");
					
					 },
 			});

		//}

        });


document.getElementById('hugSavePlanCorporate').addEventListener('click', function() {
		var car=0;
		var error=0;
		var arr=[];
		var arrCar=[];
		var arr2 = [];
		var corSize=$('input:radio[name="corSize1"]:checked').val(); 
                var corFrequency=$('input:radio[name="corFrequency1"]:checked').val(); 
		
		if ($('input[name="corSize1"]:radio').is(':checked')==true && $('input[name="corFrequency1"]:radio').is(':checked')==true) {
			
			error=0;
		}else{
			error++;
		}

		
		
	if(error > 0){
		
		$('body,html').animate({scrollTop : 200}, 500);
		$('#errorl').html("<div class='alert alert-danger' role='alert'>You must select at least one product</div>");
		return false;
		}
		
		
		var arrName=[];
		idSubscription=$('#idSubscription').val();

		$('.totalpriceCorp1').each(function(i, elem) {
		   var frecuency= elem.getAttribute('frecuency');
		   var price= elem.getAttribute('price');
		   var var_id= elem.getAttribute('id');
		   var product= elem.getAttribute('value');
		   var valor=corFrequency+", "+corSize;
		    if(frecuency==valor){
		       		//alert(product);
				arr2.push(product);
			}
		  });

		

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
					
					//setTimeout(location.reload(), 300);
					 },
				error: function(data) { 
					//alert("no");
					
					 },
 			});

		//}

        });
document.getElementById('hugSaveAddressUser').addEventListener('click', function() {
		
                 idUser=$('#idUser').val();
		idSubscription=$('#idSubscription').val();
		 idUser=$('#idUser').val();
		sReceiver=$('#sReceiver').val();
		sOtherInfo=$('#sOtherInfo').val();

		var parametros2 = {
                "idUser": idUser,
                "idSubscription" : idSubscription,
		"sReceiver" : sReceiver,
		"sOtherInfo" : sOtherInfo,
 		"action": "SaveAddressUser",
                };
		if(sReceiver!="" || sOtherInfo!=""){
		$.ajax({ 
				 type: 'POST',
				dataType: 'json',
				 url: '/wordpress/wp-admin/admin-ajax.php', 
				 data: parametros2,
				 success: function(data) { 
					$('body,html').animate({scrollTop : 200}, 500);
		$('#errorl').empty();
		$('#errorl').html("<div class='alert alert-success' role='alert'>Record saved successfully</div>");
					//setTimeout(location.reload(), 300);
					 },
				error: function(data) { 
					//alert("no");
					
					 },
 			});

		}

        });

 	document.getElementById('hugSkipWeek').addEventListener('click', function() {
		
                 idUser=$('#idUser').val();
		idSubscription=$('#idSubscription').val();
		

		var parametros2 = {
                "idUser": idUser,
                "idSubscription" : idSubscription,
 		"action": "changeNextPayment",
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
					//alert("no");
					
					 },
 			});

		//}

        });
} )( jQuery );			

	
</script>
<link href="/wordpress/wp-includes/css/bootstrap.css" rel="stylesheet">
<script src="/wordpress/wp-includes/js/bootstrap.js" type="text/javascript"></script>

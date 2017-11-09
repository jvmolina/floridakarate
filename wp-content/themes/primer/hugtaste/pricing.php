<?php 
global $wpdb;
global $wp_session;


//echo "<pre>",print_r($wp_session),"</pre>";die();
//echo $wp_session['session_id'];
$query="SELECT * FROM wp_posts as a JOIN wp_postmeta as b ON a.ID=b.post_id WHERE a.post_type='product' and a.post_status='publish' and b.meta_key='_price'  ";
$products = $wpdb->get_results( $query );

$query="SELECT * FROM wp_posts as a JOIN wp_postmeta as b ON a.ID=b.post_id WHERE a.post_type='product_variation' and a.post_status='publish' and b.meta_key='_price'  ";
$variations = $wpdb->get_results( $query );



//echo "<pre>",print_r($variations),"</pre>";die();
$arr=array(); 
$arr2=array();
?>
<div id="errLang"></div>
<style>
.su-note-inner {
    padding: 4em;
    border-width: 1px;
    border-style: solid;
}
</style>
<div id="form">
<div class="su-note-inner su-clearfix" style="background-color:#f1f7fd;border-color:#fcfdff;color:#16191b;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;">
<ul class="nav nav-tabs" style="margin-top:-50px;">
  <li class="active"><a data-toggle="tab" href="#individual">Individual</a></li>
  <li><a data-toggle="tab" href="#corporate">Corporate</a></li>
 
</ul>

<div class="tab-content">
  <div id="individual" class="tab-pane fade in active">
 <br/>
<?php  foreach($products as $i=>$product) { 

	$res = get_post_meta($product->ID);
	
$attributes=unserialize($res['_product_attributes'][0]);
//echo "<pre>",print_r($attributes),"</pre>";die();
	if($attributes['type']['value']=="individual"){
		?>	
			
			<?php  if(!in_array($product->ID, $arr2)){ $arr2[]=$product->ID;?>
			<input type="checkbox"  name="<?php echo $product->ID; ?>" class="validCheck" id="<?php echo $product->post_name; ?>" value="<?php echo $product->ID; ?>"> <label><?php echo $product->post_name; ?></label>
     
			<br/>
			<b >Frequency</b> 
			<br/>
			
			<?php }  ?>

	
		<?php 
			 foreach($variations as $variation){ ?>
			<?php if($product->ID==$variation->post_parent) { ?>
			<?php $title=explode(" - ",$variation->post_title); 
			
			if(!in_array($variation->ID, $arr)){  $arr[]=$variation->ID;?>
			<div class="col-lg-4" class="checkInd" style="margin-left:50px;">
			<input type="radio"  class="totalprice" disabled name="<?php echo $product->post_name; ?>" id="<?php echo $product->ID; ?>" price="<?php echo $variation->meta_value; ?>"  frecuency="<?php echo $title[1]; ?>" value="<?php echo $variation->ID; ?>"> <?php echo $title[1]; ?>  <br/> (<?php echo "$".$variation->meta_value; ?> x <?php echo $product->post_title; ?>) 
			</div>

				<?php } //echo "<pre>",print_r($arr),"</pre>";die();  } ?>


			<?php } ?>
		
		<?php } ?>
		<br/><br/>
		
			
			
	<?php } ?>
<?php } ?>

<hr>
<div class="row">
<div class="col-lg-1" style="margin-top:10px;">
<label><strong>Total: </strong></label>
</div>
<div class="col-lg-4">
<input id="total" type="text" disabled style="color:#424242;font-weight:bold;font-size:1em;border:0px;background-color:transparent" />
</div>

</div>
	<div  style="text-align:center">

		<button type="submit" class="single_add_to_cart_button button alt" id="hugCheckout" >CHECKOUT</button>
	</div>
</div><!--cierra tab-->

<!-- TAB CORPORATIVO-->
  <div id="corporate" class="tab-pane fade">
    <br/>

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
		
			 foreach($variations as $variation){ ?>
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
			<b >Frequency</b> 
			<br/>
<div class="row" >
<?php
$valoresFrecuency=explode("|",$arr);
$valoresSize=explode("|",$arr2);
 foreach($valoresFrecuency as $i=>$value){

$value=trim($value);
?>

<?php if($i<3){ ?>

<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio"  class="calculatePrice"  id="<?php echo $value;?>" name="corFrequency" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }elseif($i>3 and $i<6){ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio" class="calculatePrice"   id="<?php echo $value;?>" name="corFrequency" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }else{ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio"  class="calculatePrice"  id="<?php echo $value;?>" name="corFrequency" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php } ?>
<?php } ?>
</div>
<hr>
<br/>
			<b >Size</b> 
			<br/>
<div class="row" >
<?php
$valoresFrecuency=explode("|",$arr);
$valoresSize=explode("|",$arr2);
 foreach($valoresSize as $i=>$value){

$value=trim($value);
?>

<?php if($i<3){ ?>

<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio" class="calculatePrice"  id="<?php echo $value;?>" name="corSize" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }elseif($i>3 and $i<6){ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio" class="calculatePrice"   id="<?php echo $value;?>" name="corSize" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php }else{ ?>
<div class="col-lg-4 col-sm-4" style="margin-top:20px;" >
<i class="fa fa-check" aria-hidden="true"></i>
<input   style="margin-top:20px;" type="radio"  class="calculatePrice"  id="<?php echo $value;?>" name="corSize" value="<?php echo $value; ?>" > <?php echo $value; ?> <br/>
</div>
<?php } ?>
<?php } ?>
</div>
<hr>
<div class="row">
<div class="col-lg-1" style="margin-top:10px;">
<label><strong>Total: </strong></label>
</div>
<div class="col-lg-4">
<input id="totalCorp" type="text" disabled style="color:#424242;font-weight:bold;font-size:1em;border:0px;background-color:transparent" />
</div>

</div>
	<div  style="text-align:center">

		<button type="submit" class="single_add_to_cart_button button alt" id="hugCheckoutCorp" >CHECKOUT</button>
	</div>



</div>
</div>
 <link href="/wordpress/wp-includes/css/bootstrap.css" rel="stylesheet">
<script src="/wordpress/wp-includes/js/hugjs.js" type="text/javascript"></script>
<script src="/wordpress/wp-includes/js/bootstrap.js" type="text/javascript"></script>



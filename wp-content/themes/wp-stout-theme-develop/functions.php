<?php

/**
 * Child theme version.
 *
 * @since 1.0.0
 *
 * @var string
 */
define( 'PRIMER_CHILD_VERSION', '1.1.0' );

/**
 * Move some elements around.
 *
 * @action template_redirect
 * @since  1.0.0
 */
function stout_move_elements() {

	remove_action( 'primer_header',                'primer_add_hero',               7 );
	remove_action( 'primer_after_header',          'primer_add_primary_navigation', 11 );
	remove_action( 'primer_after_header',          'primer_add_page_title',         12 );
	remove_action( 'primer_before_header_wrapper', 'primer_video_header',           5 );

	add_action( 'primer_after_header', 'primer_add_hero',               7 );
	add_action( 'primer_header',       'primer_add_primary_navigation', 11 );
	add_action( 'primer_hero',         'primer_video_header',           3 );

	if ( ! is_front_page() || ! is_active_sidebar( 'hero' ) ) {

		add_action( 'primer_hero', 'primer_add_page_title', 12 );

	}

}
add_action( 'template_redirect', 'stout_move_elements' );

/**
 * Set hero image target element.
 *
 * @filter primer_hero_image_selector
 * @since  1.0.0
 *
 * @return string
 */
function stout_hero_image_selector() {

	return '.hero';

}
add_filter( 'primer_hero_image_selector', 'stout_hero_image_selector' );

/**
 * Set custom logo args.
 *
 * @filter primer_custom_logo_args
 * @since  1.0.0
 *
 * @param  array $args
 *
 * @return array
 */
function stout_custom_logo_args( $args ) {

	$args['width']  = 248;
	$args['height'] = 100;

	return $args;

}
add_filter( 'primer_custom_logo_args', 'stout_custom_logo_args' );

/**
 * Set fonts.
 *
 * @filter primer_fonts
 * @since  1.0.0
 *
 * @param  array $fonts
 *
 * @return array
 */
function stout_fonts( $fonts ) {

	$fonts[] = 'Lato';
	$fonts[] = 'Oswald';

	return $fonts;

}
add_filter( 'primer_fonts', 'stout_fonts' );

/**
 * Set font types.
 *
 * @filter primer_font_types
 * @since  1.0.0
 *
 * @param  array $font_types
 *
 * @return array
 */
function stout_font_types( $font_types ) {

	$overrides = array(
		'site_title_font' => array(
			'default' => 'Oswald',
		),
		'navigation_font' => array(
			'default' => 'Oswald',
		),
		'heading_font' => array(
			'default' => 'Oswald',
		),
		'primary_font' => array(
			'default' => 'Lato',
		),
		'secondary_font' => array(
			'default' => 'Lato',
		),
	);

	return primer_array_replace_recursive( $font_types, $overrides );

}
add_filter( 'primer_font_types', 'stout_font_types' );

/**
 * Set colors.
 *
 * @filter primer_colors
 * @since  1.0.0
 *
 * @param  array $colors
 *
 * @return array
 */
function stout_colors( $colors ) {

	unset(
		$colors['content_background_color'],
		$colors['footer_widget_content_background_color']
	);

	$overrides = array(
		/**
		 * Text colors
		 */
		'header_textcolor' => array(
			'default' => '#e3ad31',
		),
		'tagline_text_color' => array(
			'default' => '#686868',
		),
		'hero_text_color' => array(
			'default' => '#ffffff',
		),
		'menu_text_color' => array(
			'default' => '#686868',
		),
		'heading_text_color' => array(
			'default' => '#353535',
		),
		'primary_text_color' => array(
			'default' => '#252525',
		),
		'secondary_text_color' => array(
			'default' => '#686868',
		),
		'footer_widget_heading_text_color' => array(
			'default' => '#ffffff',
		),
		'footer_widget_text_color' => array(
			'default' => '#ffffff',
		),
		'footer_menu_text_color' => array(
			'default' => '#252525',
			'css'      => array(
				'.footer-menu ul li a,
				.footer-menu ul li a:visited' => array(
					'color' => '%1$s',
				),
				'.site-info-wrapper .social-menu a,
				.site-info-wrapper .social-menu a:visited' => array(
					'background-color' => '%1$s',
				),
			),
			'rgba_css' => array(
				'.footer-menu ul li a:hover,
				.footer-menu ul li a:visited:hover' => array(
					'color' => 'rgba(%1$s, 0.8)',
				),
			),
		),
		'footer_text_color' => array(
			'default' => '#686868',
		),
		/**
		 * Link / Button colors
		 */
		'link_color' => array(
			'default' => '#e3ad31',
		),
		'button_color' => array(
			'default' => '#e3ad31',
		),
		'button_text_color' => array(
			'default' => '#ffffff',
		),
		/**
		 * Background colors
		 */
		'background_color' => array(
			'default' => '#ffffff',
		),
		'hero_background_color' => array(
			'default' => '#252525',
		),
		'menu_background_color' => array(
			'default' => '#ffffff',
			'css'     => array(
				'.site-header-wrapper' => array(
					'background-color' => '%1$s',
				),
			),
		),
		'footer_widget_background_color' => array(
			'default' => '#4e4e4e',
		),
		'footer_background_color' => array(
			'default' => '#ffffff',
		),
	);

	return primer_array_replace_recursive( $colors, $overrides );

}
add_filter( 'primer_colors', 'stout_colors' );

/**
 * Set color schemes.
 *
 * @filter primer_color_schemes
 * @since  1.0.0
 *
 * @param  array $color_schemes
 *
 * @return array
 */
function stout_color_schemes( $color_schemes ) {

	unset( $color_schemes['canary'] );

	$overrides = array(
		'blush' => array(
			'colors' => array(
				'header_textcolor' => $color_schemes['blush']['base'],
				'link_color'       => $color_schemes['blush']['base'],
				'button_color'     => $color_schemes['blush']['base'],
			),
		),
		'bronze' => array(
			'colors' => array(
				'header_textcolor' => $color_schemes['bronze']['base'],
				'link_color'       => $color_schemes['bronze']['base'],
				'button_color'     => $color_schemes['bronze']['base'],
			),
		),
		'cool' => array(
			'colors' => array(
				'header_textcolor' => $color_schemes['cool']['base'],
				'link_color'       => $color_schemes['cool']['base'],
				'button_color'     => $color_schemes['cool']['base'],
			),
		),
		'dark' => array(
			'colors' => array(
				// Text
				'tagline_text_color'               => '#999999',
				'menu_text_color'                  => '#ffffff',
				'heading_text_color'               => '#ffffff',
				'primary_text_color'               => '#e5e5e5',
				'secondary_text_color'             => '#c1c1c1',
				'footer_widget_heading_text_color' => '#ffffff',
				'footer_widget_text_color'         => '#ffffff',
				'footer_menu_text_color'           => '#ffffff',
				// Backgrounds
				'background_color'               => '#222222',
				'hero_background_color'          => '#282828',
				'menu_background_color'          => '#333333',
				'footer_widget_background_color' => '#282828',
				'footer_background_color'        => '#222222',
			),
		),
		'iguana' => array(
			'colors' => array(
				'header_textcolor' => $color_schemes['iguana']['base'],
				'link_color'       => $color_schemes['iguana']['base'],
				'button_color'     => $color_schemes['iguana']['base'],
			),
		),
		'muted' => array(
			'colors' => array(
				// Text
				'header_textcolor'       => '#5a6175',
				'menu_text_color'        => '#5a6175',
				'heading_text_color'     => '#4f5875',
				'primary_text_color'     => '#4f5875',
				'secondary_text_color'   => '#888c99',
				'footer_menu_text_color' => $color_schemes['muted']['base'],
				'footer_text_color'      => '#4f5875',
				// Links & Buttons
				'link_color'   => $color_schemes['muted']['base'],
				'button_color' => $color_schemes['muted']['base'],
				// Backgrounds
				'background_color'               => '#ffffff',
				'hero_background_color'          => '#5a6175',
				'menu_background_color'          => '#ffffff',
				'footer_widget_background_color' => '#b6b9c5',
				'footer_background_color'        => '#ffffff',
			),
		),
		'plum' => array(
			'colors' => array(
				'header_textcolor'               => $color_schemes['plum']['base'],
				'link_color'                     => $color_schemes['plum']['base'],
				'button_color'                   => $color_schemes['plum']['base'],
				'footer_widget_background_color' => '#191919',
			),
		),
		'rose' => array(
			'colors' => array(
				'header_textcolor' => $color_schemes['rose']['base'],
				'link_color'       => $color_schemes['rose']['base'],
				'button_color'     => $color_schemes['rose']['base'],
			),
		),
		'tangerine' => array(
			'colors' => array(
				'header_textcolor' => $color_schemes['tangerine']['base'],
				'link_color'       => $color_schemes['tangerine']['base'],
				'button_color'     => $color_schemes['tangerine']['base'],
			),
		),
		'turquoise' => array(
			'colors' => array(
				'header_textcolor' => $color_schemes['turquoise']['base'],
				'link_color'       => $color_schemes['turquoise']['base'],
				'button_color'     => $color_schemes['turquoise']['base'],
			),
		),
	);

	return primer_array_replace_recursive( $color_schemes, $overrides );

	$overrides = array(
		'blush' => array(
			'colors' => array(
				'header_textcolor'      => '#cc494f',
				'menu_background_color' => '#ffffff',
			),
		),
		'bronze' => array(
			'colors' => array(
				'header_textcolor'      => '#b1a18b',
				'menu_background_color' => '#ffffff',
			),
		),
		'cool' => array(
			'colors' => array(
				'header_textcolor'      => '#78c3fb',
				'menu_background_color' => '#ffffff',
			),
		),
		'dark' => array(
			'colors' => array(
				'link_color'   => '#e3ad31',
				'button_color' => '#e3ad31',
			),
		),
		'iguana' => array(
			'colors' => array(
				'header_textcolor'      => '#62bf7c',
				'menu_background_color' => '#ffffff',
			),
		),
		'muted' => array(
			'colors' => array(
				'header_textcolor'               => '#5a6175',
				'menu_text_color'                => '#5a6175',
				'background_color'               => '#ffffff',
				'menu_background_color'          => '#ffffff',
				'footer_widget_background_color' => '#d5d6e0',
				'footer_background_color'        => '#ffffff',
			),
		),
		'plum' => array(
			'colors' => array(
				'header_textcolor'      => '#5d5179',
				'menu_background_color' => '#ffffff',
			),
		),
		'rose' => array(
			'colors' => array(
				'header_textcolor'      => '#f49390',
				'menu_background_color' => '#ffffff',
			),
		),
		'tangerine' => array(
			'colors' => array(
				'header_textcolor'      => '#fc9e4f',
				'menu_background_color' => '#ffffff',
			),
		),
		'turquoise' => array(
			'colors' => array(
				'header_textcolor'      => '#48e5c2',
				'menu_background_color' => '#ffffff',
			),
		),
	);

	return primer_array_replace_recursive( $color_schemes, $overrides );

}
add_filter( 'primer_color_schemes', 'stout_color_schemes' );

/**
 * Enqueue stout hero scripts.
 *
 * @link  https://codex.wordpress.org/Function_Reference/wp_enqueue_script
 * @since 1.1.0
 */
function stout_hero_scripts() {

	$suffix = SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'stout-hero', get_stylesheet_directory_uri() . "/assets/js/stout-hero{$suffix}.js", array( 'jquery' ), PRIMER_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'stout_hero_scripts' );


function maps_hero_scripts() {
    
          wp_enqueue_script( 'maps-hero', get_stylesheet_directory_uri() . "/assets/js/maps-hero.js", array( 'jquery' ), PRIMER_VERSION, true );

		
          
    
}

add_action( 'wp_enqueue_scripts', 'maps_hero_scripts' ); 
function maps_change_scripts() {
    
          wp_enqueue_script( 'maps-change-hero', get_stylesheet_directory_uri() . "/assets/js/changeMaps.js", array( 'jquery' ), PRIMER_VERSION, true );

		
          
    
}

add_action( 'wp_enqueue_scripts', 'maps_change_scripts' ); 


add_action( 'init', 'cyb_session_start', 1 );
add_action( 'wp_logout', 'cyb_session_end' );
add_action( 'wp_login', 'cyb_session_end' );

function cyb_session_start() {
    if( ! session_id() ) {
	global $wp_session;
	 $wp_session = session_start();
	
       
    }
}

function cyb_session_end() {
    session_destroy();
}


function guias() {

global $wpdb;
$sShortAddress=$_POST['sShortAddress'];
$myId=$_POST['myId'];
$street_number=$_POST['street_number'];
$route=$_POST['route'];
$locality=$_POST['locality'];
$administrative_area_level_1=$_POST['administrative_area_level_1'];
$postal_code=$_POST['postal_code'];
$country=$_POST['country'];
$sStreetAddress=$street_number." ".$route;
$query="INSERT INTO  st_SearchedAddresses (sStreetAddress,sCity,sState,sZipCode,sCountry,sShortAddress,IDSession) VALUES ('".$sStreetAddress."','".$locality."','".$administrative_area_level_1."','".$postal_code."','".$country."','".$sShortAddress."','".$myId."')";


$insert = $wpdb->query($query);

die();

}
add_action('wp_ajax_guias', 'guias');
add_action('wp_ajax_nopriv_guias', 'guias');

function deliveryDays() {
$entityBody = file_get_contents('php://input');
$data = json_decode($entityBody, true);
global $wpdb;

$idUser=$_POST['idUser'];
$product=$_POST['product'];
$idSubscriptions=$_POST['idSubscriptions'];
$data = $_POST['delivery'];
$data = stripslashes($data);
$delivery=json_decode($data ,TRUE);

$query="SELECT * FROM st_DeliveryDays WHERE IDUser='".$idUser."' and IDOrder='".$idSubscriptions."' and IDProduct='".$product."' ";

$result = $wpdb->get_results( $query );

if($result!=""){
$query="DELETE FROM st_DeliveryDays WHERE IDUser='".$idUser."' and IDOrder='".$idSubscriptions."' and IDProduct='".$product."' ";

 $wpdb->query($query);
}

foreach($delivery as $day){

$query="INSERT INTO  st_DeliveryDays (IDUser,IDOrder,IDProduct,IDday) VALUES ('".$idUser."','".$idSubscriptions."','".$product."','".$day['value']."')";


$insert = $wpdb->query($query);

}

if($wpdb->insert_id==""){
echo "NoApproved";
}

die();

}
add_action('wp_ajax_deliveryDays', 'deliveryDays');
add_action('wp_ajax_nopriv_deliveryDays', 'deliveryDays');
 


 
function changeProduct() {
global $wpdb;
$idSubscriptions=$_POST['idSubscription'];
$plan = $_POST['plan'];
$qty=1;

 //echo "<pre>",print_r($plan),"</pre><br/>";
$subscription = wcs_get_subscription($idSubscriptions);
$subscription->remove_order_items('line_item');
$total=0;
    //add product item again
    foreach($plan as $product ) {
	$product_id=$product;
        if($qty > 0) {
            //we will need to set dynamic prices based on cusotm field
           // $price_level = get_field('coffee_price_level', $subscription->id);
            //Get the product
            $product = wc_get_product($product_id);
            
           // $product->set_price(floatval($price_level));
            $tax = ($product->get_price_including_tax())*$qty;
	    $total=$total+$product->get_price_including_tax();
            //subscription item price level
            $subscription->add_product($product, $qty, array(
                'totals' => array(
                    'subtotal'     => $product->get_price(),
                    'subtotal_tax' => $tax,
                    'total'        => $product->get_price(),
                    'tax'          => $tax,
                    'tax_data'     => array( 'subtotal' => array(1=>$tax), 'total' => array(1=>$tax) )
                )
            ));
        }
    }



$query="UPDATE wp_postmeta SET meta_value='".$total."' WHERE post_id=".$idSubscriptions." and meta_key='_order_total'  ";
$result = $wpdb->query( $query );





die();


}
add_action('wp_ajax_changeProduct', 'changeProduct');
add_action('wp_ajax_nopriv_changeProduct', 'changeProduct');


function changeNextPayment() {
global $wpdb;
$week=date('W');
$idSubscription=$_POST['idSubscription'];
$idUser = $_POST['idUser'];
$qty=1;

$subscription = wcs_get_subscription($idSubscription);
$time_Actual=$subscription->get_time('next_payment');
$date = date('Y-m-d H:i:s', $time_Actual);
$next=strtotime('next saturday', strtotime($date));

$next_date = date('Y-m-d H:i:s', $next);

 $dates = array (
           
            'next_payment' => $next_date,
            
        );
        $subscription->update_dates($dates);

$query="INSERT INTO  st_SkipWeek (IDUser,IDSubscripcion,iWeek) VALUES ('".$idUser."','".$idSubscription."','".$week."')";


$insert = $wpdb->query($query);





die();


}
add_action('wp_ajax_changeNextPayment', 'changeNextPayment');
add_action('wp_ajax_nopriv_changeNextPayment', 'changeNextPayment');


function SaveAddressUser() {
global $wpdb;

$idSubscription=$_POST['idSubscription'];
$idUser = $_POST['idUser']; 
$sReceiver = $_POST['sReceiver']; 
$sOtherInfo = $_POST['sOtherInfo'];

$query="SELECT * FROM st_AddressUser WHERE IDUser='".$idUser."' and IDSubscripcion='".$idSubscription."' ";
$result = $wpdb->get_results( $query );


if(empty($result)){
$query="INSERT INTO  st_AddressUser (IDUser,IDSubscripcion,sReceiver,sOtherInfo) VALUES ('".$idUser."','".$idSubscription."','".$sReceiver."','".$sOtherInfo."')";
$insert = $wpdb->query($query);
}else{
$query="UPDATE  st_AddressUser SET sOtherInfo='".$sOtherInfo."', sReceiver='".$sReceiver."' WHERE IDUser='".$idUser."' and IDSubscripcion='".$idSubscription."'";
$insert = $wpdb->query($query);
}




}
add_action('wp_ajax_SaveAddressUser', 'SaveAddressUser');
add_action('wp_ajax_nopriv_SaveAddressUser', 'SaveAddressUser');


function updateAddress() {
global $wpdb;

$idSubscription=$_POST['idSubscription'];
$idUser = $_POST['idUser'];
$autocomplete = $_POST['autocomplete'];

$query="SELECT * FROM st_AddressUser WHERE IDUser='".$idUser."' and IDSubscripcion='".$idSubscription."' ";
$result = $wpdb->get_results( $query );


if(empty($result)){
$query="INSERT INTO  st_AddressUser (IDUser,IDSubscripcion,sAddress) VALUES ('".$idUser."','".$idSubscription."','".$autocomplete."')";
$insert = $wpdb->query($query);
}else{
$query="UPDATE  st_AddressUser SET sAddress='".$autocomplete."' WHERE IDUser='".$idUser."' and IDSubscripcion='".$idSubscription."'";
$insert = $wpdb->query($query);
}




die();


}
add_action('wp_ajax_updateAddress', 'updateAddress');
add_action('wp_ajax_nopriv_updateAddress', 'updateAddress');








function changeFrecuency() {
global $wpdb;

$idSubscriptions = $_POST['idSubscriptions'];
$plan = $_POST['arrProducts'];
$qty=1;

//echo "<pre>",print_r($plan),"</pre><br/>";
$subscription = wcs_get_subscription($idSubscriptions);
$subscription->remove_order_items('line_item');
$total=0;
    //add product item again
    foreach($plan as $item ) {


	$product_id=$item;
        if($qty > 0) {
            //we will need to set dynamic prices based on cusotm field
           // $price_level = get_field('coffee_price_level', $subscription->id);
            //Get the product
            $product = wc_get_product($product_id);
            
            $tax = ($product->get_price_including_tax())*$qty;
	    $total=$total+$product->get_price_including_tax();
            //subscription item price level
            $subscription->add_product($product, $qty, array(
                'totals' => array(
                    'subtotal'     => $product->get_price(),
                    'subtotal_tax' => $tax,
                    'total'        => $product->get_price(),
                    'tax'          => $tax,
                    'tax_data'     => array( 'subtotal' => array(1=>$tax), 'total' => array(1=>$tax) )
                )
            ));
        }
    }



$query="UPDATE wp_postmeta SET meta_value='".$total."' WHERE post_id=".$idSubscriptions." and meta_key='_order_total'  ";
$result = $wpdb->query( $query );




die();


}
add_action('wp_ajax_changeFrecuency', 'changeFrecuency');
add_action('wp_ajax_nopriv_changeFrecuency', 'changeFrecuency');


function emptyCart() {
global $wpdb;
global $woocommerce;
$woocommerce->cart->empty_cart();

 return $cart_item_data;
die();


}
add_action('wp_ajax_emptyCart', 'emptyCart');
add_action('wp_ajax_nopriv_emptyCart', 'emptyCart');



//Shortcode para contenido exclusivo
function insertphp ($atts) { include ( TEMPLATEPATH.'/hugtaste/configuration.php'); }
add_shortcode ('woo_delivery', 'insertphp');


add_shortcode( 'dataUser', 'subscriber' );
function subscriber( $atts, $content = null ) {
    
if (is_user_logged_in()){
        $html="";
                $cu = wp_get_current_user();
            
		$subscriptions = wcs_get_users_subscriptions($cu->ID);
		
		
		foreach($subscriptions as $subs){
			$subscriptions=$subs;
		}
		  
	$html .= "<input type='hidden' id='idUser' name='idUser'  value='".$cu->ID."'>";
	$html .= "<input type='hidden' id='idSubscriptions' name='idSubscriptions'  value='".$subscriptions->id."'>";        
	$html .= "<input type='hidden' id='statusSubscriptions' name='statusSubscriptions'  value='".$subscriptions->status."'>";
		
        return $html;
        }

}

//Shortcode para contenido exclusivo
function pricingphp ($atts) { include ( TEMPLATEPATH.'/hugtaste/pricing.php'); }
add_shortcode ('woo_pricing', 'pricingphp');

function listConfiguration ($atts) { include ( TEMPLATEPATH.'/hugtaste/listConfiguration.php'); }
add_shortcode ('woo_listconfiguration', 'listConfiguration');
//Shortcode para contenido exclusivo
function updateNextPayment ($atts) { include ( TEMPLATEPATH.'/hugtaste/changeDate.php'); }
add_shortcode ('woo_date', 'updateNextPayment');

function changeAddressUser ($atts) { include ( TEMPLATEPATH.'/hugtaste/changeAddressUser.php'); }
add_shortcode ('woo_changeAddress', 'changeAddressUser');

function availability ($atts) { include ( TEMPLATEPATH.'/hugtaste/availability.php'); }
add_shortcode ('woo_availability', 'availability');

function so_30165014_price_shortcode_callback( $atts ) {
    $atts = shortcode_atts( array(
        'id' => null,
        'name' => null,
    ), $atts, 'bartag' );

    $html = '';

   if( intval( $atts['id'] ) > 0 && function_exists( 'wc_get_product' ) ){
         $_product = wc_get_product( $atts['id'] );
         $html = "<input type='hidden' id='".$atts['name']."' name='".$atts['name']."'  value='" . $_product->get_price()."'>";
    }
    return $html;
}
add_shortcode( 'woocommerce_price', 'so_30165014_price_shortcode_callback' );

/*add_filter( 'wp_nav_menu_items', 'add_logout_link', 10, 2);
function add_logout_link( $items, $args )
{
    if($args->theme_location == 'site_navigation')
    {
        if(is_user_logged_in())
        {
            $items .= '<li><a href="'. wp_logout_url() .'">Log Out</a></li>';
        } else {
            $items .= '<li><a href="'. wp_login_url() .'">Log In</a></li>';
        }
    }

    return $items;

SELECT * FROM `wp_598da06b1a_posts` WHERE `post_status`="publish" and `post_type`="page" 
}*/
/*
add_filter( 'wp_nav_menu_items', 'new_nav_menu_items', 10, 2 );
function new_nav_menu_items($items, $args) {
//echo "<pre>",print_r($args),"</pre>";die();

    if( $args->menu->name == 'molina' ){
	//echo "joselyn";

$root = 0;
	 $pages = get_pages(); 

	  foreach ( $pages as $item=>$page ) {
		 
		if($page->post_type=="page" and $page->post_status="publish" and $page->post_parent==0){
			
		$parent_item = wp_update_nav_menu_item($args->menu->term_id, 0, array(
		    'menu-item-title' =>  __($page->post_title),
		    'menu-item-url' =>  $page->guid , 
		    'menu-item-status' => 'publish',)
		);
		
		unset($pages[$item]);
		
		  foreach ( $pages as $item2=>$page2 ) {
			if($page2->post_type=="page" and $page2->post_status="publish" and $page2->post_parent==$page->ID){
			$parent_item2 = wp_update_nav_menu_item($args->menu->term_id, 0, array(
			    'menu-item-title' =>  __($page2->post_title),
			    'menu-item-url' =>  $page2->guid, 
			    'menu-item-status' => 'publish', 
			    'menu-item-parent-id' => $parent_item)
			);
			 unset($pages[$item]);

			foreach ( $pages as $item3=>$page3 ) {
				if($page3->post_type=="page" and $page3->post_status="publish" and $page3->post_parent==$page2->ID){
				$parent_item3 = wp_update_nav_menu_item($args->menu->term_id, 0, array(
				    'menu-item-title' =>  __($page3->post_title),
				    'menu-item-url' =>  $page3->guid, 
				    'menu-item-status' => 'publish', 
				    'menu-item-parent-id' => $parent_item2)
				);
			
				unset($pages[$item]);

			foreach ( $pages as $item4=>$page4 ) {
				if($page4->post_type=="page" and $page4->post_status="publish" and $page4->post_parent==$page3->ID){
				$parent_item4 = wp_update_nav_menu_item($args->menu->term_id, 0, array(
				    'menu-item-title' =>  __($page4->post_title),
				    'menu-item-url' =>  $page4->guid, 
				    'menu-item-status' => 'publish', 
				    'menu-item-parent-id' => $parent_item3)
				);
			

			
				}
			}
			
				}
			}


			}
		}
	 }

}


		

}
//return $items;
}
*/

<?php
/**
 *
 *
 * @author Sergey Burkov, http://www.wppizzatime.com
 * @copyright 2016
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

function pizzatime_upload_file($name, $index) {
	$uploadedfile = array(
		'name'     => $_FILES[$name]['name'][$index],
		'type'     => $_FILES[$name]['type'][$index],
		'tmp_name' => $_FILES[$name]['tmp_name'][$index],
		'error'    => $_FILES[$name]['error'][$index],
		'size'     => $_FILES[$name]['size'][$index]
	);

	$upload_overrides = array( 'test_form' => false );
	$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
	if (isset( $movefile['error'])) echo $movefile['error'];
	return $movefile;
}

add_action( 'admin_menu', 'register_pizzatime_menu_page' );
function register_pizzatime_menu_page() {
	add_menu_page( 'PizzaTime', 'PizzaTime', 'manage_options', 'pizzatime', 'register_pizzatime_menu_page_callback' );
}

function register_pizzatime_menu_page_callback() {
	global $wpdb;
	if ( $_GET['page'] != 'pizzatime' ) return false;
	if ( !current_user_can('administrator') ) return false;
	if ( isset( $_POST['action'] ) && $_POST['action']=='remove_size' && check_admin_referer( 'pizzatime-delete-ingredient_' )) {
		$wpdb->delete( $wpdb->prefix."pizzatime_sizes", array('id'=>(int)$_POST['size_id']) );	
	}

	if ( isset( $_POST['action'] ) && $_POST['action']=='remove_crust' && check_admin_referer( 'pizzatime-delete-ingredient_' )) {
		$wpdb->delete( $wpdb->prefix."pizzatime_crusts", array('id'=>(int)$_POST['crust_id']) );
	}

	if ( isset( $_POST['action'] ) && $_POST['action']=='remove_sauce' && check_admin_referer( 'pizzatime-delete-ingredient_' )) {
		$wpdb->delete( $wpdb->prefix."pizzatime_sauces", array('id'=>(int)$_POST['sauce_id']) );	
	}

	if ( isset( $_POST['action'] ) && $_POST['action']=='remove_cheese' && check_admin_referer( 'pizzatime-delete-ingredient_' )) {
		$wpdb->delete( $wpdb->prefix."pizzatime_cheese", array('id'=>(int)$_POST['cheese_id']) );	
	}

	if ( isset( $_POST['action'] ) && $_POST['action']=='remove_meat' && check_admin_referer( 'pizzatime-delete-ingredient_' )) {
		$wpdb->delete( $wpdb->prefix."pizzatime_meats", array('id'=>(int)$_POST['meat_id']) );	
	}

	if ( isset( $_POST['action'] ) && $_POST['action']=='remove_topping' && check_admin_referer( 'pizzatime-delete-ingredient_' )) {
		$wpdb->delete( $wpdb->prefix."pizzatime_toppings", array('id'=>(int)$_POST['topping_id']) );
	}

	if ( isset( $_POST['action'] ) && $_POST['action']=='remove_dressing' && check_admin_referer( 'pizzatime-delete-ingredient_' )) {
		$wpdb->delete( $wpdb->prefix."pizzatime_dressings", array('id'=>(int)$_POST['dressing_id']) );
	}




	if ( isset( $_POST['pizzatime_size_name'] ) && count( $_POST['pizzatime_size_name'] )>0 && check_admin_referer( 'pizzatime-save-settings_' )) {
		foreach ( $_POST['pizzatime_size_name'] as $size_id => $size ) {
		        if ($_POST['pizzatime_size_name'][$size_id]=='') continue;
			$sizes[$size_id]['status']=(int) $_POST['pizzatime_size_status'][$size_id] ;
			$sizes[$size_id]['name']=sanitize_text_field( $_POST['pizzatime_size_name'][$size_id] );
			$sizes[$size_id]['description']=sanitize_text_field( $_POST['pizzatime_size_description'][$size_id] );
			$sizes[$size_id]['price']= (float)pizzatime_fix_price($_POST['pizzatime_size_price'][$size_id]) ;
			$sizes[$size_id]['price_multiplier']= (float)$_POST['pizzatime_size_price_multiplier'][$size_id] ;
			$sizes[$size_id]['sort_order']=(int)$_POST['pizzatime_size_sort_order'][$size_id] ;
			$sizes[$size_id]['photo']=sanitize_text_field(str_replace('http:','',$_POST['pizzatime_size_photo'][$size_id]));
			$sizes[$size_id]['id']=(int)$size_id ;

			if (isset($_FILES['pizzatime_size_photo_upload']['tmp_name'][$size_id]) && strlen($_FILES['pizzatime_size_photo_upload']['tmp_name'][$size_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_size_photo_upload', $size_id);
				$sizes[$size_id]['photo']=str_replace('http:','',$uploaded_file['url']);
			}
		}



		foreach ($sizes as $size) {
			pizzatime_update_option( 'pizzatime_sizes', $size );
		}
	}

	if ( isset( $_POST['pizzatime_crust_name'] ) && count( $_POST['pizzatime_crust_name'] )>0 && check_admin_referer( 'pizzatime-save-settings_' )) {

		foreach ( $_POST['pizzatime_crust_name'] as $crust_id => $crust ) {
		        if ($_POST['pizzatime_crust_name'][$crust_id]=='') continue;
			$crusts[$crust_id]['name']=sanitize_text_field( $_POST['pizzatime_crust_name'][$crust_id] );
			$crusts[$crust_id]['description'] = sanitize_text_field($_POST['pizzatime_crust_description'][$crust_id]) ;
			$crusts[$crust_id]['price']=(float)pizzatime_fix_price($_POST['pizzatime_crust_price'][$crust_id]);
			$crusts[$crust_id]['image']=sanitize_text_field(str_replace('http:','',$_POST['pizzatime_crust_image'][$crust_id]));
			$crusts[$crust_id]['image_path'] = sanitize_text_field(realpath($_POST['pizzatime_crust_image_path'][$crust_id])) ;
			$crusts[$crust_id]['photo']=sanitize_text_field(str_replace('http:','',$_POST['pizzatime_crust_photo'][$crust_id]));
			$crusts[$crust_id]['status']=(int)$_POST['pizzatime_crust_status'][$crust_id];
			$crusts[$crust_id]['id']=(int)$crust_id ;

			if (isset($_FILES['pizzatime_crust_image_upload']['tmp_name'][$crust_id]) && strlen($_FILES['pizzatime_crust_image_upload']['tmp_name'][$crust_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_crust_image_upload', $crust_id);
				$crusts[$crust_id]['image']=str_replace('http:','',$uploaded_file['url']);
				$crusts[$crust_id]['image_path']=$uploaded_file['file'];
			}
			if (isset($_FILES['pizzatime_crust_photo_upload']['tmp_name'][$crust_id]) && strlen($_FILES['pizzatime_crust_photo_upload']['tmp_name'][$crust_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_crust_photo_upload', $crust_id);
				$crusts[$crust_id]['photo']=str_replace('http:','',$uploaded_file['url']);
			}

		}

		foreach ($crusts as $crust) {
			pizzatime_update_option( 'pizzatime_crusts', $crust );
		}
	}
	if ( isset( $_POST['pizzatime_sauce_name'] ) && count( $_POST['pizzatime_sauce_name'] )>0 && check_admin_referer( 'pizzatime-save-settings_' )) {

		foreach ( $_POST['pizzatime_sauce_name'] as $sauce_id => $sauce ) {
		        if ($_POST['pizzatime_sauce_name'][$sauce_id]=='') continue;
			$sauces[$sauce_id]['name']=sanitize_text_field( $_POST['pizzatime_sauce_name'][$sauce_id] );
			$sauces[$sauce_id]['description'] = sanitize_text_field($_POST['pizzatime_sauce_description'][$sauce_id]) ;
			$sauces[$sauce_id]['price']=(float)pizzatime_fix_price($_POST['pizzatime_sauce_price'][$sauce_id]);
			$sauces[$sauce_id]['price_extra']=(float)pizzatime_fix_price($_POST['pizzatime_sauce_price_extra'][$sauce_id]) ;
			$sauces[$sauce_id]['has_extra']=(int)$_POST['pizzatime_sauce_has_extra'][$sauce_id] ;
			$sauces[$sauce_id]['image']=sanitize_text_field(str_replace('http:','',$_POST['pizzatime_sauce_image'][$sauce_id]));
			$sauces[$sauce_id]['image_extra']=sanitize_text_field(str_replace('http:','',$_POST['pizzatime_sauce_image_extra'][$sauce_id]));
			$sauces[$sauce_id]['image_path']=sanitize_text_field(realpath($_POST['pizzatime_sauce_image_path'][$sauce_id]));
			$sauces[$sauce_id]['image_extra_path']=sanitize_text_field($_POST['pizzatime_sauce_image_extra_path'][$sauce_id]);
			$sauces[$sauce_id]['photo']=sanitize_text_field(str_replace('http:','',$_POST['pizzatime_sauce_photo'][$sauce_id]));
			$sauces[$sauce_id]['status']=(int)$_POST['pizzatime_sauce_status'][$sauce_id];
			$sauces[$sauce_id]['sort_order']=(int)$_POST['pizzatime_sauce_sort_order'][$sauce_id] ;
			$sauces[$sauce_id]['id']=(int)$sauce_id ;

			if (isset($_FILES['pizzatime_sauce_image_upload']['tmp_name'][$sauce_id]) && strlen($_FILES['pizzatime_sauce_image_upload']['tmp_name'][$sauce_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_sauce_image_upload', $sauce_id);
				$sauces[$sauce_id]['image']=str_replace('http:','',$uploaded_file['url']);
				$sauces[$sauce_id]['image_path']=$uploaded_file['file'];
			}
			if (isset($_FILES['pizzatime_sauce_image_upload_extra']['tmp_name'][$sauce_id]) && strlen($_FILES['pizzatime_sauce_image_upload_extra']['tmp_name'][$sauce_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_sauce_image_upload_extra', $sauce_id);
				$sauces[$sauce_id]['image_extra']=str_replace('http:','',$uploaded_file['url']);
				$sauces[$sauce_id]['image_extra_path']=$uploaded_file['file'];
			}
			if (isset($_FILES['pizzatime_sauce_photo_upload']['tmp_name'][$sauce_id]) && strlen($_FILES['pizzatime_sauce_photo_upload']['tmp_name'][$sauce_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_sauce_photo_upload', $sauce_id);
				$sauces[$sauce_id]['photo']=str_replace('http:','',$uploaded_file['url']);
			}

		}

		foreach ($sauces as $sauce) {
			pizzatime_update_option( 'pizzatime_sauces', $sauce );
		}
	}

	if ( isset( $_POST['pizzatime_cheese_name'] ) && count( $_POST['pizzatime_cheese_name'] )>0 && check_admin_referer( 'pizzatime-save-settings_' )) {

		foreach ( $_POST['pizzatime_cheese_name']  as $cheese_id => $cheese ) {
		        if ($_POST['pizzatime_cheese_name'][$cheese_id]=='') continue;
			$cheeses[$cheese_id]['name']=sanitize_text_field( $_POST['pizzatime_cheese_name'][$cheese_id] );
			$cheeses[$cheese_id]['price']= (float)pizzatime_fix_price($_POST['pizzatime_cheese_price'][$cheese_id]) ;
			$cheeses[$cheese_id]['price_extra']=(float)pizzatime_fix_price($_POST['pizzatime_cheese_price_extra'][$cheese_id]) ;
			$cheeses[$cheese_id]['has_extra']=(int)$_POST['pizzatime_cheese_has_extra'][$cheese_id] ;
			$cheeses[$cheese_id]['description']=sanitize_text_field($_POST['pizzatime_cheese_description'][$cheese_id]);
			$cheeses[$cheese_id]['image']=sanitize_text_field(str_replace('http:','',$_POST['pizzatime_cheese_image'][$cheese_id]));
			$cheeses[$cheese_id]['photo']=sanitize_text_field(str_replace('http:','',$_POST['pizzatime_cheese_photo'][$cheese_id]));
			$cheeses[$cheese_id]['image_extra']=sanitize_text_field(str_replace('http:','',$_POST['pizzatime_cheese_image_extra'][$cheese_id]));
			$cheeses[$cheese_id]['image_path']=sanitize_text_field(realpath($_POST['pizzatime_cheese_image_path'][$cheese_id]));
			$cheeses[$cheese_id]['image_extra_path']=sanitize_text_field($_POST['pizzatime_cheese_image_extra_path'][$cheese_id]);
			$cheeses[$cheese_id]['status']=(int)$_POST['pizzatime_cheese_status'][$cheese_id];
			$cheeses[$cheese_id]['sort_order']= (int)$_POST['pizzatime_cheese_sort_order'][$cheese_id] ;
			$cheeses[$cheese_id]['id']=(int)$cheese_id;

			if (isset($_FILES['pizzatime_cheese_image_upload']['tmp_name'][$cheese_id]) && strlen($_FILES['pizzatime_cheese_image_upload']['tmp_name'][$cheese_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_cheese_image_upload', $cheese_id);
				$cheeses[$cheese_id]['image']=str_replace('http:','',$uploaded_file['url']);
				$cheeses[$cheese_id]['image_path']=$uploaded_file['file'];
			}
			if (isset($_FILES['pizzatime_cheese_image_upload_extra']['tmp_name'][$cheese_id]) && strlen($_FILES['pizzatime_cheese_image_upload_extra']['tmp_name'][$cheese_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_cheese_image_upload_extra', $cheese_id);
				$cheeses[$cheese_id]['image_extra']=str_replace('http:','',$uploaded_file['url']);
				$cheeses[$cheese_id]['image_extra_path']=$uploaded_file['file'];
			}
			if (isset($_FILES['pizzatime_cheese_photo_upload']['tmp_name'][$cheese_id]) && strlen($_FILES['pizzatime_cheese_photo_upload']['tmp_name'][$cheese_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_cheese_photo_upload', $cheese_id);
				$cheeses[$cheese_id]['photo']=str_replace('http:','',$uploaded_file['url']);
			}
		}

		foreach ($cheeses as $cheese) {
			pizzatime_update_option( 'pizzatime_cheeses', $cheese );
		}
	}

	if ( isset( $_POST['pizzatime_meat_name'] ) && count( $_POST['pizzatime_meat_name'] )>0 && check_admin_referer( 'pizzatime-save-settings_' )) {

		foreach ( $_POST['pizzatime_meat_name'] as $meat_id => $meat ) {
		        if ($_POST['pizzatime_meat_name'][$meat_id]=='') continue;
			$meats[$meat_id]['name']=sanitize_text_field( $_POST['pizzatime_meat_name'][$meat_id] );
			$meats[$meat_id]['price']=(float)pizzatime_fix_price($_POST['pizzatime_meat_price'][$meat_id]) ;
			$meats[$meat_id]['price_extra']=(float)pizzatime_fix_price($_POST['pizzatime_meat_price_extra'][$meat_id]) ;
			$meats[$meat_id]['has_extra']=(int)$_POST['pizzatime_meat_has_extra'][$meat_id] ;
			$meats[$meat_id]['has_left_right']=(int)$_POST['pizzatime_meat_has_left_right'][$meat_id] ;
			$meats[$meat_id]['description']=sanitize_text_field($_POST['pizzatime_meat_description'][$meat_id]);
			$meats[$meat_id]['image']=sanitize_text_field($_POST['pizzatime_meat_image'][$meat_id]);
			$meats[$meat_id]['image_extra']=sanitize_text_field($_POST['pizzatime_meat_image_extra'][$meat_id]);
			$meats[$meat_id]['image_path']=sanitize_text_field(realpath($_POST['pizzatime_meat_image_path'][$meat_id]));
			$meats[$meat_id]['image_extra_path']=sanitize_text_field($_POST['pizzatime_meat_image_extra_path'][$meat_id]);
			$meats[$meat_id]['photo']=sanitize_text_field($_POST['pizzatime_meat_photo'][$meat_id]);
			$meats[$meat_id]['layer']=(int)$_POST['pizzatime_meat_layer'][$meat_id];
			$meats[$meat_id]['is_ingredient']=(int)$_POST['pizzatime_meat_is_ingredient'][$meat_id];
			$meats[$meat_id]['status']=(int)$_POST['pizzatime_meat_status'][$meat_id];
			$meats[$meat_id]['sort_order']= (int)$_POST['pizzatime_meat_sort_order'][$meat_id];
			$meats[$meat_id]['id']=(int)$meat_id;

			if (isset($_FILES['pizzatime_meat_image_upload']['tmp_name'][$meat_id]) && strlen($_FILES['pizzatime_meat_image_upload']['tmp_name'][$meat_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_meat_image_upload', $meat_id);
				$meats[$meat_id]['image']=str_replace('http:','',$uploaded_file['url']);
				$meats[$meat_id]['image_path']=$uploaded_file['file'];
			}
			if (isset($_FILES['pizzatime_meat_image_upload_extra']['tmp_name'][$meat_id]) && strlen($_FILES['pizzatime_meat_image_upload_extra']['tmp_name'][$meat_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_meat_image_upload_extra', $meat_id);
				$meats[$meat_id]['image_extra']=str_replace('http:','',$uploaded_file['url']);
				$meats[$meat_id]['image_extra_path']=$uploaded_file['file'];

			}
			if (isset($_FILES['pizzatime_meat_photo_upload']['tmp_name'][$meat_id]) && strlen($_FILES['pizzatime_meat_photo_upload']['tmp_name'][$meat_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_meat_photo_upload', $meat_id);
				$meats[$meat_id]['photo']=str_replace('http:','',$uploaded_file['url']);
			}

		}

		foreach ($meats as $meat) {
			pizzatime_update_option( 'pizzatime_meats', $meat );

		}
	}

	if ( isset( $_POST['pizzatime_topping_name'] ) && count( $_POST['pizzatime_topping_name'] )>0 && check_admin_referer( 'pizzatime-save-settings_' )) {

		foreach ( $_POST['pizzatime_topping_name'] as $topping_id => $topping ) {
		        if ($_POST['pizzatime_topping_name'][$topping_id]=='') continue;
			$toppings[$topping_id]['name']=sanitize_text_field( $_POST['pizzatime_topping_name'][$topping_id] );
			$toppings[$topping_id]['price']=(float)pizzatime_fix_price($_POST['pizzatime_topping_price'][$topping_id]) ;
			$toppings[$topping_id]['price_extra']=(float)pizzatime_fix_price($_POST['pizzatime_topping_price_extra'][$topping_id]) ;
			$toppings[$topping_id]['has_extra']=(int)$_POST['pizzatime_topping_has_extra'][$topping_id];
			$toppings[$topping_id]['has_left_right']=(int)$_POST['pizzatime_topping_has_left_right'][$topping_id];
			$toppings[$topping_id]['description']=sanitize_text_field($_POST['pizzatime_topping_description'][$topping_id]);
			$toppings[$topping_id]['image']=sanitize_text_field($_POST['pizzatime_topping_image'][$topping_id]);
			$toppings[$topping_id]['image_extra']=sanitize_text_field($_POST['pizzatime_topping_image_extra'][$topping_id]);
			$toppings[$topping_id]['image_path']=sanitize_text_field(realpath($_POST['pizzatime_topping_image_path'][$topping_id]));
			$toppings[$topping_id]['image_extra_path']=sanitize_text_field($_POST['pizzatime_topping_image_extra_path'][$topping_id]);
			$toppings[$topping_id]['photo']=sanitize_text_field($_POST['pizzatime_topping_photo'][$topping_id]);
			$toppings[$topping_id]['layer']=(int)$_POST['pizzatime_topping_layer'][$topping_id];
			$toppings[$topping_id]['is_ingredient']=(int)$_POST['pizzatime_topping_is_ingredient'][$topping_id];
			$toppings[$topping_id]['status']=(int)$_POST['pizzatime_topping_status'][$topping_id];
			$toppings[$topping_id]['sort_order']=(int)$_POST['pizzatime_topping_sort_order'][$topping_id];
			$toppings[$topping_id]['id']=(int)$topping_id;

			if (isset($_FILES['pizzatime_topping_image_upload']['tmp_name'][$topping_id]) && strlen($_FILES['pizzatime_topping_image_upload']['tmp_name'][$topping_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_topping_image_upload', $topping_id);
				$toppings[$topping_id]['image']=str_replace('http:','',$uploaded_file['url']);
				$toppings[$topping_id]['image_path']=$uploaded_file['file'];
			}
			if (isset($_FILES['pizzatime_topping_image_upload_extra']['tmp_name'][$topping_id]) && strlen($_FILES['pizzatime_topping_image_upload_extra']['tmp_name'][$topping_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_topping_image_upload_extra', $topping_id);
				$toppings[$topping_id]['image_extra']=str_replace('http:','',$uploaded_file['url']);
				$toppings[$topping_id]['image_extra_path']=$uploaded_file['file'];
			}
			if (isset($_FILES['pizzatime_topping_photo_upload']['tmp_name'][$topping_id]) && strlen($_FILES['pizzatime_topping_photo_upload']['tmp_name'][$topping_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_topping_photo_upload', $topping_id);
				$toppings[$topping_id]['photo']=str_replace('http:','',$uploaded_file['url']);
			}


		}
		foreach ($toppings as $topping) {
			pizzatime_update_option( 'pizzatime_toppings', $topping );
		}
	}

	if ( isset( $_POST['pizzatime_dressing_name'] ) && count( $_POST['pizzatime_dressing_name'] )>0 && check_admin_referer( 'pizzatime-save-settings_' )) {

		foreach ( $_POST['pizzatime_dressing_name'] as $dressing_id => $dressing ) {
		        if ($_POST['pizzatime_dressing_name'][$dressing_id]=='') continue;
			$dressings[$dressing_id]['name']=sanitize_text_field( $_POST['pizzatime_dressing_name'][$dressing_id] );
			$dressings[$dressing_id]['price']=(float)pizzatime_fix_price($_POST['pizzatime_dressing_price'][$dressing_id]) ;
			$dressings[$dressing_id]['price_extra']=(float)pizzatime_fix_price($_POST['pizzatime_dressing_price_extra'][$dressing_id]) ;
			$dressings[$dressing_id]['has_extra']=(int)$_POST['pizzatime_dressing_has_extra'][$dressing_id];
			$dressings[$dressing_id]['has_left_right']=(int)$_POST['pizzatime_dressing_has_left_right'][$dressing_id];
			$dressings[$dressing_id]['description']=sanitize_text_field($_POST['pizzatime_dressing_description'][$dressing_id]);
			$dressings[$dressing_id]['image']=sanitize_text_field($_POST['pizzatime_dressing_image'][$dressing_id]);
			$dressings[$dressing_id]['image_extra']=sanitize_text_field($_POST['pizzatime_dressing_image_extra'][$dressing_id]);
			$dressings[$dressing_id]['image_path']=sanitize_text_field(realpath($_POST['pizzatime_dressing_image_path'][$dressing_id]));
			$dressings[$dressing_id]['image_extra_path']=sanitize_text_field($_POST['pizzatime_dressing_image_extra_path'][$dressing_id]);
			$dressings[$dressing_id]['photo']=sanitize_text_field($_POST['pizzatime_dressing_photo'][$dressing_id]);
			$dressings[$dressing_id]['layer']=(int)$_POST['pizzatime_dressing_layer'][$dressing_id];
			$dressings[$dressing_id]['is_ingredient']=(int)$_POST['pizzatime_dressing_is_ingredient'][$dressing_id];
			$dressings[$dressing_id]['status']=(int)$_POST['pizzatime_dressing_status'][$dressing_id];
			$dressings[$dressing_id]['sort_order']=(int)$_POST['pizzatime_dressing_sort_order'][$dressing_id];
			$dressings[$dressing_id]['id']=(int)$dressing_id;

			if (isset($_FILES['pizzatime_dressing_image_upload']['tmp_name'][$dressing_id]) && strlen($_FILES['pizzatime_dressing_image_upload']['tmp_name'][$dressing_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_dressing_image_upload', $dressing_id);
				$dressings[$dressing_id]['image']=str_replace('http:','',$uploaded_file['url']);
			}
			if (isset($_FILES['pizzatime_dressing_image_upload_extra']['tmp_name'][$dressing_id]) && strlen($_FILES['pizzatime_dressing_image_upload_extra']['tmp_name'][$dressing_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_dressing_image_upload_extra', $dressing_id);
				$dressings[$dressing_id]['image_extra']=str_replace('http:','',$uploaded_file['url']);
			}
			if (isset($_FILES['pizzatime_dressing_photo_upload_extra']['tmp_name'][$dressing_id]) && strlen($_FILES['pizzatime_dressing_photo_upload_extra']['tmp_name'][$dressing_id])>0) {
				$uploaded_file = pizzatime_upload_file('pizzatime_dressing_photo_upload_extra', $dressing_id);
				$dressings[$dressing_id]['photo_extra']=str_replace('http:','',$uploaded_file['url']);
			}
		}
		foreach ($dressings as $dressing) {
			pizzatime_update_option( 'pizzatime_dressings', $dressing );
		}
	}




	if ( isset( $_POST['pizzatime_settings'] ) && count( $_POST['pizzatime_settings'] ) && check_admin_referer( 'pizzatime-save-settings_' )) {
		$_POST['pizzatime_settings']['price_multiplier_sections']=implode(',', $_POST['pizzatime_settings']['price_multiplier_sections']);
		update_option( 'pizzatime_settings', array_map('sanitize_text_field', $_POST['pizzatime_settings']) );
	}


	if ( isset( $_POST['pizzatime_size_default'] ) && strlen( $_POST['pizzatime_size_default'] ) && check_admin_referer( 'pizzatime-save-settings_' )) {
		update_option( 'pizzatime_size_default', (int)$_POST['pizzatime_size_default'] );
	}

	if ( isset( $_POST['pizzatime_crust_default'] ) && strlen( $_POST['pizzatime_crust_default'] ) && check_admin_referer( 'pizzatime-save-settings_' )) {
		update_option( 'pizzatime_crust_default', (int)$_POST['pizzatime_crust_default'] );
	}

	if ( isset( $_POST['pizzatime_sauce_default'] ) && strlen( $_POST['pizzatime_sauce_default'] ) && check_admin_referer( 'pizzatime-save-settings_' )) {
		update_option( 'pizzatime_sauce_default', (int)$_POST['pizzatime_sauce_default'] );
	}

	if ( isset( $_POST['pizzatime_cheese_default'] ) && strlen( $_POST['pizzatime_cheese_default'] ) && check_admin_referer( 'pizzatime-save-settings_' )) {
		update_option( 'pizzatime_cheese_default', (int)$_POST['pizzatime_cheese_default'] );
	}

	if ( isset( $_POST['pizzatime_sauce_required'] ) && strlen( $_POST['pizzatime_sauce_required'] ) && check_admin_referer( 'pizzatime-save-settings_' )) {
		update_option( 'pizzatime_sauce_required', (int)$_POST['pizzatime_sauce_required'] );
	}

	if ( isset( $_POST['pizzatime_cheese_required'] ) && strlen( $_POST['pizzatime_cheese_required'] ) && check_admin_referer( 'pizzatime-save-settings_' )) {
		update_option( 'pizzatime_cheese_required', (int)$_POST['pizzatime_cheese_required'] );
	}
	if ( isset( $_POST['meats_free_ingredients'] ) && strlen( $_POST['meats_free_ingredients'] ) && check_admin_referer( 'pizzatime-save-settings_' )) {
		update_option( 'meats_free_ingredients', (int)$_POST['meats_free_ingredients'] );
	}
	if ( isset( $_POST['toppings_free_ingredients'] ) && strlen( $_POST['toppings_free_ingredients'] ) && check_admin_referer( 'pizzatime-save-settings_' )) {
		update_option( 'toppings_free_ingredients', (int)$_POST['toppings_free_ingredients'] );
	}
	if ( isset( $_POST['dressings_free_ingredients'] ) && strlen( $_POST['dressings_free_ingredients'] ) && check_admin_referer( 'pizzatime-save-settings_' )) {
		update_option( 'dressings_free_ingredients', (int)$_POST['dressings_free_ingredients'] );
	}


	$sizes=pizzatime_get_option( 'pizzatime_sizes' );
	$crusts=pizzatime_get_option( 'pizzatime_crusts' );
	$sauces=pizzatime_get_option( 'pizzatime_sauces' );
	$cheeses=pizzatime_get_option( 'pizzatime_cheeses' );
	$meats=pizzatime_get_option( 'pizzatime_meats' );


	$toppings=pizzatime_get_option( 'pizzatime_toppings' );
	$dressings=pizzatime_get_option( 'pizzatime_dressings' );
	$settings=pizzatime_get_option( 'pizzatime_settings' );
	//$others=pizzatime_get_option( 'pizzatime_others' );


	$pizzatime_size_default=get_option( 'pizzatime_size_default' );
	$pizzatime_crust_default=get_option( 'pizzatime_crust_default' );
	$pizzatime_sauce_default=get_option( 'pizzatime_sauce_default' );
	$pizzatime_cheese_default=get_option( 'pizzatime_cheese_default' );
	$pizzatime_sauce_required=get_option( 'pizzatime_sauce_required' );
	$pizzatime_cheese_required=get_option( 'pizzatime_cheese_required' );

	add_thickbox(); 
	$pm_sections=array('Crusts', 'Sauces', 'Cheeses', 'Meats', 'Toppings', 'Dressings');


?>
<div class="wrap pizzatime">

	<h2><?php _e( 'PizzaTime settings', 'pizzatime' );?></h2>
	<div id="pizzatime_tabs">


		<ul>
			<li><a href="#pizzatime_tabs-0"><?php _e( 'Settings', 'pizzatime' );?></a></li>
			<li><a href="#pizzatime_tabs-1"><?php _e( 'Sizes', 'pizzatime' );?></a></li>
			<li><a href="#pizzatime_tabs-2"><?php _e( 'Crusts', 'pizzatime' );?></a></li>
			<li><a href="#pizzatime_tabs-3"><?php _e( 'Sauces', 'pizzatime' );?></a></li>
			<li><a href="#pizzatime_tabs-4"><?php _e( 'Cheeses', 'pizzatime' );?></a></li>
			<li><a href="#pizzatime_tabs-5"><?php _e( 'Meats', 'pizzatime' );?></a></li>
			<li><a href="#pizzatime_tabs-6"><?php _e( 'Toppings', 'pizzatime' );?></a></li>
			<li><a href="#pizzatime_tabs-7"><?php _e( 'Dressings', 'pizzatime' );?></a></li>
			<li><a href="#pizzatime_tabs-9"><?php _e( 'Pizza presets', 'pizzatime' );?></a></li>
			<li><a href="#pizzatime_tabs-10"><?php _e( 'Custom sections', 'pizzatime' );?></a></li>



		</ul>
		<div id="pizzatime_tabs-0">
			<form method="post" action="admin.php?page=pizzatime#pizzatime_tabs-0">
			<?php    wp_nonce_field( 'pizzatime-save-settings_' ); ?>
				<hr>
				<p><b><?php _e( 'Settings', 'pizzatime' );?></b></p>
				<p><?php _e( 'In  <a target="_blank" href="https://youtu.be/p4oN8wmmEPE">this video</a> you can see how to set up a PizzaTime product.', 'pizzatime' );?></p>
				<table>
					<tr>
						<td><?php _e( 'Color scheme', 'pizzatime' );?></td>
						<td>
							<select name="pizzatime_settings[color_scheme]">
								<option <?php if ( $settings['color_scheme']=='default' ) echo "selected";?> value="default">Default</option>
								<option disabled value="darkness">Darkness</option>
								<option disabled value="redmond">Redmond</option>
								<option disabled value="blitzer">Blitzer</option>
								<option disabled value="lefrog">Le frog</option>
							</select>
							<?php _e('Buy <a href="https://secure.avangate.com/order/checkout.php?PRODS=4701306&QTY=1&CART=1&CARD=1">PRO</a> to unlock!');?>

						</td>
					</tr>
					<tr>
						<td><?php _e( 'Maximum number of ingredients', 'pizzatime' );?></td>
						<td>
							<input size="3" type="text" name="pizzatime_settings[maximum_ingredients]" value="<?php echo $settings['maximum_ingredients'];?>">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'Maximum number of ingredients that can be selected (meats, toppings). Empty field - no limit.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<td><?php _e( 'Price multiplier sections', 'pizzatime' ); ?></td>
						<td>
							<select autocomplete="off" name="pizzatime_settings[price_multiplier_sections][]" multiple="multiple" class="sumoselect">
								<?php 

									if (count($pm_sections)) {
										foreach ($pm_sections as $pm_section) {
											if (in_array($pm_section, explode(',',$settings['price_multiplier_sections']))) $selected="selected"; else $selected="";
											$name = $pm_section;

											echo '<option '.$selected.' value="'.$pm_section.'">'.__($name, 'pizzatime');
										}
									}
								?>
							</select>
							<img class="tooltip" data-title="<?php htmlentities(_e( 'Sections where the price multiplier should work. The multiplier value is set on the sizes tab.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr>
						<td><?php _e( 'Adjust pizza position on scroll', 'pizzatime' );?></td>
						<td>
							<select name="pizzatime_settings[adjust_position]">
								<option <?php if ( $settings['adjust_position']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $settings['adjust_position']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
					<tr>
						<td><?php _e( 'Show reviews/description section', 'pizzatime' );?></td>
						<td>
							<select name="pizzatime_settings[show_reviews]">
								<option <?php if ( $settings['show_reviews']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $settings['show_reviews']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
					<tr>
						<td><?php _e( 'Show sizes', 'pizzatime' );?></td>
						<td>
							<select name="pizzatime_settings[show_sizes]">
								<option <?php if ( $settings['show_sizes']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $settings['show_sizes']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php _e( 'Show crusts', 'pizzatime' );?></td>
						<td>
							<select name="pizzatime_settings[show_crusts]">
								<option <?php if ( $settings['show_crusts']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $settings['show_crusts']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
					<tr>
						<td><?php _e( 'Show sauces', 'pizzatime' );?></td>
						<td>
							<select name="pizzatime_settings[show_sauces]">
								<option <?php if ( $settings['show_sauces']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $settings['show_sauces']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
					<tr>
						<td><?php _e( 'Show cheeses', 'pizzatime' );?></td>
						<td>
							<select name="pizzatime_settings[show_cheeses]">
								<option <?php if ( $settings['show_cheeses']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $settings['show_cheeses']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
					<tr>
						<td><?php _e( 'Show meats', 'pizzatime' );?></td>
						<td>
							<select name="pizzatime_settings[show_meats]">
								<option <?php if ( $settings['show_meats']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $settings['show_meats']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
					<tr>
						<td><?php _e( 'Show toppings', 'pizzatime' );?></td>
						<td>
							<select name="pizzatime_settings[show_toppings]">
								<option <?php if ( $settings['show_toppings']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $settings['show_toppings']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
					<tr>
						<td><?php _e( 'Show dressings', 'pizzatime' );?></td>
						<td>
							<select name="pizzatime_settings[show_dressings]">
								<option <?php if ( $settings['show_dressings']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $settings['show_dressings']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
					<tr>
						<td><?php _e( 'Load Everywhere', 'pizzatime' );?></td>
						<td>
							<input type="hidden" name="pizzatime_settings[load_everywhere]" value="0">
							<input type="checkbox" name="pizzatime_settings[load_everywhere]" <?php if ($settings['load_everywhere']=='on') echo 'checked';?>>
							<img class="tooltip" data-title="<?php htmlentities(_e( 'Enable if you need to load PizzaTime css and js files on all pages of the site. This is needed for product_page shortcode.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
				</table>


				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'pizzatime' ) ?>" />
				</p>
			</form>
		</div>
		<div id="pizzatime_tabs-1">
			<form method="post" action="admin.php?page=pizzatime#pizzatime_tabs-1" enctype="multipart/form-data">
			<?php    wp_nonce_field( 'pizzatime-save-settings_' ); ?>

<?php

	if ( is_array( $sizes ) && count( $sizes )>0 ) {

		$i=0;
		foreach ( $sizes as $size ) {

?>
				<div class="pizzatime-expand">
				<h3><?php echo "<b>#".$size['id']."</b> - ".esc_html($size['name']);?></h3>
				<div>
				<table class="form-table size">
					<tr>
						<td colspan="3"><hr></td>
					</tr>
					<tr>
						<td colspan="3"><span class="item_id"><?php echo "<b>ID #".$size['id']."</b>";?></span></td>
					</tr>
					<tr valign="top">
					<tr>
						<th scope="row"><?php _e( 'Enabled', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_size_status[<?php echo $size['id'];?>]">
								<option <?php if ( $size['status']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $size['status']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

							<a style="<?php if (count( $sizes )==1) echo 'display:none;';?>" class="remove_size" href="javascript:void(0);" onclick="pizzatimeRemoveSize(<?php echo $size['id'];?>);return false;">
								<img alt="<?php _e( 'Remove size', 'pizzatime' );?>" title="<?php _e( 'Remove size', 'pizzatime' );?>" src="<?php echo plugins_url( 'pizzatime/images/remove.png' ); ?>">
							</a>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Default', 'pizzatime' );?></th>
						<td>
							<input autocomplete="off" type="checkbox" name="pizzatime_size_default" class="pizzatime-default" value="<?php echo $size['id']; ?>" <?php if ($pizzatime_size_default==$size['id']) echo 'checked';?>>
						</td>
					</tr>

						<th scope="row">
							<?php _e( 'Size', 'pizzatime' ); ?>
						</th>
						<td>
							<input type="text" name="pizzatime_size_name[<?php echo $size['id'];?>]" value="<?php echo esc_attr($size['name']);?>" />&nbsp;

						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Description', 'pizzatime' ); ?></th>
						<td>
							<textarea name="pizzatime_size_description[<?php echo $size['id'];?>]"><?php echo esc_textarea($size['description']);?></textarea>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Photo', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $size['photo'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($size['photo']);?>"></a>
							<input type="text" name="pizzatime_size_photo[<?php echo $size['id'];?>]" value="<?php echo esc_url($size['photo']);?>" />
							<input type="file" name="pizzatime_size_photo_upload[<?php echo $size['id'];?>]" accept="image/*">
						</td>

					</tr>



					<tr valign="top">
						<th scope="row"><?php _e( 'Price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" name="pizzatime_size_price[<?php echo $size['id'];?>]" value="<?php echo $size['price'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 

						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Ingredient price multiplier', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" name="pizzatime_size_price_multiplier[<?php echo $size['id'];?>]" value="<?php echo $size['price_multiplier'];?>" />&#10005;
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Sort order', 'pizzatime' );?></th>
						<td>
							<input size="3" type="text" name="pizzatime_size_sort_order[<?php echo $size['id'];?>]" value="<?php echo $size['sort_order'];?>" />
						</td>
					</tr>

				</table>
				</div>
				</div>
<?php
			$i++;
		}
	}
?>
				<button id="add_size_button" class="button-secondary" onclick="pizzatimeAddsize();return false;"><?php _e( 'Add size', 'pizzatime' );?></button>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="new_option_name,some_other_option,option_etc" />

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'pizzatime' ) ?>" />
				</p>
			</form>
		</div><!-- pizzatime_tabs-1 -->
		<div id="pizzatime_tabs-2">
			<form method="post" enctype="multipart/form-data" action="admin.php?page=pizzatime#pizzatime_tabs-2">
			<?php    wp_nonce_field( 'pizzatime-save-settings_' ); ?>

<?php
	if ( is_array( $crusts ) && count( $crusts )>0 ) {
		$i=0;
		foreach ( $crusts as $crust ) {

?>
				<div class="pizzatime-expand">
				<h3><?php echo "<b>#".$crust['id']."</b> - ".esc_html($crust['name']);?></h3>
				<div>
				<table class="form-table crust">
					<tr>
						<td colspan="2"><hr></td>
					</tr>
				 	<tr>
						<td colspan="2"><span class="item_id"><?php echo "<b>ID #".$crust['id']."</b>";?></span></td>
				 	</tr>
				 	<tr valign="top">
					<tr>
						<th scope="row"><?php _e( 'Enabled', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_crust_status[<?php echo $crust['id'];?>]">
								<option <?php if ( $crust['status']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $crust['status']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>
							<a style="<?php if (count( $crusts )==1) echo 'display:none;';?>" class="remove_crust" href="javascript:void(0);" onclick="pizzatimeRemoveCrust(<?php echo $crust['id'];?>);return false;">
								<img alt="<?php _e( 'Remove crust', 'pizzatime' );?>" title="<?php _e( 'Remove crust', 'pizzatime' );?>" src="<?php echo plugins_url( 'pizzatime/images/remove.png' ); ?>">
					 		</a>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Default', 'pizzatime' );?></th>
						<td>
							<input autocomplete="off" type="checkbox" name="pizzatime_crust_default" class="pizzatime-default" value="<?php echo $crust['id']; ?>" <?php if ($pizzatime_crust_default==$crust['id']) echo 'checked';?>>
						</td>
					</tr>

					<th scope="row"><?php _e( 'Name', 'pizzatime' );?></th>
						<td>
							<input type="text" name="pizzatime_crust_name[<?php echo $crust['id'];?>]" value="<?php echo esc_attr($crust['name']);?>" />&nbsp;

						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Description', 'pizzatime' ); ?></th>
						<td>
							<textarea name="pizzatime_crust_description[<?php echo $crust['id'];?>]"><?php echo esc_textarea($crust['description']);?></textarea>

						</td>
					</tr>


					<tr valign="top">
						<th scope="row"><?php _e( 'Price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" class="pizzatime_price" name="pizzatime_crust_price[<?php echo $crust['id'];?>]" value="<?php echo $crust['price'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $crust['image'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($crust['image']);?>"></a>
							<input type="hidden" name="pizzatime_crust_image_path[<?php echo $crust['id'];?>]" value="<?php echo esc_attr($crust['image_path']);?>" />
							<input type="text" name="pizzatime_crust_image[<?php echo $crust['id'];?>]" value="<?php echo esc_attr($crust['image']);?>" />
							<input type="file" name="pizzatime_crust_image_upload[<?php echo $crust['id'];?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>

					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Photo', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $crust['photo'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($crust['photo']);?>"></a>
							<input type="text" name="pizzatime_crust_photo[<?php echo $crust['id'];?>]" value="<?php echo esc_attr($crust['photo']);?>" />
							<input type="file" name="pizzatime_crust_photo_upload[<?php echo $crust['id'];?>]" accept="image/*">
						</td>

					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Sort order', 'pizzatime' );?></th>
						<td>
							<input size="3" type="text" name="pizzatime_crust_sort_order[<?php echo $crust['id'];?>]" value="<?php echo $crust['sort_order'];?>" />
						</td>
					</tr>
				</table>
				</div>
				</div>
<?php
			$i++;
		}
	}
?>
				<button id="add_crust_button" class="button-secondary" onclick="pizzatimeAddCrust();return false;"><?php _e( 'Add crust', 'pizzatime' );?></button>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="new_option_name,some_other_option,option_etc" />

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'pizzatime' ) ?>" />
				</p>

			</form>

		</div><!-- pizzatime_tabs-2 -->

		<div id="pizzatime_tabs-3">
			<form method="post" enctype="multipart/form-data" action="admin.php?page=pizzatime#pizzatime_tabs-3">
			<?php    wp_nonce_field( 'pizzatime-save-settings_' ); ?>
				<table>
					<tr>
						<th scope="row"><?php _e( 'Required', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_sauce_required">
								<option <?php if ( $pizzatime_sauce_required=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $pizzatime_sauce_required=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
				</table>
<?php
//pizzatime_sauce_required
	if ( is_array( $sauces ) && count( $sauces )>0 ) {
		$i=0;
		foreach ( $sauces as $sauce ) {
?>
				<div class="pizzatime-expand">
				<h3><?php echo "<b>#".$sauce['id']."</b> - ".esc_html($sauce['name']);?></h3>
				<div>
				<table class="form-table sauce">
					<tr>
						<td colspan="2"><hr></td>
					</tr>

				 	<tr>
						<td colspan="2"><span class="item_id"><?php echo "<b>ID #".$sauce['id']."</b>";?></span></td>
				 	</tr>
					<tr>
						<th scope="row"><?php _e( 'Enabled', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_sauce_status[<?php echo $sauce['id'];?>]">
								<option <?php if ( $sauce['status']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $sauce['status']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>
							<a style="<?php if (count( $sauces )==1) echo 'display:none;';?>" class="remove_sauce" href="javascript:void(0);" onclick="pizzatimeRemoveSauce(<?php echo $sauce['id'];?>);return false;">
								<img alt="<?php _e( 'Remove sauce', 'pizzatime' );?>" title="<?php _e( 'Remove sauce', 'pizzatime' );?>" src="<?php echo plugins_url( 'pizzatime/images/remove.png' ); ?>">
					 		</a>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Default', 'pizzatime' );?></th>
						<td>
							<input autocomplete="off" type="checkbox" name="pizzatime_sauce_default" class="pizzatime-default" value="<?php echo $sauce['id']; ?>" <?php if ($pizzatime_sauce_default==$sauce['id']) echo 'checked';?>>
						</td>
					</tr>

				 	<tr valign="top">
					<th scope="row"><?php _e( 'Name', 'pizzatime' );?></th>
						<td>
							<input type="text" name="pizzatime_sauce_name[<?php echo $sauce['id'];?>]" value="<?php echo esc_attr($sauce['name']);?>" />&nbsp;
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Description', 'pizzatime' ); ?></th>
						<td>
							<textarea name="pizzatime_sauce_description[<?php echo $sauce['id'];?>]"><?php echo esc_textarea($sauce['description']);?></textarea>

						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Has extra portion', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_sauce_has_extra[<?php echo $sauce['id']?>]">
								<option <?php if ( $sauce['has_extra']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $sauce['has_extra']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>




					<tr valign="top">
						<th scope="row"><?php _e( 'Price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" class="pizzatime_price" name="pizzatime_sauce_price[<?php echo $sauce['id'];?>]" value="<?php echo $sauce['price'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Extra portion price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" class="pizzatime_price" name="pizzatime_sauce_price_extra[<?php echo $sauce['id']?>]" value="<?php echo $sauce['price_extra'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $sauce['image'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($sauce['image']);?>"></a>
							<input type="hidden" name="pizzatime_sauce_image_path[<?php echo $sauce['id'];?>]" value="<?php echo esc_attr($sauce['image_path']);?>" />
							<input type="text" name="pizzatime_sauce_image[<?php echo $sauce['id'];?>]" value="<?php echo esc_attr($sauce['image']);?>" />
							<input type="file" name="pizzatime_sauce_image_upload[<?php echo $sauce['id'];?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Extra portion image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $sauce['image_extra'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($sauce['image_extra']);?>"></a>
							<input type="hidden" name="pizzatime_sauce_image_extra_path[<?php echo $sauce['id']?>]" value="<?php echo esc_attr($sauce['image_extra_path']);?>" />
							<input type="text" name="pizzatime_sauce_image_extra[<?php echo $sauce['id']?>]" value="<?php echo esc_attr($sauce['image_extra']);?>" />
							<input type="file" name="pizzatime_sauce_image_upload_extra[<?php echo $sauce['id']?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Photo', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $sauce['photo'];?>"><img class="pizzatime-preview" src="<?php echo esc_attr($sauce['photo']);?>"></a>
							<input type="text" name="pizzatime_sauce_photo[<?php echo $sauce['id'];?>]" value="<?php echo esc_attr($sauce['photo']);?>" />
							<input type="file" name="pizzatime_sauce_photo_upload[<?php echo $sauce['id'];?>]" accept="image/*">
						</td>

					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Sort order', 'pizzatime' );?></th>
						<td>
							<input size="3" type="text" name="pizzatime_sauce_sort_order[<?php echo $sauce['id'];?>]" value="<?php echo $sauce['sort_order'];?>" />
						</td>
					</tr>
				</table>
				</div>
				</div>
<?php
			$i++;
		}
	}
?>
				<button id="add_sauce_button" class="button-secondary" onclick="pizzatimeAddSauce();return false;"><?php _e( 'Add sauce', 'pizzatime' );?></button>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="new_option_name,some_other_option,option_etc" />

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'pizzatime' ) ?>" />
				</p>

			</form>

		</div><!-- pizzatime_tabs-3 -->
		<div id="pizzatime_tabs-4">
			<form method="post" enctype="multipart/form-data" action="admin.php?page=pizzatime#pizzatime_tabs-4">
			<?php    wp_nonce_field( 'pizzatime-save-settings_' ); ?>
				<table>
					<tr>
						<th scope="row"><?php _e( 'Required', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_cheese_required">
								<option <?php if ( $pizzatime_cheese_required=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $pizzatime_cheese_required=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
				</table>
<?php
	if ( is_array( $cheeses ) && count( $cheeses )>0 ) {
		$i=0;
		foreach ( $cheeses as $cheese ) {
?>
				<div class="pizzatime-expand">
				<h3><?php echo "<b>#".$cheese['id']."</b> - ".esc_html($cheese['name']);?></h3>
				<div>
				<table class="form-table cheese">
					<tr>
						<td colspan="2"><hr></td>
					</tr>
				 	<tr>
						<td colspan="2"><span class="item_id"><?php echo "<b>ID #".$cheese['id']."</b>";?></span></td>
				 	</tr>
					<tr>
						<th scope="row"><?php _e( 'Enabled', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_cheese_status[<?php echo $cheese['id']?>]">
								<option <?php if ( $cheese['status']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $cheese['status']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>
							<a style="<?php if (count( $cheeses )==1) echo 'display:none;';?>" class="remove_cheese" href="javascript:void(0);" onclick="pizzatimeRemoveCheese(<?php echo $cheese['id'];?>);return false;">
								<img alt="<?php _e( 'Remove cheese', 'pizzatime' );?>" title="<?php _e( 'Remove cheese', 'pizzatime' );?>" src="<?php echo plugins_url( 'pizzatime/images/remove.png' ); ?>">
					 		</a>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Default', 'pizzatime' );?></th>
						<td>
							<input autocomplete="off" type="checkbox" name="pizzatime_cheese_default" class="pizzatime-default" value="<?php echo $cheese['id']; ?>" <?php if ($pizzatime_cheese_default==$cheese['id']) echo 'checked';?>>
						</td>
					</tr>
				 	<tr valign="top">
					<th scope="row"><?php _e( 'Name', 'pizzatime' );?></th>
						<td>
							<input type="text" name="pizzatime_cheese_name[<?php echo $cheese['id']?>]" value="<?php echo esc_attr($cheese['name']);?>" />&nbsp;
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Description', 'pizzatime' ); ?></th>
						<td>
							<textarea name="pizzatime_cheese_description[<?php echo $cheese['id']?>]"><?php echo esc_textarea($cheese['description']);?></textarea>

						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Has extra portion', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_cheese_has_extra[<?php echo $cheese['id']?>]">
								<option <?php if ( $cheese['has_extra']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $cheese['has_extra']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>


					<tr valign="top">
						<th scope="row"><?php _e( 'Price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" class="pizzatime_price" name="pizzatime_cheese_price[<?php echo $cheese['id']?>]" value="<?php echo $cheese['price'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Extra portion price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" class="pizzatime_price" name="pizzatime_cheese_price_extra[<?php echo $cheese['id']?>]" value="<?php echo $cheese['price_extra'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>



					<tr valign="top">
						<th scope="row"><?php _e( 'Image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $cheese['image'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($cheese['image']);?>"></a>
							<input type="hidden" name="pizzatime_cheese_image_path[<?php echo $cheese['id']?>]" value="<?php echo esc_attr($cheese['image_path']);?>" />
							<input type="text" name="pizzatime_cheese_image[<?php echo $cheese['id']?>]" value="<?php echo esc_attr($cheese['image']);?>" />
							<input type="file" name="pizzatime_cheese_image_upload[<?php echo $cheese['id']?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Extra portion image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $cheese['image_extra'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($cheese['image_extra']);?>"></a>
							<input type="hidden" name="pizzatime_cheese_image_extra_path[<?php echo $cheese['id']?>]" value="<?php echo esc_attr($cheese['image_extra_path']);?>" />
							<input type="text" name="pizzatime_cheese_image_extra[<?php echo $cheese['id']?>]" value="<?php echo esc_attr($cheese['image_extra']);?>" />
							<input type="file" name="pizzatime_cheese_image_upload_extra[<?php echo $cheese['id']?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Photo', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $cheese['photo'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($cheese['photo']);?>"></a>
							<input type="text" name="pizzatime_cheese_photo[<?php echo $cheese['id'];?>]" value="<?php echo esc_attr($cheese['photo']);?>" />
							<input type="file" name="pizzatime_cheese_photo_upload[<?php echo $cheese['id'];?>]" accept="image/*">
						</td>

					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Sort order', 'pizzatime' );?></th>
						<td>
							<input size="3" type="text" name="pizzatime_cheese_sort_order[<?php echo $cheese['id'];?>]" value="<?php echo $cheese['sort_order'];?>" />
						</td>
					</tr>


				</table>
				</div>
				</div>
<?php
			$i++;
		}
	}
?>
				<button id="add_cheese_button" class="button-secondary" onclick="pizzatimeAddCheese();return false;"><?php _e( 'Add cheese', 'pizzatime' );?></button>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="new_option_name,some_other_option,option_etc" />

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'pizzatime' ) ?>" />
				</p>

			</form>

		</div><!-- pizzatime_tabs-4 -->

		<div id="pizzatime_tabs-5">
			<form method="post" enctype="multipart/form-data" action="admin.php?page=pizzatime#pizzatime_tabs-5">
			<?php    wp_nonce_field( 'pizzatime-save-settings_' ); ?>
			<?php _e('Number of free ingredients', 'pizzatime');?> <input type="text" name="meats_free_ingredients" size="3" value="<?php echo (int)get_option('meats_free_ingredients'); ?>">
<?php
	if ( is_array( $meats ) && count( $meats )>0 ) {
		$i=0;
		foreach ( $meats as $meat ) {
?>

				<div class="pizzatime-expand">
				<h3><?php echo "<b>#".$meat['id']."</b> - ".esc_html($meat['name']);?></h3>
				<div>
				<table class="form-table meat">
					<tr>
						<td colspan="2"><hr></td>
					</tr>
				 	<tr>
						<td colspan="2"><span class="item_id"><?php echo "<b>ID #".$meat['id']."</b>";?></span></td>
				 	</tr>
					<tr>
						<th scope="row"><?php _e( 'Enabled', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_meat_status[<?php echo $meat['id'];?>]">
								<option <?php if ( $meat['status']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $meat['status']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>
							<a style="<?php if (count( $meats )==1) echo 'display:none;';?>" class="remove_meat" href="javascript:void(0);" onclick="pizzatimeRemoveMeat(<?php echo $meat['id'];?>);return false;">
								<img alt="<?php _e( 'Remove meat', 'pizzatime' );?>" title="<?php _e( 'Remove meat', 'pizzatime' );?>" src="<?php echo plugins_url( 'pizzatime/images/remove.png' ); ?>">
					 		</a>

						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Is ingredient', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_meat_is_ingredient[<?php echo $meat['id'];?>]">
								<option <?php if ( $meat['is_ingredient']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $meat['is_ingredient']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>
							<img class="tooltip" data-title="<?php htmlentities(_e( 'Consider this an ingredient when checking the maximum number of ingredients.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>

				 	<tr valign="top">
					<th scope="row"><?php _e( 'Name', 'pizzatime' );?></th>
						<td>
							<input type="text" name="pizzatime_meat_name[<?php echo $meat['id'];?>]" value="<?php echo esc_attr($meat['name']);?>" />&nbsp;
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Description', 'pizzatime' ); ?></th>
						<td>
							<textarea name="pizzatime_meat_description[<?php echo $meat['id'];?>]"><?php echo esc_textarea($meat['description']);?></textarea>

						</td>
					</tr>


					<tr>
						<th scope="row"><?php _e( 'Has extra portion', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_meat_has_extra[<?php echo $meat['id'];?>]">
								<option <?php if ( $meat['has_extra']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $meat['has_extra']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Has left/right parts', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_meat_has_left_right[<?php echo $meat['id'];?>]">
								<option <?php if ( $meat['has_left_right']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $meat['has_left_right']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>


					<tr valign="top">
						<th scope="row"><?php _e( 'Price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" class="pizzatime_price" name="pizzatime_meat_price[<?php echo $meat['id'];?>]" value="<?php echo $meat['price'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Extra portion price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" name="pizzatime_meat_price_extra[<?php echo $meat['id'];?>]" value="<?php echo $meat['price_extra'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>



					<tr valign="top">
						<th scope="row"><?php _e( 'Image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $meat['image'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($meat['image']);?>"></a>
							<input type="hidden" name="pizzatime_meat_image_path[<?php echo $meat['id'];?>]" value="<?php echo esc_attr($meat['image_path']);?>" />
							<input type="text" name="pizzatime_meat_image[<?php echo $meat['id'];?>]" value="<?php echo esc_attr($meat['image']);?>" />
							<input type="file" name="pizzatime_meat_image_upload[<?php echo $meat['id'];?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>

					</tr>


					<tr valign="top">
						<th scope="row"><?php _e( 'Extra portion image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $meat['image_extra'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($meat['image_extra']);?>"></a>
							<input type="hidden" name="pizzatime_meat_image_extra_path[<?php echo $meat['id'];?>]" value="<?php echo esc_attr($meat['image_extra_path']);?>" />
							<input type="text" name="pizzatime_meat_image_extra[<?php echo $meat['id'];?>]" value="<?php echo esc_attr($meat['image_extra']);?>" />
							<input type="file" name="pizzatime_meat_image_upload_extra[<?php echo $meat['id'];?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Photo', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $meat['photo'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($meat['photo']);?>"></a>
							<input type="text" name="pizzatime_meat_photo[<?php echo $meat['id'];?>]" value="<?php echo esc_attr($meat['photo']);?>" />
							<input type="file" name="pizzatime_meat_photo_upload[<?php echo $meat['id'];?>]" accept="image/*">
						</td>

					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Layer', 'pizzatime' );?></th>
						<td>
							<input size="3" type="text" name="pizzatime_meat_layer[<?php echo $meat['id'];?>]" value="<?php echo $meat['layer'];?>" />
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The higher the number the higher the layer.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Sort order', 'pizzatime' );?></th>
						<td>
							<input size="3" type="text" name="pizzatime_meat_sort_order[<?php echo $meat['id'];?>]" value="<?php echo $meat['sort_order'];?>" />
						</td>
					</tr>
				</table>
				</div>
				</div>
<?php
			$i++;
		}
	}
?>
				<button id="add_meat_button" class="button-secondary" onclick="pizzatimeAddMeat();return false;"><?php _e( 'Add meat', 'pizzatime' );?></button>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="new_option_name,some_other_option,option_etc" />

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'pizzatime' ) ?>" />
				</p>

			</form>

		</div><!-- pizzatime_tabs-5 -->

		<div id="pizzatime_tabs-6">
			<form method="post" enctype="multipart/form-data" action="admin.php?page=pizzatime#pizzatime_tabs-6">
			<?php    wp_nonce_field( 'pizzatime-save-settings_' ); ?>
			<?php _e('Number of free ingredients', 'pizzatime');?> <input type="text" name="toppings_free_ingredients" size="3" value="<?php echo (int)get_option('toppings_free_ingredients'); ?>">
<?php
	if ( is_array( $toppings ) && count( $toppings )>0 ) {
		$i=0;
		foreach ( $toppings as $topping ) {
?>
				<div class="pizzatime-expand">
				<h3><?php echo "<b>#".$topping['id']."</b> - ".esc_html($topping['name']);?></h3>
				<div>
				<table class="form-table topping">
					<tr>
						<td colspan="2"><hr></td>
					</tr>
				 	<tr>
						<td colspan="2"><span class="item_id"><?php echo "<b>ID #".$topping['id']."</b>";?></span></td>
				 	</tr>
					<tr>
						<th scope="row"><?php _e( 'Enabled', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_topping_status[<?php echo $topping['id']?>]">
								<option <?php if ( $topping['status']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $topping['status']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>
							<a style="<?php if (count( $toppings )==1) echo 'display:none;';?>" class="remove_topping" href="javascript:void(0);" onclick="pizzatimeRemoveTopping(<?php echo $topping['id'];?>);return false;">
								<img alt="<?php _e( 'Remove topping', 'pizzatime' );?>" title="<?php _e( 'Remove topping', 'pizzatime' );?>" src="<?php echo plugins_url( 'pizzatime/images/remove.png' ); ?>">
					 		</a>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Is ingredient', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_topping_is_ingredient[<?php echo $topping['id'];?>]">
								<option <?php if ( $topping['is_ingredient']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $topping['is_ingredient']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>
							<img class="tooltip" data-title="<?php htmlentities(_e( 'Consider this an ingredient when checking the maximum number of ingredients.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>


				 	<tr valign="top">
					<th scope="row"><?php _e( 'Name', 'pizzatime' );?></th>
						<td>
							<input type="text" name="pizzatime_topping_name[<?php echo $topping['id']?>]" value="<?php echo esc_attr($topping['name']);?>" />&nbsp;

						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Description', 'pizzatime' ); ?></th>
						<td>
							<textarea name="pizzatime_topping_description[<?php echo $topping['id']?>]"><?php echo esc_textarea($topping['description']);?></textarea>

						</td>
					</tr>


					<tr>
						<th scope="row"><?php _e( 'Has extra portion', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_topping_has_extra[<?php echo $topping['id']?>]">
								<option <?php if ( $topping['has_extra']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $topping['has_extra']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Has left/right parts', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_topping_has_left_right[<?php echo $topping['id'];?>]">
								<option <?php if ( $topping['has_left_right']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $topping['has_left_right']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>




					<tr valign="top">
						<th scope="row"><?php _e( 'Price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" class="pizzatime_price" name="pizzatime_topping_price[<?php echo $topping['id']?>]" value="<?php echo $topping['price'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Extra portion price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" name="pizzatime_topping_price_extra[<?php echo $topping['id']?>]" value="<?php echo $topping['price_extra'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>



					<tr valign="top">
						<th scope="row"><?php _e( 'Image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $topping['image'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($topping['image']);?>"></a>
							<input type="hidden" name="pizzatime_topping_image_path[<?php echo $topping['id']?>]" value="<?php echo esc_attr($topping['image_path']);?>" />
							<input type="text" name="pizzatime_topping_image[<?php echo $topping['id']?>]" value="<?php echo esc_attr($topping['image']);?>" />
							<input type="file" name="pizzatime_topping_image_upload[<?php echo $topping['id']?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>

					</tr>


					<tr valign="top">
						<th scope="row"><?php _e( 'Extra portion image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $topping['image_extra'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($topping['image_extra']);?>"></a>
							<input type="hidden" name="pizzatime_topping_image_extra_path[<?php echo $topping['id']?>]" value="<?php echo esc_attr($topping['image_extra_path']);?>" />
							<input type="text" name="pizzatime_topping_image_extra[<?php echo $topping['id']?>]" value="<?php echo esc_attr($topping['image_extra']);?>" />
							<input type="file" name="pizzatime_topping_image_upload_extra[<?php echo $topping['id']?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Photo', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $topping['photo'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($topping['photo']);?>"></a>
							<input type="text" name="pizzatime_topping_photo[<?php echo $topping['id'];?>]" value="<?php echo esc_attr($topping['photo']);?>" />
							<input type="file" name="pizzatime_topping_photo_upload[<?php echo $topping['id'];?>]" accept="image/*">
						</td>

					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Layer', 'pizzatime' );?></th>
						<td>
							<input size="3" type="text" name="pizzatime_topping_layer[<?php echo $topping['id']?>]" value="<?php echo $topping['layer'];?>" />
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The higher the number the higher the layer.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Sort order', 'pizzatime' );?></th>
						<td>
							<input size="3" type="text" name="pizzatime_topping_sort_order[<?php echo $topping['id'];?>]" value="<?php echo $topping['sort_order'];?>" />
						</td>
					</tr>
				</table>
				</div>
				</div>
<?php
			$i++;
		}
	}
?>
				<button id="add_topping_button" class="button-secondary" onclick="pizzatimeAddTopping();return false;"><?php _e( 'Add topping', 'pizzatime' );?></button>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="new_option_name,some_other_option,option_etc" />

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'pizzatime' ) ?>" />
				</p>

			</form>
		</div><!-- pizzatime_tabs-6 -->
		<div id="pizzatime_tabs-7">
			<form method="post" enctype="multipart/form-data" action="admin.php?page=pizzatime#pizzatime_tabs-7">
			<?php    wp_nonce_field( 'pizzatime-save-settings_' ); ?>
			<?php _e('Number of free ingredients', 'pizzatime');?> <input type="text" name="dressings_free_ingredients" size="3" value="<?php echo (int)get_option('dressings_free_ingredients'); ?>">
<?php
	if ( is_array( $dressings ) && count( $dressings )>0 ) {
		$i=0;
		foreach ( $dressings as $dressing ) {
?>
				<div class="pizzatime-expand">
				<h3><?php echo "<b>#".$dressing['id']."</b> - ".esc_html($dressing['name']);?></h3>
				<div>
				<table class="form-table dressing">
					<tr>
						<td colspan="2"><hr></td>
					</tr>
				 	<tr>
						<td colspan="2"><span class="item_id"><?php echo "<b>ID #".$dressing['id']."</b>";?></span></td>
				 	</tr>
					<tr>
						<th scope="row"><?php _e( 'Enabled', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_dressing_status[<?php echo $dressing['id']?>]">
								<option <?php if ( $dressing['status']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $dressing['status']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>
							<a style="<?php if (count( $dressings )==1) echo 'display:none;';?>" class="remove_dressing" href="javascript:void(0);" onclick="pizzatimeRemoveDressing(<?php echo $dressing['id'];?>);return false;">
								<img alt="<?php _e( 'Remove dressing', 'pizzatime' );?>" title="<?php _e( 'Remove dressing', 'pizzatime' );?>" src="<?php echo plugins_url( 'pizzatime/images/remove.png' ); ?>">
					 		</a>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Is ingredient', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_dressing_is_ingredient[<?php echo $dressing['id'];?>]">
								<option <?php if ( $dressing['is_ingredient']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $dressing['is_ingredient']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>
							<img class="tooltip" data-title="<?php htmlentities(_e( 'Consider this an ingredient when checking the maximum number of ingredients.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>


				 	<tr valign="top">
					<th scope="row"><?php _e( 'Name', 'pizzatime' );?></th>
						<td>
							<input type="text" name="pizzatime_dressing_name[<?php echo $dressing['id']?>]" value="<?php echo esc_attr($dressing['name']);?>" />&nbsp;

						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Description', 'pizzatime' ); ?></th>
						<td>
							<textarea name="pizzatime_dressing_description[<?php echo $dressing['id']?>]"><?php echo esc_textarea($dressing['description']);?></textarea>
						</td>
					</tr>


					<tr>
						<th scope="row"><?php _e( 'Has extra portion', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_dressing_has_extra[<?php echo $dressing['id']?>]">
								<option <?php if ( $dressing['has_extra']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $dressing['has_extra']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Has left/right parts', 'pizzatime' );?></th>
						<td>
							<select name="pizzatime_dressing_has_left_right[<?php echo $dressing['id'];?>]">
								<option <?php if ( $dressing['has_left_right']=='1' ) echo "selected";?> value="1"><?php _e('Yes', 'pizzatime');?></option>
								<option <?php if ( $dressing['has_left_right']=='0' ) echo "selected";?> value="0"><?php _e('No', 'pizzatime');?></option>
							</select>

						</td>
					</tr>



					<tr valign="top">
						<th scope="row"><?php _e( 'Price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" class="pizzatime_price" name="pizzatime_dressing_price[<?php echo $dressing['id']?>]" value="<?php echo $dressing['price'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Extra portion price', 'pizzatime' ); ?></th>
						<td>
							<input size="3" type="text" name="pizzatime_dressing_price_extra[<?php echo $dressing['id']?>]" value="<?php echo $dressing['price_extra'];?>" /><?php echo get_woocommerce_currency_symbol(); ?> 
						</td>
					</tr>



					<tr valign="top">
						<th scope="row"><?php _e( 'Image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $dressing['image'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($dressing['image']);?>"></a>
							<input type="hidden" name="pizzatime_dressing_image_path[<?php echo $dressing['id']?>]" value="<?php echo esc_attr($dressing['image_path']);?>" />
							<input type="text" name="pizzatime_dressing_image[<?php echo $dressing['id']?>]" value="<?php echo esc_url($dressing['image']);?>" />
							<input type="file" name="pizzatime_dressing_image_upload[<?php echo $dressing['id']?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>

					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Extra portion image', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $dressing['image_extra'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($dressing['image_extra']);?>"></a>
							<input type="hidden" name="pizzatime_dressing_image_extra_path[<?php echo $dressing['id']?>]" value="<?php echo esc_attr($dressing['image_extra_path']);?>" />
							<input type="text" name="pizzatime_dressing_image_extra[<?php echo $dressing['id']?>]" value="<?php echo esc_attr($dressing['image_extra']);?>" />
							<input type="file" name="pizzatime_dressing_image_upload_extra[<?php echo $dressing['id']?>]" accept="image/png">
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The image has to be a transparent 600x600px PNG file.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Photo', 'pizzatime' );?></th>
						<td>
							<a href="<?php echo $dressing['photo'];?>"><img class="pizzatime-preview" src="<?php echo esc_url($dressing['photo']);?>"></a>
							<input type="text" name="pizzatime_dressing_photo[<?php echo $dressing['id'];?>]" value="<?php echo esc_attr($dressing['photo']);?>" />
							<input type="file" name="pizzatime_dressing_photo_upload[<?php echo $dressing['id'];?>]" accept="image/*">
						</td>

					</tr>



					<tr valign="top">
						<th scope="row"><?php _e( 'Layer', 'pizzatime' );?></th>
						<td>
							<input size="3" type="text" name="pizzatime_dressing_layer[<?php echo $dressing['id']?>]" value="<?php echo $dressing['layer'];?>" />
							<img class="tooltip" data-title="<?php htmlentities(_e( 'The higher the number the higher the layer.', 'pizzatime' ));?>" src="<?php echo plugins_url( 'pizzatime/images/question.png' ); ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Sort order', 'pizzatime' );?></th>
						<td>
							<input size="3" type="text" name="pizzatime_dressing_sort_order[<?php echo $dressing['id'];?>]" value="<?php echo $dressing['sort_order'];?>" />
						</td>
					</tr>
				</table>
				</div>
				</div>
<?php
			$i++;
		}
	}
?>
				<button id="add_dressing_button" class="button-secondary" onclick="pizzatimeAddDressing();return false;"><?php _e( 'Add dressing', 'pizzatime' );?></button>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="new_option_name,some_other_option,option_etc" />

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'pizzatime' ) ?>" />
				</p>

			</form>

		</div><!-- pizzatime_tabs-7 -->


		<div id="pizzatime_tabs-9">
			<?php _e('Buy <a href="https://secure.avangate.com/order/checkout.php?PRODS=4701306&QTY=1&CART=1&CARD=1">PRO</a> to unlock!');?>


		</div><!-- pizzatime_tabs-9-->
		<div id="pizzatime_tabs-10">
			<?php _e('Buy <a href="https://secure.avangate.com/order/checkout.php?PRODS=4701306&QTY=1&CART=1&CARD=1">PRO</a> to unlock!');?>

		</div><!-- pizzatime_tabs-10 -->
	</div><!-- pizzatime_tabs -->
	<div id="pizzatime_nonce" style="display:none;">
		<?php    wp_nonce_field( 'pizzatime-delete-ingredient_' ); ?>
	</div>
</div> <!-- wrap -->
<?php
}
?>
<?php

/**
 *
 *
 * @author Sergey Burkov, http://www.wppizzatime.com
 * @copyright 2016
 */

function pizzatime_activate() {
	global $wpdb;

	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die ('Woocommerce is not installed!');
	}

	$current_version = get_option( 'pizzatime_version');

	$check_query = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies" );

	$attr = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = '%s'", 'pizzatime_ingredients' ) );
	if ( strlen( $attr->attribute_id )==0 ) {

		$attribute=array( 'attribute_name'=>'pizzatime_ingredients',
			'attribute_label'=>__( 'Ingredients', 'pizzatime' ),
			'attribute_type'=>'text',
			'attribute_orderby'=>'menu_order',
			'attribute_public'=>'0' );
		if ( !isset( $check_query->attribute_public ) ) unset( $attribute['attribute_public'] );
		$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );

		do_action( 'woocommerce_attribute_added', $wpdb->insert_id, $attribute );
		delete_transient( 'wc_attribute_taxonomies' );


	}

	pizzatime_check_install();

	add_option( 'pizzatime_do_activation_redirect', true );

	update_option( 'pizzatime_version', '1.0.8.6' );

	do_action( 'pizzatime_activate' );
}

function pizzatime_check_install() {

	global $wpdb;

	$current_version = get_option( 'pizzatime_version');

	$charset_collate = $wpdb->get_charset_collate();

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );



	$default_image_url = str_replace('http:','',plugins_url()).'/pizzatime/images/';
	$default_image_path = pizzatime_plugin_path().'/images/';


	$sql = "CREATE TABLE ".$wpdb->prefix."pizzatime_sizes (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  name varchar(64) DEFAULT '' NOT NULL,
		  description varchar(4096) DEFAULT '' NOT NULL,
		  photo varchar(2048) DEFAULT '' NOT NULL,
		  price varchar(128) DEFAULT '0' NOT NULL,
		  price_multiplier FLOAT DEFAULT '1' NOT NULL,
		  status tinyint(1) DEFAULT '0' NOT NULL,
		  sort_order int(10) DEFAULT '0' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";


	dbDelta( $sql );

	$cols = $wpdb->get_col($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."pizzatime_sizes LIMIT 1", null ) );

	$sort_order=0;
	if ( empty($cols) ){
		$default_sizes[]=array(
			'name' => __('Small size', 'pizzatime'),
			'description' => __('6 slices', 'pizzatime'),
			'photo' => $default_image_url.'6-slices.png',
			'price' => '10',
			'price_multiplier' => '1',
			'sort_order' => $sort_order+=10,
			'status' => '1',
		);

		$default_sizes[]=array(
			'name' => __('Medium size', 'pizzatime'),
			'description' => __('8 slices', 'pizzatime'),
			'photo' => $default_image_url.'8-slices.png',
			'price' => '15',
			'price_multiplier' => '1',
			'sort_order' => $sort_order+=10,
			'status' => '1',
		);
	
		$default_sizes[]=array(
			'name' => __('Large size', 'pizzatime'),
			'description' => __('10 slices', 'pizzatime'),
			'photo' => $default_image_url.'10-slices.png',
			'price' => '20',
			'price_multiplier' => '1',
			'sort_order' => $sort_order+=10,
			'status' => '1'

		);
		foreach ($default_sizes as $size) {
			$wpdb->insert( $wpdb->prefix."pizzatime_sizes", $size );
		}
	}
	add_option( 'pizzatime_size_default', 2);

	$sql = "CREATE TABLE ".$wpdb->prefix."pizzatime_crusts (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  name varchar(64) DEFAULT '' NOT NULL,
		  description varchar(4096) DEFAULT '' NOT NULL,
		  image varchar(2048) DEFAULT '' NOT NULL,
		  image_path varchar(2048) DEFAULT '' NOT NULL,
		  photo varchar(2048) DEFAULT '' NOT NULL,
		  price float DEFAULT '0' NOT NULL,
		  status tinyint(1) DEFAULT '0' NOT NULL,
		  sort_order int(10) DEFAULT '0' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";


	dbDelta( $sql );

	$cols = $wpdb->get_col($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."pizzatime_crusts LIMIT 1", null ) );
	$sort_order=0;

	if ( empty($cols) ){

		$default_crusts[]=array(
			'name' => __('Regular Crust', 'pizzatime'),
			'description' => '',
			'image' => $default_image_url.'pizza-crust.png',
			'image_path' => $default_image_path.'pizza-crust.png',
			'photo' => $default_image_url.'pizza-crust.png',
			'price' => '',
			'status' => '1',
			'sort_order' => $sort_order+=10,
		);

		foreach ($default_crusts as $crust) {
			$wpdb->insert( $wpdb->prefix."pizzatime_crusts", $crust );
		}
	}

	add_option( 'pizzatime_crust_default', 1);

	$sql = "CREATE TABLE ".$wpdb->prefix."pizzatime_sauces (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  name varchar(64) DEFAULT '' NOT NULL,
		  description varchar(4096) DEFAULT '' NOT NULL,
		  photo varchar(2048) DEFAULT '' NOT NULL,
		  image varchar(2048) DEFAULT '' NOT NULL,
		  image_extra varchar(2048) DEFAULT '' NOT NULL,
		  image_path varchar(2048) DEFAULT '' NOT NULL,
		  image_extra_path varchar(2048) DEFAULT '' NOT NULL,
		  price float DEFAULT '0' NOT NULL,
		  price_extra float DEFAULT '0' NOT NULL,
		  has_extra tinyint(1) DEFAULT '0' NOT NULL,
		  status tinyint(1) DEFAULT '0' NOT NULL,
		  sort_order int(10) DEFAULT '0' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

	dbDelta( $sql );

	$cols = $wpdb->get_col($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."pizzatime_sauces LIMIT 1", null ) );
	$sort_order=0;
	if ( empty($cols) ){


		$default_sauces[]=array(
			'name' => __('Tomato Sauce', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'tomato-sauce.png',
			'image' => $default_image_url.'tomato-sauce.png',
			'image_extra' => $default_image_url.'tomato-sauce.png',
			'image_path' => $default_image_path.'tomato-sauce.png',
			'image_extra_path' => $default_image_path.'tomato-sauce.png',
			'price' => '0',
			'price_extra' => '0',
			'has_extra' => '0',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_sauces[]=array(
			'name' => __('Pesto Sauce', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'pesto-sauce.png',
			'image' => $default_image_url.'pesto-sauce.png',
			'image_extra' => $default_image_url.'pesto-sauce.png',
			'image_path' => $default_image_path.'pesto-sauce.png',
			'image_extra_path' => $default_image_path.'pesto-sauce.png',
			'price' => '0',
			'price_extra' => '0',
			'has_extra' => '0',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_sauces[]=array(
			'name' => __('BBQ Sauce', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'bbq-sauce.png',
			'image' => $default_image_url.'bbq-sauce.png',
			'image_extra' => $default_image_url.'bbq-sauce.png',
			'image_path' => $default_image_path.'bbq-sauce.png',
			'image_extra_path' => $default_image_path.'bbq-sauce.png',
			'price' => '0',
			'price_extra' => '0',
			'has_extra' => '0',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_sauces[]=array(
			'name' => __('Salsa Sauce', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'salsa-sauce.png',
			'image' => $default_image_url.'salsa-sauce.png',
			'image_extra' => $default_image_url.'salsa-sauce.png',
			'image_path' => $default_image_path.'salsa-sauce.png',
			'image_extra_path' => $default_image_path.'salsa-sauce.png',
			'price' => '0',
			'price_extra' => '0',
			'has_extra' => '0',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		#add_option( 'pizzatime_sauces', $default_sauces );

		foreach ($default_sauces as $sauce) {
			$wpdb->insert( $wpdb->prefix."pizzatime_sauces", $sauce );
		}
	}

	add_option( 'pizzatime_sauce_default', 1);

	$sql = "CREATE TABLE ".$wpdb->prefix."pizzatime_cheeses (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  name varchar(64) DEFAULT '' NOT NULL,
		  image varchar(2048) DEFAULT '' NOT NULL,
		  image_extra varchar(2048) DEFAULT '' NOT NULL,
		  image_path varchar(2048) DEFAULT '' NOT NULL,
		  image_extra_path varchar(2048) DEFAULT '' NOT NULL,
		  photo varchar(2048) DEFAULT '' NOT NULL,
		  description varchar(4096) DEFAULT '' NOT NULL,
		  price float DEFAULT '0' NOT NULL,
		  price_extra float DEFAULT '0' NOT NULL,
		  has_extra tinyint(1) DEFAULT '0' NOT NULL,
		  status tinyint(1) DEFAULT '0' NOT NULL,
		  sort_order int(10) DEFAULT '0' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

	dbDelta( $sql );

	$cols = $wpdb->get_col($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."pizzatime_cheeses LIMIT 1", null ) );
	$sort_order = 0;
	if ( empty($cols) ){
		

		$default_cheeses[]=array(
			'name' => __('Mozzarella', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'cheese.png',
			'image' => $default_image_url.'cheese.png',
			'image_extra' => $default_image_url.'cheese.png',
			'image_path' => $default_image_path.'cheese.png',
			'image_extra_path' => $default_image_path.'cheese.png',
			'price' => '0',
			'price_extra' => '0.5',
			'has_extra' => '1',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_cheeses[]=array(
			'name' => __('Gorgonzola', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'cheese.png',
			'image' => $default_image_url.'cheese.png',
			'image_extra' => $default_image_url.'cheese.png',
			'image_path' => $default_image_path.'cheese.png',
			'image_extra_path' => $default_image_path.'cheese.png',
			'price' => '0',
			'price_extra' => '0.5',
			'has_extra' => '1',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		foreach ($default_cheeses as $cheese) {
			$wpdb->insert( $wpdb->prefix."pizzatime_cheeses", $cheese );
		}

	}
	add_option( 'pizzatime_cheese_default', 1);


	$sql = "CREATE TABLE ".$wpdb->prefix."pizzatime_meats (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  name varchar(64) DEFAULT '' NOT NULL,
		  image varchar(2048) DEFAULT '' NOT NULL,
		  image_extra varchar(2048) DEFAULT '' NOT NULL,
		  image_path varchar(2048) DEFAULT '' NOT NULL,
		  image_extra_path varchar(2048) DEFAULT '' NOT NULL,
		  photo varchar(2048) DEFAULT '' NOT NULL,
		  description varchar(4096) DEFAULT '' NOT NULL,
		  price float DEFAULT '0' NOT NULL,
		  price_extra float DEFAULT '0' NOT NULL,
		  has_extra tinyint(1) DEFAULT '0' NOT NULL,
		  has_left_right tinyint(1) DEFAULT '1' NOT NULL,
		  is_ingredient tinyint(1) DEFAULT '1' NOT NULL,
		  status tinyint(1) DEFAULT '0' NOT NULL,
		  layer int(10) DEFAULT '0' NOT NULL,
		  sort_order int(10) DEFAULT '0' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

	dbDelta( $sql );

	$cols = $wpdb->get_col($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."pizzatime_meats LIMIT 1", null ) );
	$sort_order = 0;
	if ( empty($cols) ){

		$default_meats[]=array(
			'name' => __('Pepperoni', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'pepperoni.png',
			'image' => $default_image_url.'pepperoni.png',
			'image_extra' => $default_image_url.'pepperoni-extra.png',
			'image_path' => $default_image_path.'pepperoni.png',
			'image_extra_path' => $default_image_path.'pepperoni-extra.png',
			'price' => '0.5',
			'price_extra' => '1',
			'has_extra' => '1',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '30',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_meats[]=array(
			'name' => __('Shrimp', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'shrimp.png',
			'image' => $default_image_url.'shrimp.png',
			'image_extra' => $default_image_url.'shrimp-extra.png',
			'image_path' => $default_image_path.'shrimp.png',
			'image_extra_path' => $default_image_path.'shrimp-extra.png',
			'price' => '0.5',
			'price_extra' => '1',
			'has_extra' => '1',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '150',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_meats[]=array(
			'name' => __('Ham', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'ham.png',
			'image' => $default_image_url.'ham.png',
			'image_extra' => $default_image_url.'ham-extra.png',
			'image_path' => $default_image_path.'ham.png',
			'image_extra_path' => $default_image_path.'ham-extra.png',
			'price' => '0.5',
			'price_extra' => '1',
			'has_extra' => '1',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '50',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_meats[]=array(
			'name' => __('Chicken', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'chicken.png',
			'image' => $default_image_url.'chicken.png',
			'image_extra' => $default_image_url.'chicken-extra.png',
			'image_path' => $default_image_path.'chicken.png',
			'image_extra_path' => $default_image_path.'chicken-extra.png',
			'price' => '0.5',
			'price_extra' => '1',
			'has_extra' => '1',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '60',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_meats[]=array(
			'name' => __('Steak', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'steak.png',
			'image' => $default_image_url.'steak.png',
			'image_extra' => $default_image_url.'steak-extra.png',
			'image_path' => $default_image_path.'steak.png',
			'image_extra_path' => $default_image_path.'steak-extra.png',
			'price' => '0.5',
			'price_extra' => '1',
			'has_extra' => '1',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '70',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_meats[]=array(
			'name' => __('Bacon', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'bacon.png',
			'image' => $default_image_url.'bacon.png',
			'image_extra' => $default_image_url.'bacon-extra.png',
			'image_path' => $default_image_path.'bacon.png',
			'image_extra_path' => $default_image_path.'bacon-extra.png',
			'price' => '0.5',
			'price_extra' => '1',
			'has_extra' => '1',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '90',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_meats[]=array(
			'name' => __('Beef', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'beef.png',
			'image' => $default_image_url.'beef.png',
			'image_extra' => $default_image_url.'beef-extra.png',
			'image_path' => $default_image_path.'beef.png',
			'image_extra_path' => $default_image_path.'beef-extra.png',
			'price' => '0.5',
			'price_extra' => '1',
			'has_extra' => '1',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '100',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		foreach ($default_meats as $meat) {
			$wpdb->insert( $wpdb->prefix."pizzatime_meats", $meat );
		}

	}
	#add_option( 'pizzatime_meats', $default_meats);

	$sql = "CREATE TABLE ".$wpdb->prefix."pizzatime_toppings (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  name varchar(64) DEFAULT '' NOT NULL,
		  image varchar(2048) DEFAULT '' NOT NULL,
		  image_extra varchar(2048) DEFAULT '' NOT NULL,
		  image_path varchar(2048) DEFAULT '' NOT NULL,
		  image_extra_path varchar(2048) DEFAULT '' NOT NULL,
		  photo varchar(2048) DEFAULT '' NOT NULL,
		  description varchar(4096) DEFAULT '' NOT NULL,
		  price float DEFAULT '0' NOT NULL,
		  price_extra float DEFAULT '0' NOT NULL,
		  has_extra tinyint(1) DEFAULT '0' NOT NULL,
		  has_left_right tinyint(1) DEFAULT '1' NOT NULL,
		  is_ingredient tinyint(1) DEFAULT '1' NOT NULL,
		  status tinyint(1) DEFAULT '0' NOT NULL,
		  layer int(10) DEFAULT '0' NOT NULL,
		  sort_order int(10) DEFAULT '0' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

	dbDelta( $sql );

	$cols = $wpdb->get_col($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."pizzatime_toppings LIMIT 1", null ) );
	$sort_order = 10;
	if ( empty($cols) ){
		

		$default_toppings[]=array(
			'name' => __('Tomatoes', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'tomatoes.png',
			'image' => $default_image_url.'tomatoes.png',
			'image_extra' => $default_image_url.'tomatoes-extra.png',
			'image_path' => $default_image_path.'tomatoes.png',
			'image_extra_path' => $default_image_path.'tomatoes-extra.png',
			'price' => '0.5',
			'price_extra' => '1',
			'has_extra' => '1',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '40',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_toppings[]=array(
			'name' => __('Spinach', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'spinach.png',
			'image' => $default_image_url.'spinach.png',
			'image_extra' => $default_image_url.'spinach-extra.png',
			'image_path' => $default_image_path.'spinach.png',
			'image_extra_path' => $default_image_path.'spinach-extra.png',
			'price' => '0.5',
			'price_extra' => '1',
			'has_extra' => '1',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '80',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_toppings[]=array(
			'name' => __('Mushrooms', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'mushrooms.png',
			'image' => $default_image_url.'mushrooms.png',
			'image_extra' => $default_image_url.'mushrooms-extra.png',
			'image_path' => $default_image_path.'mushrooms.png',
			'image_extra_path' => $default_image_path.'mushrooms-extra.png',
			'price' => '0.5',
			'price_extra' => '1',
			'has_extra' => '1',
			'is_ingredient' => '1',
			'layer' => '110',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_toppings[]=array(
			'name' => __('Green Peppers', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'green-peppers.png',
			'image' => $default_image_url.'green-peppers.png',
			'image_extra' => $default_image_url.'green-peppers-extra.png',
			'image_path' => $default_image_path.'green-peppers.png',
			'image_extra_path' => $default_image_path.'green_peppers-extra.png',
			'has_extra' => '0',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'price' => '0.5',
			'price_extra' => '1',
			'layer' => '115',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_toppings[]=array(
			'name' => __('Red Peppers', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'red-peppers.png',
			'image' => $default_image_url.'red-peppers.png',
			'image_extra' => $default_image_url.'red-peppers-extra.png',
			'image_path' => $default_image_path.'red-peppers.png',
			'image_extra_path' => $default_image_path.'red-peppers-extra.png',
			'has_extra' => '0',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'price' => '0.5',
			'price_extra' => '1',
			'layer' => '120',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_toppings[]=array(
			'name' => __('Onions', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'onions.png',
			'image' => $default_image_url.'onions.png',
			'image_extra' => $default_image_url.'onions-extra.png',
			'image_path' => $default_image_path.'onions.png',
			'image_extra_path' => $default_image_path.'onions-extra.png',
			'has_extra' => '0',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'price' => '0.5',
			'price_extra' => '1',
			'layer' => '130',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_toppings[]=array(
			'name' => __('Black Olives', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'black-olives.png',
			'image' => $default_image_url.'black-olives.png',
			'image_extra' => $default_image_url.'black-olives-extra.png',
			'image_path' => $default_image_path.'black-olives.png',
			'image_extra_path' => $default_image_path.'black-olives-extra.png',
			'has_extra' => '0',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'price' => '0.5',
			'price_extra' => '1',
			'layer' => '140',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_toppings[]=array(
			'name' => __('Pineapples', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'pineapples.png',
			'image' => $default_image_url.'pineapples.png',
			'image_extra' => $default_image_url.'pineapples-extra.png',
			'image_path' => $default_image_path.'pineapples.png',
			'image_extra_path' => $default_image_path.'pineapples-extra.png',
			'has_extra' => '0',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'price' => '0.5',
			'price_extra' => '1',
			'layer' => '160',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_toppings[]=array(
			'name' => __('Corn', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'corn.png',
			'image' => $default_image_url.'corn.png',
			'image_extra' => $default_image_url.'corn-extra.png',
			'image_path' => $default_image_path.'corn.png',
			'image_extra_path' => $default_image_path.'corn-extra.png',
			'has_extra' => '0',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'price' => '0.5',
			'price_extra' => '1',
			'layer' => '170',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		foreach ($default_toppings as $topping) {
			$wpdb->insert( $wpdb->prefix."pizzatime_toppings", $topping );
		}

	}



	$sql = "CREATE TABLE ".$wpdb->prefix."pizzatime_dressings (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  name varchar(64) DEFAULT '' NOT NULL,
		  image varchar(2048) DEFAULT '' NOT NULL,
		  image_extra varchar(2048) DEFAULT '' NOT NULL,
		  image_path varchar(2048) DEFAULT '' NOT NULL,
		  image_extra_path varchar(2048) DEFAULT '' NOT NULL,
		  photo varchar(2048) DEFAULT '' NOT NULL,
		  description varchar(4096) DEFAULT '' NOT NULL,
		  price float DEFAULT '0' NOT NULL,
		  price_extra float DEFAULT '0' NOT NULL,
		  has_extra tinyint(1) DEFAULT '0' NOT NULL,
		  has_left_right tinyint(1) DEFAULT '1' NOT NULL,
		  is_ingredient tinyint(1) DEFAULT '0' NOT NULL,
		  status tinyint(1) DEFAULT '0' NOT NULL,
		  layer int(10) DEFAULT '0' NOT NULL,
		  sort_order int(10) DEFAULT '0' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

	dbDelta( $sql );

	$cols = $wpdb->get_col($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."pizzatime_dressings LIMIT 1", null ) );
	$sort_order = 0;
	if ( empty($cols) ){


		$default_dressings[]=array(
			'name' => __('BBQ Sauce', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'bbq-sauce-top.png',
			'image' => $default_image_url.'bbq-sauce-top.png',
			'image_extra' => $default_image_url.'bbq-sauce-top-extra.png',
			'image_path' => $default_image_path.'bbq-sauce-top.png',
			'image_extra_path' => $default_image_path.'bbq-sauce-top-extra.png',
			'price' => '',
			'price_extra' => '',
			'has_extra' => '0',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '180',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_dressings[]=array(
			'name' => __('Hot Sauce', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'hot-sauce.png',
			'image' => $default_image_url.'hot-sauce.png',
			'image_extra' => $default_image_url.'hot-sauce-extra.png',
			'image_path' => $default_image_path.'hot-sauce.png',
			'image_extra_path' => $default_image_path.'hot-sauce-extra.png',
			'price' => '',
			'price_extra' => '',
			'has_extra' => '0',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '190',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		$default_dressings[]=array(
			'name' => __('Ranch Dressing', 'pizzatime'),
			'description' => '',
			'photo' => $default_image_url.'ranch-dressing.png',
			'image' => $default_image_url.'ranch-dressing.png',
			'image_path' => $default_image_path.'ranch-dressing.png',
			'image_extra_path' => $default_image_path.'ranch-dressing-extra.png',
			'price' => '',
			'price_extra' => '',
			'has_extra' => '0',
			'has_left_right' => '1',
			'is_ingredient' => '1',
			'layer' => '200',
			'sort_order' => $sort_order+=10,
			'status' => '1'
		);
		foreach ($default_dressings as $dressing) {
			$wpdb->insert( $wpdb->prefix."pizzatime_dressings", $dressing );
		}

	}

	$sql = "CREATE TABLE ".$wpdb->prefix."pizzatime_presets (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  name varchar(64) DEFAULT '' NOT NULL,
		  crust mediumint(9) DEFAULT '0' NOT NULL,
		  sauce mediumint(9) DEFAULT '0' NOT NULL,
		  cheese mediumint(9) DEFAULT '0' NOT NULL,
		  sizes varchar(256) DEFAULT '0' NOT NULL,
		  meats varchar(256) DEFAULT '0' NOT NULL,
		  toppings varchar(256) DEFAULT '0' NOT NULL,
		  dressings varchar(256) DEFAULT '0' NOT NULL,
		  custom_ingredients varchar(256) DEFAULT '0' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

	dbDelta( $sql );

	$cols = $wpdb->get_col($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."pizzatime_presets LIMIT 1", null ) );
	$sort_order = 0;
	if ( empty($cols) ){
		$default_presets[]=array(
			'name' => 'Margherita',
			'crust' => '1',
			'sauce' => '1',
			'cheese' => '1',
		  	'sizes' => '1,2,3',
			'meats' => '',
			'toppings' => '1,2', 
			'dressings' => '',
			'custom_ingredients' =>  ''
		);

		foreach ($default_presets as $preset) {
			$wpdb->insert( $wpdb->prefix."pizzatime_presets", $preset );
		}

	}

	$sql = "CREATE TABLE ".$wpdb->prefix."pizzatime_custom_ingredients (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  category_id mediumint(9) default '1' NOT NULL,
		  name varchar(64) DEFAULT '' NOT NULL,
		  image varchar(2048) DEFAULT '' NOT NULL,
		  image_extra varchar(2048) DEFAULT '' NOT NULL,
		  image_path varchar(2048) DEFAULT '' NOT NULL,
		  image_extra_path varchar(2048) DEFAULT '' NOT NULL,
		  photo varchar(2048) DEFAULT '' NOT NULL,
		  description varchar(4096) DEFAULT '' NOT NULL,
		  price float DEFAULT '0' NOT NULL,
		  price_extra float DEFAULT '0' NOT NULL,
		  has_extra tinyint(1) DEFAULT '0' NOT NULL,
		  has_left_right tinyint(1) DEFAULT '1' NOT NULL,
		  is_ingredient tinyint(1) DEFAULT '0' NOT NULL,
		  status tinyint(1) DEFAULT '0' NOT NULL,
		  layer int(10) DEFAULT '0' NOT NULL,
		  sort_order int(10) DEFAULT '0' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

	dbDelta( $sql );
	

	$current_settings = get_option( 'pizzatime_settings' );

	$settings=array(
		'layout' => (isset($current_settings['layout']) ? $current_settings['layout'] : 'on_page'),
		'adjust_position' => (isset($current_settings['adjust_position']) ? $current_settings['adjust_position'] : '1'),
		'color_scheme' => (isset($current_settings['color_scheme']) ? $current_settings['color_scheme'] : 'default'),
		'maximum_ingredients' => (isset($current_settings['maximum_ingredients']) ? $current_settings['maximum_ingredients'] : ''),
		'price_multiplier_sections' => (isset($current_settings['price_multiplier_sections']) ? $current_settings['price_multiplier_sections'] : 'Crusts,Sauces,Cheeses,Meats,Toppings,Dressings'),
#		'hide_single_ingredient' => (isset($current_settings['hide_single_ingredient']) ? $current_settings['hide_single_ingredient'] : '1'),
		'left_right' => (isset($current_settings['left_right']) ? $current_settings['left_right'] : '1'),
		'jquery_ui_theme' => (isset($current_settings['jquery_ui_theme']) ? $current_settings['jquery_ui_theme'] : 'smoothness'),
		'api_login' => (isset($current_settings['api_login']) ? $current_settings['api_login'] : ''),
		'show_reviews' => (isset($current_settings['show_reviews']) ? $current_settings['show_reviews'] : '1'),
		'show_sizes' => (isset($current_settings['show_sizes']) ? $current_settings['show_sizes'] : '1'),
		'show_crusts' => (isset($current_settings['show_crusts']) ? $current_settings['show_crusts'] : '1'),
		'show_sauces' => (isset($current_settings['show_sauces']) ? $current_settings['show_sauces'] : '1'),
		'show_cheeses' => (isset($current_settings['show_cheeses']) ? $current_settings['show_cheeses'] : '1'),
		'show_meats' => (isset($current_settings['show_meats']) ? $current_settings['show_meats'] : '1'),
		'show_toppings' => (isset($current_settings['show_toppings']) ? $current_settings['show_toppings'] : '1'),
		'show_dressings' => (isset($current_settings['show_dressings']) ? $current_settings['show_dressings'] : '1'),
		'load_everywhere' => (isset($current_settings['load_everywhere']) ? $current_settings['load_everywhere'] : '')
	);

	update_option( 'pizzatime_settings', $settings );

	$current_pizzatime_sauce_required = get_option('pizzatime_sauce_required');
	if ($current_pizzatime_sauce_required=='')
		update_option( 'pizzatime_sauce_required', 1 );

	$current_pizzatime_cheese_required = get_option('pizzatime_cheese_required');
	if ($current_pizzatime_cheese_required=='')
		update_option( 'pizzatime_cheese_required', 1 );

	$meats_free_ingredients = get_option('meats_free_ingredients');
	if ($meats_free_ingredients=='')
		update_option( 'meats_free_ingredients', 0 );

	$toppings_free_ingredients = get_option('toppings_free_ingredients');
	if ($toppings_free_ingredients=='')
		update_option( 'toppings_free_ingredients', 0 );

	$dressings_free_ingredients = get_option('dressings_free_ingredients');
	if ($dressings_free_ingredients=='')
		update_option( 'dressings_free_ingredients', 0 );
	update_option( 'pizzatime_version', '1.0.8.6' );

}

function pizzatime_get_option ($option) {
	global $wpdb;
	$output=array();
	if ($option=='pizzatime_settings')
		return get_option($option);
	else {
		$results = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.$option, ARRAY_A );

		foreach ($results as $result) {
			$result['_option']=$option;
			$output[$result['id']]=$result;
		}

		return $output;

	}
}

function pizzatime_add_option ($option, $data) {
	add_option($data);
}

function pizzatime_update_option ($option, $data) {
	global $wpdb;

	switch ($option) {
		case 'pizzatime_settings' :
			update_option($option, $data);
		break;
		default :

			$wpdb->replace( $wpdb->prefix . $option, $data );


		break;
	
	}
}

add_action( 'plugins_loaded', 'pizzatime_load_textdomain' );
function pizzatime_load_textdomain() {
	load_plugin_textdomain( 'pizzatime', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
}

function pizzatime_filter_update_checks($queryArgs) {
	$settings = get_option('pizzatime_settings');
	if ( !empty($settings['api_login']) ) {
		$queryArgs['login'] = $settings['api_login'];
	}
	return $queryArgs;
}


function pizzatime_enqueue_scripts_backend() {
	global $wp_scripts;
	$pizzatime_current_version = get_option('pizzatime_version');


	if (isset($_GET['page']) && $_GET['page']=='pizzatime') {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'pizzatime-backend.js', plugin_dir_url( __FILE__ ).'js/pizzatime-backend.js', array( 'jquery' ), $pizzatime_current_version );
		wp_enqueue_script( 'jquery-ui.min.js',  plugin_dir_url( __FILE__ ).'ext/jquery-ui/jquery-ui.min.js', array( 'jquery' ), $pizzatime_current_version );
		wp_enqueue_script( 'tooltipster.js',  plugin_dir_url( __FILE__ ).'ext/tooltipster/js/jquery.tooltipster.js', array( 'jquery' ), $pizzatime_current_version );
		wp_enqueue_script( 'jquery.sumoselect.min.js',  plugin_dir_url( __FILE__ ).'ext/sumoselect/jquery.sumoselect.min.js', array( 'jquery' ), $pizzatime_current_version );
		wp_enqueue_style( 'sumoselect.css', plugin_dir_url( __FILE__ ).'ext/sumoselect/sumoselect.css', array(), $pizzatime_current_version );
		wp_enqueue_style( 'tooltipster.css', plugin_dir_url( __FILE__ ).'ext/tooltipster/css/tooltipster.css', array(), $pizzatime_current_version );
		wp_enqueue_style( 'jquery-ui.min.css', plugin_dir_url( __FILE__ ).'ext/jquery-ui/themes/default/jquery-ui.min.css', array(), $pizzatime_current_version );
		wp_enqueue_style( 'pizzatime-backend.css', plugin_dir_url( __FILE__ ).'css/pizzatime-backend.css', array(), $pizzatime_current_version );
	}

}

function pizzatime_enqueue_scripts_frontend() {
	global $post;
	if ( is_shop() ) return false;
	$available_variations = array();
	$post_object = get_post(get_the_ID());

	if ($post_object->post_type=='product') {
		$product = new WC_Product_Variable( get_the_ID() );
		if ( !method_exists( $product, 'get_available_variations' ) ) return false;
		$available_variations=$product->get_available_variations();
	}

	$pizzatime_current_version = get_option('pizzatime_version');

	$settings = get_option( 'pizzatime_settings' );

	if ($settings['load_everywhere']!='on') $condition = (isset( $available_variations[0]['attributes']['attribute_pa_pizzatime_ingredients'] ) );
	else $condition = true;

	if ( $condition ) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'pizzatime-jquery-ui.js',  plugin_dir_url( __FILE__ ).'ext/jquery-ui/jquery-ui.min.js', array( 'jquery' ), $pizzatime_current_version );
# 		wp_enqueue_script( 'event-manager.js',  plugin_dir_url( __FILE__ ).'ext/event-manager/event-manager.js', array('jquery'), $pizzatime_current_version );
		wp_enqueue_script( 'accounting.js',  plugin_dir_url( __FILE__ ).'ext/accounting/accounting.min.js', array('jquery'), $pizzatime_current_version );
		wp_enqueue_script( 'jquery.stickyelement.js',  plugin_dir_url( __FILE__ ).'ext/sticky-element/jquery.stickyelement.js', array('jquery'), $pizzatime_current_version );
		wp_enqueue_script( 'pizzatime-frontend.js',  plugin_dir_url( __FILE__ ).'js/pizzatime-frontend.js', array('jquery'), $pizzatime_current_version );
		wp_enqueue_style( 'pizzatime-jquery-ui.min.css', plugin_dir_url( __FILE__ ).'ext/jquery-ui/themes/'.$settings['color_scheme'].'/jquery-ui.min.css', array(), $pizzatime_current_version );
		wp_enqueue_style( 'pizzatime-jquery-ui.theme.min.css', plugin_dir_url( __FILE__ ).'ext/jquery-ui/themes/'.$settings['color_scheme'].'/jquery-ui.theme.min.css', array(), $pizzatime_current_version );
		wp_enqueue_style( 'pizzatime-jquery-ui.structure.min.css', plugin_dir_url( __FILE__ ).'ext/jquery-ui/themes/'.$settings['color_scheme'].'/jquery-ui.structure.min.css', array(), $pizzatime_current_version );
		wp_enqueue_style( 'pizzatime-frontend.css', plugin_dir_url( __FILE__ ).'css/pizzatime-frontend.css', array(), $pizzatime_current_version );


		

		wp_localize_script( 'pizzatime-frontend.js', 'pizzatime',
			array(
				'maximum_ingredients' => $settings['maximum_ingredients'],
				'adjust_position' => $settings['adjust_position'],
				'currency_symbol' => get_woocommerce_currency_symbol(),
				'currency_position' => get_option( 'woocommerce_currency_pos' ),
				'thousand_sep' => get_option( 'woocommerce_price_thousand_sep', ',' ),
				'decimal_sep' => get_option( 'woocommerce_price_decimal_sep', '.' ),
				'price_num_decimals' => wc_get_price_decimals(),
				'show_reviews' => $settings['show_reviews'],
				'text_left' => __('Left', 'pizzatime'),
				'text_right' => __('Right', 'pizzatime'),
				'text_extra' => __('Extra', 'pizzatime'),
				'text_maximum' => __(sprintf('You have exceeded the maximum number of ingredients (%s).', $settings['maximum_ingredients']), 'pizzatime'),

			)
		);


		//look for product_page shortcode
		if (isset($post->post_content)) {
			$pattern = get_shortcode_regex();
			preg_match('/'.$pattern.'/s', $post->post_content, $matches);

			if (is_array($matches) && $matches[2] == 'product_page') {
				$shortcode = $matches[0];
				list (, $product_id) = explode('=', $matches[3]);
			}
		}
		if  ( pizzatime_is_pizzatime( get_queried_object_id() ) || pizzatime_is_pizzatime ( get_the_ID() ) || pizzatime_is_pizzatime ( $product_id )) {
			$fix_css = "
				form.variations_form.cart div.woocommerce-variation-add-to-cart {
					display:none;
				}
				table.variations {
					display:none;
				}
			";
			if ($settings['show_reviews']=='0')
				$fix_css .= "
					div.woocommerce-tabs.wc-tabs-wrapper {
						display:none;
					}";
			if ($settings['color_scheme']=='blitzer') {
				$fix_css .= '
					.pizzatime-rad-0 > input:checked + i {
						background-image: url("'.plugin_dir_url( dirname(__FILE__) ).'images/radio0-selected-red.png") !important;
						border: 1px solid red !important;
					}

					.pizzatime-rad-1 > input:checked + i {
					 	background-image: url("'.plugin_dir_url( dirname(__FILE__) ).'images/radio1-selected-red.png") !important;
						border: 1px solid red !important;
					}

					.pizzatime-rad-2 > input:checked + i {
						background-image: url("'.plugin_dir_url( dirname(__FILE__) ).'images/radio2-selected-red.png") !important;
						border: 1px solid red !important;
					}

					.pizzatime-rad-3 > input:checked + i {
						background-image: url("'.plugin_dir_url( dirname(__FILE__) ).'images/radio3-selected-red.png") !important;
						border: 1px solid red !important;
					} ';
			}
		}
		wp_add_inline_style( 'pizzatime-frontend.css', $fix_css );




	}
}


add_action( 'admin_init', 'pizzatime_plugin_redirect' );
function pizzatime_plugin_redirect() {
	if ( get_option( 'pizzatime_do_activation_redirect', false ) ) {
		delete_option( 'pizzatime_do_activation_redirect' );
		if ( !isset( $_GET['activate-multi'] ) ) {
			wp_redirect( admin_url( 'admin.php?page=pizzatime' ) );exit;
		}
	}
}


function pizzatime_deactivate() {
	global $wpdb;
/*
	$postlist = get_posts(array('post_type'  => 'product'));
	foreach ( $postlist as $post ) {
		if ( pizzatime_is_pizzatime( $post->ID ) ) pizzatime_delete_pizzatime( $post->ID );
	}
*/

	do_action( 'pizzatime_deactivate' );
}

function pizzatime_delete_pizzatime( $post_id ) {
	$children = get_posts( array(
			'post_parent'  => $post_id,
			'posts_per_page'=> -1,
			'post_type'  => 'product_variation',
			'fields'   => 'ids',
			'post_status' => 'publish'
		) );
	if ( count( $children ) ) {
		foreach ( $children as $child_id ) {
			$child_meta=get_post_meta( $child_id );
			if ( isset( $child_meta['attribute_pa_ingredients'] ) ) {
				wp_delete_post( $child_id );
			}
		}
	}
}

function pizzatime_is_pizzatime( $post_id ) {
        if (!is_numeric($post_id)) return false;
	$children = get_posts( array(
			'post_parent'  => $post_id,
			'posts_per_page'=> -1,
			'post_type'  => 'product_variation',
			'fields'   => 'ids',
			'post_status' => 'publish'
		) );

	if ( count( $children ) ) {

		foreach ( $children as $child_id ) {
			$child_meta=get_post_meta( $child_id );
			if ( isset( $child_meta['attribute_pa_pizzatime_ingredients'] ) ) {
				return true;
			}
		}
	}
	return false;
}

function pizzatime_fix_price ($price) {	
	return str_replace(',', '.', $price);
}


add_filter( 'product_type_options', 'pizzatime_product_type_options' );
function pizzatime_product_type_options( $options ) {

	if ( isset( $_REQUEST['post'] ) ) {
		$post_id=(int)$_REQUEST['post'];
		if ( pizzatime_is_pizzatime( $post_id ) ) $default='yes';
		else $default='no';
	}
	else $default='no';

	$new_option=array( 'pizzatime' => array(
			'id'            => '_pizzatime',
			'wrapper_class' => 'show_if_variable',
			'label'         => __( 'PizzaTime Product', 'pizzatime' ),
			'description'   => __( 'PizzaTime Product', 'pizzatime' ),
			'default'       => $default
		) );
	$options['pizzatime']=$new_option['pizzatime'];
	return $options;
}

//PizzaTime product checked
function pizzatime_save_post( $post_id ) {

	if ( wp_is_post_revision( $post_id ) )
		return;
	if ( isset( $_POST['post_ID'] ) && $_POST['post_ID']==$post_id ) {

		if ( isset( $_POST['_pizzatime'] ) && $_POST['_pizzatime']=='on' ) {

			update_post_meta( $post_id, '_pizzatime', 'yes' );

			if ( pizzatime_is_pizzatime( $post_id ) ) return false;
			wp_set_object_terms( $post_id, 'all', 'pa_pizzatime_ingredients' , false );

			$attrs = array(
				'pa_pizzatime_ingredients'=>array(
					'name'=>'pa_pizzatime_ingredients',
					'value'=>'',
					'is_visible' => '0',
					'is_variation' => '1',
					'is_taxonomy' => '1'
				)
			);

			update_post_meta( $post_id, '_product_attributes', $attrs );
			update_post_meta( $post_id, '_price', '1' );
			update_post_meta( $post_id, '_visibility', 'visible' );
			update_post_meta( $post_id, '_stock_status', 'instock' );


			$new_post = array(
				'post_title'=> "Variation #".( $post_id+1 )." of $post_id",
				'post_name' => 'product-' . $post_id . '-variation',
				'post_status' => 'publish',
				'post_parent' => $post_id,
				'post_type' => 'product_variation',
				'guid'=>home_url() . '/?product_variation=product-' . $post_id . '-variation'
			);
			$variation_id = wp_insert_post( $new_post );

			update_post_meta( $post_id, '_min_regular_price_variation_id', $variation_id );
			update_post_meta( $post_id, '_max_regular_price_variation_id', $variation_id );
			update_post_meta( $post_id, '_min_price_variation_id', $variation_id );
			update_post_meta( $post_id, '_max_price_variation_id', $variation_id );

			update_post_meta( $post_id, '_min_variation_price', 1 );
			update_post_meta( $post_id, '_max_variation_price', 1 );
			update_post_meta( $post_id, '_min_variation_regular_price', 1 );
			update_post_meta( $post_id, '_max_variation_regular_price', 1 );

			update_post_meta( $variation_id, '_price', '1' );
			update_post_meta( $variation_id, '_regular_price', '1' );
			update_post_meta( $variation_id, '_stock_status', 'instock' );

			update_post_meta( $variation_id, 'attribute_pa_pizzatime_ingredients', '' );

			wp_set_object_terms( $variation_id, '1', 'pa_pizzatime_ingredients' , false );

			wp_update_post( array( 'ID'=>$post_id, 'post_status'=>'publish' ) );
		}
		else if ( pizzatime_is_pizzatime( $post_id ) ) {
				delete_post_meta( $post_id, '_pizzatime' );
				pizzatime_delete_pizzatime( $post_id );
			}
	}

}
add_action( 'save_post', 'pizzatime_save_post' );




function pizzatime_sort_by_layer ($array) {
	$sort = array();
	foreach($array as $key=>$value) {
		$sort['name'][$key] = $value['name'];
		$sort['layer'][$key] = $value['layer'];
	}

	array_multisort($sort['layer'], SORT_ASC, $sort['name'], SORT_ASC, $array);
	return $array;
}

function pizzatime_sort_by_sort_order ($array) {
	$sort = array();
	foreach($array as $key=>$value) {
		$sort['name'][$key] = $value['name'];
		$sort['sort_order'][$key] = $value['sort_order'];
	}

	array_multisort($sort['sort_order'], SORT_ASC, $sort['name'], SORT_ASC, $array);
	return $array;
}

function pizzatime_sort_by_name ($array) {
	$sort = array();
	foreach($array as $key=>$value) {
		$sort['name'][$key] = $value['name'];
		if (isset($value['layer']))
			$sort['layer'][$key] = $value['layer'];
		else $sort['layer'][$key] = 9999;
	}

	array_multisort($sort['name'], SORT_ASC, $sort['layer'], SORT_ASC, $array);
	return $array;
}



#add_filter('woocommerce_variation_is_visible', 'pizzatime_variation_is_visible', 10, 3);
#function pizzatime_variation_is_visible( $visible, $variation_id, $id) {
#	if ( pizzatime_is_pizzatime($id) ) $visible = false;
#	return $visible;
#}

/*add_filter('woocommerce_hide_invisible_variations', 'pizzatime_hide_invisible_variations', 10, 3);
function pizzatime_hide_invisible_variations ($visible, $id, $variation) {
	if ( pizzatime_is_pizzatime($id) ) $visible = false;

	return $visible;
}*/

add_action('woocommerce_before_add_to_cart_form', 'pizzatime_before_add_to_cart_form', 10, 0);
function pizzatime_before_add_to_cart_form() {
	//hack to make the attribute selected by default
#	$_REQUEST['attribute_pa_pizzatime_ingredients']='all';
}

add_action('woocommerce_before_add_to_cart_button', 'pizzatime_before_add_to_cart_button', 10, 0);
function pizzatime_before_add_to_cart_button() {
	global $product;
	if (pizzatime_is_pizzatime($product->id)) {
		$settings = pizzatime_get_option( 'pizzatime_settings' );
		$sizes = pizzatime_sort_by_sort_order(pizzatime_get_option( 'pizzatime_sizes' ));
		$crusts = pizzatime_sort_by_sort_order(pizzatime_get_option( 'pizzatime_crusts' ));
		$sauces = pizzatime_sort_by_sort_order(pizzatime_get_option( 'pizzatime_sauces' ));
		$cheeses = pizzatime_sort_by_sort_order(pizzatime_get_option( 'pizzatime_cheeses' ));
		$meats = pizzatime_sort_by_sort_order(pizzatime_get_option( 'pizzatime_meats' ));
		$toppings = pizzatime_sort_by_sort_order(pizzatime_get_option( 'pizzatime_toppings' ));
		$dressings = pizzatime_sort_by_sort_order(pizzatime_get_option( 'pizzatime_dressings' ));
		$presets = pizzatime_get_option( 'pizzatime_presets' );
		$customs=get_option( 'pizzatime_customs' );
		$custom_ingredients=pizzatime_get_option( 'pizzatime_custom_ingredients' );
		$pizzatime_size_default = get_option( 'pizzatime_size_default' );
		$pizzatime_crust_default = get_option( 'pizzatime_crust_default' );
		$pizzatime_sauce_default = get_option( 'pizzatime_sauce_default' );
		$pizzatime_cheese_default = get_option( 'pizzatime_cheese_default' );
		$pizzatime_sauce_required = get_option('pizzatime_sauce_required');
		$pizzatime_cheese_required = get_option('pizzatime_cheese_required');

		$pizzatime_preset_meta = get_post_meta($product->id, 'pizzatime_preset'); 
		if (is_array($pizzatime_preset_meta) && count($pizzatime_preset_meta)) {
			$pizzatime_preset = $pizzatime_preset_meta[0];
			$pizzatime_is_customizable_meta = get_post_meta($product->id, 'pizzatime_is_customizable'); 
			$pizzatime_is_customizable = $pizzatime_is_customizable_meta[0];
		}

		$current_preset=false;
		$pizzatime_is_customizable = '1';





		echo '<div id="pizzatime-accordion">';
		echo '<h3 '.($settings['show_sizes']=='0' ? 'style="display:none;"' : '').'>'.__('Sizes', 'pizzatime').'</h3>';
		echo '<div '.($settings['show_sizes']=='0' ? 'style="display:none;"' : '').' class="pizzatime-ingredient-list">';
		foreach ($sizes as $size) {
			if ($size['status']=='0') continue;
			if ($current_preset && !in_array($size['id'], explode(',',$current_preset['sizes']))) continue;
			echo '<div class="pizzatime-ingredient">';
			echo '<div class="pizzatime-ingredient-left">';
			if ($size['photo']) {
				echo '<img class="pizzatime-preview pizzatime-desc-image" src="'.esc_url($size['photo']).'">';
			}
			else {
				echo '<img class="pizzatime-preview" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">';
			}
			echo '</div>';
			echo '<div class="pizzatime-ingredient-right">';
			echo '<p class="pizzatime-ingredient-name">'.str_replace('\\','',esc_html__($size['name'], 'pizzatime')).'</p>';
			echo '<p class="pizzatime-ingredient-description">'.str_replace('\\','',esc_html__($size['description'], 'pizzatime')).'</p>';

			if ($pizzatime_size_default==$size['id']) { 
				$checked='checked="checked"'; 
			}
			else $checked='';


			$pizzatime_hidden = '';	

			echo '<label class="pizzatime-label pizzatime-rad-0 pizzatime-hidden"><input type="radio" name="pizzatime-input-sizes['.$size['id'].']" data-type="sizes" data-single="1" data-id="'.$size['id'].'" data-price="0" data-price-extra="0" value="0" class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			echo '<label class="pizzatime-label pizzatime-rad-3"><input type="radio" name="pizzatime-input-sizes['.$size['id'].']" data-type="sizes" data-single="1" data-id="'.$size['id'].'" data-name="'.esc_attr($size['name']).'" data-price="'.$size['price'].'" data-price-multiplier="'.$size['price_multiplier'].'" value="3" '.$checked.' class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';

//		if ($pizzatime_is_customizable=='0') $accordion_class = 'pizzatime-hidden';
		echo '<h3 '.(($settings['show_crusts']=='0' || $pizzatime_is_customizable=='0') ? 'style="display:none;"' : '').'>'.__('Crusts', 'pizzatime').'</h3>';
		echo '<div '.(($settings['show_crusts']=='0' || $pizzatime_is_customizable=='0') ? 'style="display:none;"' : '').' class="pizzatime-ingredient-list" data-use-multiplier="'.(int)in_array('Crusts', explode(',',$settings['price_multiplier_sections'])).'">';
		foreach ($crusts as $crust) {
			if ($crust['status']=='0') continue;
			echo '<div class="pizzatime-ingredient">';
			echo '<div class="pizzatime-ingredient-left">';
			if ($crust['photo']) {
				echo '<img class="pizzatime-preview pizzatime-desc-image" src="'.esc_url($crust['photo']).'">';
			}
			else {
				echo '<img class="pizzatime-preview" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">';
			}
			echo '</div>';
			echo '<div class="pizzatime-ingredient-right">';
			echo '<p class="pizzatime-ingredient-name">'.str_replace('\\','',esc_html__($crust['name'], 'pizzatime')).'</p>';
			echo '<p class="pizzatime-ingredient-description">'.str_replace('\\','',esc_html__($crust['description'], 'pizzatime')).'</p>';

			if ($current_preset) {
				if ($current_preset['crust']==$crust['id']) {
					$checked='checked="checked"'; 
				}
				else $checked='';
			}
			else {
				if ($pizzatime_crust_default==$crust['id']) { 
					$checked='checked="checked"'; 
				}
				else $checked='';
			}



			echo '<label class="pizzatime-label pizzatime-rad-0 pizzatime-hidden"><input type="radio" name="pizzatime-input-crusts['.$crust['id'].']" data-type="crusts" data-single="1" data-id="'.$crust['id'].'" data-price="0" data-price-extra="0" value="0" class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			echo '<label class="pizzatime-label pizzatime-rad-3"><input type="radio" name="pizzatime-input-crusts['.$crust['id'].']" data-type="crusts" data-single="1" data-id="'.$crust['id'].'" data-layer="0" data-layer="0" data-name="'.esc_attr($crust['name']).'" data-price="'.$crust['price'].'" value="3" '.$checked.' class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';

		echo '<h3 '.(($settings['show_sauces']=='0' || $pizzatime_is_customizable=='0') ? 'style="display:none;"' : '').'>'.__('Sauces', 'pizzatime').'</h3>';
		echo '<div '.(($settings['show_sauces']=='0' || $pizzatime_is_customizable=='0') ? 'style="display:none;"' : '').' class="pizzatime-ingredient-list" data-use-multiplier="'.(int)in_array('Sauces', explode(',',$settings['price_multiplier_sections'])).'">';
		foreach ($sauces as $sauce) {
			if ($sauce['status']=='0') continue;
			echo '<div class="pizzatime-ingredient">';
			echo '<div class="pizzatime-ingredient-left">';
			if ($sauce['photo']) {
				echo '<img class="pizzatime-preview pizzatime-desc-image" src="'.esc_url($sauce['photo']).'">';
			}
			else {
				echo '<img class="pizzatime-preview" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">';
			}
			echo '</div>';
			echo '<div class="pizzatime-ingredient-right">';
			echo '<p class="pizzatime-ingredient-name">'.str_replace('\\','',esc_html__($sauce['name'], 'pizzatime')).'</p>';
			echo '<p class="pizzatime-ingredient-description">'.str_replace('\\','',esc_html__($sauce['description'], 'pizzatime')).'</p>';

			if ($current_preset) {
				if ($current_preset['sauce']==$sauce['id']) {
					$checked='checked="checked"'; 
				}
				else $checked='';
			}
			else {
				if ($pizzatime_sauce_default==$sauce['id']) { 
					$checked='checked="checked"'; 
				}
				else $checked='';
			}

			if ($pizzatime_sauce_required == '1') { 
				$pizzatime_hidden = 'pizzatime-hidden';	

			}
			else $pizzatime_hidden = '';	

			echo '<label class="pizzatime-label pizzatime-rad-0 '.$pizzatime_hidden.'"><input type="radio" name="pizzatime-input-sauces['.$sauce['id'].']" data-type="sauces" data-single="1" data-id="'.$sauce['id'].'" data-price="0" data-price-extra="0" value="0" class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			echo '<label class="pizzatime-label pizzatime-rad-3"><input type="radio" name="pizzatime-input-sauces['.$sauce['id'].']" data-type="sauces" data-single="1" data-id="'.$sauce['id'].'" data-layer="1" data-name="'.esc_attr($sauce['name']).'" data-price="'.$sauce['price'].'" value="3" '.$checked.' class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			if ($sauce['has_extra']==1) {
				echo '<fieldset class="pizzatime-fieldset pizzatime-invisible">';
				echo '<label id="pizzatime-label-sauces-extra-'.$sauce['id'].'-regular" class="pizzatime-button-label" for="pizzatime-input-sauces-portion-'.$sauce['id'].'-regular">'.__('Regular', 'pizzatime').'<input type="radio" autocomplete="off" class="pizzatime-checkbox pizzatime-regular" data-type="sauces" data-price="'.$sauce['price_extra'].'" data-id="extra-'.$sauce['id'].'-regular" id="pizzatime-input-sauces-portion-'.$sauce['id'].'-regular" name="pizzatime-input-sauces-portion['.$sauce['id'].']" value="regular"></label>';
				echo '<label id="pizzatime-label-sauces-extra-'.$sauce['id'].'-extra" class="pizzatime-button-label" for="pizzatime-input-sauces-portion-'.$sauce['id'].'-extra">'.__('Extra', 'pizzatime').'<input type="radio" autocomplete="off" class="pizzatime-checkbox pizzatime-extra" data-type="sauces" data-price="'.$sauce['price_extra'].'" data-id="extra-'.$sauce['id'].'-extra" id="pizzatime-input-sauces-portion-'.$sauce['id'].'-extra" name="pizzatime-input-sauces-portion['.$sauce['id'].']" value="extra"></label>';
				echo '</fieldset>';
			}

			echo '</div>';
			echo '</div>';
		}
		echo '</div>';

		echo '<h3 '.(($settings['show_cheeses']=='0' || $pizzatime_is_customizable=='0') ? 'style="display:none;"' : '').'>'.__('Cheeses', 'pizzatime').'</h3>';
		echo '<div '.(($settings['show_cheeses']=='0' || $pizzatime_is_customizable=='0') ? 'style="display:none;"' : '').' class="pizzatime-ingredient-list" data-use-multiplier="'.(int)in_array('Cheeses', explode(',',$settings['price_multiplier_sections'])).'">';
		foreach ($cheeses as $cheese) {
			if ($cheese['status']=='0') continue;
			echo '<div class="pizzatime-ingredient">';
			echo '<div class="pizzatime-ingredient-left">';
			if ($cheese['photo']) {
				echo '<img class="pizzatime-preview pizzatime-desc-image" src="'.esc_url($cheese['photo']).'">';
			}
			else {
				echo '<img class="pizzatime-preview" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">';
			}
			echo '</div>';
			echo '<div class="pizzatime-ingredient-right">';
			echo '<p class="pizzatime-ingredient-name">'.str_replace('\\','',esc_html__($cheese['name'], 'pizzatime')).'</p>';
			echo '<p class="pizzatime-ingredient-description">'.str_replace('\\','',esc_html__($cheese['description'], 'pizzatime')).'</p>';

			if ($current_preset) {
				if ($current_preset['cheese']==$cheese['id']) {
					$checked='checked="checked"'; 
				}
				else $checked='';
			}
			else {
				if ($pizzatime_cheese_default==$cheese['id']) { 
					$checked='checked="checked"'; 
				}
				else $checked='';
			}

			if ($pizzatime_cheese_required == '1') $pizzatime_hidden = 'pizzatime-hidden';	
			else $pizzatime_hidden = '';	

			echo '<label class="pizzatime-label pizzatime-rad-0 '.$pizzatime_hidden.'"><input type="radio" name="pizzatime-input-cheeses['.$cheese['id'].']" data-type="cheeses" data-single="1" data-id="'.$cheese['id'].'" data-price="0" data-price-extra="0" value="0" class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			echo '<label class="pizzatime-label pizzatime-rad-3"><input type="radio" name="pizzatime-input-cheeses['.$cheese['id'].']" data-type="cheeses" data-single="1" data-id="'.$cheese['id'].'" data-layer="2" data-name="'.esc_attr($cheese['name']).'" data-price="'.$cheese['price'].'" data-price-extra="'.$cheese['price_extra'].'" value="3" '.$checked.' class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			if ($cheese['has_extra']==1) {
				echo '<fieldset class="pizzatime-fieldset pizzatime-invisible">';
				echo '<label id="pizzatime-label-cheeses-extra-'.$cheese['id'].'-regular" class="pizzatime-button-label" for="pizzatime-input-cheeses-portion-'.$cheese['id'].'-regular">'.__('Regular', 'pizzatime').'<input type="radio" autocomplete="off" class="pizzatime-checkbox pizzatime-regular" data-type="cheeses" data-price="'.$cheese['price_extra'].'" data-id="extra-'.$cheese['id'].'-regular" id="pizzatime-input-cheeses-portion-'.$cheese['id'].'-regular" name="pizzatime-input-cheeses-portion['.$cheese['id'].']" value="regular"></label>';
				echo '<label id="pizzatime-label-cheeses-extra-'.$cheese['id'].'-extra" class="pizzatime-button-label" for="pizzatime-input-cheeses-portion-'.$cheese['id'].'-extra">'.__('Extra', 'pizzatime').'<input type="radio" autocomplete="off" class="pizzatime-checkbox pizzatime-extra" data-type="cheeses" data-price="'.$cheese['price_extra'].'" data-id="extra-'.$cheese['id'].'-extra" id="pizzatime-input-cheeses-portion-'.$cheese['id'].'-extra" name="pizzatime-input-cheeses-portion['.$cheese['id'].']" value="extra"></label>';
				echo '</fieldset>';
			}
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';


		echo '<h3 '.(($settings['show_meats']=='0' || $pizzatime_is_customizable=='0') ?  'style="display:none;"' : '').'>'.__('Meats', 'pizzatime').'</h3>';
		echo '<div '.(($settings['show_meats']=='0' || $pizzatime_is_customizable=='0') ?  'style="display:none;"' : '').' class="pizzatime-ingredient-list" data-free-ingredients="'.(int)get_option('meats_free_ingredients').'" data-use-multiplier="'.(int)in_array('Meats', explode(',',$settings['price_multiplier_sections'])).'">';
		foreach ($meats as $meat) {
			if ($meat['status']=='0') continue;
			echo '<div class="pizzatime-ingredient">';
			echo '<div class="pizzatime-ingredient-left">';
			if ($meat['photo']) {
				echo '<img class="pizzatime-preview pizzatime-desc-image" src="'.esc_url($meat['photo']).'">';
			}
			else {
				echo '<img class="pizzatime-preview" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">';
			}
			echo '</div>';
			echo '<div class="pizzatime-ingredient-right">';
			echo '<p class="pizzatime-ingredient-name">'.str_replace('\\','',esc_html__($meat['name'], 'pizzatime')).'</p>';
			echo '<p class="pizzatime-ingredient-description">'.str_replace('\\','',esc_html__($meat['description'], 'pizzatime')).'</p>';

			$checked='';
			if ($current_preset && count($current_preset['meats'])) {
				if (in_array($meat['id'], explode(',', $current_preset['meats']))) {
					$checked='checked="checked"'; 
				}
				else $checked='';
			}

			echo '<label class="pizzatime-label pizzatime-rad-0"><input type="radio" name="pizzatime-input-meats['.$meat['id'].']" data-type="meats" data-id="'.$meat['id'].'" data-price="0" data-price-extra="0" value="0" class="pizzatime-input pizzatime-for-layer" checked="checked" autocomplete="off"><i></i></label>';
			if ($meat['has_left_right']==1) {
				echo '<label class="pizzatime-label pizzatime-rad-1"><input type="radio" name="pizzatime-input-meats['.$meat['id'].']" data-type="meats" data-id="'.$meat['id'].'" data-layer="'.$meat['layer'].'" data-name="'.esc_attr($meat['name']).'" data-part="1" data-ingredient="'.$meat['is_ingredient'].'" data-price="'.$meat['price'].'" data-price-extra="'.$meat['price_extra'].'" value="1" class="pizzatime-input pizzatime-for-layer pizzatime-half" autocomplete="off"><i></i></label>';
				echo '<label class="pizzatime-label pizzatime-rad-2"><input type="radio" name="pizzatime-input-meats['.$meat['id'].']" data-type="meats" data-id="'.$meat['id'].'" data-layer="'.$meat['layer'].'" data-name="'.esc_attr($meat['name']).'" data-part="1" data-ingredient="'.$meat['is_ingredient'].'" data-price="'.$meat['price'].'" data-price-extra="'.$meat['price_extra'].'" value="2" class="pizzatime-input pizzatime-for-layer pizzatime-half" autocomplete="off"><i></i></label>';
			}
			echo '<label class="pizzatime-label pizzatime-rad-3"><input type="radio" name="pizzatime-input-meats['.$meat['id'].']" data-type="meats" data-id="'.$meat['id'].'" data-layer="'.$meat['layer'].'" data-name="'.esc_attr($meat['name']).'" data-ingredient="'.$meat['is_ingredient'].'" data-price="'.$meat['price'].'" data-price-extra="'.$meat['price_extra'].'" value="3" '.$checked.' class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			if ($meat['has_extra']==1) {
				echo '<fieldset class="pizzatime-fieldset pizzatime-invisible">';
				echo '<label id="pizzatime-label-meats-extra-'.$meat['id'].'-regular" class="pizzatime-button-label" for="pizzatime-input-meats-portion-'.$meat['id'].'-regular">'.__('Regular', 'pizzatime').'<input type="radio" autocomplete="off" class="pizzatime-checkbox pizzatime-regular" data-type="meats" data-price="'.$meat['price_extra'].'" data-id="extra-'.$meat['id'].'-regular" id="pizzatime-input-meats-portion-'.$meat['id'].'-regular" name="pizzatime-input-meats-portion['.$meat['id'].']" value="regular"></label>';
				echo '<label id="pizzatime-label-meats-extra-'.$meat['id'].'-extra" class="pizzatime-button-label" for="pizzatime-input-meats-portion-'.$meat['id'].'-extra">'.__('Extra', 'pizzatime').'<input type="radio" autocomplete="off" class="pizzatime-checkbox pizzatime-extra" data-type="meats" data-price="'.$meat['price_extra'].'" data-id="extra-'.$meat['id'].'-extra" id="pizzatime-input-meats-portion-'.$meat['id'].'-extra" name="pizzatime-input-meats-portion['.$meat['id'].']" value="extra"></label>';
				echo '</fieldset>';
			}
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';

		echo '<h3 '.(($settings['show_toppings']=='0' || $pizzatime_is_customizable=='0') ?  'style="display:none;"' : '').'>'.__('Toppings', 'pizzatime').'</h3>';
		echo '<div '.(($settings['show_toppings']=='0' || $pizzatime_is_customizable=='0') ?  'style="display:none;"' : '').' class="pizzatime-ingredient-list" data-free-ingredients="'.(int)get_option('toppings_free_ingredients').'" data-use-multiplier="'.(int)in_array('Toppings', explode(',',$settings['price_multiplier_sections'])).'">';
		foreach ($toppings as $topping) {
			echo '<div class="pizzatime-ingredient">';
			echo '<div class="pizzatime-ingredient-left">';
			if ($topping['photo']) {
				echo '<img class="pizzatime-preview pizzatime-desc-image" src="'.esc_url($topping['photo']).'">';
			}
			else {
				echo '<img class="pizzatime-preview" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">';
			}
			echo '</div>';
			echo '<div class="pizzatime-ingredient-right">';
			echo '<p class="pizzatime-ingredient-name">'.str_replace('\\','',esc_html__($topping['name'], 'pizzatime')).'</p>';
			echo '<p class="pizzatime-ingredient-description">'.str_replace('\\','',esc_html__($topping['description'], 'pizzatime')).'</p>';

			$checked='';
			if ($current_preset && count($current_preset['toppings'])) {
				if (in_array($topping['id'], explode(',', $current_preset['toppings']))) {
					$checked='checked="checked"'; 
				}
				else $checked='';
			}

			echo '<label class="pizzatime-label pizzatime-rad-0"><input type="radio" name="pizzatime-input-toppings['.$topping['id'].']" data-type="toppings" data-id="'.$topping['id'].'" data-price="0" data-price-extra="0" value="0" class="pizzatime-input pizzatime-for-layer" checked="checked" autocomplete="off"><i></i></label>';
			if ($topping['has_left_right']==1) {
				echo '<label class="pizzatime-label pizzatime-rad-1"><input type="radio" name="pizzatime-input-toppings['.$topping['id'].']" data-type="toppings" data-ingredient="'.$topping['is_ingredient'].'" data-id="'.$topping['id'].'" data-layer="'.$topping['layer'].'" data-name="'.esc_attr($topping['name']).'" data-part="1" data-price="'.$topping['price'].'" data-price-extra="'.$topping['price_extra'].'" value="1" class="pizzatime-input pizzatime-for-layer pizzatime-half" autocomplete="off"><i></i></label>';
				echo '<label class="pizzatime-label pizzatime-rad-2"><input type="radio" name="pizzatime-input-toppings['.$topping['id'].']" data-type="toppings" data-ingredient="'.$topping['is_ingredient'].'" data-id="'.$topping['id'].'" data-layer="'.$topping['layer'].'" data-name="'.esc_attr($topping['name']).'" data-part="1" data-price="'.$topping['price'].'" data-price-extra="'.$topping['price_extra'].'" value="2" class="pizzatime-input pizzatime-for-layer pizzatime-half" autocomplete="off"><i></i></label>';
			}
			echo '<label class="pizzatime-label pizzatime-rad-3"><input type="radio" name="pizzatime-input-toppings['.$topping['id'].']" data-type="toppings" data-ingredient="'.$topping['is_ingredient'].'" data-id="'.$topping['id'].'" data-layer="'.$topping['layer'].'" data-name="'.esc_attr($topping['name']).'" data-price="'.$topping['price'].'" data-price-extra="'.$topping['price_extra'].'" value="3" '.$checked.' class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			if ($topping['has_extra']==1) {
				echo '<fieldset class="pizzatime-fieldset pizzatime-invisible">';
				echo '<label id="pizzatime-label-toppings-extra-'.$topping['id'].'-regular" class="pizzatime-button-label" for="pizzatime-input-toppings-portion-'.$topping['id'].'-regular">'.__('Regular', 'pizzatime').'<input type="radio" autocomplete="off" class="pizzatime-checkbox pizzatime-regular" data-type="toppings" data-price="'.$topping['price_extra'].'" data-id="extra-'.$topping['id'].'-regular" id="pizzatime-input-toppings-portion-'.$topping['id'].'-regular" name="pizzatime-input-toppings-portion['.$topping['id'].']" value="regular"></label>';
				echo '<label id="pizzatime-label-toppings-extra-'.$topping['id'].'-extra" class="pizzatime-button-label" for="pizzatime-input-toppings-portion-'.$topping['id'].'-extra">'.__('Extra', 'pizzatime').'<input type="radio" autocomplete="off" class="pizzatime-checkbox pizzatime-extra" data-type="toppings" data-price="'.$topping['price_extra'].'" data-id="extra-'.$topping['id'].'-extra" id="pizzatime-input-toppings-portion-'.$topping['id'].'-extra" name="pizzatime-input-toppings-portion['.$topping['id'].']" value="extra"></label>';
				echo '</fieldset>';
			}
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';

		echo '<h3 '.(($settings['show_dressings']=='0' || $pizzatime_is_customizable=='0') ?  'style="display:none;"' : '').'>'.__('Dressings', 'pizzatime').'</h3>';
		echo '<div '.(($settings['show_dressings']=='0' || $pizzatime_is_customizable=='0') ?  'style="display:none;"' : '').' class="pizzatime-ingredient-list" data-free-ingredients="'.(int)get_option('dressings_free_ingredients').'" data-use-multiplier="'.(int)in_array('Dressings', explode(',',$settings['price_multiplier_sections'])).'">';
		foreach ($dressings as $dressing) {
			if ($dressing['status']=='0') continue;
			echo '<div class="pizzatime-ingredient">';
			echo '<div class="pizzatime-ingredient-left">';
			if ($dressing['photo']) {
				echo '<img class="pizzatime-preview pizzatime-desc-image" src="'.esc_url($dressing['photo']).'">';
			}
			else {
				echo '<img class="pizzatime-preview" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">';
			}
			echo '</div>';
			echo '<div class="pizzatime-ingredient-right">';
			echo '<p class="pizzatime-ingredient-name">'.str_replace('\\','',esc_html__($dressing['name'], 'pizzatime')).'</p>';
			echo '<p class="pizzatime-ingredient-description">'.str_replace('\\','',esc_html__($dressing['description'], 'pizzatime')).'</p>';

			$checked='';
			if ($current_preset && count($current_preset['dressings'])) {
				if (in_array($dressing['id'], explode(',', $current_preset['dressings']))) {
					$checked='checked="checked"'; 
				}
				else $checked='';
			}


			echo '<label class="pizzatime-label pizzatime-rad-0"><input type="radio" name="pizzatime-input-dressings['.$dressing['id'].']" data-type="dressings" data-id="'.$dressing['id'].'" data-price="0" data-price-extra="0" value="0" class="pizzatime-input pizzatime-for-layer" checked="checked" autocomplete="off"><i></i></label>';
			if ($dressing['has_left_right']==1) {
				echo '<label class="pizzatime-label pizzatime-rad-1"><input type="radio" name="pizzatime-input-dressings['.$dressing['id'].']" data-type="dressings" data-ingredient="'.$dressing['is_ingredient'].'" data-id="'.$dressing['id'].'" data-layer="'.$dressing['layer'].'" data-name="'.esc_attr($dressing['name']).'" data-part="1" data-price="'.$dressing['price'].'" data-price-extra="'.$dressing['price_extra'].'" value="1" class="pizzatime-input pizzatime-for-layer pizzatime-half" autocomplete="off"><i></i></label>';
				echo '<label class="pizzatime-label pizzatime-rad-2"><input type="radio" name="pizzatime-input-dressings['.$dressing['id'].']" data-type="dressings" data-ingredient="'.$dressing['is_ingredient'].'" data-id="'.$dressing['id'].'" data-layer="'.$dressing['layer'].'" data-name="'.esc_attr($dressing['name']).'" data-part="1" data-price="'.$dressing['price'].'" data-price-extra="'.$dressing['price_extra'].'" value="2" class="pizzatime-input pizzatime-for-layer pizzatime-half" autocomplete="off"><i></i></label>';
			}
			echo '<label class="pizzatime-label pizzatime-rad-3"><input type="radio" name="pizzatime-input-dressings['.$dressing['id'].']" data-type="dressings" data-ingredient="'.$dressing['is_ingredient'].'" data-id="'.$dressing['id'].'" data-layer="'.$dressing['layer'].'" data-name="'.esc_attr($dressing['name']).'" data-price="'.$dressing['price'].'" data-price-extra="'.$dressing['price_extra'].'" value="3" '.$checked.' class="pizzatime-input pizzatime-for-layer" autocomplete="off"><i></i></label>';
			if ($dressing['has_extra']==1) {
				echo '<fieldset class="pizzatime-fieldset pizzatime-invisible">';
				echo '<label id="pizzatime-label-dressings-extra-'.$dressing['id'].'-regular" class="pizzatime-button-label" for="pizzatime-input-dressings-portion-'.$dressing['id'].'-regular">'.__('Regular', 'pizzatime').'<input type="radio" autocomplete="off" class="pizzatime-checkbox pizzatime-regular" data-type="dressings" data-price="'.$dressing['price_extra'].'" data-id="extra-'.$dressing['id'].'-regular" id="pizzatime-input-dressings-portion-'.$dressing['id'].'-regular" name="pizzatime-input-dressings-portion['.$dressing['id'].']" value="regular"></label>';
				echo '<label id="pizzatime-label-dressings-extra-'.$dressing['id'].'-extra" class="pizzatime-button-label" for="pizzatime-input-dressings-portion-'.$dressing['id'].'-extra">'.__('Extra', 'pizzatime').'<input type="radio" autocomplete="off" class="pizzatime-checkbox pizzatime-extra" data-type="dressings" data-price="'.$dressing['price_extra'].'" data-id="extra-'.$dressing['id'].'-extra" id="pizzatime-input-dressings-portion-'.$dressing['id'].'-extra" name="pizzatime-input-dressings-portion['.$dressing['id'].']" value="extra"></label>';
				echo '</fieldset>';
			}
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';


		echo '</div>';

		echo '<div class="single_variation_wrap">';
		echo '<div class="woocommerce-variation-add-to-cart variations_button">';
		echo '<div class="quantity pizzatime-quantity">
				<input id="pizzatime-qty-m" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric" type="number">
			      </div>';
		echo '<button type="submit" onclick="window.myPizza.submitForm();" class="ui-button ui-widget ui-corner-all pizzatime-button">'.esc_html( $product->single_add_to_cart_text() ).'</button>';
		echo '</div>';
		echo '</div>';





	}
}

add_filter( 'woocommerce_single_product_image_html', 'pizzatime_woocommerce_single_product_image_html', 99, 2 );
function pizzatime_woocommerce_single_product_image_html ($image_url, $post_id) {
	global $product;
	if (!pizzatime_is_pizzatime($post_id)) return $image_url;
	$image_url = '';
	$settings=pizzatime_get_option( 'pizzatime_settings' );
	$sizes=pizzatime_get_option( 'pizzatime_sizes' );
	$crusts=pizzatime_get_option( 'pizzatime_crusts' );
	$sauces=pizzatime_get_option( 'pizzatime_sauces' );
	$cheeses=pizzatime_get_option( 'pizzatime_cheeses' );
	$presets=pizzatime_get_option( 'pizzatime_presets' );
	$meats=pizzatime_get_option( 'pizzatime_meats' );
	$toppings=pizzatime_get_option( 'pizzatime_toppings' );
	$dressings=pizzatime_get_option( 'pizzatime_dressings' );
	$custom_ingredients=pizzatime_get_option( 'pizzatime_custom_ingredients' );
	$layered_ingredients=pizzatime_sort_by_layer(array_merge((array)$meats, (array)$toppings, (array)$dressings));

	$pizzatime_preset_meta = get_post_meta($post_id, 'pizzatime_preset'); 
	if (is_array($pizzatime_preset_meta) && count($pizzatime_preset_meta)) {
		$pizzatime_preset = $pizzatime_preset_meta[0];
		$pizzatime_is_customizable_meta = get_post_meta($product->id, 'pizzatime_is_customizable'); 
		$pizzatime_is_customizable = $pizzatime_is_customizable_meta[0];
	}



	//$others=pizzatime_get_option( 'pizzatime_others' );
	#$image_url.='<div id="pizzatime-image-container">';

	$i=0;
	foreach ( $crusts as $crust ) {
		if ($crust['status']=='0') continue;
		if ($i==0) $class = "";
		else $class = "pizzatime-hidden";
		$image_url.= '<div id="pizzatime-crusts-'.$crust['id'].'" class="pizzatime-image-container pizzatime-crusts '.$class.'"><img class="pizzatime-image" src="'.esc_url($crust['image']).'"></div>';
		$i++;
	}

	$i=0;
	foreach ( $sauces as $sauce ) {
		if ($sauce['status']=='0') continue;
		if ($i==0) $class = "";
		else $class = "pizzatime-hidden";
		$image_url.= '<div id="pizzatime-sauces-'.$sauce['id'].'" class="pizzatime-image-container pizzatime-sauces '.$class.'"><img class="pizzatime-image" src="'.esc_url($sauce['image']).'"></div>';
		$i++;
	}


	foreach ( $cheeses as $cheese ) {
		if ($cheese['status']=='0') continue;
		$image_url.= 
		'<div id="pizzatime-cheeses-'.$cheese['id'].'" class="pizzatime-image-container pizzatime-hidden">
			<img class="pizzatime-image" src="'.esc_url($cheese['image']).'">';
		if ($cheese['has_extra']=='1') {
			$image_url.= '
				<img class="pizzatime-image-extra" src="'.esc_url($cheese['image_extra']).'">';
		}
		$image_url.= '</div>';
	}

	foreach ( $layered_ingredients as $ingredient ) {
		if ($ingredient['status']=='0') continue;
		if (strlen($ingredient['image'])>0) {
			$option = str_replace('pizzatime_', '', $ingredient['_option']);
			$image_url.= 
			'<div id="pizzatime-'.$option.'-'.$ingredient['id'].'" class="pizzatime-image-container pizzatime-'.$option.' pizzatime-hidden">
				<img class="pizzatime-image" src="'.esc_url($ingredient['image']).'">';
			if ($ingredient['has_extra']=='1') {
				$image_url.='<img class="pizzatime-image-extra" src="'.esc_url($ingredient['image_extra']).'">';
			}
			$image_url.= '</div>';
		}
	}




	$image_url.=  '<div class="pizzatime-info">';
	$image_url.=  '<p class="price">';
	$image_url.=  '<span class="amount" id="pizzatime-price">';

	$image_url.=  '</span>';
	$image_url.=  '</p>';


	$image_url.= '<div class="single_variation_wrap">';
	$image_url.= '<div class="woocommerce-variation-add-to-cart variations_button">';
	$image_url.= '<div class="quantity pizzatime-quantity">
			<input id="pizzatime-qty" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric" type="number">
		      </div>';
	$image_url.= '<button type="submit" onclick="window.myPizza.submitForm();" class="ui-button ui-widget ui-corner-all pizzatime-button">'.esc_html( $product->single_add_to_cart_text() ).'</button>';
	$image_url.= '</div>';
	$image_url.= '</div>';
	$image_url.=  '<br>';
	$reset_button =  '(<a style="text-decoration:underline;" href="javascript:void(0)" onclick="window.myPizza.resetPizza()">'.__('Reset', 'pizzatime').')</a>';
	$image_url.=  '<p><strong>'.__('Ingredients', 'pizzatime').':</strong>'.$reset_button.'</p>';
	$image_url.=  '<p id="pizzatime-ingredients"></p>';
	$image_url.=  '</div>';

	return $image_url;
}


//Some smart themes use their own templates,  let's keep the default layout 
add_filter( 'wc_get_template', 'pizzatime_get_template', 99, 2 );
function pizzatime_get_template( $located, $template_name ) {
	$pizzatime_templates=array( 'single-product/product-image.php' );

	if ( pizzatime_is_pizzatime( get_queried_object_id() ) || pizzatime_is_pizzatime( get_the_ID() ) ) {
		if ( in_array( $template_name, $pizzatime_templates ) && !strstr( $located, 'pizzatime' ) ) {
			$pizzatime_dir = pizzatime_plugin_path();
			$located = $pizzatime_dir."/woocommerce/$template_name";
		}
	}
	return $located;
}

add_filter( 'woocommerce_locate_template', 'pizzatime_woocommerce_locate_template', 99, 3 );
function pizzatime_woocommerce_locate_template( $template, $template_name, $template_path ) {
	$_template = $template;

	if ( pizzatime_is_pizzatime( get_the_ID() ) ) {
		if ( ! $template_path ) $template_path = $woocommerce->template_url;
		$plugin_path  = pizzatime_plugin_path() . '/woocommerce/';

		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name
			)
		);

		if ( ! $template && file_exists( $plugin_path . $template_name ) )
			$template = $plugin_path . $template_name;
	}
	else {

	}

	if ( ! $template )
		$template = $_template;

	return $template;
}


function pizzatime_plugin_path() {
	return untrailingslashit( dirname( plugin_dir_path( __FILE__ ) ) );
}

function pizzatime_gd_warning() {
	$class = 'notice notice-error is-dismissible';

	if (!extension_loaded('gd') || !function_exists('gd_info')) {
		$message = __( 'PHP gd extenstion is not loaded. Please enable it to be able to see pizza thumbnails in the shopping cart.', 'pizzatime' );
		printf( '<div class="%1$s"><b>Pizzatime:</b><p>%2$s</p></div>', $class, $message ); 
	}
}
add_action( 'admin_notices', 'pizzatime_gd_warning' );


//price for mini-cart, etc
add_filter( 'woocommerce_cart_item_price', 'pizzatime_cart_item_price', 10, 3 );
function pizzatime_cart_item_price( $price, $cart_item, $cart_item_key ) {
	if ( isset( $cart_item['pizzatime_options'] ) && is_array( $cart_item['pizzatime_options'] ) && !empty( $cart_item['pizzatime_options']['product-price'] ) ) {
		$price=$cart_item['pizzatime_options']['product-price'];
	}

	return $price;
}


add_action( 'woocommerce_before_calculate_totals', 'pizzatime_add_custom_price', 9999 );
function pizzatime_add_custom_price( $cart_object ) {
	global $woocommerce;
	$settings = get_option('pizzatime_settings');
	foreach ( $cart_object->cart_contents as $key => $value ) {
		if ( version_compare( $woocommerce->version, '3.0.0', '<' ) ) { 
			if ( isset( $value['pizzatime_options']['product-price'] ) ) $value['data']->price=$value['pizzatime_options']['product-price'];
		}
		else {
			if ( isset( $value['pizzatime_options']['product-price'] ) ) $value['data']->set_price($value['pizzatime_options']['product-price']);
		}
		if ( isset( $value['pizzatime_options']['ingredients'] ) ) $cart_object->cart_contents[$key]['variation']['attribute_pa_pizzatime_ingredients']=$value['pizzatime_options']['ingredients'];
	}
}


function pizzatime_price_by_type($price, $left_right, $extra_price=0) { //left/right or whole
	if ($extra_price>0) $price=$extra_price;
	switch ($left_right) {
		case 0:
			$price=0;
		break;
		case 1:
			$price=$price/2;
		break;
		case 2:
			$price=$price/2;
		break;
		case 3:
			$price=$price;
		break;
	}
	return $price;
}

function pizzatime_ingredient_by_type($name, $left_right, $portion) { //left/right or whole
	$name_array=array();

	switch ($left_right) {
		case 0:
			$name='';
		break;
		case 1:
			$name_array[]=__('Left', 'pizzatime');
		break;
		case 2:
			$name_array[]=__('Right', 'pizzatime');
		break;
		case 3:
			$name=$name;
		break;
	}

	if ($portion=='extra') {
		$name_array[]=__('Extra', 'pizzatime');
	}
	if (count($name_array)) $name = "$name (".implode(', ', $name_array).")";
	return $name;
}
function pizzatime_image_by_type($ingredient, $portion) {
	if ($portion == 'extra') $image = $ingredient['image_extra_path'];
	else $image = $ingredient['image_path'];


	return $image;

}

function pizzatime_merge_images ($images) {

	// Allocate new image
	$img = imagecreatetruecolor(600, 600);
	// Make alpha channels work
	imagealphablending($img, true);
	imagesavealpha($img, true);

	foreach($images as $fn) {
		// Load image
		if (is_dir ($fn['file']) || !file_exists($fn['file'])) continue;
			if (isset($fn['left_right']) && ($fn['left_right']==1 || $fn['left_right']==2))
				$cur = pizzatime_half_image($fn['file'], $fn['left_right']);
			else
				$cur = imagecreatefrompng($fn['file']);

		// Copy over image
		imagecopy($img, $cur, 0, 0, 0, 0, 600, 600);

		// Free memory
		imagedestroy($cur);
	}   

	$final = imagecreatetruecolor(200, 200);
	imagealphablending($final, true);
	imagesavealpha($final, true);
	imagecopyresized($final, $img, 0, 0, 0, 0, 200, 200, 600, 600);


	ob_start();
	imagepng($final);

	$imagedata = ob_get_contents();

	ob_end_clean();

	return $imagedata;
}

function pizzatime_half_image ($image, $left_right) {


	$background = imagecreatefrompng(pizzatime_plugin_path().'/images/background.png');
	imagesavealpha( $background, true );
	imagealphablending( $background, false );

	$background2 = imagecreatefrompng(pizzatime_plugin_path().'/images/background2.png');
	imagesavealpha( $background2, true );
	imagealphablending( $background2, false );



	$target = imagecreatetruecolor(600, 600);
	imagealphablending( $target, false );
	imagesavealpha( $target, true );

	$source = imagecreatefrompng($image);
	imagealphablending( $source, false );
	imagesavealpha( $source, true );


	if ($left_right==2) { //right
		imagecopyresampled( $target, $source, 0, 0, 300, 0, 600, 600, 600, 600 );
		imagecopyresampled( $background, $target, 300, 0, 0, 0, 600, 600, 600, 600 );
		return $background;   
	}
	elseif ($left_right==1) { //left
		imagecopyresampled( $target, $source, 0, 0, 0, 0, 600, 600, 600, 600 );
		imagecopyresampled( $target, $background2, 300, 0, 0, 0, 600, 600, 600, 600 );
		return $target; 
	}
}


//Show the screenshot of the product
add_filter( 'woocommerce_cart_item_thumbnail', 'pizzatime_cart_item_thumbnail', 11, 3 );
function pizzatime_cart_item_thumbnail( $img, $cart_item, $cart_item_key ) {
	if ( isset( $cart_item['pizzatime_options'] ) && is_array( $cart_item['pizzatime_options'] ) && !empty( $cart_item['pizzatime_options']['thumbnail_data'] ) ) {
		$img = '<img src="data:image/png;base64,'.$cart_item['pizzatime_options']['thumbnail_data'].'" style="width:100px;">';
	}
	return $img;
}

add_filter( 'woocommerce_add_cart_item_data', 'pizzatime_add_cart_item_data', 10, 2 );
function pizzatime_add_cart_item_data( $cart_item_meta, $product_id ) {
	global $woocommerce, $order;
	$settings=pizzatime_get_option( 'pizzatime_settings' );
	$sizes=pizzatime_get_option( 'pizzatime_sizes' );
	$crusts=pizzatime_get_option( 'pizzatime_crusts' );
	$sauces=pizzatime_get_option( 'pizzatime_sauces' );
	$cheeses=pizzatime_get_option( 'pizzatime_cheeses' );
	$meats=pizzatime_get_option( 'pizzatime_meats' );
	$toppings=pizzatime_get_option( 'pizzatime_toppings' );
	$dressings=pizzatime_get_option( 'pizzatime_dressings' );
	$presets=pizzatime_get_option( 'pizzatime_presets' );
	$custom_ingredients=pizzatime_get_option( 'pizzatime_custom_ingredients' );
	$ingredients = array();


	$images[0]['file']=pizzatime_plugin_path().'/images/white.png';
	$total = 0;
	$price_multiplier = 1;


	if ( isset( $_REQUEST['attribute_pa_pizzatime_ingredients'] ) ) {
			$product = new WC_Product( $product_id );

			


			if (count($_POST['pizzatime-input-sizes'])) {
				foreach ($_POST['pizzatime-input-sizes'] as $id => $left_right) {
					if ($_POST['pizzatime-input-sizes'][$id]>0) {
						$price_multiplier = $sizes[$id]['price_multiplier'];
						$ingredients[]=$sizes[$id]['name'];
						$total += pizzatime_price_by_type($sizes[$id]['price'], $left_right, 0);
					}
				}
			}

			if (count($_POST['pizzatime-input-crusts'])) {
				foreach ($_POST['pizzatime-input-crusts'] as $id => $left_right) {
					if ($_POST['pizzatime-input-crusts'][$id]>0) {
						$ingredients[]=$crusts[$id]['name'];
						$images[1]['file']=$crusts[$id]['image_path']; 
						$total += pizzatime_price_by_type($crusts[$id]['price'], $left_right, 0) * (in_array('Crusts', explode(',',$settings['price_multiplier_sections'])) ? $price_multiplier : 1);
					}
				}
			}

			if (count($_POST['pizzatime-input-sauces'])) {
				foreach ($_POST['pizzatime-input-sauces'] as $id => $left_right) {
					if ($_POST['pizzatime-input-sauces'][$id]>0) {
						$portion = (isset($_POST['pizzatime-input-sauces-portion'][$id]) ? $_POST['pizzatime-input-sauces-portion'][$id] : 'regular');
						$ingredients[]=pizzatime_ingredient_by_type($sauces[$id]['name'], $left_right, $portion);
						$images[2]['file']=$sauces[$id]['image_path']; 
						$total += pizzatime_price_by_type($sauces[$id]['price'], $left_right, ($portion=='extra' ? $sauces[$id]['price_extra'] : 0)) * (in_array('Sauces', explode(',',$settings['price_multiplier_sections'])) ? $price_multiplier : 1);
					}
				}
			}

			if (count($_POST['pizzatime-input-cheeses'])) {
				foreach ($_POST['pizzatime-input-cheeses'] as $id => $left_right) {
					if ($_POST['pizzatime-input-cheeses'][$id]>0) {
						$portion = (isset($_POST['pizzatime-input-cheeses-portion'][$id]) ? $_POST['pizzatime-input-cheeses-portion'][$id] : 'regular');
						$ingredients[]=pizzatime_ingredient_by_type($cheeses[$id]['name'], $left_right, $portion);
						$images[3]['file']=$cheeses[$id]['image_path']; 
						$total += pizzatime_price_by_type($cheeses[$id]['price'], $left_right, ($portion=='extra' ? $cheeses[$id]['price_extra'] : 0)) * (in_array('Cheeses', explode(',',$settings['price_multiplier_sections'])) ? $price_multiplier : 1);
					}
				}
			}

			if (count($_POST['pizzatime-input-meats'])) {
				$cnt=0;
				foreach ($_POST['pizzatime-input-meats'] as $id => $left_right) {
					if ($_POST['pizzatime-input-meats'][$id]>0) {
						$cnt++;
						$portion = (isset($_POST['pizzatime-input-meats-portion'][$id]) ? $_POST['pizzatime-input-meats-portion'][$id] : 'regular');
						$ingredients[]=pizzatime_ingredient_by_type($meats[$id]['name'], $left_right, $portion);
						if (!isset($images[$meats[$id]['layer']])) { 
							$images[$meats[$id]['layer']]['file']=pizzatime_image_by_type($meats[$id], $portion);
							$images[$meats[$id]['layer']]['left_right']=$left_right;
						}
						if ((int)get_option('meats_free_ingredients') < $cnt) {
							$total += pizzatime_price_by_type($meats[$id]['price'], $left_right, ($portion=='extra' ? $meats[$id]['price_extra'] : 0)) * (in_array('Meats', explode(',',$settings['price_multiplier_sections'])) ? $price_multiplier : 1);
						}
					}
				}
			}
			if (count($_POST['pizzatime-input-toppings'])) {
				$cnt=0;
				foreach ($_POST['pizzatime-input-toppings'] as $id => $left_right) {
					if ($_POST['pizzatime-input-toppings'][$id]>0) {
						$cnt++;
						$portion = (isset($_POST['pizzatime-input-toppings-portion'][$id]) ? $_POST['pizzatime-input-toppings-portion'][$id] : 'regular');
						$ingredients[]=pizzatime_ingredient_by_type($toppings[$id]['name'], $left_right, $portion);
						if (!isset($images[$toppings[$id]['layer']])) {
							$images[$toppings[$id]['layer']]['file']=pizzatime_image_by_type($toppings[$id], $portion);
							$images[$toppings[$id]['layer']]['left_right']=$left_right;
						}
						if ((int)get_option('toppings_free_ingredients') < $cnt) {
							$total += pizzatime_price_by_type($toppings[$id]['price'], $left_right, ($portion=='extra' ? $toppings[$id]['price_extra'] : 0)) * (in_array('Toppings', explode(',',$settings['price_multiplier_sections'])) ? $price_multiplier : 1);
						}
					}
				}
			
			}
			if (count($_POST['pizzatime-input-dressings'])) {
				$cnt=0;
				foreach ($_POST['pizzatime-input-dressings'] as $id => $left_right) {
					if ($_POST['pizzatime-input-dressings'][$id]>0) {
						$cnt++;
						$portion = (isset($_POST['pizzatime-input-dressings-portion'][$id]) ? $_POST['pizzatime-input-dressings-portion'][$id] : 'regular');
						$ingredients[]=pizzatime_ingredient_by_type($dressings[$id]['name'], $left_right, $portion);
						if (!isset($images[$dressings[$id]['layer']])) { 
							$images[$dressings[$id]['layer']]['file']=pizzatime_image_by_type($dressings[$id],  $portion);
							$images[$dressings[$id]['layer']]['left_right']=$left_right;
						}
						if ((int)get_option('dressings_free_ingredients') < $cnt) {
							$total += pizzatime_price_by_type($dressings[$id]['price'], $left_right, ($portion=='extra' ? $dressings[$id]['price_extra'] : 0)) * (in_array('Dressings', explode(',',$settings['price_multiplier_sections'])) ? $price_multiplier : 1);
						}
					}
				}
			}

			ksort($images);
			if (extension_loaded('gd') && function_exists('gd_info')) {
	                        $cart_item_meta['pizzatime_options']['thumbnail_data'] = base64_encode(pizzatime_merge_images($images));
			}

			$cart_item_meta['pizzatime_options']['ingredients']=implode(', ', $ingredients);
			$cart_item_meta['pizzatime_options']['product-price'] = $total;

		}

	return $cart_item_meta;

}
?>
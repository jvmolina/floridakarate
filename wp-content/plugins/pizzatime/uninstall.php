<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();
global $wpdb;
delete_option( 'pizzatime_settings' );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pizzatime_sizes" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pizzatime_crusts" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pizzatime_cheeses" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pizzatime_sauces" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pizzatime_meats" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pizzatime_cheeses" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pizzatime_toppings" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pizzatime_dressings" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pizzatime_presets" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pizzatime_custom_ingredients" );
$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name = '%s'", 'pizzatime_ingredients' ) );



?>
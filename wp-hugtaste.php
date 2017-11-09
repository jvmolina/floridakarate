d<?php 
/* REQUERIMIENTOS ADICIONALES HUGTASTE */

if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

public function search_groups($_POST){
global $wpdb;


$street_number=$_POST['street_number'];
$route=$_POST['route'];
$sStreetAddress=$street_number.$route;
$query="INSERT INTO  st_SearchedAddresses (sStreetAddress) VALUES ('".$sStreetAddress."')";
echo $query;
echo  $wpdb;
$insert = $wpdb->query($query);
echo "<pre>",print_r($_POST),"</pre>";


}

























?>

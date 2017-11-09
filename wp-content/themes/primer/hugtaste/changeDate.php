<?php
global $wp_session;
global $wpdb;

 function update($id,$user){


$day=date('Y-m-d H:i:s');
$next=strtotime('next saturday', strtotime($day));

$next_date = date('Y-m-d H:i:s', $next);


$subscription =  wcs_get_subscription($id);
    $current_next_subscription_payment = $subscription->get_time('next_payment');

    $dates = array (
           
            'next_payment' => $next_date,
            
        );
        $subscription->update_dates($dates);



	
//return $_SESSION;

}


//echo "-->".$nextPayment;
$json = file_get_contents('php://input');
	$action = json_decode($json, true);
	if(isset($action) and !empty($action)){


$id=$action['subscription']['id'];
$user=$action['subscription']['customer']['id'];


  update($id,$user);

}












?>

<?php
ini_set("display_errors", 1);

require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");

// including files
include_once("db_connect.php");
if($_SERVER['REQUEST_METHOD'] === "POST"){
		
		$obj = (array) json_decode(file_get_contents("php://input", true));
		
		$headers = getallheaders();
		if(!empty($obj['order_hdrs']->shop_id) && !empty($obj['order_hdrs']->order_total) ){

     try{

       $jwt = $headers["Authorization"];

       $secret_key = "owt125";

       $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));

		$shop_id  =$obj['order_hdrs']->shop_id;
$order_date  =$obj['order_hdrs']->order_date;
$order_time  =$obj['order_hdrs']->order_time;
$order_total  =$obj['order_hdrs']->order_total;
$customer_id  =$obj['order_hdrs']->order_customer->customer_id;
$phone_number  =$obj['order_hdrs']->order_customer->phone_number;
$payment_mode  =$obj['order_hdrs']->order_payments->payment_mode;
$paid_amount  =$obj['order_hdrs']->order_payments->paid_amount;
$payment_date  =$obj['order_hdrs']->order_payments->payment_date;
$payment_time  =$obj['order_hdrs']->order_payments->payment_time;
$discount_code  =$obj['order_hdrs']->order_payments->discount_code;

$sql_check="SELECT *FROM shop_schedule  WHERE shop_id = '1' ";
		$result =  $conn->query($sql_check);
	
			if($result->num_rows< 1) {	}	
		
		
		
		
		
$order_id=uniqid();
$status='';
/*$sql = "INSERT INTO crudtable(firstname, lastname, email,favjob)
VALUES ('".$dxname."', 'Doe', 'john@example.com','coder')";*/
$sql = "INSERT INTO `order_details`( `order_id`, `customer_id`, `shop_id`, `order_date`,`order_time`, `discount_code`, `total_amount`, `payment_method`, `order_status`) VALUES ('".$order_id."','".$customer_id."','".$shop_id."','".$order_date."','".$order_time."','".$discount_code."','".$paid_amount."','".$payment_mode."','".$status."')";

//print_r($sql);exit();

if ($conn->query($sql) === TRUE) {
	$ref_id ='ICSSTORE-OOT-0000'.$conn->insert_id;;
	$up_qry="UPDATE order_details SET order_id = '".$ref_id."' WHERE id = '".$conn->insert_id."'";
	$updates=$conn->query($up_qry);
	
	
	foreach($obj['order_hdrs']->order_dtls as $food){
		//$re=(array) $food;
		
		$sale_item_code=$food->sale_item_code;
		$name=$food->name;
		$qty=$food->qty;
		$fixed_price=$food->fixed_price;
		$tax=$food->tax;
		$item_total=$food->item_total;
		$food_sql = "INSERT INTO `food_details`(`order_id`, `sale_item_code`, `name`, `qty`,`fixed_price`, `tax`, `item_total`) VALUES ('".$ref_id."','".$sale_item_code."','".$name."','".$qty."','".$fixed_price."','".$tax."','".$item_total."')";
		$ss=$conn->query($food_sql);
	}
	$response['order_id'] = $ref_id;
	$response['status_code'] = 201;
	$response['message'] = "Order Received Successfully";
	echo json_encode($response);
   // echo json_encode("New record created successfully");
   // echo "New record created successfully";
} else{

         http_response_code(500); //server error
         echo json_encode(array(

           "status_code" => 500,
           "message" => "Failed to create order"
         ));
       }
}catch(Exception $ex){

       http_response_code(500); //server error auth error
       echo json_encode(array(
         "status_code" => 401,
         "message" => "Unauthorized access"
         //"message" => $ex->getMessage()
       ));
     }
	}	 else{

     http_response_code(404); // not content found
     echo json_encode(array(
       "status_code" => 204,
       "message" => "All data needed"
     ));
   }
}

$conn->close();
?>
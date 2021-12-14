<?php
//ini_set("display_errors", 1);

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
	if(!empty($headers["Authorization"])){
		if(!empty($obj['order_hdrs']->shop_code) && !empty($obj['order_hdrs']->total_amount) ){

			try{

			   $jwt = $headers["Authorization"];
				//$jwt = $headers["Authorization"];
				if (preg_match('/Bearer\s(\S+)/', $headers["Authorization"], $matches)) {
					$jwt = $matches[1];
				}
			   $secret_key = "owt125";

			   $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));

				$shop_code  =$obj['order_hdrs']->shop_code;
				$order_id  =$obj['order_hdrs']->order_id;
				$order_date  =$obj['order_hdrs']->order_date;
				//$order_time  =$obj['order_hdrs']->order_time;
				$order_total  =$obj['order_hdrs']->total_amount;
				//$customer_id  =$obj['order_hdrs']->order_customer->customer_id;
				//$phone_number  =$obj['order_hdrs']->order_customer->phone_number;
				//$payment_mode  =$obj['order_hdrs']->order_payments->payment_mode;
				//$paid_amount  =$obj['order_hdrs']->order_payments->paid_amount;
				//$payment_date  =$obj['order_hdrs']->order_payments->payment_date;
				//$payment_time  =$obj['order_hdrs']->order_payments->payment_time;
				//$discount_code  =$obj['order_hdrs']->order_payments->discount_code;
				$total_discount  =$obj['order_hdrs']->total_discount;
				$total_tax  =$obj['order_hdrs']->total_tax;
				$remark  =$obj['order_hdrs']->remarks;
				
				$c_name=$obj['order_hdrs']->order_customer->customer_name;
				$c_email=$obj['order_hdrs']->order_customer->customer_email;
				$c_phone=$obj['order_hdrs']->order_customer->customer_phone;

				//$sql_check="SELECT *from shop_db_settings where shop_id='".$shop_id."' ";
				$sql_check="select * from shop_db_settings where shop_id = (select id from shop where code = '$shop_code')";
				$result =  $conn->query($sql_check);

				if($result->num_rows< 1) {
					http_response_code(200);
					echo json_encode(array(
					"status" => 200,
					"message" =>'No Shops Found'
					));
			   
				}
				else {
					$row =  $result->fetch_assoc();
					// while($row =  $result->fetch_assoc() ) {
						//$servername = $row['db_server'];
						$username = $row['db_user'];
						$password = $row['db_password'];
						$database=$row['db_database'];
						
						// Create connection
						$conn = new mysqli($servername, $username, $password,$database);
						// Check connection
						if ($conn->connect_error) {
							//die("Connection failed: " . $conn->connect_error);
							http_response_code(500); //server error
							echo json_encode(array(
							"status" => 0,
							"message" => 'Connection failed'
							));
						}
			
						$online_order_id=uniqid();
						$status='';
						/**************************************/
						$sql_check1="SELECT *FROM online_order_hrds  WHERE order_id = '".$order_id."' and shop_code='".$shop_code."' ";
					$result2 =  $conn->query($sql_check1);
	
					if($result2->num_rows>0) {
						$sql_check1="DELETE  FROM online_order_hrds WHERE order_id = '".$order_id."' and shop_code='".$shop_code."' ";
						$result2 =  $conn->query($sql_check1);
						
					}
						/******************************************/
						
						/*$sql = "INSERT INTO crudtable(firstname, lastname, email,favjob)
						VALUES ('".$dxname."', 'Doe', 'john@example.com','coder')";*/
						$sql = "INSERT INTO `online_order_hrds`( `order_id`, `shop_code`, `order_date`, `total_amount`,`total_discount`, `total_tax`, `remarks`,`status`,`online_order_id`)VALUES ('".$order_id."','".$shop_code."','".$order_date."','".$order_total."','".$total_discount."','".$total_tax."','".$remark."','".$status."','".$online_order_id."')";

						//print_r($sql);exit();

						if ($conn->query($sql) === TRUE) {
							$order_in_id=$conn->insert_id;
							$ref_id ='ICSSTORE-OOT-0000'.$conn->insert_id;;
							$up_qry="UPDATE online_order_hrds SET online_order_id = '".$ref_id."' WHERE Id = '".$conn->insert_id."'";
							$updates=$conn->query($up_qry);
		
		
							foreach($obj['order_hdrs']->order_dtls as $food){
								//$re=(array) $food;
								
								$sale_item_code=$food->sale_item_code;
								//$name=$food->name;
								$qty=$food->sale_qty;
								//$fixed_price=$food->fixed_price;
								$tax=$food->item_tax;
								$dicnt=$food->Item_discount;
								$item_total=$food->item_total;
								$food_sql = "INSERT INTO `online_order_dtls`(`order_id`, `sale_item_code`, `sale_qty`, `item_total`,`item_tax`, `Item_discount`)
								VALUES ('".$order_in_id."','".$sale_item_code."','".$qty."','".$item_total."','".$tax."','".$dicnt."')";
								$ss=$conn->query($food_sql);
							}
							//customer data insert
							
							$customer_sql = "INSERT INTO `online_order_customer`(`order_id`, `customer_name`, `customer_phone`, `customer_email`)
								VALUES ('".$order_in_id."','".$c_name."','".$c_phone."','".$c_email."')";
								$ss=$conn->query($customer_sql);
							
							$response['order_id'] = $ref_id;
							$response['status_code'] = 201;
							$response['message'] = "Order Received Successfully";
							echo json_encode($response);
						   // echo json_encode("New record created successfully");
						   // echo "New record created successfully";
						}else{

							 http_response_code(500); //server error
							 echo json_encode(array(

							   "status_code" => 500,
							   "message" => "Failed to create order"
							 ));
						}
					 //}
				}
					}catch(Exception $ex){

		   http_response_code(401); //server error auth error
		   echo json_encode(array(
			 "status_code" => 401,
			 "message" => "Invalid Token"
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
	else{
		http_response_code(401); //server error auth error
		   echo json_encode(array(
			 "status_code" => 401,
			 "message" => "Unauthorized access"
			 //"message" => $ex->getMessage()
		   ));
	}
}

//$conn->close();
?>

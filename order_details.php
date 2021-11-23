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

   // body
   $data = json_decode(file_get_contents("php://input"));

  $headers = getallheaders();
		if(!empty($headers["Authorization"]) && !empty($data->shop_id)){

     try{
		 
			$jwt = $headers["Authorization"];
			if (preg_match('/Bearer\s(\S+)/', $headers["Authorization"], $matches)) {
				$jwt = $matches[1];
			}
			$secret_key = "owt125";

			$decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));
			
			$created_on  =$data->created_on;
			$created_at  =date("Y-m-d H:i:s", strtotime($data->created_at));
			$shop_id  =$data->shop_id;
	  

		$sql_check="SELECT *from shop_db_settings where shop_id='".$shop_id."' ";
		
		$result =  $conn->query($sql_check);

if($result->num_rows< 1) {
				http_response_code(404);
              echo json_encode(array(
                "status" => 404,
                "message" =>'No Shops Found'
              ));
           
			} else {
				$shops=array();
				 while($row =  $result->fetch_assoc() ) {
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
					//echo "Connected successfully";
					/***********************************************/
					
		  $sql_check="SELECT t1.*,t3.customer_name,t3.customer_phone,t3.customer_email FROM online_order_hrds as t1 LEFT JOIN
online_order_customer AS t3 ON t3.order_id=t1.Id
WHERE t1.order_date>='".$created_at."' AND t1.shop_code='".$shop_id."'";
	  		//print_r($sql_check);exit();
			$result =  $conn->query($sql_check);

if($result->num_rows< 1) {
				http_response_code(404);
              echo json_encode(array(
                "status" => 404,
                "message" => "No orders found"
              ));
           
			} else {
				$items=array();
				 while($row =mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					 $ss=array();
					 $food=array();
					// $ss['id']=$row['id'];
					 $ss['order_hdrs']['order_id']=$row['order_id'];
					 $ss['order_hdrs']['shop_code']=$row['shop_code'];
					 $ss['order_hdrs']['order_date']=$row['order_date'];
					 $ss['order_hdrs']['total_amount']=$row['total_amount'];
					 $ss['order_hdrs']['total_discount']=$row['total_discount'];
					 $ss['order_hdrs']['total_tax']=$row['total_tax'];
					 $ss['order_hdrs']['remarks']=$row['remarks'];
					 $ss['order_hdrs']['status']=$row['status'];
					 
					 $ss['order_customer']['customer_name']=$row['customer_name'];
					 $ss['order_customer']['customer_phone']=$row['customer_phone'];
					 $ss['order_customer']['customer_email']=$row['customer_email'];
					 
					 $food_sql="SELECT* from online_order_dtls WHERE order_id='".$row['Id']."' ";
					 $f_result =  $conn->query($food_sql);
					if($result->num_rows>0) {
						while($f_row =mysqli_fetch_array($f_result, MYSQLI_ASSOC)) {
							 $fs=array();
							 $fs['sale_item_code']=$f_row['sale_item_code'];
							 $fs['sale_qty']=$f_row['sale_qty'];
							 $fs['item_total']=$f_row['item_total'];					 
							 $fs['item_tax']=$f_row['item_tax'];
							 $fs['Item_discount']=$f_row['Item_discount'];							 
							 $food[]=$fs;
					 
						}

					}
					 
					$ss['order_dtls']=$food;
					 $items[]=$ss;
					 
				 }
        // http_response_code(500); //server error
         echo json_encode(array(

           "status" => 200,
           "items" => $items
         ));
       }
					/****************************************************/
					
				 }		 
       
       }
}catch(Exception $ex){

       http_response_code(500); //server error
       echo json_encode(array(
         "status" => 0,
         "message" => $ex->getMessage()
       ));
     }
	}	 else{

     http_response_code(404); // not found
     echo json_encode(array(
       "status" => 0,
       "message" => 'Invalid Shop'
     ));
   }
}

?>
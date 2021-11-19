<?php
ini_set("display_errors", 1);

//require 'vendor/autoload.php';
//use \Firebase\JWT\JWT;

//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");

// including files
include_once("db_connect.php");
if($_SERVER['REQUEST_METHOD'] === "POST"){

   // body
   $data = json_decode(file_get_contents("php://input"));

  
		if(!empty($data->shop_id)){

     try{
			$order_id  =$data->order_id;
			$shop_id  =$data->shop_id;
			$status  =$data->status;
			$updated_at  =$data->updated_at;
	  

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
					
		  $sql_check1="SELECT *FROM online_order_hrds  WHERE order_id = '".$order_id."' and shop_code='".$shop_id."' ";
					$result2 =  $conn->query($sql_check1);
	
					if($result2->num_rows< 1) {
						http_response_code(404);
					  echo json_encode(array(
						"status" => 404,
						"message" => "Invalid credentials"
					  ));
				   
					} else {
						$sql3="UPDATE online_order_hrds SET `status`= '".$status."' WHERE order_id = '".$order_id."' and shop_code='".$shop_id."' ";

						if ($conn->query($sql3) === TRUE) {
							
							$response['order_id'] = $order_id;
							$response['status_code'] = 201;
							if($status=='accepted'){
								$response['message'] = "Order Accept Status Updated  Successfully";
							}elseif($status==3){
								$response['message'] = "KOT Print Status  Updated  Successfully";
							}
							elseif($status==4){
								$response['message'] = "KOT Print Status  Updated  Successfully";
							}
							elseif($status=='delivered'){
								$response['message'] = "Order dispatched Status Updated Successfully";
							}
							elseif($status=='cancelled'){
								$response['message'] = "Order Canceled Successfully";
							}
							
							echo json_encode($response);
						   
						} else{

							 http_response_code(500); //server error
							 echo json_encode(array(

							   "status" => 500,
							   "message" => $sql3
							 ));
						}
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
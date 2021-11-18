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

  // $headers = getallheaders();
		if(!empty($data->secret_id)  && !empty($data->shop_id)){

     try{

      // $jwt = $headers["Authorization"];

      // $secret_key = "owt125";

      // $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));
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
					$cat_id='';//sub_class_id
	  if(!empty($data->category)){
		  $cat_id=$data->category;
		  $sql_check="SELECT id,code,name,fixed_price FROM sale_items WHERE is_deleted='0' and sub_class_id LIKE '%".$cat_id."%' ";
	  }else{
		  $sql_check="SELECT id,code,name,fixed_price FROM sale_items WHERE is_deleted='0' ";
	  }

					//$sql_check="SELECT id,code,name,fixed_price FROM sale_items WHERE is_deleted='0'";
		$result =  $conn->query($sql_check);

if($result->num_rows< 1) {
				http_response_code(404);
              echo json_encode(array(
                "status" => 404,
                "message" => "No items found"
              ));
           
			} else {
				$items=array();
				 while($row =mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					 $ss=array();
					 $ss['id']=$row['id'];
					 $ss['code']=$row['code'];
					 $ss['name']=$row['name'];					 
					 $ss['fixed_price']=$row['fixed_price'];
					 
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
       "message" => $data->secret_id
     ));
   }
}

?>
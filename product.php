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
		if(!empty($data->name) && !empty($data->code) && !empty($data->shop_id)){

     try{

       $jwt = $headers["Authorization"];

       $secret_key = "owt125";

       $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));
		$name  =$data->name;
		$code  =$data->code;
		$brand  =$data->brand;
		$mrp  =$data->mrp;
		$shop_id  =$data->shop_id;
		$catid  =$data->catid;
		$unit  =$data->unit;
		$tax  =$data->tax;
		$hsncode  =$data->hsncode;
		$cd=date('Y-m-d H:i:a');

		/*$sql = "INSERT INTO crudtable(firstname, lastname, email,favjob)
		VALUES ('".$dxname."', 'Doe', 'john@example.com','coder')";*/
		$sql = "INSERT INTO `product`( `name`, `code`, `brand`, `mrp`, `shop_id`, `catid`, `tax`,`unit`, `hsncode`, `created_on`) VALUES ('".$name."','".$code."','".$brand."','".$mrp."','".$shop_id."','".$catid."','".$tax."','".$unit."','".$hsncode."','".$cd."')";



		if ($conn->query($sql) === TRUE) {
			$response['message'] = 'New product created successfully';
			
			echo json_encode($response);
	   // echo "New record created successfully";
		} else{

         http_response_code(500); //server error
         echo json_encode(array(

           "status" => 0,
           "message" => "Failed to create Product"
         ));
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
       "message" => "All data needed"
     ));
   }
}

//End of file
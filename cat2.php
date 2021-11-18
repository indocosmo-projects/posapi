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

   if(!empty($data->catid) && !empty($data->name) && !empty($data->shop_id)){

     try{

       $jwt = $headers["Authorization"];

       $secret_key = "owt125";

       $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));


	$catid  =$data->catid;
	$name  =$data->name;
	$tax  =$data->tax;
	$unit  =$data->unit;
	$hsncode  =$data->hsncode;
	$shop_id  =$data->shop_id;
	$cd=date('Y-m-d H:i:a');

	/*$sql = "INSERT INTO crudtable(firstname, lastname, email,favjob)
	VALUES ('".$dxname."', 'Doe', 'john@example.com','coder')";*/
	$sql = "INSERT INTO `category`( `catid`, `name`, `tax`, `unit`, `hsncode`, `shop_id`,`created_on`) VALUES ('".$catid."','".$name."','".$tax."','".$unit."','".$hsncode."','".$shop_id."','".$cd."')";



	if ($conn->query($sql) === TRUE) {
		$response['message'] = 'New category created successfully';
		
		echo json_encode($response);
   // echo "New record created successfully";
	} else{

         http_response_code(500); //server error
         echo json_encode(array(

           "status" => 0,
           "message" => "Failed to create Category"
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
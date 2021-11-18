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
		if(!empty($data->name) && !empty($data->taxid) ){

     try{

       $jwt = $headers["Authorization"];

       $secret_key = "owt125";

       $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));

$name  =$data->name;
$legalName  =$data->legalName;
$addressline1  =$data->addressline1;
$addressline2  =$data->addressline2;
$addressline3  =$data->addressline3;
$state  =$data->state;
$country  =$data->country;
$pincode  =$data->pincode;
$phone  =$data->phone;
$email  =$data->email;
$fssai  =$data->fssai;
$taxid  =$data->taxid;
$cd=date('Y-m-d H:i:a');

/*$sql = "INSERT INTO crudtable(firstname, lastname, email,favjob)
VALUES ('".$dxname."', 'Doe', 'john@example.com','coder')";*/
$sql = "INSERT INTO `shop`( `name`, `legalName`, `addressline1`, `addressline2`, `addressline3`,`state`, `country`, `pincode`, `phone`, `email`, `fssai`, `taxid`, `created_on`) VALUES ('".$name."','".$legalName."','".$addressline1."','".$addressline2."','".$addressline3."','".$state."','".$country."','".$pincode."','".$phone."','".$email."','".$fssai."','".$taxid."','".$cd."')";



if ($conn->query($sql) === TRUE) {
	$response['shop_id'] = $conn->insert_id;;
	$response['status_code'] = 201;
	$response['message'] = 'Shop Created Successfully';
    
    echo json_encode($response);
   // echo "New record created successfully";
} else{

         http_response_code(500); //server error
         echo json_encode(array(

           "status" => 0,
           "message" => "Failed to create Shop"
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

?>
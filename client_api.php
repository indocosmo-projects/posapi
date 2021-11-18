<?php
	require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");

require_once 'db_connect.php';

if($_SERVER['REQUEST_METHOD']==='POST') {
	$data = json_decode(file_get_contents("php://input", true));
	$client_name  =$data->client_name;
	$email  =$data->email;
	$cid  =uniqid(false);
	$lmt  =10;
	
	$cd=date('Y-m-d H:i:a');

	/*$sql = "INSERT INTO crudtable(firstname, lastname, email,favjob)
	VALUES ('".$dxname."', 'Doe', 'john@example.com','coder')";*/
$sql = "SELECT * FROM client_details WHERE email = '" . mysqli_real_escape_string($conn, $data->email) . "' ";
	
	$result =  $conn->query($sql);
	
	if($result->num_rows >0) {
  http_response_code(404);
              echo json_encode(array(
                "status" => 0,
                "message" => "Already Exist"
              ));
           
	} else {
	$sql = "INSERT INTO`client_details` (`client_name`, `email`, `client_id`, `client_lmt`, `doc`) VALUES ('".$client_name."','".$email."','".$cid."','".$lmt."','".$cd."')";



	if ($conn->query($sql) === TRUE) {
		$response['status_code'] = 201;
		$response['secret_id']=$cid;
		$response['message'] = 'New user created successfully';
		
		echo json_encode($response);
   // echo "New record created successfully";
	} else {
		$response['status_code'] = 500;
		$response['message'] = 'Some error';
    
		echo json_encode($response);
    
    //echo json_encode("Error: " . $sql . "<br>" . $conn->error);
	}
}
} else {
	$response['message'] = 'Access denied';
	echo json_encode($response);
}

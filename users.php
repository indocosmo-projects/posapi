<?php
	require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");

require_once 'db_connect.php';

if($_SERVER['REQUEST_METHOD']==='POST') {
	$data = json_decode(file_get_contents("php://input"));
	
	if(!empty($data->name) && !empty($data->username) && !empty($data->password) && !empty($data->scert_id)){
	$name  =$data->name;
	$phone  =$data->phone;
	$username  =$data->username;
	$scert_id  =$data->scert_id;
	//$password  =password_hash($data->password,PASSWORD_DEFAULT);
$password  =md5($data->password);
	
	$cd=date('Y-m-d');

	
$sql = "SELECT * FROM user WHERE username = '" . mysqli_real_escape_string($conn, $data->username) . "' ";
	
	$result =  $conn->query($sql);
	
	if($result->num_rows >0) {
  http_response_code(404);
              echo json_encode(array(
                "status" => 500,
                "message" => "Already Exist"
              ));
           
	} else {
		
		$client_sql = "SELECT * FROM client_details WHERE client_id = '" . mysqli_real_escape_string($conn, $data->scert_id) . "' and client_lmt >0 limit 1";
	
	$client_result =  $conn->query($client_sql);
	
	if($client_result->num_rows >0) {
		$client_row = mysqli_fetch_array($client_result,MYSQLI_ASSOC);
		
	$sql = "INSERT INTO`user` (`name`,`phone`,`username`, `password`, `doc`) VALUES ('".$name."','".$phone."','".$username."','".$password."','".$cd."')";



	if ($conn->query($sql) === TRUE) {
		$up_lmt=$client_row['client_lmt']-1;
		$up_qry="UPDATE client_details SET client_lmt = '".$up_lmt."' WHERE client_id = '".mysqli_real_escape_string($conn, $data->scert_id) ."'";
		$updates=$conn->query($up_qry);
		$response['status_code'] = 201;
		$response['message'] = 'New user created successfully';
		
		echo json_encode($response);
   // echo "New record created successfully";
	} else {
		$response['status_code'] = 500;
		$response['message'] = 'Some error';
    
		echo json_encode($response);
    
    //echo json_encode("Error: " . $sql . "<br>" . $conn->error);
	}
	}else{
		$response['status_code'] = 404;
		$response['message'] = 'Client details not found';
		echo json_encode($response);
	}
}
} else{

     http_response_code(404); // not content found
     echo json_encode(array(
       "status_code" => "All data needed"
     ));
   }
} else {
	$response['message'] = 'Access denied';
	echo json_encode($response);
}

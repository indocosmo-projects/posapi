<?php
ini_set("display_errors", 1);
// include vendor
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");

// including files
include_once("db_connect.php");



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// get posted data
	$data = json_decode(file_get_contents("php://input", true));
	
	$sql = "SELECT * FROM online_order_login WHERE username = '" . mysqli_real_escape_string($conn, $data->username) . "' AND password = '" . mysqli_real_escape_string($conn, md5($data->password)) . "' LIMIT 1";
	
	$result =  $conn->query($sql);
	
	if($result->num_rows< 1) {
		

              http_response_code(404);
              echo json_encode(array(
                "status" => 401,
                "message" => "Invalid credentials"
              ));
           
	} else {
		$row = $result->fetch_assoc();
		
		$username = $row['username'];
		
		
		$iss = "localhost";
              $iat = time();
              $nbf = $iat + 10;
              $exp = $iat + 180;
              $aud = "user";
              $user_arr_data = array(
                "id" => $row['Id'],
                "username" => $row['username']
               // "" => $user_data['email']
              );

              $secret_key = "owt125";

              $payload_info = array(
                "iss"=> $iss,
                "iat"=> $iat,
                "nbf"=> $nbf,
               // "exp"=> $exp,
                "aud"=> $aud,
                "data"=> $user_arr_data
              );

              $jwt = JWT::encode($payload_info, $secret_key, 'HS512');
			  http_response_code(200);
              echo json_encode(array(
                "status" => 200,
                "token" => $jwt,
                "message" => "User logged in successfully"
              ));
	}
}

?>
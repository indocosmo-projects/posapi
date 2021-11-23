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
   //echo $headers["Authorization"];exit();
		if(!empty($headers["Authorization"])  ){

     try{

       $jwt = $headers["Authorization"];
		if (preg_match('/Bearer\s(\S+)/', $headers["Authorization"], $matches)) {
           $jwt = $matches[1];
        }
       $secret_key = "owt125";

       $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));

$sql_check="SELECT s.id,s.code,s.name,s.area_id,s.description,s.phone,s.address,s.latitude,s.longitude,a.name AS area_name FROM shop s LEFT JOIN area_codes a 
ON s.area_id=a.id WHERE s.id >0 ";
		$result =  $conn->query($sql_check);

if($result->num_rows< 1) {
				http_response_code(404);
              echo json_encode(array(
                "status" => 404,
                "message" => "No Shops found"
              ));
           
			} else {
				$shops=array();
				 while($row =mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					 $ss=array();
					 $ss['id']=$row['id'];
					 $ss['code']=$row['code'];
					 $ss['name']=$row['name'];
					 $ss['phone']=$row['phone'];
					 $ss['address']=$row['address'];
					 $ss['description']=$row['description'];
					 $ss['area_id']=$row['area_id'];
					 $ss['area_name']=$row['area_name'];
					 $ss['latitude']=$row['latitude'];
					 $ss['longitude']=$row['longitude'];
					 $shops[]=$ss;
					 
				 }
        // http_response_code(500); //server error
         echo json_encode(array(

           "status" => 200,
           "shops" => $shops
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
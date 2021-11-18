<?php
ini_set("display_errors", 1);

include_once("db_connect.php");


      // $jwt = $headers["Authorization"];

      // $secret_key = "owt125";

      // $decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));

$sql_check="SELECT count(*) from sale_items ";
		$result =  $conn->query($sql_check);

if($result->num_rows< 1) {
				http_response_code(404);
              echo json_encode(array(
                "status" => 404,
                "message" =>$result  
              ));
           
			} else {
				$shops=array();
				print_r($result);exit();
				 while($row =  $result->fetch_assoc() ) {
					//$servername = $row['db_server'];
					$username = $row['db_user'];
					$password = $row['db_password'];
					$database=$row['db_database'];
					
					// Create connection
					$conn = new mysqli($servername, $username, $password,$database);
					// Check connection
					if ($conn->connect_error) {
					  die("Connection failed: " . $conn->connect_error);
					}
					echo "Connected successfully";
				 }
				 
				
        // http_response_code(500); //server error
        
			}

?>
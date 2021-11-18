<?php
require_once 'db_connect.php';

if(!isset($_SERVER['PHP_AUTH_USER'])){
	header("WWW-Authenticate: Basic realm\"Private Area\"");
	header("HTTP/1.4 401 Unauthorized");
	$response['message'] = "Authentication Failed3";    
	echo json_encode($response);
}
else{
	if(($_SERVER['PHP_AUTH_USER']=='admin@mail.com')&&($_SERVER['PHP_AUTH_PW']=='admin123') ){


		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: POST");



		
		$data = json_decode(file_get_contents("php://input", true));
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
		} else {
			$response['message'] = 'Some error';
		
			echo json_encode($response);
		
		//echo json_encode("Error: " . $sql . "<br>" . $conn->error);
		}
	}else{
		$response['message'] = "Authentication Failed";
    
		echo json_encode($response);
	}
}

//End of file
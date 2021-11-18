<?php
include_once('db_connect.php');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type,x-prototype-version,x-requested-with');
header('Cache-Control: max-age=900');
header("Content-Type: application/json"); // tell client that we are sending json data



$catid  =$_POST['catid'];
$name  =$_POST['name'];
$tax  =$_POST['tax'];
$unit  =$_POST['unit'];
$hsncode  =$_POST['hsncode'];
$shop_id  =$_POST['shop_id'];
$cd=date('Y-m-d H:i:a');

/*$sql = "INSERT INTO crudtable(firstname, lastname, email,favjob)
VALUES ('".$dxname."', 'Doe', 'john@example.com','coder')";*/
$sql = "INSERT INTO `category`( `catid`, `name`, `tax`, `unit`, `hsncode`, `shop_id`,`created_on`) VALUES ('".$catid."','".$name."','".$tax."','".$unit."','".$hsncode."','".$shop_id."','".$cd."')";



if ($conn->query($sql) === TRUE) {
	$response['message'] = 'New category created successfully';
    
    echo json_encode($response);
   // echo "New record created successfully";
} else {
	$response['message'] = 'Some error';
    
    echo json_encode($response);
    
    //echo json_encode("Error: " . $sql . "<br>" . $conn->error);
}

$conn->close();
?>
<?php
// including files
include_once("db_connect.php");

//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Allow-Headers: *");

$obj = json_decode(file_get_contents("php://input", true));

$moviename=$obj->moviename;
$review=$obj->review;

$sql="INSERT INTO `moview_review`(`movie_nme`, `review`) VALUES ('".$moviename."','".$review."')";

	if ($conn->query($sql) === TRUE) {
	echo 'succwss';
}else{
	print_r($sql);
	echo 'error';
}



?>
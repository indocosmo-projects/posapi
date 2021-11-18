<?php
/*$servername = "192.168.1.107";
$username = "shopdemosix";
$password = "mysql2021";
$database='shopdemosix_hq_db';*/
$servername = "192.168.1.250";
$username = "amazon";
$password = "posella123456^.";
$database='amazon_hq_db';


// Create connection
$conn = new mysqli($servername, $username, $password,$database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
?>
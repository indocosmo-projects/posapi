<?php
$apiKey = "apikey";

 

$secretKey = "secretkey";

 

// Generates a random string of ten digits

$salt = mt_rand();

 

// Computes the signature by hashing the salt with the secret key as the key

$signature = hash_hmac('sha256', $salt, $secretKey, true);

 

// base64 encode...

$encodedSignature = base64_encode($signature);

 

// urlencode...

$encodedSignature = urlencode($encodedSignature);

 

echo "Voila! A signature: " . $encodedSignature;

 

?>
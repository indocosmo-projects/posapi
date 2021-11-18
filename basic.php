<?php
$auth='';
	if(!isset($_SERVER['PHP_AUTH_USER'])){
		header("WWW-Authenticate: Basic realm\"Private Area\"");
		header("HTTP/1.4 401 Unauthorized");
		$auth= 0;
	}
	else{
		if(($_SERVER['PHP_AUTH_USER']=='admin@gmail.com')&&($_SERVER['PHP_AUTH_PW']=='admin123') )
		{$auth= 1;
	
	}
}
?>
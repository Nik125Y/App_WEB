<?php
	$user = 'u68791'; 
	$pass = '1609462'; 
	$db = new PDO('mysql:host=localhost;dbname=u68791', $user, $pass,
	[PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
?>
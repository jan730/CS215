<?php
    $db_host = "localhost";

	$db_user = "izk251";  // DB username
	$db_pwd = "pwd"; // Enter your MySQL password here
	$db_db = "izk251"; // Database name

	$charset = 'utf8mb4';
	$attr = "mysql:host=$db_host;dbname=$db_db;charset=$charset";
	$options = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
		
	];

	try {
		$db = new PDO($attr, $db_user, $db_pwd, $options);
	} catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

	$db = null;
?>
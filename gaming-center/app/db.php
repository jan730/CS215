<?php
	//you need the docker setup to run this
    $db_host = "gaming-center_db";

	$db_user = getenv('MARIADB_USER');  
	$db_pwd = getenv('MARIADB_PASSWORD'); 
	$db_db = getenv('MARIADB_DATABASE');

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
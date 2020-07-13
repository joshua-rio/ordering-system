<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "guillys";

	try{
		$dsn = "mysql:host=".$servername.";dbname=".$dbname;
		$pdo = new PDO($dsn,$username,$password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
		return $pdo;
	}catch(PDOException $e){
		echo "Connection Failed: ".$e->getMessage();
	}
?>
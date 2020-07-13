<?php
    session_start();
    include "connection.php";
    include "classes.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);

    $output = "";
    $searchQ = $_POST['searchQuery'];

    if($searchQ == ""){
        $output = "";
    }else if(strlen($searchQ) <= 13){
        $output = "";
    }else{
        $sql = "SELECT * FROM profile WHERE `CardNumber` = ?";
        $pds = $pdo->prepare($sql);
        $pds->execute(array($searchQ));
        if($pds->rowcount() == 0){
            $output = "";
        }else{
            $output = "VALID";
        }
    }
	echo("$output");
?>
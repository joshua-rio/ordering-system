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

            } $sql = "SELECT * FROM profile WHERE `CardNumber` = ?";
        $pds = $pdo->prepare($sql);
        $pds->execute(array($searchQ));
        if($pds->rowcount() == 0){
            $output = "";
        }else{
            $num = $pds->rowcount();
            while($row = $pds->fetch()){
                $name = $row['Name'];
                $birthday = $row['Birthday'];
                $email = $row['Email'];
                $contact = $row['Contact'];
                $status = $row['Status'];
                $output = "Name: $name <br>";
                if($AdminInSession->type == "Manager"){
                    $output .= "Birthdate: $birthday <br> Email: $email <br> Contact: $contact <br> Status: $status";
                }else
                    $output .= "<br><button style='background-color:gold;width:50%;' id=details onclick=fulldetails($searchQ) value=$searchQ>FULL DETAILS</button>";
        }
    }
	echo("$output");
?>
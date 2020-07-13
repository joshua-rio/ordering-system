<?php
    session_start();
    include "connection.php";
    include "classes.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $CardNumber = $_POST['cardNumber'];
    $output = "";
    $sql = "SELECT * from profile where CardNumber = ?";
     $pds = $pdo->prepare($sql);
     $pds->execute(array($CardNumber));
        
        while($row = $pds->fetch()){
            $output ="
                <p style=font-size:50px;>".$row['Name']."</p>
                <p>".$row['CardNumber']."</p>
                <p>".$row['Address']."</p>
                <p>".$row['Email']."</p>
                <p>+63".$row['Contact']."</p>
                <p>Date Registered<br>".$row['Date_Register']."</p>
            ";
          
        
            
        }

    echo ("$output");
?>
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
            $output ="<p>THE WINNER IS</p>
                <p style=font-size:30px;>".$row['Name']."</p>
              
            ";
          
            
        }

     $sql = "UPDATE `profile` SET `RaffleEntry`= ? WHERE CardNumber = ?";
     $pds = $pdo->prepare($sql);
     $pds->execute(array("Winner",$CardNumber));
    
    echo ("$output");
?>
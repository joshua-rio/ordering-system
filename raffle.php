<?php
    session_start();
    include "connection.php";
    include "classes.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $output =array();
    $index=0;
   
    $sql = "SELECT CardNumber from profile where `Status`= ? and `RaffleEntry` IS NULL";
     $pds = $pdo->prepare($sql);
     $pds->execute(array("Activate"));
    
    
        while($row = $pds->fetch()){
           $output[$index] = $row['CardNumber'];
           $index++;
        }


    $sql = "SELECT logs.CardNumber,Sum(logs.Total) as total from logs inner join profile on logs.CardNumber = profile.CardNumber where profile.Status = ? and profile.RaffleEntry is null Group By CardNumber ";
     $pds = $pdo->prepare($sql);
     $pds->execute(array("Activate"));
    
    
        while($row = $pds->fetch()){
           if($row['total']> 10000)
          array_push($output,  $row['CardNumber']);
        }
    
        
    shuffle($output);
 
    echo json_encode($output);;
?>
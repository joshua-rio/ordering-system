<?php
    include "connection.php";
    $output = " <table class=table table-hover>
                    <thead style=background-color: #E0A800;>
                    <tr><td style=border: none;>Winner</td>
                        <td style=border: none;>Name</td>
                    </tr>";
    
    $index = 1;    
    $sql = "SELECT * from profile where RaffleEntry=?";
     $pds = $pdo->prepare($sql);
     $pds->execute(array("Winner"));
     if($pds->rowcount() == 0){
         $output .="<tbody><tr><td colspan='8'>No Winners</td></tr></tbody>"; 
     }
        else
            while($row = $pds->fetch()){
          
              $output .="<tbody>
             <tr onclick='".'showContact("'.$row['CardNumber'].'")'."'>
            <td>#$index</td>
            <td>".$row['Name']."</td>
              </tr></tbody>"; 
              $index++;
        } 

        $output.= "</table>"; 
    echo ("$output");
?>
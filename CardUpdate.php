<?php 
 include "connection.php";
 include "classes.php";

    $cardnumber = $_POST['cardno'];
    $validation = $_POST['searchValidation'];
    $output = "hahah";
    $stats="";
        $query = "Select * from profile where CardNumber=?";
        $search  = $pdo->prepare($query);
        $search->execute(array($cardnumber));
        if($search->rowcount()!=0)
        {while($rows = $search->fetch()){
          $cardtype = $rows['CardType'];
          $stats = $rows['Status'];
          $name = $rows['Name'];
          $bday = $rows['Birthday'];
          $add = $rows['Address'];
          $email = $rows['Email'];
          $contact   = $rows['Contact'];
          $date = $rows['Date_Register'];
          $fb = $rows['Facebook'];
    }}   


    if($validation =='Activate')
    {   
date_default_timezone_set("Asia/Manila");
$extra = date('Y');
$id = 40000000000000;
$id += $extra * 1000000;
$query = "SELECT count(CardNumber) from profile where CardNumber like '4000".$extra."%'";
$go = $pdo->prepare($query);
$go->execute();
while($row = $go->fetch()){
$id += $row[0];
}
$today = date('Y-m-d');
$expire = date('Y-m-d', strtotime($today."+1 Year + 3 days"));

  //  $sql = "UPDATE `profile` SET Status=?,Expiration='".$expire."' WHERE CardNumber=?";        
    $sql = "INSERT INTO `profile`(`CardNumber`, `CardType`, `Name`, `Birthday`, `Address`, `Email`, `Contact`, `Expiration`, `Status`, `Date_Register`,`RaffleEntry`,`Facebook`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
    $array = array($id,$cardtype,$name,$bday,$add,$email,$contact,$expire,$validation,$today,null,$fb);
    $sql2 = "UPDATE `profile` SET Status='Invalid' WHERE CardNumber=?";
    $pds2 = $pdo->prepare($sql2);
    $pds2->execute(array($cardnumber));


    }
    else
    {$sql = "UPDATE `profile` SET Status=? WHERE CardNumber=?";
    $array = array($validation,$cardnumber);
    }
    try{
    $pds = $pdo->prepare($sql);
    $pds->execute($array);
    if($pds->rowcount() == 0){
        $output = "Invalid Card";
    }else{
        $output = "Card Updated";
    }
}catch(PDOException $e){
  $output = "Error:Card Number is Already Used";}
     echo ("$output");
   ?>

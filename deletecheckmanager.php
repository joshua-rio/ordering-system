<?php
 session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $code = $_POST['manager'];
    $cardnumber = $_POST['cardno'];
    $validation = $_POST['searchValidation'];
    $manager = false;
    $user ="";
    $output= "";
    $sql = "SELECT * FROM staff WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array('Manager'));
    if($pds->rowcount() == 0){
        $output = "Invalid Card";
    }else{
        while($row = $pds->fetch()){
                $user = $row['Username'];
                if (password_verify($user, $code)) {
                    $manager = true;
                     
                }

            }
    }
if($manager == true)
{
    $cn="";
        $query = "Select * from profile where CardNumber=?";
        $search  = $pdo->prepare($query);
        $search->execute(array($cardnumber));
        if($search->rowcount()!=0)
        {while($rows = $search->fetch()){$cn = $rows['Status'];}}   


    if($validation =='Active' && $cn !="Invalid")
    {   
    date_default_timezone_set('Asia/Manila');
    $expire = date('Y-m-d',strtotime(date("Y-m-d",time()) . " + 1 year"));
    $sql = "UPDATE `profile` SET Status=?,Expiration='".$expire."' WHERE CardNumber=?";        
    }
    else
    $sql = "UPDATE `profile` SET Status=? WHERE CardNumber=?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($validation,$cardnumber));
    if($pds->rowcount() == 0){
        $output = "Invalid Card";
    }else
      {
        $output = "Card Updated";
      }

  }else
  $output = "Access Denied: Contact your Manager";
   echo ("$output");
    ?>
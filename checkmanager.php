<?php
 session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $code = $_POST['manager'];
    $cardnumber = $_POST['cardno'];
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
    $sql = "SELECT * FROM staff WHERE Username=? and Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($user,"Manager"));
    if($pds->rowcount() == 0){
        $output = "Invalid Card";
    }else{
         $sql = "SELECT * FROM profile WHERE CardNumber = ?";
            $pds = $pdo->prepare($sql);
        $pds->execute(array($cardnumber));

            while($row = $pds->fetch()){
                $Name = $row['Name'];
                 $birthday = $row['Birthday'];
                  $Address = $row['Address'];
                   $email = $row['Email'];
                    $contact = $row['Contact'];
                     $Expiration = $row['Expiration'];
                      $status = $row['Status'];
            }
      
  $output = "Birthdate: $birthday <br> Email: $email <br> Contact: $contact <br> Status: $status <br>";
     
    }


 

  }else
  $output = "Access Denied<br> Contact your Manager";
   echo ("$output");
    ?>
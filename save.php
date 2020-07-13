<?php
session_start();
include "connection.php";
include "classes.php";  
include "checkSession.php";
$AdminInSession = unserialize($_SESSION['AdminSession']);
        $cardnum = $_POST['card'];
        $name =  ucwords($_POST['Name']);
        $birthday = $_POST['Bday'];
        $address = ucwords($_POST['Add']);
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        try{   
            $sql = "UPDATE `profile` SET `Name`=?,`Birthday`=?,`Address`=?,`Email`=?,`Contact`=? WHERE CardNumber=?";
            $pds = $pdo->prepare($sql);
            $pds->execute(array($name,$birthday,$address,$email,$contact,$cardnum));
             echo "<script>alert('UPDATED');</script>";   
        }catch(PDOException $e){
            echo "<script>alert('Error: Profile was not updated! $e');</script>";
}
echo "<meta http-equiv=refresh content='0;url=cardholder.php'>";

?>
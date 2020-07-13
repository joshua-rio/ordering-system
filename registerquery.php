<?php
session_start();
include "connection.php";
include "classes.php";  
include "checkSession.php";
$AdminInSession = unserialize($_SESSION['AdminSession']);
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

        $name = $_POST['Name'];
        $age = $_POST['Age'];
        $birthday = $_POST['Birthday'];
        if(isset($_POST['Address']) || isset($_POST['E-mail']) || isset($_POST['Mobile']) || isset($_POST['Facebook']) || isset($_POST['Occupation'])){
        $address = $_POST['Address'];
        $email = $_POST['Email'];
        $contact = $_POST['Mobile'];
        $fb = $_POST['Facebook'];
        $Occupation = $_POST['Occupation'];
        }
        try{   
             $sql = "INSERT INTO profile (CardNumber,CardType,Name,Birthday,Address,Email,Contact,Expiration,Status,Date_Register,RaffleEntry,Facebook) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            $pds = $pdo->prepare($sql);
            $pds->execute(array($id,"Gold",$name,$birthday,$address,$email,$contact,$expire,"Request",$today,"",$fb));
             echo "<script>alert('REGISTERED');</script>";   
        }catch(PDOException $e){
            echo "<script>alert('Error: Profile was not updated! $e');</script>";
}
echo "<meta http-equiv=refresh content='0;url=adduser.php'>";

?>
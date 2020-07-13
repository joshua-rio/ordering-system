<?php
require 'PHPMailer-master/PHPMailerAutoload.php';
  date_default_timezone_set("Asia/Manila");
  include "connection.php";
  $day = date('d');
  $month = date('m');
   
$sql = "SELECT Name,Email FROM `profile` WHERE Month(Birthday) = $month and Day(Birthday) = $day";
$pdo->prepare($sql);
$pds = $pdo->prepare($sql);
$pds->execute(array());
$email =array();
$names= array();
$index = 0;

foreach ($pds as $value) {
  if($value[1]!= ""){
    $names[$index]=$value[0];
  $email[$index] = $value[1];
  
  $index++;
  }
}
if($index > 0)
{
  if(!isset($_COOKIE["Greetings"])){
    setcookie("Greetings", "true",time()  + 86400);
    for ($i=0; $i < count($email); $i++) { 
    $mailto = $email[$i];
    $mailSub = "Happy Birthday ".$names[$i];
    $mailMsg = "

<div style=' padding: 5px;
  text-align: center;
  background: black;
  color: white;
  font-size: 20px;'>

  <h1>Guilly's Night Club</h1>
  <p>#1 Club in the Philippines</p>
</div>

<div style='padding:100px;'>
  <h1>Event</h1>
  <p>HAPPY BIRTHDAY</p>
</div>


<div style=' padding: 5px;
  text-align: center;
  background: black;
  color: white;'>
  <p>Guilly's Night Club @ All Rights Reserved 2019</p>
</div>";
 
   $mail = new PHPMailer();
   $mail ->IsSmtp();
   $mail ->SMTPDebug = 0;
   $mail ->SMTPAuth = true;
   $mail ->SMTPSecure = 'ssl';
   $mail ->Host = "smtp.gmail.com";
   $mail ->Port = 465; // or 587
   $mail ->IsHTML(true);
   $mail ->Username = "joshuarioflorido@gmail.com";
   $mail ->Password = "rolynne26";
   $mail ->SetFrom("joshuarioflorido@gmail.com");
   $mail ->Subject = $mailSub;
   $mail ->Body = $mailMsg;
   $mail ->AddAddress($mailto);
 }
  }
}

?>
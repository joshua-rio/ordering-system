<?php
require 'PHPMailer-master/PHPMailerAutoload.php';
session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
  $AdminInSession = unserialize($_SESSION['AdminSession']);

$emails = array();
$index=0;
$sent=0;
$hasemail=0;
$sql = "SELECT `Email` FROM `profile`";
$pdo->prepare($sql);
$pds = $pdo->prepare($sql);
$pds->execute(array());
foreach ($pds as $value) {
 $emails[$index] = $value[0];
 $index++;
}
for ($i=0; $i < count($emails); $i++) { 
if($emails[$i] != ""){
  $hasemail++;
  $mailto = $emails[$i];
    $mailSub = $_POST['mail_sub'];
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
  <p>".$_POST['mail_msg']."</p>
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
   $mail ->Port = 465;//587;
   $mail ->IsHTML(true);
   $mail ->Username = "joshuarioflorido@gmail.com";
   $mail ->Password = "rolynne26";
   $mail ->SetFrom("joshuarioflorido@gmail.com");
   $mail ->Subject = $mailSub;
   $mail ->Body = $mailMsg;
  
 $mail ->AddAddress($mailto);

	if($mail->Send()) 	
	$sent++;
   
}
 }
 if($sent == $hasemail)
  echo "<script>alert('E-mail Sent');</script><meta http-equiv=refresh content='0;url=index.php'>";
 else
  echo "<script>alert('There was a problem sending E-mails');</script><meta http-equiv=refresh content='0;url=email.php'>";
/*try{
mail("lopezchristiangabriel12@gmail.com","OKAY NA","TEST");
}catch(Exception $e){
echo $e;
}*/

?>

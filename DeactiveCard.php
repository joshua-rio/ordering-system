<?php
date_default_timezone_set("Asia/Manila");
include "connection.php";
$date = date('Y-m-d');
$sql = "SELECT * FROM `profile` WHERE Expiration <= '$date' and Status='Active'";
$pdo->prepare($sql);
$pds = $pdo->prepare($sql);
$pds->execute(array());
$cn =array();
foreach ($pds as $value) {
  if($value[0]!= ""){
    $cn[$index]=$value[0];
    $index++;
  }
}
if($index > 0)
{
for ($i=0; $i < count($cn); $i++) { 
$sql = "Update profile set Status='Deactivate' where CardNumber= $cn[$i]";
$pdo->prepare($sql);
$pds = $pdo->prepare($sql);
$pds->execute(array());
 }
  }

?>
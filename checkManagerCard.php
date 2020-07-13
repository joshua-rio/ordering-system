<?php
 session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $code = $_POST['manager'];
    $output = false;
    $user ="";
    $sql = "SELECT * FROM staff WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array('Manager'));
    if($pds->rowcount() == 0){
        $output = false;
    }else{
        while($row = $pds->fetch()){
                $user = $row['Username'];
                if (password_verify($user, $code)) {
                    $output = true;
                     
                }

            }
    }
    echo ($output);
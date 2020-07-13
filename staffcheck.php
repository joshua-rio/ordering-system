<?php
    include "connection.php";
    include "classes.php";
    $output = "";
    $searchQ = $_POST['searchQuery'];
    
    $ctr = 0;
    $sql = "SELECT * FROM staff_settings WHERE `Type` = '$searchQ'";
    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array());
    if($pds->rowcount() == 0 && !preg_match('/\s/',$searchQ)){
        $output = "<button type='submit' name='addtype' class='btn btn-warning'>Add Type </button>";
    }else{
        $output = "<button type='submit' name='addtype' class='btn btn-warning' disabled>Add Type </button>";
        
    }

    echo("$output");
?>
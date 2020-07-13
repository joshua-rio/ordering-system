<?php
    include "connection.php";
    include "classes.php";
    $output = "";
    $searchQ = $_POST['searchQuery'];

    if($searchQ == ""){
        $output = "<option value =''>No Sub Menu Available</option>";
    }else{
        $sql = "SELECT * FROM sub_category WHERE `category` = '$searchQ'";
        $pdo->prepare($sql);
        $pds = $pdo->prepare($sql);
        $pds->execute(array());
        if($pds->rowcount() == 0){
            $output = "<option value=''>No Sub Menu Available</option>";
        }else{
            while($row = $pds->fetch()){
                $id = $row['ID'];
                $sub = $row['sub_category'];
                $output .= "<option value='$sub'>$sub</option>";
            }
        }
    }

    echo("$output");
?>
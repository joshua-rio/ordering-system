<?php
    include "connection.php";
	include "classes.php";
    $output = "";
    $searchQ = $_POST['searchQuery'];

    if($searchQ == ""){
        $output = "<option>No Result</option>";
    }else{
        $sql = "SELECT * FROM items WHERE `Name` LIKE '%$searchQ%' ORDER BY `Name`";
        $pdo->prepare($sql);
        $pds = $pdo->prepare($sql);
        $pds->execute(array());
        if($pds->rowcount() == 0){
            $output = "<option>No Result</option>";
        }else{
            while($row = $pds->fetch()){
                $id = $row['Id'];
                $name = $row['Name'];
                $price = $row['Price'];
                $output .= "<option value='$id'>$name $price</option>";
            }
        }
    }

	echo("$output");
?>
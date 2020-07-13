<?php
    include "connection.php";
	include "classes.php";
    $output = "";
    $searchQ = $_POST['searchQuery'];

    $sql = "SELECT * FROM discountday WHERE Day = ?";
    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array($searchQ));
    if($pds->rowcount() == 0){
        $output = "";
    }else{
        while($row = $pds->fetch()){
            $output = $row['Discount'];
        }
    }

	echo("$output");
?>
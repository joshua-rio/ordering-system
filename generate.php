<?php
    include "connection.php";
	include "classes.php";
    $output = "";
    $type = $_POST['searchQuery'];

    if($type == "none"){
        $output = "";
    }else{
        $count = 0;
        
        $sql = "SELECT * FROM profile WHERE CardType = ?";
        $pds = $pdo->prepare($sql);
        $pds->execute(array($type));
        $count = $pds->rowcount();
        $count++;

        $first = "";
        if($type == "Gold"){
            $first = "4000";
        }else{
            $first = "5000";
        }
        $second = date("Y");
        $third = "";

        $length = strlen($count);
		for($a=$length;$a<8;$a++){
			$third.="0";
		}
        $third.=$count;
        $output = $first.$second.$third;
    }

	echo("$output");
?>
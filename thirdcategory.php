<?php
    session_start();
    include "connection.php";
    include "classes.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $output ="<br>";
    $subcat = $_POST['subcat'];
   
    $sql = "SELECT * FROM `sub_category` WHERE `category` = '$subcat '";
     $pds = $pdo->prepare($sql);
     $pds->execute(array());

    if($pds->rowcount() == 0){
    $sql = "SELECT * FROM `items` WHERE `SubCategory` = '$subcat '";
     $pds = $pdo->prepare($sql);
     $pds->execute(array());


       while($row = $pds->fetch()){
            $item = $row['Name'];
            $price = $row['Price'];
            $output .= "";?>
                   <button onClick="addtocart(this)" value="<?php echo $price;?>" name="<?php echo $item; ?>"><?php echo $item;?></button>
       <?php                        
        }
    }else{
        while($row = $pds->fetch()){
            $item = $row['sub_category'];
            
            $output .= "";?>
                   <button onClick="viewItems('<?php echo $item;?>')"><?php echo $item;?></button>
       <?php                        
        }
    }

//onClick='addtocart(this)'
     $output .= "<br><div id=items></div>";

    echo("$output");



?>

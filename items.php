<?php
    session_start();
    include "connection.php";
    include "classes.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $output ="";
    $subcat = $_POST['subcat'];
   
    $sql = "SELECT * FROM `items` WHERE `SubCategory` = '$subcat '";
     $pds = $pdo->prepare($sql);
     $pds->execute(array());

    if($pds->rowcount() == 0){
        $output .= "
                No Result";
    }else{
        while($row = $pds->fetch()){
            $item = $row['Name'];
             $price = $row['Price'];
            $output .= "";?>
                   <button  class="items" onClick="addtocart(this)" value="<?php echo $price;?>" name="<?php echo $item;?>"><?php echo $item;?></button>
     
     <?php                          
        }
    }

//onClick='addtocart(this)'
  $output .="<br>";
     
    echo("$output");



?>

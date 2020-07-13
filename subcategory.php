<?php
    session_start();
    include "connection.php";
    include "classes.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $output ="";
    $subcategory = $_POST['Subcategory'];
   
    $sql = "SELECT * FROM `menu_category` WHERE `Main` = '$subcategory '";
     $pds = $pdo->prepare($sql);
     $pds->execute(array());

    if($pds->rowcount() == 0){
        $output = "
                No Result";
    }else{
        while($row = $pds->fetch()){
            $item = $row['Category'];
            // $price = $row['Price'];
            $output .= "";
            ?>
                   <button style="width: 10%;" onClick="viewSubCategory('<?php echo $item;?>')" name="<?php echo $item;?>"><?php echo $item;?></button>
        <?php                     
        }
    }

//onClick='addtocart(this)'

    echo("$output");



?>

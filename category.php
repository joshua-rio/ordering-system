<?php
    session_start();
    include "connection.php";
    include "classes.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $output = "";
    $category = $_POST['category'];
   
    $sql = "SELECT * FROM `menu_category` Group By Main";
     $pds = $pdo->prepare($sql);
     $pds->execute(array());
    $output = "<div align=center style=display:block;background-color:black;>";
    if($pds->rowcount() == 0){
        $output = "
                No Result";
    }else{
        while($row = $pds->fetch()){
            $Main = $row['Main'];
            $output .= "
                           ";?>
                           <button class=tablinks onClick="category(event, '<?php echo $Main; ?>')" style="width: 12%; height: 50px;"><?php echo $Main; ?></button>
                               
    <?php
        }
    }
?>
  <?php
     $output .= "</div>";



    echo("$output");
?>
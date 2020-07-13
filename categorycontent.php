<?php
    session_start();
    include "connection.php";
    include "classes.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $output = "";
    $category = $_POST['category'];
   
    $sql = "SELECT * FROM `menu_category`";
     $pds = $pdo->prepare($sql);
     $pds->execute(array());
    $output = "<div align=center style=display:block;>";
    if($pds->rowcount() == 0){
        $output = "
                No Result";
    }else{
        while($row = $pds->fetch()){
            $Main = $row['Main'];
            $output .= "
                           ";?>
                            <div id="<?php echo $Main;?>" class="tabcontent">
                    </div>
                               
    <?php
        }
    }
?>
  <?php
     $output .= "</div><br><div id=item>
    
</div><br>";



    echo("$output");
?>
<?php 
include "connection.php";
include "classes.php";

if(isset($_POST['action'])){
    if($_POST['action'] == 'updatediscount'){
        $id = $_POST['id'];
        $discount = $_POST['discount'];
        try{
            $sql =  "UPDATE `discountitem` SET Discount=? WHERE `Id` = ?";
            $pds = $pdo->prepare($sql);
            $pds->execute(array($discount,$id));
            echo "Item Updated";
        }catch(PDOException $e){
            echo "<script>alert($e->getMessage());</script>";
        }
    }

    if($_POST['action'] == 'updateprice'){
        $id = $_POST['id'];
        $newprice = $_POST['newprice'];
        try{
            $sql =  "UPDATE `items` SET Price=? WHERE `Id` = ?";
            $pds = $pdo->prepare($sql);
            $pds->execute(array($newprice,$id));
            echo "Price Updated";
        }catch(PDOException $e){
            echo "<script>alert($e->getMessage());</script>";
        }
    }
}

?>
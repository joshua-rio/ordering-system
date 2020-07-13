<?php 
include "connection.php";
include "classes.php";

if(isset($_POST['action'])){
    if($_POST['action'] == 'updateStaffRecords'){
        $id = $_POST['id'];
        $array = $_POST['input'];
        array_push($array,$id);
        try{
            $sql =  "UPDATE `staff` SET `Username`=?,`Password`=?,`Type`=? WHERE Username=?";
            $pds = $pdo->prepare($sql);
            $pds->execute($array);
            echo "Staff Updated";
        }catch(PDOException $e){
            echo "<script>alert($e->getMessage());</script>";
        }
 }
}

?>
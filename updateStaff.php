<?php 
include "connection.php";
include "classes.php";

if(isset($_POST['action'])){
    if($_POST['action'] == 'updateStaff'){
        $id = $_POST['id'];
        $array = $_POST['input'];
        array_push($array,$id);
        try{
            $sql =  "UPDATE `staff_settings` SET `Cashier`=?,`Check_Card`=?,`Logs`=?,`Card_Holder`=?,`Users`=?,`Register`=?,`Discount`=?,`Register_Staff`=?,`Send_Email`=? WHERE Staff_ID=?";
            $pds = $pdo->prepare($sql);
            $pds->execute($array);
            echo "Staff Updated";
        }catch(PDOException $e){
            echo "<script>alert($e->getMessage());</script>";
        }
 }
}

?>



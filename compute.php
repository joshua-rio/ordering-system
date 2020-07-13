<?php
    include "connection.php";
	include "classes.php";
    $output = "";
    $totaldisc=$disccard=$tempprice=0;
    $price = $_POST['price'];
    $discount = $_POST['disc'];
    $cardnum = $_POST['card'];
    $staff = $_POST['staff'];

    if($price == ""){
        $output = "<option>No Result</option>";
    }else{
        $disccard = $price * 0.1;
        $tempprice=$price;
        $output = "<p style='color: white;padding: 2px 5px;margin:0;'>SubTotal: $" . number_format((float)$price, 2, '.', '') . "</p>";
        if($discount == 0){
            $output .= "<p style='color: white;padding: 2px 5px;margin:0;'>Discount: -$" . number_format((float)$disccard, 2, '.', '') . " - (VIP Card 10% OFF)</p>";
        }else{
            $disccard = $tempprice * (0.01 * $discount);
            $output .= "<p style='color: white;padding: 2px 5px;margin:0;'>Discount: -$" . number_format((float)$disccard, 2, '.', '') . " - (Birthweek $discount% OFF)</p>";
        }

        $tempprice-=$disccard;
        $output .= "<p style='color: white;padding: 2px 5px;margin:0;'>Total: $" . number_format((float)$tempprice, 2, '.', '') . "</p>";
        $output .= "<input type='hidden' name='fprice' value='" . number_format((float)$price, 2, '.', '') . "'>
                    <input type='hidden' name='fdiscount' value='" . number_format((float)$disccard, 2, '.', '') . "'>
                    <input type='hidden' name='ftotal' value='" . number_format((float)$tempprice, 2, '.', '') . "'>
                    <input type='hidden' name='cardnum' value='$cardnum'>
                    <input type='hidden' name='staff' value='$staff'>";
    }

	echo("$output");
?>
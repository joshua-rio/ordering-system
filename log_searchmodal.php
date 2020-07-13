<?php
    include "connection.php";
    include "classes.php";
    $output = "";
    $searchQ = "";
    if(isset($_POST['searchQuery'])){
        $searchQ = $_POST['searchQuery'];
    }else{
        $searchQ = "";
    }
    $sql = "SELECT logs.Price,logs.Discount,logs.Total,logs.Order_ID,logs.Staff,logs.Date,profile.Name
            FROM logs LEFT JOIN profile on logs.CardNumber = profile.CardNumber
            WHERE `Order_ID` LIKE '%$searchQ%'";
    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array());
    $discount=$totalprice=$subtotal=$orderid=0;
    $day=$staff="";
    if($pds->rowcount() == 0){
        $output = "";
    }else{
        while($row = $pds->fetch()){
            $name = $row['Name'];
            $subtotal = $row['Price'];
            $discount = $row['Discount'];
            $totalprice = $row['Total'];
            $orderid = $row['Order_ID'];
            $staff = $row['Staff'];
            $day = $row['Date'];
            $day = date('F j, Y',strtotime($day));
            }
    }

 $output .= "<div class='left'>Order # :$orderid</div></p>
            <div class='right'>Date : $day</div><br><br>
            <hr>
             <p class='adjust'>
             Customer : $name<br>
             Cashier : $staff<br></p>
             <div class='col-lg-10 col-md-10 mx-auto tbl'>
             <table class='data'>
             <tr style='text-align:center;'>
             <th>Price</th>
             <th>Discount</th>
             <th>SubTotal</th>
             </tr>
            ";
        $output.="<tr style='text-align:center;'><td>$$subtotal</td>
                  <td>$$discount</td>  
                  <td> $$totalprice</td></tr>";


 
                 
$output = "<p><button onclick=reprint(this) value=$orderid>PRINT</button>
  ".$output;

    echo("$output");
?>



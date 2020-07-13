<?php
    session_start();
    include "connection.php";
    include "classes.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $output = "";
    $searchQ = $_POST['searchQuery'];
    $searchD = $_POST['searchQuery2'];
    $concat= "";
    if(!empty($searchD) && empty($searchQ)){
        $concat = "Date LIKE '".$searchD."%'";
    }
    elseif(!empty($searchQ) && empty($searchD)){
        $concat = "CardNumber LIKE '%".$searchQ."%' or Staff LIKE '%".$searchQ."%'";
    }
    else{
        $concat = "Date LIKE'".$searchD."%' and CardNumber LIKE '%".$searchQ."%'  or Staff LIKE '%".$searchQ."%'";
    }

    $ctr = 0;
       $sql = "SELECT CardNumber,`Order_ID`,Price,Discount,Total,Staff,DATE(`Date`) AS new_Date FROM LOGS WHERE
       $concat ORDER BY`Order_ID` DESC LIMIT 20";
    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array());
    if($pds->rowcount() == 0){
        $output = "<tbody>
                <tr>
                    <td colspan='8'>No Result</td>
                </tr>
                </tbody>";
    }else{
        while($row = $pds->fetch()){
            $ctr++;
            $total = $row['Total']; 
            $price = $row['Price']; 
            $discount = $row['Discount'];  
            $cardnum = $row['CardNumber'];
            $orderID = $row['Order_ID'];
            $staff = $row['Staff'];
            $date = $row['new_Date'];
            $day = date('F j, Y',strtotime($date));

            $output .= "<tbody>
                            <tr onclick='".'show("'.$orderID.'")'."'>
                                <td>$ctr</td>
                                <td>$cardnum</td>
                                <td>$orderID</td>
                                <td>".number_format((float)$price, 2, '.', '')."</td>
                                <td>".number_format((float)$discount, 2, '.', '')."</td>
                                <td>".number_format((float)$total, 2, '.', '')."</td>
                                <td>$staff</td>
                                <td>$day</td>
                            </tr>
                            </tbody>";
        }
    }

    $output = "<thead style='background-color: #E0A800;'>
                    <tr>
                        <td style='border: none;'>No.</td>
                        <td style='border: none;'>Card Number</td>
                        <td style='border: none;'>Order ID</td>
                        <td style='border: none;'>Price</td>
                        <td style='border: none;'>Discount</td>
                        <td style='border: none;'>Total</td>
                        <td style='border: none;'>Staff</td>
                        <td style='border: none;'>Date</td>
                    </tr>
                </thead>".$output;

	echo("$output");
?>
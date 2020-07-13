<?php
    include "connection.php";
	include "classes.php";
    $output = "";
    $searchQ = $_POST['searchQuery'];

    $ctr = 0;
    $sql = "SELECT * FROM items";
    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array());
    if($pds->rowcount() == 0){
        $output = "<tbody>
                <tr>
                    <td colspan='6'>No Result</td>
                </tr>
                </tbody>";
    }else{
        while($row = $pds->fetch()){
            $ctr++;
            $id = $row['Id'];
            $cardnum = $row['CardNumber'];
            $item = $row['ItemId']."-".$row['Name'];
            $quantity = $row['Quantity'];
            $total = $row['Total'];
            $date = $row['Date'];
            $day = date('F j, Y',strtotime($date));
            $time = date('h:ia',strtotime($date));
            $output .= "<tbody>
                            <tr>
                                <td>$ctr</td>
                                <td>$cardnum</td>
                                <td>$item</td>
                                <td>$quantity</td>
                                <td>$total</td>
                                <td>$day $time</td>
                            </tr>
                            </tbody>";
        }
    }

    $output = "<thead style='background-color: #E0A800;'>
                    <tr>
                        <td style='border: none;'>No.</td>
                        <td style='border: none;'>Card Number</td>
                        <td style='border: none;'>Id-Item</td>
                        <td style='border: none;'>Quantity</td>
                        <td style='border: none;'>Total</td>
                        <td style='border: none;'>Date</td>
                    </tr>
                </thead>".$output;

	echo("$output");
?>
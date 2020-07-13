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

    $sql = "SELECT * FROM discountitem WHERE `Id` LIKE '%$searchQ%' OR `ItemName` LIKE '%$searchQ%'";

    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array());
    if($pds->rowcount() == 0){
        $output = "<tbody>
                <tr style='text-align: center;'>
                    <td colspan='43'>No Result</td>
                </tr>
                </tbody>";
    }else{
        while($row = $pds->fetch()){
            $id = $row['Id'];
            $name = $row['ItemName'];
            $disc = $row['Discount'];
            $output .= "<tbody>
                            <tr style='text-align: center;' onclick='".'updateDiscount("'.$id.'","'.$name.'")'."'>
                                <td>$id</td>
                                <td>$name</td>
                                <td>$disc</td>
                            </tr>
                            </tbody>";
        }
    }

    $output = "<thead style='background-color: #E0A800;'>
                    <tr style='text-align: center;'>
                        <td style='border: none;'>Item Id</td>
                        <td style='border: none;'>Item Name</td>
                        <td style='border: none;'>Discount</td>
                    </tr>
                </thead>".$output;

	echo("$output");
?>
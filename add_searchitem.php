<?php
    include "connection.php";
	include "classes.php";
    $output = "";
    $searchQ = $_POST['searchQuery'];

    $ctr = 0;
    $sql = "SELECT * FROM items WHERE `Name` LIKE '%$searchQ%' OR `Price` LIKE '%$searchQ%' Order by Name ASC LIMIT 20";
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
            $name = $row['Name'];
            $price = $row['Price'];
            $main = $row['Category'];
            $menu = $row['SubCategory'];
            $submenu = $row['ThirdMenu'];
  
            $output .= "<tbody>
                            <tr>
                                <td>$ctr</td>
                                <td>$name</td>
                                <td onclick='".'updateItem("'.$id.'","'.$name.'")'."'>$price</td>
                                <td>$main</td>
                                <td>$menu</td>
                                <td>$submenu</td>
                              
                            </tr>
                            </tbody>";
        }
    }

    $output = "<thead style='background-color: #E0A800;'>
                    <tr>
                        <td style='border: none;'>No.</td>
                        <td style='border: none;'>Items</td>
                        <td style='border: none;'>Price</td>
                        <td style='border: none;'>Main</td>
                        <td style='border: none;'>Category</td>
                        <td style='border: none;'>Sub-Category</td>
                  
                    </tr>
                </thead>".$output;

	echo("$output");
?>
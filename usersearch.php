
<?php
    include "connection.php";
	include "classes.php";
    $output = "";
    $searchQ = $_POST['searchQuery'];

    $ctr = 0;
    $sql = "SELECT * FROM register WHERE `Name` LIKE '%$searchQ%' OR `Birthday` LIKE '%$searchQ%' OR `Contact` LIKE '%$searchQ%' LIMIT 20";
    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array());
    if($pds->rowcount() == 0){
        $output = "<tbody>
                <tr>
                    <td colspan='4'>No Result</td>
                </tr>
                </tbody>";
    }else{
        while($row = $pds->fetch()){
            $ctr++;
            $name = $row['Name'];
            $date = $row['Birthday'];
            $address = $row['Address'];
            $email = $row['Email'];
            $birthday = date('F j, Y',strtotime($date));
            $contact = $row['Contact'];
            $output .= "<tbody>
                            <tr onclick='".'getName("'.$name.'","'.$date.'","'.$address.'","'.$email.'","'.$contact.'")'."'>
                                <td>$ctr</td>
                                <td>$name</td>
                                <td>$birthday</td>
                                <td>$contact</td>
                            </tr>
                            </tbody>";
        }
    }

    $output = "<thead style='background-color: #E0A800;'>
                    <tr>
                        <td style='border: none;'>No.</td>
                        <td style='border: none;'>Name</td>
                        <td style='border: none;'>Birthday</td>
                        <td style='border: none;'>Contact No.</td>
                    </tr>
                </thead>".$output;
	echo("$output");
?>
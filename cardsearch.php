<?php
    session_start();
    include "connection.php";
    include "classes.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $output = "";
    $searchQ = $_POST['searchQuery'];
    $searchStatus = $_POST['searchValidation'];
    $sql = "SELECT * FROM profile  WHERE `CardNumber` = '$searchQ' and `Status`='$searchStatus' or Name Like '%$searchQ%' and `Status`='$searchStatus'Limit 50";
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
            $stat = "";
            $cardnum = $row['CardNumber'];
            $Name = $row['Name'];  
            $add = $row['Address'];
            $bday = $row['Birthday'];
            $email = $row['Email'];
            $ex = $row['Expiration'];
            $status = $row['Status'];
            $contact = $row['Contact'];
            if($searchStatus=='Activate')
               { $stat = 'Invalid';
                 $data_tbl = $ex;
                }
            elseif($searchStatus == 'Deactivate')
            {
                $stat = 'Request';
                $data_tbl = $status;
            }
                else
                {$stat = 'Activate';
                $data_tbl = $status;
            }
            $Action =" <td><input onclick='".'show("'.$cardnum.'")'."' style='width:100%;border:none;background-color:#2ecc71;color:white;' type='button' value='Update'>
                       <input onclick='gotoLog($cardnum)' style='width:100%;border:none;background-color:#d64541;color:white;' type='button' value='Logs'>
                        <input onclick='invalid($cardnum)' style='width:100%;border:none;background-color:#2ecc71;color:white;' type='button' value='$stat'></td>";
        

            if($searchStatus == 'Invalid')
            {
                $Action ="";   
            }
            $output .= "<tbody>
                            <tr>
                                <td>$cardnum</td>
                                <td>$Name</td>
                                <td>$add</td>
                                <td>$bday</td>
                                <td>$email</td>
                                <td>$contact</td>
                                <td>$data_tbl</td>
                                $Action
                            </tr>
                            </tbody>";
        }
    }
    if($searchStatus !='Active')$header = "Status";
    else $header  = "Expiration";
    if($searchStatus == 'Invalid')$Action = "";   
    else $Action =  "<td style='border: none;'>Action</td>";

    $output = "<thead style='background-color: #E0A800;'>
                    <tr>
                        <td style='border: none;'>Card Number</td>
                        <td style='border: none;'>Name</td>
                        <td style='border: none;'>Address</td>
                        <td style='border: none;'>Birthdate</td>
                        <td style='border: none;'>E-mail</td>
                        <td style='border: none;'>Contact Number</td>
                        <td style='border: none;'>$header</td>
                        $Action
                    </tr>
                </thead>".$output;

    echo("$output");
?>
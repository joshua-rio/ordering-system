<?php
    include "connection.php";
    include "classes.php";
    $output = "";
    $searchQ = $_POST['searchQuery'];
    $searchtype = $_POST['searchtype'];


    $ctr = 0;
    $sql = "SELECT * FROM staff WHERE `Username` LIKE '%$searchQ%' AND Type != ? OR `Type` LIKE '%$searchQ%' AND Type != ? Order by Username ASC LIMIT 20";
    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array('Admin','Admin'));
    if($pds->rowcount() == 0){
        $output = "<tbody>
                <tr>
                    <td colspan='4'>No Result</td>
                </tr>
                </tbody>";
    }else{
        while($row = $pds->fetch()){
            $ctr++;
            $username = $row['Username'];
            $password = $row['Password'];
            $type = $row['Type'];

            $output .= "<tbody>
                            <tr>
                                <td>$ctr</td>";
if($searchtype==$username)
{
    if($type != "Manager")
    $output.= "<td><input type='text' class='form-control' id='$username+1' value='$username'></td>";
    else
    $output.= "<td><a id='$username+1' value='$username'>$username</td>";
    $output.="<td><input type='text' class='form-control' id='$username+2' value='$password'></td>
                                <td><select class='form-control' name='type' id='$username+3' style='margin-bottom: 20px;'>";

    $sql1 = "SELECT * FROM staff_settings";
    $pds1 = $pdo->prepare($sql1);
    $pds1->execute(array());
        while($rows1 = $pds1->fetch()){
            if($rows1['Type'] == $type)
            $output.="<option value='".$rows1['Type']."' selected>".$rows1['Type']."</option>";
            else
            $output.="<option value='".$rows1['Type']."'>".$rows1['Type']."</option>";
                
        }
    


    $output.="                  </select></td>
                                <td>
                                <button type='button' class='btn btn-warning' style='margin-bottom:10px;' onclick=update('$username')>SAVE</button>
                                <button type='button' class='btn btn-warning' onclick=edit('0') >CANCEL</button>

                                </td>
                                </tr>
                                </tbody>";
   }
else
    {
         if($searchtype == '0') $lock2=""; else $lock2="disabled";
    $output.="                  <td>$username</td>
                                <td>$password</td>
                                <td>$type</td>
                                <td>
                                <button type='button' class='btn btn-warning' style='margin-bottom:10px;' $lock2 onclick=edit('$username')>EDIT</button>

                                </td>
                                </tr>
                                </tbody>";
   }

     
        }
    }

    $output = "<thead style='background-color: #E0A800;'>
                    <tr>
                        <td style='border: none;'>No.</td>
                        <td style='border: none;'>Username</td>
                        <td style='border: none;'>Password</td>
                        <td style='border: none;'>Type</td>
                        <td style='border: none;'>Action</td>
                     
                    </tr>
                </thead>".$output;

    echo("$output");
?>
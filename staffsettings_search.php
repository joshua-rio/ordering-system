<?php
        include "connection.php";
    	include "classes.php";
        $output = "";
        $searchQ = $_POST['searchQuery'];
        $searchtype = $_POST['searchtype'];

        $ctr = 0;
        $sql = "SELECT * FROM staff_settings WHERE `Type` LIKE '%$searchQ%'";
        $pdo->prepare($sql);
        $pds = $pdo->prepare($sql);
        $pds->execute(array());
        if($pds->rowcount() == 0){
            $output = "<tbody>
                    <tr>
                        <td colspan='10'>No Result</td>
                    </tr>
                    </tbody>";
        }else{
            while($row = $pds->fetch()){
                $id = $row['Staff_ID'];
                $type = $row['Type'];
                    if($searchtype =="$id+")
                        $lock = "";
                    else
                        $lock = "disabled";
                $output .= "<tbody>
                                <tr>
                                    <td>$id</td>
                                    <td>$type </td>";
                        for($x = 2;$x<=10; $x++ )
                        {
                                if($row[$x] == 1)
                                    $output .="<td><input type='checkbox' checked $lock name='$id+$x' id='$id+$x'></td>";
                                else
                                    $output .="<td><input type='checkbox'  $lock name='$id+$x' id='$id+$x'></td>";           
                        }
                        if($lock =="disabled"){
                            if($searchtype == 0) $lock2=""; else $lock2="disabled";
                        
                        $output .="<td>
                        <button type='button' value='$id+' class='btn btn-warning' $lock2 onclick=unlock('$id+') style='margin-bottom:10px;'>EDIT</button>
                        <button type='button' value='$id+' class='btn btn-warning' $lock2 onclick=show('".$id."','".$type."')>DELETE</button>
                        </td></tr>  
                        </tbody>";
                        }
                        else{

                        $output .="<td>
                        <button type='submit' name='save' class='btn btn-warning' value=$id onclick=updateStaff($id)
                        style='margin-bottom:10px;'> SAVE</button>
                        <button type='button' value='$id+' class='btn btn-warning' onclick=unlock('0')>CANCEL</button>
                        </td></tr></tbody>";
                      }
            }
        }

        $output = "<thead style='background-color: #E0A800;'>
                        <tr>
                            <td style='border: none;'>No.</td>
                            <td style='border: none;'>Type</td>
                            <td style='border: none;'>Cashier</td>
                            <td style='border: none;'>Check Holder</td>
                            <td style='border: none;'>Logs</td>
                            <td style='border: none;'>Card Holder</td>
                            <td style='border: none;'>Users</td>
                            <td style='border: none;'>Register</td>
                            <td style='border: none;'>Discount</td>
                            <td style='border: none;'>Staff</td>
                            <td style='border: none;'>Send Email</td>
                            <td style='border: none;'>Action</td>
                            
                        </tr>
                    </thead>".$output;

    	echo("$output");
    ?>
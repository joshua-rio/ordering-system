<script type="text/javascript">
    var labels = ["name","add","bday","email","contact"];
    var textboxes = ["txtname","txtadd","txtbday","txtemail","txtcontact"];
    function Update(){
       for (var i = 0; i < textboxes.length; i++) {
           if(i==2)
            document.getElementById(textboxes[i]).type = 'date';
          else
           document.getElementById(textboxes[i]).type = 'text';
           document.getElementById(labels[i]).style.display = 'none';
       }
       document.getElementById('update').style.display = 'none';
       document.getElementById('save').style.display = 'inline';
        document.getElementById('cancel').style.display = 'inline';
    }

</script>
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

    $sql = "SELECT * FROM profile WHERE `CardNumber` = '$searchQ' ";
    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array());

    if($pds->rowcount() == 0){
        $output = "";
    }else{
        while($row = $pds->fetch()){
           $cardnum = $row['CardNumber'];
            $Name = $row['Name'];  
            $add = $row['Address'];
            $bday = $row['Birthday'];
            $email = $row['Email'];
            $contact = $row['Contact'];
            $datereg = $row['Date_Register'];
            }
    }

 $output .= "<div class='left'><input type=submit value=UPDATE id=update onclick='Update()'></div>
            <form method=post action=save.php>
            <div class='left'><input type=submit id=save value=SAVE style=display:none></div><br>
            
           <br>
            <div class='left'>CARD # :$cardnum</div><br>
            <div class='left'>Date Registered :$datereg</div><br><br>
            <hr>
             <h1 align=center>Card Holder's Info</h1><br>
            <p class='adjust'>
             Name : <label id=name>$Name</label>
           <input type=hidden name=Name value='$Name' id=txtname></p><br><br>
          
           <p class='adjust'>
            Address : <label id=add>$add</label> 
            <input type=hidden name=Add value='$add' id=txtadd size=".strlen($add).">
            </p><br><br>
            
            <p class='adjust'>
           Birthdate : <label id=bday>$bday</label> 
            <input type=hidden name=Bday id=txtbday value='$bday'></p><br><br>

            <p class='adjust'>
            E-mail Address : <label id=email>$email</label> 
             <input type=hidden id=txtemail name=email value='$email' size=".strlen($email).">
            </p><br><br>

             <p class='adjust'>
           Contact Number : <label id=contact>$contact</label> 
           <input type=hidden id=txtcontact name=contact value='$contact'>
           </p><br><br>
              <input type=hidden name=card value='$cardnum'>
              </form>
            ";




$output = "<p>  
  ".$output;

  echo("$output");
?>



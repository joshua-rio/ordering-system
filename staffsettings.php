<?php
	session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    include("notallowed.php");
    
    if(isset($_POST['addtype'])){
        $text = array("Cashier","Check","Logs","Card","User","Register","Discount","Staff","Email");
        $input = array();
        for($x = 0 ; $x <= count($text)-1;$x++){
                   if(isset($_POST[$text[$x]]))
            {
                  array_push($input,"1");  
            }
            else
            {  
                 array_push($input,"0");
            }  

        }     
             array_unshift( $input,$_POST['type']);   
        try{
            $sql = 'INSERT INTO `staff_settings`(`Type`, `Cashier`, `Check_Card`, `Logs`, `Card_Holder`, `Users`, `Register`, `Discount`, `Register_Staff`, `Send_Email`) VALUES (?,?,?,?,?,?,?,?,?,?)';  
            $pds = $pdo->prepare($sql);
            $pds->execute($input);
            echo "<script>alert('Staff has been added!');location='staffsettings.php';</script>";
        }catch(PDOException $e){
            echo "<script>alert('Error: Can't add this user! $e');</script>";
        }

    
}
if(isset($_POST['delete'])){
    $x = 0;
        $sql1 = 'SELECT count(*) as number from staff where Type = ? ';
        $pds1 = $pdo->prepare($sql1);
        $pds1->execute(array($_POST['delete']));
        while($rows = $pds1->fetch()){
            $x = $rows['number'];
        }
         
        if($x==0){
          try{
            
        $sql = 'Delete from staff_settings where Type=?';  
            $pds = $pdo->prepare($sql);
            $pds->execute(array($_POST['delete']));
            echo "<script>alert('Type Deleted!');location='staffsettings.php';</script>";
        }catch(PDOException $e){
            echo "<script>alert('Error: Type cant be deleted! $e');</script>";
        }
    }
        else{
            echo "<script>alert('Error: Selected Type cant be deleted : Type is currently used! ');</script>";
            
        }

}
    


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Guillys - Register Staff</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
       <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
      <script type="text/javascript">
        function searchStaff_settings(){
            var searchTxt = $("input[name='searchtype']").val();
            $.post("staffsettings_search.php", {searchQuery: searchTxt, searchtype:'0'}, function(output) {
                $("#tableStaff").html(output);
                if(output == "<option>No Result</option>"){
                    document.getElementById('btnAdd').setAttribute("disabled","");
                }else{
                    document.getElementById('btnAdd').removeAttribute("disabled");
                }
            });
        }
    function updateStaff(id) {
               var input = [] ;
               for (var i = 2; i<= 10;i++) {
                    if(document.getElementById(id+"+"+i).checked == true)
                   input.push('1');
                    else
                    input.push('0');
               }

                $.ajax({
                    type: "POST",
                    url: 'updateStaff.php',
                    data:
                        {action:'updateStaff',
                        id: id,
                        input:input,
                        },
                    success:function(html) {
                        alert(html);
                        window.location.href="staffsettings.php";
                    }
                });
            
        }

function show(id,name){
var modal = document.getElementById('myModal');
var span = document.getElementsByClassName("close")[0];

//$.post("log_searchmodal.php", {searchQuery: orderno}, function(output) {$("#content").html(output);});
modal.style.display = "block";
var element = document.getElementById("name");
element.innerHTML = "DO YOU WANT TO DELETE '"+name+"' ?";


document.getElementById("delete").value = name;
document.getElementById("cancel").onclick = function() {modal.style.display = "none";}
span.onclick = function() {modal.style.display = "none";}
window.onclick = function(event) {if (event.target == modal) {modal.style.display = "none";}}

}
 



    </script>

  <script type="text/javascript">
        function unlock(id){
            var searchTxt = $("input[name='searchtype']").val();
            $.post("staffsettings_search.php", {searchQuery: searchTxt, searchtype:id}, function(output) {
                $("#tableStaff").html(output); });
        }

 function check(){
            var searchTxt = $("input[name='type']").val();
            $.post("staffcheck.php", {searchQuery: searchTxt}, function(output) {
                $("#insert").html(output); });
        }

    </script>

</head>
<body onload="searchStaff_settings()">
    <?php include "template.php"; ?>
	<div class="back" onclick="window.history.back();"><img src="img/back.png" width="100%" height="100%"></div>
    
    <img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv" style="margin-top: -50px;">
        <div class="row">
            <div class="col-lg-6 col-md-6 mx-auto">
                <h1 style="text-align: center;color: white;">ADD STAFF TYPE</h1>
                <form method="POST">
                    <input type="text" class="form-control" style="text-align: center;margin-bottom: 20px;" name="type" placeholder="Input Type" required onkeyup="check()">
                    <table class="col-12" > 
                        <tr><td><input type="checkbox"  name="Cashier">Cashier Page</td>
                        <td><input type="checkbox"  name="Check">Check Card Page</td></tr>
                        <tr><td><input type="checkbox"  name="Logs">Logs Page</td>
                        <td><input type="checkbox"  name="Card">Card Holder Page</td></tr>
                        <tr><td><input type="checkbox"  name="User">Users Page</td>
                        <td><input type="checkbox"  name="Register">Register Page</td></tr>
                        <tr><td><input type="checkbox"  name="Discount">Discount Page</td>
                        <td><input type="checkbox"  name="Staff">Register Staff Page</td></tr>
                        <tr><td><input type="checkbox"  name="Email">Send Email Page</td></tr>
                        </table>
                        <div id="insert">
                    <button type="submit" name="addtype" class="btn btn-warning">Add Type </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <!-- DIV STAFF-->
    <br>
 <div class="container myDiv" style="margin-bottom: 50px;">
        <h1 style="text-align: center;color: white;">STAFF TYPE</h1>
                <form method="POST">
                    <div class="form-group">
                        <center><input type="text" class="form-control" style="text-align: center;width: 80%;" name="searchtype" onkeyup="searchStaff_settings();" placeholder="Search Staffs"></center>
                    </div>
                </form>
        <div class="row">
            <div class="col-lg-10 col-md-10 mx-auto" style="height: 50vh;overflow-y: auto;">
                <table class="table table-hover" id="tableStaff">
                    <thead style="background-color: #E0A800;">
                    <tr>
                        <td style="border: none;">No.</td>
                        <td style="border: none;">Type</td>
                        <td style="border: none;">Cashier</td>
                        <td style="border: none;">Check Holder</td>
                        <td style="border: none;">Logs</td>
                        <td style="border: none;">Card Holder</td>
                        <td style="border: none;">Users</td>
                        <td style="border: none;">Register</td>
                        <td style="border: none;">Discount</td>
                        <td style='border: none;'>Staff</td>
                        <td style="border: none;">Send Email</td>
                        <td style="border: none;">Action</td>
         
                    </tr>
                    </thead>
                    <tbody>
                        <tr
>                            <td colspan="10">Search</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
   <script src="js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>



    <div id="myModal" class="modal">
    <div class="modal-content" style="width: 500px;height: 40%; overflow-y: auto;">
    <span class="close" style="color: red;margin-left: 95%;">&times;</span>
    <div id="content" style="color: black;">
    <center><p id="name">*</p></center>
     <div class="col-lg-6 col-md-6 mx-auto">
        <form method="post">
          <div class="col-12"><button type="submit" name="delete" class="btn btn-warning" id="delete" style="margin-bottom:10px;" value=""> DELETE</button></div>
            </form>
          <div class="col-12"><button type="button" name="save" class="btn btn-warning" id="cancel" > CANCEL</button></div>
         
          </div>
           </div>


</body>
</html>
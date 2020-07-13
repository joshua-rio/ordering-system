<?php
	session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $siteenabled = 0;
    $sql = "SELECT Card_Holder FROM staff_settings WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($AdminInSession->type));
    if($pds->rowcount() == 0){

    }else{
        while($row = $pds->fetch()){
            $siteenabled = $row['Card_Holder'];
        }
    }


    $_SESSION['TempType'] = "";

    if($siteenabled == 1){
        $_SESSION['TempType'] = "Admin";
    }else{
        $_SESSION['TempType'] = "Not";
    }

    if(isset($_POST['manager'])){
        $manager = $_POST['manager'];
        $sql = "SELECT * FROM staff WHERE `Type` = ?";
		$pds = $pdo->prepare($sql);
		$pds->execute(array('Manager'));

		if($pds->rowcount() == 0){
			echo "<script type='text/javascript'>alert('Card not Valid!');</script>";
		}else{
			while($row = $pds->fetch()){
                $user = $row['Username'];
                if (password_verify($user, $manager)) {
					$_SESSION['TempType'] = "Admin";
                }
			}
		}
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guillys - Card Holders</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/logs.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        var number = "";
        var validation = "Activate";
        var req = "";
        function searchLog(){
            var searchTxt = $("input[name='searchcard']").val();
        	$.post("cardsearch.php", {searchQuery: searchTxt,searchValidation:validation}, function(output) {
                $("#tablecardholder").html(output);
            });
        }
        function gotoLog(cardnum){
            window.location.href="logs.php?fromholder="+cardnum;
        }
        function show(cardnum){
        var modal = document.getElementById('myModal');
        var span = document.getElementsByClassName("close")[0];
        $.post("cardholdermodal.php", {searchQuery: cardnum}, function(output) {$("#content").html(output);});
        modal.style.display = "block";
        span.onclick = function() {
        modal.style.display = "none";
        }
        window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        }

        }
        function invalid(cardnum){
        number = cardnum;
        document.getElementById("Cardnumber").innerHTML = cardnum;
        var modal = document.getElementById('myModal_invalid');
        var span = document.getElementsByClassName("close_invalid")[0];
        if(validation=='Activate')
        document.getElementById("ActionType").innerHTML = "INVALID";
        else if(validation == 'Deactivate')
        document.getElementById("ActionType").innerHTML = "REQUEST";
       else     
        document.getElementById("ActionType").innerHTML = "ACTIVATE";
       
       // $.post("cardholdermodal.php", {searchQuery: cardnum}, function(output) {$("#content").html(output);});
        modal.style.display = "block";
        document.getElementById('manager').focus();
     
        span.onclick = function() {
        modal.style.display = "none";
        number = "";

        }
        window.onclick = function(event) {
        if (event.target == modal) {
        document.getElementById('manager').focus();
        }

        }

        modal.onclick = function(event){
            if (event.target !=modal ){
        document.getElementById('manager').focus();
            }
        }

        }

        function checkManager(managercode){
        var modal = document.getElementById('myModal_invalid');
        if(validation == 'Activate')
            valid_2 = 'Invalid';
        else if(validation == 'Request')
            valid_2 = 'Activate';
        else
            valid_2 = 'Request';
       
        $.post("checkManagerCard.php", {manager: managercode.value}, function(output) {
        if(output){
        updatecard();
        }else{
         alert('Invalid Managers Card');
       
        }

        });
           modal.style.display = "none";
            document.getElementById('manager').value = "";
      
        }
        function updatecard(){
         $.post("CardUpdate.php", {cardno:number,searchValidation:valid_2}, function(output) {
            alert(output);   
            searchLog();
            document.getElementById('Card').value = "";
            document.getElementById('myModal_activation').style.display = "none";

        });




        }

        function valid(type){
            validation = type;
                searchLog();
       
            // if(validation == 'Active'){
            //     validation = 'Invalid';
            //     document.getElementById('validation').value = "ACTIVE";
            // }
            // else{
            //     validation ='Active';
            //     document.getElementById('validation').value = "DEACTIVATE";
            //     }
             
                
        }
        // function open(){
        // var modal = document.getElementById('myModal_activation');
        // var span = document.getElementsByClassName("close_invalid_renew")[0];
        // modal.style.display = "block";
        // document.getElementById('Card').focus();
        
        // span.onclick = function() {
        // modal.style.display = "none";
        // number = "";

        // }
        // window.onclick = function(event) {
        // if (event.target == modal) {
        // document.getElementById('Card').focus();
        // }

        // }

        // modal.onclick = function(event){
        //     if (event.target !=modal ){
        // document.getElementById('Card').focus();
        //     }
        // }

        // }

        // function requesttbl(){
        //     var searchTxt = $("input[name='searchcard']").val();
        //     if(req =="Request"){
        //         req = validation;
        //         document.getElementById('request_btn').value = "REQUEST";
        //         document.getElementById('validation').style.visibility = "";
        //     }
        //     else{
        //         req = "Request" ;
        //         document.getElementById('validation').style.visibility = "hidden";
          
        //         document.getElementById('request_btn').value = "BACK";
                
        //     }
        //     $.post("cardsearch.php", {searchQuery: searchTxt,searchValidation:req}, function(output) {
        //         $("#tablecardholder").html(output);
        //     });
        // }


    </script>
</head>

<?php if($_SESSION['TempType'] != 'Admin'): ?>
    <body>

    <?php include "template.php"; ?>
    <div class="back" onclick="window.history.back();"><img src="img/back.png" width="100%" height="100%"></div>
    <img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv">

        <div class="row">
            <div class="col-lg-10 col-md-10 mx-auto tbl" style="height: 70vh;overflow-y: auto;">
                <h1 style="text-align: center;color: white;">Manager Only</h1>
                <form method="POST" autocomplete="off">
                    <div class="form-group">
                        <input type="password" class="form-control manager" style="text-align: center;" name="manager" placeholder="Tap Manager Card" autofocus>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    </body>
<?php else: ?>
<body onload="searchLog()">
    <?php include "template.php"; ?>
	<div class="back" onclick="window.history.back();"><img src="img/back.png" width="100%" height="100%"></div>
    <img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv">    
    <h1 style="text-align: center;color: white;">CARD HOLDERS</h1>
		<form method="POST">
                    <div class="form-group">
                        <center><input type="text" class="form-control" style="text-align: center;width:80%;" name="searchcard" onkeyup="searchLog();" placeholder="Search Card Number"></center>
                    </div>
                </form>
        <div class="row">
            <div class="col-lg-11 col-md-11 mx-auto tbl" style="height: 50vh;overflow-y: auto;border-bottom:1px solid #777777;">
                <table class="table table-hover" id="tablecardholder">
                    <thead style="background-color: #E0A800;">
                    <tr>
                        <td style="border: none;">Card Number</td>
                        <td style="border: none;">Name</td>
                        <td style="border: none;">Address</td>
                        <td style="border: none;">Birthdate</td>
                        <td style="border: none;">E-mail</td>
                        <td style="border: none;">Contact Number</td>
                        <td style="border: none;">Expiration</td>
                        <td style="border: none;">Action</td>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="col-lg-10 col-md-10 mx-auto">
                <input type="submit" class="btn btn-warning" style="width: 150px;margin-top: 20px;" onclick="valid('Activate')" id="validation" value='ACTIVATE'>
                <input type="submit" class="btn btn-warning" style="width: 150px;margin-top: 20px;" onclick="valid('Deactivate')" id="validation" value='DEACTIVATE'>
                <input type="submit" class="btn btn-warning" style="width: 150px;margin-top: 20px;" onclick="valid('Request')" id="validation" value='REQUEST'>
                <input type="submit" class="btn btn-warning" style="width: 150px;margin-top: 20px;" onclick="valid('Invalid')" id="validation" value='INVALID'>
                
                 </div>
        </div>
    </div>
    <script src="js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<div id="myModal" class="modal">
  <div class="modal-content" style="width: 500px;height: 90%; overflow-y: auto;">
    <span class="close" style="color: red;margin-left: 95%;">&times;</span>
   <div id="content" style="color: black;">
  </div>

</div>

</div>

<div id="myModal_invalid" class="modal">
  <div class="modal-content" style="width: 700px;height: 50%; overflow-y: auto;">
    <span class="close_invalid" style="color: red;margin-left: 95%;">&times;</span>
   <div id="content" style="color: black;">

    <h1>MANAGER AUTHORIZATION</h1><br><h4>TAP THE CARD</h4>
   <center> DO YOU WANT TO <a id="ActionType">INVALID</a> <p id="Cardnumber">0</p></center> 
    <input type="password" id=manager name="manager" style="width: 100%;height: 50%;" 
    onchange="checkManager(this)" autofocus>
  </div>

</div>

</div>


<div id="myModal_activation" class="modal">
  <div class="modal-content" style="width: 700px;height: 50%; overflow-y: auto;">
    <span class="close_invalid_renew" style="color: red;margin-left: 95%;">&times;</span>
   <div id="content" style="color: black;">

    <h1>RENEW CARD</h1><br><h4>TAP THE CARD</h4>
    <input type="password" id="Card" name="Card" style="width: 100%;height: 50%;" 
    onchange="updatecard(this)" autofocus>
  </div>

</div>

</div>



</body>
<?php endif; 
?>
</html>
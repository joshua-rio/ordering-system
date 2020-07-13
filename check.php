<?php
	session_start();
	include "connection.php";
	include "classes.php";
	include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $siteenabled = 0;
    $sql = "SELECT Check_Card FROM staff_settings WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($AdminInSession->type));
    if($pds->rowcount() == 0){

    }else{
        while($row = $pds->fetch()){
            $siteenabled = $row['Check_Card'];
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
		<title>Guillys - Purchase</title>
		<link rel="icon" href="img/logoblack.png" />
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/index.css">
		<link rel="stylesheet" href="css/menu.css"> 
        <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/logs.css">
    <link rel="stylesheet" href="css/modal.css">

		<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
		<script type="text/javascript">
            var cdn;
        function searchQ(){
            var searchTxt = $("input[name='searchcard']").val();
        	$.post("searchcard.php", {searchQuery: searchTxt}, function(output) {
                $("#validity").html(output);
                if(output != ""){
                    document.getElementById('validity').style.backgroundColor = "green";
                }else{
                    document.getElementById('validity').style.backgroundColor = "red";
                    $("#validity").html("NO USER FOUND");
                }
            });
        }

        function fulldetails(cardnumber){
             document.getElementById('manager').focus();
            cdn = cardnumber;
           // alert(cdn);
            var modal = document.getElementById('myModal');
        var span = document.getElementsByClassName("close")[0];
        modal.style.display = "block";
        span.onclick = function() {
        modal.style.display = "none";
        }
        window.onclick = function(event) {
        if (event.target == modal) {
            document.getElementById('manager').focus();
        }   
        }
        }
        function checkManager(managercode){
           var modal = document.getElementById('myModal');
          $.post("checkmanager.php", {manager: managercode.value,cardno:cdn}, function(output) {
             $("#validity").append(output);
            
             modal.style.display = "none";
             document.getElementById('details').style.display = 'none';
               document.getElementById('manager').value = "";
              
                 document.getElementById('exampleInputEmail1').focus();
      });
        }
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
<body>
		<?php include "template.php"; ?>
		<div class="back" onclick="window.history.back();"><img src="img/back.png" width="100%" height="100%"></div>
        <img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
		<div class="container myDiv">
            <div class="row">
                <div class="col-lg-6 col-md-8 details mx-auto">
                    <h1 style="text-align: center;color: white;">CHECK CARD</h1>
                    <form method="POST">
                        <div class="form-group">
                            <input type="text" class="form-control" id="exampleInputEmail1" maxlength="14" minlength="14" name="searchcard" onkeyup="searchQ();" style="font-size:17px;padding:10px;" placeholder="Card Number" autofocus>
                            <input type="submit" class="form-control" style="visibility: hidden;height: 0px;" disabled>
                        </div>
                         </form>
                        <div class="form-group">
                            <p id="validity" style="color:white;text-align:center;background-color:red;padding: 10px 0px;">NO CARD</p>
                        </div>
                   
                </div>
            </div>
		</div>
    <script src="js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<div id="myModal" class="modal">
  <div class="modal-content" style="width: 700px;height: 40%; overflow-y: auto;">
    <span class="close" style="color: red;margin-left: 95%;">&times;</span>
   <div id="content" style="color: black;">

    <h1>MANAGER AUTHORIZATION</h1><br><h4>TAP THE CARD</h4>
    
    <input type="password" id=manager name="manager" style="width: 100%;height: 50%;" 
    onchange ="checkManager(this)" autofocus>
  </div>

</div>

</div>
</body>
<?php endif; ?>
</html>
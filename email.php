<?php
  session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
  $AdminInSession = unserialize($_SESSION['AdminSession']);
  $siteenabled = 0;
    $sql = "SELECT Send_Email FROM staff_settings WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($AdminInSession->type));
    if($pds->rowcount() == 0){

    }else{
        while($row = $pds->fetch()){
            $siteenabled = $row['Send_Email'];
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
  <style type="text/css">
    body{
    background-color: black;
	  font-family: 'Fresh';
}
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.message {
  height: 100%;
 width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit]:hover {
  background-color: #45a049;
}

  </style>
    <title>Guillys - Email Blast</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/logs.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    
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
            <div class="col-lg-10 col-md-10 mx-auto tbl" style="height: 70vh;overflow-y: auto;">
               <h3 align="center" style="color: white; font-family: Fresh Eaters;">EMAIL BLAST</h3>
        <form method="post" action="send_mail.php">
        Subject :   <input type="text" name="mail_sub">
       <br/>
         Message   <textarea class="message" name="mail_msg" rows="10"></textarea>
        <br/>
            <input type="submit" value="Send Email">
        </form>

            </div>
        </div>
    </div>
    <script src="js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<?php endif; ?>
</html>
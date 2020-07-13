<?php
	session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    include "notallowed.php";

    $type = "";
    if(isset($_GET['type'])){
        $type = $_GET['type'];
    }else{
        $type = "password";
    }
    
    if(isset($_POST['change'])){
        $old = $_POST['oldpass'];
		$new = $_POST['newpass'];
		$new2 = $_POST['newpass2'];
		$sql = "SELECT * FROM staff WHERE Username = ?";
        $pds = $pdo->prepare($sql);
        $pds->execute(array($AdminInSession->username));

        if($pds->rowcount() == 0){
           echo 'Username not Found!';
        }else{
            while($row = $pds->fetch()){
                $pass = $row['Password'];
                if ($old == $pass) {
                    if($new == $new2){
						$sql = 'UPDATE staff SET password=? WHERE username=?';
						$pds = $pdo->prepare($sql);
						if($pds->execute(array($new,$AdminInSession->username))){
							echo "<script>alert('Password has been updated!');</script>";
						}else{
							echo "<script>alert('Error! Password not changed!');</script>";
						}
					}else{
						echo "<script>alert('Confirm Password is not the same');</script>";
					}
                } else {
                    echo "<script>alert('Old password is incorrect');</script>";
                }
            }
        }
    }


    if(isset($_POST['changepin'])){
        $oldpin = $_POST['oldpin'];
		$newpin = $_POST['newpin'];
		$newpin2 = $_POST['newpin2'];
		$sql = "SELECT * FROM staff WHERE Username = ?";
        $pds = $pdo->prepare($sql);
        $pds->execute(array($AdminInSession->username));

        if($pds->rowcount() == 0){
           echo 'Username not Found!';
        }else{
            while($row = $pds->fetch()){
                $pin = $row['Pin'];
                if ($pin == $oldpin) {
                    if($newpin == $newpin2){
						$sql = 'UPDATE staff SET Pin=? WHERE username=?';
						$pds = $pdo->prepare($sql);
						if($pds->execute(array($newpin,$AdminInSession->username))){
							echo "<script>alert('PIN has been updated!');</script>";
						}else{
							echo "<script>alert('Error! PIN not changed!');</script>";
						}
					}else{
						echo "<script>alert('Confirm PIN is not the same');</script>";
					}
                } else {
                    echo "<script>alert('Old PIN is incorrect');</script>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Guillys - Change Password</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/discount.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
</head>
<body>
    <?php include "template.php"; ?>
	<img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv" style="position:absolute;top:45%;left:50%;transform: translate(-50%,-50%);">
        <div class="row">
            <div class="col-lg-6 col-md-6 mx-auto">
                <?php if($type == "password"): ?>
                <h1 style="text-align: center;color: white;">CHANGE PASSWORD</h1>
                <form method="POST">
                    <input type="password" class="form-control" style="text-align: center;margin-bottom: 20px;" name="oldpass" placeholder="Old Password">
                    <input type="password" class="form-control" style="text-align: center;margin-bottom: 20px;" name="newpass" placeholder="New Password">
                    <input type="password" class="form-control" style="text-align: center;margin-bottom: 20px;" name="newpass2" placeholder="Confirm Password">
                    <button type="submit" name="change" class="btn btn-warning">Change Password</button>
                    <?php if($AdminInSession->type == "Admin"): ?><p style="color:#CCCCCC;text-decoration: underline;" onclick="window.location.href='changepass.php?type=pin'">Change PIN</p><?php endif;?>
                </form>
                <?php elseif($type == "pin"): ?>
                <h1 style="text-align: center;color: white;">CHANGE PIN</h1>
                <form method="POST">
                    <input type="password" class="form-control" style="text-align: center;margin-bottom: 20px;"  maxlength="4" name="oldpin" placeholder="Old PIN">
                    <input type="password" class="form-control" style="text-align: center;margin-bottom: 20px;" minlength="4" maxlength="4" name="newpin" placeholder="New PIN">
                    <input type="password" class="form-control" style="text-align: center;margin-bottom: 20px;" minlength="4" maxlength="4" name="newpin2" placeholder="Confirm PIN">
                    <button type="submit" name="changepin" class="btn btn-warning">Change PIN</button>
                    <p style="color:#CCCCCC;text-decoration: underline;" onclick="window.location.href='changepass.php?type=password'">Change Password</p>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
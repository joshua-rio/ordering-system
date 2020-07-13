<?php
    session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    date_default_timezone_set('Asia/Manila');
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $siteenabled = 0;
    $sql = "SELECT Users FROM staff_settings WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($AdminInSession->type));
    if($pds->rowcount() == 0){

    }else{
        while($row = $pds->fetch()){
            $siteenabled = $row['Users'];
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
    
    $email = 'lopezchristiangabriel12@gmail.com';
	$subject = 'Guillys VIP Card Activated';
	$headers = "From: guillys@website.com" . "\r\n" . "CC: guillys@website.com";
	$headers .= "\r\nMIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    
    if(isset($_POST['add'])){
        $name = $_POST['name'];
        $birthday = $_POST['birthday'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $cardnum = $_POST['cardnum'];
        $dateregister = date('Y-m-d')." ".date('h:i:s');
        $expire = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + 365 day"));

        try{
            $sql = 'INSERT INTO profile (CardNumber,CardType,Name,Birthday,Address,Email,Contact,Expiration,Status,Date_Register) VALUES (?,?,?,?,?,?,?,?,?,?)';
            $pds = $pdo->prepare($sql);
            $pds->execute(array($cardnum,"Gold",$name,$birthday,$address,$email,$contact,$expire,"Active",$dateregister));
            echo "<script>alert('Profile has been added!');location='adduser.php';</script>";
            $result = mail($email,$subject,"Thank you $name for purchasing our VIP Card, now you can enjoy getting discounts by just presenting the card on purchase! 
                                            Card Expires at $expire",$headers);
            if(!$result){
                echo "<script>alert('Email was not sent');location='adduser.php';</script>";
            }else{
                echo "<script>alert('Email Sent Successfully');location='adduser.php';</script>";
            }
        }catch(PDOException $e){
            echo "<script>alert('Error: Profile was not updated! $e');</script>";
        }

        try{
            $sql = 'DELETE FROM register WHERE Name = ?';
            $pds = $pdo->prepare($sql);
            $pds->execute(array($name));
        }catch(PDOException $e){
            echo "<script>alert('Error: Account was not deleted in register database! $e');</script>";
        }
        
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Guillys - Add User</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/logs.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        function searchLog(){
            var searchTxt = $("input[name='search']").val();
        	$.post("usersearch.php", {searchQuery: searchTxt}, function(output) {
                $("#tableLog").html(output);
            });
        }

        function getName(name,birthday,address,email,contact){
            document.getElementById('cname').value = name;
            document.getElementById('birthday').value = birthday;
            document.getElementById('address').value = address;
            document.getElementById('email').value = email;
            document.getElementById('contact').value = contact;
            console.log(name + " " + birthday);
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
            <div class="col-lg-12 col-md-12 mx-auto">
                <h1 style="text-align: center;color: white;">ADD USER</h1>
                <div class="form-group">
                    <input type="text" class="form-control" style="text-align: center;" name="search" onkeyup="searchLog();" placeholder="Search User">
                </div>
                <form method="POST">
                    <div class="scrollable"> 
                    <table class="table table-hover" id="tableLog">
                        <thead style="background-color: #E0A800;">
                        <tr>
                            <td style="border: none;">No.</td>
                            <td style="border: none;">Name</td>
                            <td style="border: none;">Birthday</td>
                            <td style="border: none;">Contact No.</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">Search</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" id="cname" style="color: black;" placeholder="Name" readonly required>
                        <input type="hidden" class="form-control" name="birthday" id="birthday">
                        <input type="hidden" class="form-control" name="address" id="address">
                        <input type="hidden" class="form-control" name="email" id="email">
                        <input type="hidden" class="form-control" name="contact" id="contact">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="cardnum" minlength="14" maxlength="14" style="color: black;" placeholder="Card">
                    </div>
                    <button type="submit" name="add" id="btnAdd" class="btn btn-warning">Add User</button>
                </form>
            </div>
        </div>
    </div>
</body>
<?php endif; ?>
</html>
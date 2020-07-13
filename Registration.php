<?php
session_start();
include "connection.php";
include "classes.php";	
include "checkSession.php";
$AdminInSession = unserialize($_SESSION['AdminSession']);
$siteenabled = 0;
    $sql = "SELECT Register FROM staff_settings WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($AdminInSession->type));
    if($pds->rowcount() == 0){

    }else{
        while($row = $pds->fetch()){
            $siteenabled = $row['Register'];
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
		$pds->execute(array('Admin'));

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
	<title>Registration</title>
</head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Guillys - Add User</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/logs.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
<style type="text/css">
body {
  font-family: Fresh Eaters, Helvetica, sans-serif;
   background-color: #222222;
   color: white;
}
* {
  box-sizing: border-box;
}
.container {
  padding: 16px;
}
input[type=text], input[type=password],input[type=number],input[type=date] {
  width: 50%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus, input[type=number]:focus, input[type=date]:focus {
  background-color: #ddd;
  outline: none;
}
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}
.registerbtn {
  background-color: #4CAF50;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}
label{
	color: white;
}

a {
  color: dodgerblue;
}	
</style>
<script type="text/javascript">
	var invalid= new Array();
	var index=0;
	function Validate(id){
 	if(Number.isInteger(parseInt(id.value.charAt(id.value.length - 1))) && id.type == "text" && id.value.trim().length > 0 && id.id != "Mobile"){
		document.getElementById(id.id+'span').style.display = 'inline';
		document.getElementById('register').disabled = true;
		document.getElementById(id.id).style.borderColor = "red";
	
			if(invalid.includes(id.id,0) == false)
				invalid.push(id.id);
 	}
 	else if(!Number.isInteger(parseInt(id.value.charAt(id.value.length - 1))) && id.type == "text" && id.value.trim().length > 0 && id.id == "Mobile"){
		document.getElementById(id.id+'span').style.display = 'inline';
		document.getElementById('register').disabled = true;
		document.getElementById(id.id).style.borderColor = "red";
	
			if(invalid.includes(id.id,0) == false)
				invalid.push(id.id);
 	}
	else{
		document.getElementById(id.id+'span').style.display = 'none';
		document.getElementById(id.id).style.borderColor = "white";
		document.getElementById('register').disabled = false;
		for (var i = 0; i < invalid.length; i++) {
			if(invalid[i] == id.id)
			index = i;
		}
		if(invalid.includes(id.id,0) == true)
		invalid.splice(index,1);
	}
			if(id.id == "Email" && id.value.trim().length > 0){
				w=0;
				for (var i = 0; i < id.value.length; i++) {
					if(id.value.charAt(i) != "@"){
						w++;
					}
				}
				if(w == id.value.length)
					{
						invalid.push(id.id);
						document.getElementById(id.id+'span').style.display = 'inline';			
				}
			}
		var name = id.id + "modal";
		document.getElementById(name).value = document.getElementById(id.id).value;	
	
}	
	function setAge(bday){
		var byear="",bmonth="",birthday="";
		var age=0;
		for (var i=0;i < bday.value.length;i++) {
				if(i< 4)
				byear += bday.value.charAt(i);
			else if (i < 7 && i > 4)
				bmonth += bday.value.charAt(i);
			else if (i < 10 && i > 7)
				birthday += bday.value.charAt(i);
		}
		var date= new Date();
		var year = date.getFullYear();
		var month = "0" + parseInt(date.getMonth() + 1);
		var date= date.getDate();
			if(parseInt(bmonth) < parseInt(month)){

				if(parseInt(birthday) <= parseInt(date))
						age = parseInt(year) - parseInt(byear);
					else
						age = (parseInt(year) - parseInt(byear)) - 1;
			}else if (parseInt(bmonth) > parseInt(month))
			{
				age = (parseInt(year) - parseInt(byear)) - 1;
			}
			else
				{
					if(parseInt(birthday) <= parseInt(date))
						age = parseInt(year) - parseInt(byear);
					else
						age = (parseInt(year) - parseInt(byear)) - 1;
				}
				
				if(age <= 0)
					age = "INVALID AGE";
				else if(age < 18)
					age = "MINOR ARE NOT ALLOWED";
		
		document.getElementById('Age').value = age;
		document.getElementById('Agemodal').value = age;
		document.getElementById('Birthdaymodal').value = byear +"-" + bmonth + "-" + birthday;
	}
	</script>

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
<center>
<h1>Register</h1>
</center>
   <div class="container myDiv">
        <div class="row">
            <div class="col-lg-10 col-md-10 mx-auto tbl" style="height: 70vh;overflow-y: auto;">

	<form  method="post" action="registerquery.php">
	<label for="Name">Name</label><br>
	<input type=text name="Name" id="Name" onkeyup="Validate(this)" placeholder="Enter Name" required>
	<span id='Namespan' style=display:none>Invalid</span><br>
	
	<label for="Address">Address</label><br>
	<input type=text name="Address" id="Address" placeholder="Enter Address" required>
	<span id='Addressspan' style=display:none>Invalid</span><br>
	
	<label for="Mobile">Mobile Number</label><br>
	<input type=text name="Mobile" id="Mobile" onkeypress ="Validate(this)" maxlength="11" placeholder="Enter Mobile Number" required>
	<span id='Mobilespan' style=display:none>Invalid</span><br>
	
	<label for="Facebook">Facebook Account</label><br>
	<input type=text name="Facebook" id="Facebook" placeholder="Enter Facebook Account">
	<span id='Facebookspan' style=display:none>Invalid</span><br>
	
	<label for="Address">Occupation</label><br>
	<input type=text name="Occupation" id="Occupation" onkeyup="Validate(this)" placeholder="Enter Occupation">
	<span id='Occupationspan' style=display:none>Invalid</span><br>
	
	<label for="E-mail">E-mail</label><br>
	<input type=text name="Email" id="Email" onkeyup="Validate(this)" placeholder="Enter E-mail" required>
	<span id='Emailspan' style=display:none>Invalid</span><br>
	
	<label for="Birthday">Birthdate</label><br>
	<input type=date name="Birthday" id="Birthday" onchange='setAge(this)' placeholder="Enter Birthdate" required><br>
	
	<label for="Age">Age</label><br>
	<input type=number name="Age" id="Age" required placeholder="Enter Age" readonly><br>

	
 <input type="submit" id="register" name="registraition" value="REGISTER">
</form>
       </div>
        </div>
    </div>

</body>
<?php endif; ?>
</html>

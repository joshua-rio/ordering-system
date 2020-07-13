<?php
    session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $siteenabled = 0;
    $sql = "SELECT Discount FROM staff_settings WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($AdminInSession->type));
    if($pds->rowcount() == 0){

    }else{
        while($row = $pds->fetch()){
            $siteenabled = $row['Discount'];
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
    
    $discount = 0;
	$sql = "SELECT * FROM discountbmonth";
	$pds = $pdo->prepare($sql);
	$pds->execute(array());
	if($pds->rowcount() == 0){

	}else{
        while($rows = $pds->fetch()){
            $discount = $rows['discount'];
        }
    }
    
    if(isset($_POST['add'])){
        $disc = $_POST['disc'];

        try{
            $sql = 'UPDATE discountbmonth SET discount = ?';
            $pds = $pdo->prepare($sql);
            $pds->execute(array($disc));
            echo "<script>alert('Discount has been updated!');location='discountbmonth.php';</script>";
        }catch(PDOException $e){
            echo "<script>alert('Error: Discount was not updated! $e');</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Guillys - Birthmonth Discounts</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/discount.css">
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
	<img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv">
        <div class="row">
            <div class="col-lg-6 col-md-6 details mx-auto">
                <h1 style="text-align: center;color: white;">BIRTHMONTH DISCOUNT</h1>
                <form method="POST">
                    <input type="number" class="form-control" style="text-align: center;margin-bottom: 20px;" name="disc" min="0" max="100" value="<?php echo $discount; ?>" placeholder="Discount %">
                    <button type="submit" name="add" class="btn btn-warning">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>
<?php endif; ?>
</html>
<?php
	session_start();
	require __DIR__ . '/autoload.php';
	include "connection.php";
	include "classes.php";
	include 'greetBirthday.php';
	include 'DeactiveCard.php';
	include "functions.php";
	include "checkSession.php";
	$AdminInSession = unserialize($_SESSION['AdminSession']);
	$siteenabled = 0;
    $sql = "SELECT Cashier FROM staff_settings WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($AdminInSession->type));
    if($pds->rowcount() == 0){

    }else{
        while($row = $pds->fetch()){
            $siteenabled = $row['Cashier'];
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
		<title>Guillys - Purchase</title>
		<link rel="icon" href="img/logoblack.png" />
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/index.css">
		<link rel="stylesheet" href="css/menu.css">
		<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
		<script type="text/javascript">
			function checkCard(){
				var card = $("input[name='cardnum']").val();
        	$.post("checkcard.php", {searchQuery: card}, function(output) {
                $("#validity").html(output);
                if(output != ""){
										document.getElementById('btnAdd').removeAttribute("disabled");
                    document.getElementById('validity').style.backgroundColor = "green";
										next();
                }else{
										document.getElementById('btnAdd').setAttribute("disabled","");
                    document.getElementById('validity').style.backgroundColor = "red";
                    $("#validity").html("INVALID");
                }
            });
			}
			function changePrice(){
				var price = $("input[name='price']").val();
				if(price != ""){
					document.getElementById('btnSubmit').removeAttribute("disabled");
				}else{
					document.getElementById('btnSubmit').setAttribute("disabled","");
				}
				var discount = <?php echo $discbmonth; ?>;
				var cardnum = <?php echo $cardnum; ?>;
				var staff = '<?php echo $AdminInSession->username; ?>';
				$.post("compute.php", {price: price,disc: discount,card: cardnum,staff: staff}, function(output) {
					if(output != ""){
						$('#outputdisplay').html(output);
					}else{
						
					}
				});
			}
      function back(len){
					if(document.referrer == "http://localhost/guillys/login.php"){
						var log = confirm('Are you sure you want to logout?');
						if(log){
							$.ajax({ url: 'index.php',
											data: {action: 'backlog'},
											type: 'post',
											success: function(output) {
												window.location.href = "index.php";
																}
							});
						}
					}else{
						window.history.back();
					}
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
		<div class="back" onclick="back(history.length)"><img src="img/back.png" width="100%" height="100%"></div>
		<img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
		<div class="container myDiv">
				<div class="row">
						<div class="col-lg-6 col-md-8 details mx-auto">
								<h1 style="text-align: center;color: white;">PURCHASE</h1>
								<form method="POST">
										<div class="form-group">
												<input type="text" class="form-control" style="text-align:center;" onkeyup="checkCard()"  maxlength="14" minlength="14" name="cardnum" value="<?php if(isset($_SESSION['cardnum'])){ echo $_SESSION['cardnum'];}?>" placeholder="Card Number">
												<p id="validity" style="text-align:center;padding: 5px;color:white;"></p>
										</div>
										<button type="submit" name="add" id="btnAdd" class="btn btn-warning" disabled>Confirm</button>
								</form>	
						</div>
						<div class="col-lg-4 mx-auto">
							<h2 style="text-align: center;color: white;padding-top: 50px;">Discounted</h2>
						

									<div class="row" style="margin-bottom: 10px;">
										<div class="col-sm-11 col-xs-11">
											<div class="input-group mb-3">
												<div class="input-group-prepend">
													<span class="input-group-text" id="basic-addon1">₱</span>
												</div>
												<input type="number" class="form-control" id="price" onkeyup="changePrice()" name="price" placeholder="Price" aria-label="Price" aria-describedby="basic-addon1" <?php if(!isset($_POST['cardnum'])){echo "disabled";}else{}?>>
											</div>
										</div>
									</div>
										<form method="POST">
											<div id="outputdisplay"></div>
											<button type="submit" name="purchase" id="btnSubmit" class="btn btn-warning" onclick="" disabled>Purchase
													
											</button>
										</form>
						</div>
				</div>
		</div>
    <script src="js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
<?php endif; ?>
</html>
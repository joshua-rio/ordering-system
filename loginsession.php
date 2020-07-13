<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
<script>
	function confirmPin(pin,username,type){
		var testpin = prompt("Enter 4 digit PIN: ");
		if(testpin==pin){
			$.post("confirm.php", {username: username,type: type}, function(output) {
				window.location.href="index.php";
			});
		}else if(testpin==null){

		}else{
			alert("Wrong PIN");
		}
	}
</script>

<?php
	include "connection.php";
	include "classes.php";
	session_start();
	
	if(isset($_POST['username']) && isset($_POST['password'])){
		$id = $_POST['username'];
		$pw = $_POST['password'];

		$sql = "SELECT * FROM staff WHERE Username = ?";
		$pds = $pdo->prepare($sql);
		$pds->execute(array($id));

		if($pds->rowcount() == 0){
			echo "<script type='text/javascript'>alert('Login details are incorrect!');</script>";
		}else{
			while($row = $pds->fetch()){
				$pass = $row['Password'];
				if ($pw == $pass){
                	$AdminInSession = new Admin($row['Username'], $row['Type']);
					$_SESSION['AdminSession'] = serialize($AdminInSession);
					echo "<script type='text/javascript'>alert('Successful Login!');window.location.href='index.php';</script>";
                } else {
                    echo "<script type='text/javascript'>alert('Invalid Password!');</script>";
                }
			}
		}
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
					echo "<script>confirmPin(".$row['Pin'].",'".$row['Username']."','".$row['Type']."');</script>";
                }
			}
		}
    }
?>


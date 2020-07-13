<?php
	if(!isset($_SESSION['AdminSession'])){
		
		
		echo "<script>
		alert('Please login to continue!');
		window.location.href='login.php';
		</script>";
	}
?>
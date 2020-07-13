<?php 
    session_start();
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    include("notallowed.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Guillys - Discounts</title>
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
	<div class="back" onclick="window.history.back();"><img src="img/back.png" width="100%" height="100%"></div>
    <img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv">
        <div class="row">
            <div class="col-lg-6 col-md-6 mx-auto">
                <h1 style="text-align: center;color: white;">SELECT DISCOUNT</h1>
                <button style="margin-bottom: 40px;margin-top: 50px;" type="submit" name="Item" onclick="window.location.href='discountitem.php'" class="btn btn-warning">Item</button>
                <button style="margin-bottom: 40px;" type="submit" name="Day" onclick="window.location.href='discountday.php'" class="btn btn-warning">Day</button>
                <button type="submit" name="Birthmonth" onclick="window.location.href='discountbmonth.php'" class="btn btn-warning">Birthmonth</button>
            </div>
        </div>
    </div>
</body>
</html>
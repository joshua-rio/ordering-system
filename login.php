<?php
	include "connection.php";
    include "loginsession.php";
    
    $type = "";
    if(isset($_GET['type'])){
        $type = $_GET['type'];
    }else{
        $type = "key";
    }
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

    <div class="container myDiv" style="position:absolute;top:45%;left:50%;transform: translate(-50%,-50%);">
        <div class="row">
            <div class="col-lg-6 col-md-6 mx-auto">
                <h1 style="text-align: center;color: white;">LOGIN</h1>
                <?php if($type == "key"): ?>
                <form method="POST">
                    <input type="text" class="form-control" style="text-align: center;margin-bottom: 20px;" name="username" placeholder="Username" autofocus>
                    <input type="password" class="form-control" style="text-align: center;margin-bottom: 20px;" name="password" placeholder="Password">
                    <button type="submit" name="login" class="btn btn-warning">Login</button>
                    <p style="color:#CCCCCC;text-decoration: underline;" onclick="window.location.href='login.php?type=card'">Login By Card</p>
                </form>
                <?php elseif($type == "card"): ?>
                <form method="POST">
                <input type="password" class="form-control manager" style="text-align: center;margin-bottom: 20px;" name="manager" placeholder="Tap Manager Card" autofocus>
                    <p style="color:#CCCCCC;text-decoration: underline;" onclick="window.location.href='login.php?type=key'">Login By Original</p>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
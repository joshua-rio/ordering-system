<?php
    session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $siteenabled = 0;
    $sql = "SELECT Logs FROM staff_settings WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($AdminInSession->type));
    if($pds->rowcount() == 0){

    }else{
        while($row = $pds->fetch()){
            $siteenabled = $row['Logs'];
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
    <title>Guillys - Logs</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/logs.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        function searchLog(){
            var searchTxt = $("input[name='searchcard']").val();
            var searchDate = $("input[name='searchdate']").val();   
            $.post("logsearch.php", {searchQuery: searchTxt,searchQuery2: searchDate}, function(output) {
                $("#tableLog").html(output);
            });
        }
        function show(orderno){
            var modal = document.getElementById('myModal');
            var span = document.getElementsByClassName("close")[0];
            $.post("log_searchmodal.php", {searchQuery: orderno}, function(output) {$("#content").html(output);});
            modal.style.display = "block";
            span.onclick = function() {
                modal.style.display = "none";
            }
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
        function reprint(orderid){
            $.post("print.php", {orderid: orderid.value}, function(output) {
               alert("Re-Printing Order # "+orderid.value);
            });
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
<body onload="searchLog()">
    <?php include "template.php"; ?>
    <div class="back" onclick="window.history.back();"><img src="img/back.png" width="100%" height="100%"></div>
    <img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv">
                    <div class="form-row">
                        <div class="col-2">
                            <p style="color: white;float:right;padding:10% ">Date</p>
                        </div>
                        <div class="col-9">
                         <input type="date" class="form-control" style="text-align: center;" name="searchdate" onchange="searchLog();" placeholder="Date">
                        </div> 
                    </div>
                    <h1 style="text-align: center;color: white;">LOGS</h1>
                        <form method="POST">
                            <div class="form-group">
                                <center><input type="text" class="form-control" style="text-align: center;width: 80%;" name="searchcard" onkeyup="searchLog();" value="<?php if(isset($_GET['fromholder'])){ echo $_GET['fromholder'];}?>" placeholder="Search Card Number"></center>
                            </div>
                        </form>
   
        <div class="row">
            <div class="col-lg-10 col-md-10 mx-auto tbl" style="height: 50vh;overflow-y: auto;">
                <table class="table table-hover" id="tableLog">
                    <thead style="background-color: #E0A800;">
                    <tr>
                        <td style="border: none;">No.</td>
                        <td style="border: none;">Card Number</td>
                        <td style="border: none;">Order ID</td>
                        <td style="border: none;">Price</td>
                        <td style="border: none;">Discount</td>
                        <td style="border: none;">Total</td>
                        <td style="border: none;">Staff</td>
                        <td style="border: none;">Date</td>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script src="js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<div id="myModal" class="modal">
  <div class="modal-content" style="width: 500px; overflow-y: auto;">
    <span class="close" style="color: red;margin-left: 95%;">&times;</span>
   <div id="content" style="color: black;">
  </div>

</div>

</body>
    <?php endif; ?>
</html>
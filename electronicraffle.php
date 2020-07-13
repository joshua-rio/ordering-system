<?php
    session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $siteenabled = 0;
    $sql = "SELECT Electronic_Raffle FROM staff_settings WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($AdminInSession->type));
    if($pds->rowcount() == 0){

    }else{
        while($row = $pds->fetch()){
            $siteenabled = $row['Electronic_Raffle'];
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
    <title>Guillys - Electronic Raffle</title>
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
    var names ="";
    var index=0,time=0;
    var Clock;
   var modal = document.getElementById('myModal');
    $(window).ready(function() {

            $.post("getpicks.php", {}, function(output) {
             $('#pick').empty();
         $('#pick').append(output);
            
        });


        $.post("raffle.php", {}, function(output) {
          names = output;
            
        },"json");

     
       
    Clock = {
    totalSeconds: 800,
    start: function () {
        var self = this;

        this.interval = setInterval(function () {
            self.totalSeconds -= 1;

            if(index >= names.length)
                index=0;
            $('#raffle').empty();
             $('#raffle').append("<p style='color: white;'>"+names[index]+"</p>");
            
             
             if(time == 300)
             {      
                Clock.pause();
                    time=0;
                    showWinner(names[index]);
                   
                }
                time++;
                 index++;
        }, 10);
    },

    pause: function () {
        clearInterval(this.interval);
        delete this.interval;
    }
};
    });
    
    function startime(){
         $('#content').empty();
     $.post("raffle.php", {}, function(output) {
          names = output;
            
        },"json");
    
    Clock.start();
    }

    function stoptime(){
         var modal = document.getElementById('pick');
   // Clock.pause();
     if(modal.style.display == "block")
        {
         modal.style.display = "none";
            $("#result").html('Show Winners');
        }
    else
    {
         modal.style.display = "block";
         $("#result").html('Hide Winners');
    }
    }
    function showWinner(winner){
         var modal = document.getElementById('myModal');
         $.post("getwinner.php", {cardNumber:winner}, function(output) {
         
         $('#content').append(output);
            
        });
          modal.style.display = "block";
    }
     function closemodal() {
    var modal = document.getElementById('myModal');
        modal.style.display = "none";
         addwinner();
        }
        window.onclick = function(event) {
              var modal = document.getElementById('myModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
         addwinner();
        }
        function addwinner() {
        
        $.post("getpicks.php", {}, function(output) {
             $('#pick').empty();
         $('#pick').append(output);
            
        });
        }

        function showContact(winner) {
       var modal = document.getElementById('myModal');
         $.post("getContact.php", {cardNumber:winner}, function(output) {
           $('#content').empty();
         $('#content').append(output);
            
        });
          modal.style.display = "block";
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
        <div class="row">
    <div class="col-lg-10 col-md-8 mx-auto tbl" style="height: 70vh;overflow-y: auto;">
                <center>
    <button style="width: 10%;height: 10%; font-size: 30px" value="Start" onclick="startime()">Start</button>
    <button style="width: auto;height: 10%; font-size: 30px" value="Show" onclick="stoptime()" id=result>Show Winners</button><br><br>
    <div id=raffle style="height:100px;border:2px solid white;font-size: 70px; ">
            <p style="color:white;">PRESS START</p>
    </div>
    <div align="left" id=pick style="height:auto;border:2px solid white;font-size: 30px; display: none;">
       

   </div>
            </div>
        </div>
    </div>
    <script src="js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<div id="myModal" class="modal">
  <div class="modal-content" style="color:black;font-size: 20px ;width: 500px;height: 70%; overflow-y: auto;">
    <span onclick="closemodal()"class="close" style="color: red;margin-left: 95%;">&times;</span>
  
   <div id="content" style="color: black;text-align: center;">

  </div>
   

    </div>

</body>
<?php endif; ?>
</html>

    



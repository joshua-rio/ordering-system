<?php 
    if(isset($_POST['logout'])){
		session_unset();
		session_destroy();
		echo "<script>window.location.href = 'index.php'</script>";
    }
?>
<script>
    $('document').ready(function(){
        $menutoggle = false;
        $('.logo').click(function(){
            if($menutoggle == false){
                $('body').css('overflow','hidden');
                $('.menu').css('marginLeft','0px');
                $('.logo').css('marginLeft','110px');
                $('.logo').css('width','70px');
                $('.logo').css('transform','rotatey(360deg)');
                $('.filter').css('backgroundColor','rgba(0,0,0,0.6)');
                $('.filter').css('visibility','visible');
                $menutoggle = true;
            }else{
                $('body').css('overflow','auto');
                $('.menu').css('marginLeft','-300px');
                $('.logo').css('marginLeft','20px');
                $('.logo').css('width','100px');
                $('.logo').css('transform','rotatey(0deg)');
                $('.filter').css('backgroundColor','rgba(0,0,0,0)');
                $('.filter').css('visibility','hidden');
                $menutoggle = false;
            }
        });
        $('.filter').click(function(){
            $('body').css('overflow','auto');
            $('.menu').css('marginLeft','-300px');
            $('.logo').css('marginLeft','20px');
            $('.logo').css('width','100px');
            $('.logo').css('transform','rotatey(0deg)');
            $('.filter').css('backgroundColor','rgba(0,0,0,0)');
            $('.filter').css('visibility','hidden');
            $menutoggle = false;
        });
    });
</script>
<img src="img/logo.png" width="100" class="logo" style="margin:20px;z-index:5;position:absolute;transition:all 0.35s ease-in-out;" draggable="false">
<div class="menu" style="overflow-y: auto; height: 100vh;">
    <div class="nav">
        <div style="width: 100%;background-color: #555555;" <?php if($AdminInSession->type == "Manager"): ?>onclick="window.location.href='changepass.php'"<?php endif;?>>
            <p style="color: white; text-align: center;padding:10px 0px;"><?php echo $AdminInSession->username . " - " . $AdminInSession->type;  ?></p>
        </div>
        <p onclick="window.location.href = 'index.php'"><img src="img/cashier.png" width="30" height="30" draggable="false">CASHIER</p>
        <p onclick="window.location.href = 'check.php'"><img src="img/cashier.png" width="30" height="30" draggable="false">CHECK CARD</p>
        <p onclick="window.location.href = 'logs.php'"><img src="img/log.png" width="30" height="30" draggable="false">LOGS</p>
        <p onclick="window.location.href = 'cardholder.php'"><img src="img/cards.png" width="30" height="30" draggable="false">CARD HOLDERS</p>
        <!--<p onclick="window.location.href = 'adduser.php'"><img src="img/user.png" width="30" height="30" draggable="false">ACTIVATE CARD</p>-->
        <p onclick="window.location.href = 'Registration.php'"><img src="img/user.png" width="30" height="30" draggable="false">REGISTER</p>
        <p onclick="window.location.href = 'discountbmonth.php'"><img src="img/discount.png" width="30" height="30" draggable="false">DISCOUNTS</p>
        <p onclick="window.location.href = 'addstaff.php'"><img src="img/user.png" width="30" height="30" draggable="false">REGISTER STAFF</p>
	    <p onclick="window.location.href = 'email.php'"><img src="img/email.png" width="30" height="30" draggable="false">SEND EMAIL</p>  
        <p onclick="window.location.href = 'electronicraffle.php'"><img src="img/raffle.png" width="30" height="30" draggable="false">ELECTRONIC RAFFLE</p>  
 </div>
    <p style="color: white; text-align: center;padding:10px 0px;">&copy; 2019 GUILLYS</p>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"><input type="submit" style="width:100%;border:none;padding: 10px;background-color:goldenrod;" value="LOG OUT" name="logout"></form>
</div>

<div class="filter"></div>
<?php
    session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    $siteenabled = 0;
    $sql = "SELECT Register_Staff FROM staff_settings WHERE Type = ?";
    $pds = $pdo->prepare($sql);
    $pds->execute(array($AdminInSession->type));
    if($pds->rowcount() == 0){

    }else{
        while($row = $pds->fetch()){
            $siteenabled = $row['Register_Staff'];
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
    
    if(isset($_POST['register'])){
        $username = $_POST['username'];
        $type = $_POST['type'];

        try{
            $sql = 'INSERT INTO staff (Username, Password, Type, Pin)
            VALUES (?,?,?,?)';
            $pds = $pdo->prepare($sql);
            $pds->execute(array($username,$username,$type,0000));
            echo "<script>alert('Staff has been added!');location='addstaff.php';</script>";
        }catch(PDOException $e){
            echo "<script>alert('Error: Can't add this user! $e');</script>";
        }
    }

    $stafftype = array();
    $sql = "SELECT * FROM staff_settings";
    $pds = $pdo->prepare($sql);
    $pds->execute(array());
    if($pds->rowcount() == 0){
        $thistype = new StaffType("");
        array_push($stafftype, $thistype);
    }else{
        while($rows = $pds->fetch()){
            $thistype = new StaffType($rows['Type']);
            array_push($stafftype, $thistype);
        }
    }


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Guillys - Register Staff</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
       <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
      <script type="text/javascript">
        function searchStaff(){
            var searchTxt = $("input[name='searchstaff']").val();
            $.post("add_searchstaff.php", {searchQuery: searchTxt,searchtype:'0'}, function(output) {
                $("#tableStaff").html(output);
                if(output == "<option>No Result</option>"){
                    document.getElementById('btnAdd').setAttribute("disabled","");
                }else{
                    document.getElementById('btnAdd').removeAttribute("disabled");
                }
            });
        }
         function edit(id){
            var searchTxt = $("input[name='searchstaff']").val();
            $.post("add_searchstaff.php", {searchQuery: searchTxt,searchtype:id}, function(output) {
                $("#tableStaff").html(output);
                if(output == "<option>No Result</option>"){
                    document.getElementById('btnAdd').setAttribute("disabled","");
                }else{
                    document.getElementById('btnAdd').removeAttribute("disabled");
                }
            });
        }
            function update(id) {
               var input = [] ;
               for (var i = 1; i<= 3;i++) { input.push(document.getElementById(id+"+"+i).value); }

                $.ajax({
                    type: "POST",
                    url: 'updateStaffRecords.php',
                    data:
                        {action:'updateStaffRecords',
                        id: id,
                        input:input,
                        },
                    success:function(html) {
                        alert(html);
                        window.location.href="addstaff.php";
                    }
                });
            
        }

        function changePass(username){
            var newpass = prompt("Change " + username + "'s password to:");
            
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
<body onload="searchStaff()">
    <?php include "template.php"; ?>
    <div class="back" onclick="window.history.back();"><img src="img/back.png" width="100%" height="100%"></div>
    <?php if($AdminInSession->type!="Manager"): else: ?><div class="settings" onclick="window.location.href='staffsettings.php'"><img src="img/settings.png" width="100%" height="100%"></div><?php endif; ?>
    <img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv" style="margin-top: -50px;">
        <div class="row">
            <div class="col-lg-6 col-md-6 mx-auto">
                <h1 style="text-align: center;color: white;">REGISTER STAFF</h1>
                <form method="POST">
                    <input type="text" class="form-control" style="text-align: center;margin-bottom: 20px;" name="username" placeholder="Username" required>
                    <select class="form-control" name="type" id="t" style="margin-bottom: 20px;">
                        <?php foreach($stafftype as $staff): ?>
                            <option value="<?php echo $staff->type; ?>"><?php echo $staff->type; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="register" class="btn btn-warning">Register</button>
                </form>
            </div>
        </div>
    </div>
    <!-- DIV STAFF-->
    <br>
 <div class="container myDiv">
 <h1 style="text-align: center;color: white;">STAFF</h1>
                <form method="POST">
                    <div class="form-group">
                        <center><input type="text" class="form-control" style="text-align: center;width: 80%;" name="searchstaff" onkeyup="searchStaff();" placeholder="Search Staffs"></center>
                    </div>
                </form>
        <div class="row">
        
            <div class="col-lg-10 col-md-10 mx-auto scroll" style="height: 35vh;overflow-y: auto;margin-bottom: 50px;border-bottom: 1px solid white;">
                <table class="table table-hover" id="tableStaff">
                    <thead style="background-color: #E0A800;">
                    <tr>
                        <td style="border: none;">No.</td>
                        <td style="border: none;">Name</td>
                        <td style="border: none;">Password</td>
                        <td style="border: none;">Type</td>
                        <td style="border: none;">Action</td>
            
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6">Search</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<?php endif; ?>
</html>
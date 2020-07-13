<?php
    session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
	$AdminInSession = unserialize($_SESSION['AdminSession']);
    
    $item = array();
	$sql = "SELECT * FROM discountitem";
	$pds = $pdo->prepare($sql);
	$pds->execute(array());
	if($pds->rowcount() == 0){
        $thisitem = new ItemDiscount(0,"",0);
        array_push($item,$thisitem);
    }else{
            while($rows = $pds->fetch()){
                    $thisitem = new ItemDiscount($rows['Id'],$rows['ItemName'],$rows['Discount']);
                    array_push($item,$thisitem);
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
    <title>Guillys - Discounts</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/logs.css">
    <link rel="stylesheet" href="css/discount.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        function searchLog(){
            var searchTxt = $("input[name='searchitem']").val();
        	$.post("discitemsearch.php", {searchQuery: searchTxt}, function(output) {
                $("#tableLog").html(output);
            });
        }

        function updateDiscount(id,name) {
            var disc = prompt("Set new discount percentage of " + name + " to: ");

            if(disc == null || disc == ""){

            }else if(disc < 0){
                alert("Invalid input!");
            }else{
                $.ajax({
                    type: "POST",
                    url: 'queries.php',
                    data:
                        {action:'updatediscount',
                        id: id,
                        discount: disc},
                    success:function(html) {
                        alert(html);
                        window.location.href="discountitem.php";
                    }
                });
            }
        }
    </script>
</head>
<body style="color: white;" onload="searchLog()">
    <?php include "template.php"; ?>
	<img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv">
        <div class="row">
            <div class="col-lg-10 col-md-10 mx-auto tbl" style="height: 70vh;overflow-y: auto;">
                <h1 style="text-align: center;color: white;">ITEM DISCOUNT</h1>
                <form method="POST" style="margin-bottom: 20px;">
                    <div class="row">
                        <div class="col-12">
                            <input type="text" class="form-control" style="text-align: center;" name="searchitem" onkeyup="searchLog();" placeholder="Search Item Id/Name">
                        </div>
                    </div>
                </form>
                <table class="table table-hover" id="tableLog">
                    <thead style="background-color: #E0A800;">
                    <tr style="text-align: center;">
                        <td style="border: none;">Item Id</td>
                        <td style="border: none;">Item Name</td>
                        <td style="border: none;">Discount</td>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
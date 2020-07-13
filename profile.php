<?php
    session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
	$AdminInSession = unserialize($_SESSION['AdminSession']);

	$sql = "SELECT * FROM profile";
	$pds = $pdo->prepare($sql);
	$pds->execute(array());
	$num = $pds->rowcount();
    
    if(isset($_POST['add'])){
        $name = $_POST['name'];
        $price = $_POST['price'];

        try{
            $sql = 'INSERT INTO items (Name, Price)
            VALUES (?,?)';
            $pds = $pdo->prepare($sql);
            $pds->execute(array($name,$price));
            echo "<script>alert('Item has been added!');</script>";
        }catch(PDOException $e){
            echo "<script>alert('Error: Item was not added! $e');</script>";
        }

        $itemid = 0;
        $sql = "SELECT * FROM items WHERE Name = ?";
        $pds = $pdo->prepare($sql);
        $pds->execute(array($name));
        if($pds->rowcount() == 0){

        }else{
            while($rows = $pds->fetch()){
                $itemid = $rows['Id'];
            }
        }

        try{
            $sql = 'INSERT INTO discountitem (Id, ItemName, Discount)
            VALUES (?,?,?)';
            $pds = $pdo->prepare($sql);
            $pds->execute(array($itemid,$name,0));
        }catch(PDOException $e){
            echo "<script>alert('Error: DiscountItem Table! $e');</script>";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Guillys - Add Item</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        function getItem(){
            var searchTxt = $("input[name='searchitems']").val();
        	$.post("logsearch.php", {searchQuery: searchTxt}, function(output) {
                $("#tableLog").html(output);
                if(output == "<option>No Result</option>"){
                    document.getElementById('btnAdd').setAttribute("disabled","");
                }else{
                    document.getElementById('btnAdd').removeAttribute("disabled");
                }
            });
        }
    </script>
     <script type="text/javascript">
        function searchItems(){
            var searchTxt = $("input[name='searchitem']").val();
            $.post("add_searchitem.php", {searchQuery: searchTxt}, function(output) {
                $("#tableItems").html(output);
                if(output == "<option>No Result</option>"){
                    document.getElementById('btnAdd').setAttribute("disabled","");
                }else{
                    document.getElementById('btnAdd').removeAttribute("disabled");
                }
            });
        }
        function updateItem(id,name) {
            var newprice = prompt("Set new price of " + name + " to: ");

            if(newprice == null || newprice == ""){

            }else if(newprice < 0){
                alert("Invalid input!");
            }else{
                $.ajax({
                    type: "POST",
                    url: 'queries.php',
                    data:
                        {action:'updateprice',
                        id: id,
                        newprice: newprice},
                    success:function(html) {
                        alert(html);
                        window.location.href="additem.php";
                    }
                });
            }
        }
    </script>
</head>
<body  onload="searchItems()">
    <?php include "template.php"; ?>
    <div class="back" onclick="window.history.back();"><img src="img/back.png" width="100%" height="100%"></div>
	<img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv">
        <div class="row">
            <div class="col-lg-10 col-md-10 mx-auto">
                <h1 style="text-align: center;color: white;">ADD PROFILE</h1>
                <form method="POST">
                    <div class="row">
                        <div class="col-6">
                            <input type="text" class="form-control" name="name" placeholder="Item Name" required>
                        </div>
                        <div class="col-3">
                            <input type="number" class="form-control" name="price" min="1" placeholder="Price" required>
                        </div>
                        <div class="col-3">
                            <button type="submit" name="add" id="btnAdd" class="btn btn-warning">Add Item</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- DIV for Searching-->
<br>
 <div class="container myDiv">
        <div class="row">
            <div class="col-lg-10 col-md-10 mx-auto" style="height: 70vh;overflow-y: auto;">
                <h1 style="text-align: center;color: white;">ITEMS</h1>
                <form method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" style="text-align: center;" name="searchitem" onkeyup="searchItems();" placeholder="Search Items">
                    </div>
                </form>
                <table class="table table-hover" id="tableItems">
                    <thead style="background-color: #E0A800;">
                    <tr>
                        <td style="border: none;">No.</td>
                        <td style="border: none;">Items</td>
                        <td style="border: none;">Price</td>
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
</html>

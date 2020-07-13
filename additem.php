<?php
    session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
    $AdminInSession = unserialize($_SESSION['AdminSession']);
    include("notallowed.php");
    
    $item = array();
	$sql = "SELECT * FROM items";
	$pds = $pdo->prepare($sql);
	$pds->execute(array());
	if($pds->rowcount() == 0){
			$thisitem = new Items(0,"",0);
			array_push($item,$thisitem);
	}else{
			while($rows = $pds->fetch()){
                    $thisitem = new Items($rows['Id'],$rows['Name'],$rows['Price']);
					array_push($item,$thisitem);
			}
    }
    
    if(isset($_POST['add']) && $_POST['Main'] != ""&& $_POST['Menu']!="" ){
        $name = $_POST['name'];
        $price = $_POST['price'];
        $main = $_POST['Main'];
        $menu = $_POST['Menu'];
        $submenu = $_POST['Submenu'];

        try{
            $sql = 'INSERT INTO items (Name, Price,Category,SubCategory,ThirdMenu)
            VALUES (?,?,?,?,?)';
            $pds = $pdo->prepare($sql);
            $pds->execute(array($name,$price,$main,$menu,$submenu));
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
    elseif(isset($_POST['add'])){
          echo "<script>alert('Error:Invalid Details');</script>";
          
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
        function changemenu(){
                    var searchmenu = $("select[name='Main']").val();
         $.post("changemenu.php", {searchQuery: searchmenu}, function(output) {
                $("#Menu").html(output);
        });
            $("#Submenu").html("<option value=''>No Sub Menu Available</option>");
    }
          function changesubmenu(){
                    var searchsubmenu = $("select[name='Menu']").val();
         $.post("searchsubmenu.php", {searchQuery: searchsubmenu}, function(output) {
                $("#Submenu").html(output);
        });
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
                <h1 style="text-align: center;color: white;">ADD ITEM</h1>
                <form method="POST">
                    <div class="row">
                        <div class="col-7">
                            <input type="text" class="form-control" name="name" placeholder="Item Name" required>
                        </div>
                        <div class="col-5">
                            <input type="number" class="form-control" name="price" min="1" placeholder="Price" required>
                        </div>
                             <div class="col-4">
                                        <select id="Main" class="form-control" name="Main" onchange="changemenu()">
                                            <option selected hidden>Choose Main</option>
                                          <?php
                                              $sql = "SELECT DISTINCT(Main)as Cat FROM menu_category";
                                              $pdo->prepare($sql);
                                              $pds = $pdo->prepare($sql);
                                              $pds->execute(array());
                                                  if($pds->rowcount() == 0){
                                       echo "<option>No Result</option>";
                                     }else{
                                     while($row = $pds->fetch()){
                                         $menu = $row['Cat'];
                                            echo "<option value='$menu'>$menu</option>";
                                             }
                                                 }
                                          ?>  
                                        </select>
                                        </div>
                        <div class="col-4">
                                        <select id="Menu" class="form-control" name="Menu" onchange="changesubmenu()" >
                                                <option value="">No Menu Available</option>
                                        </select>
                                        </div>
                        <div class="col-4">
                                        <select id="Submenu" class="form-control" name="Submenu" >
                                                <option value="">No Sub Menu Available</option>
                                        </select>
                                        </div>

                        <div class="col-12">
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
                         <td style="border: none;">Main</td>
                        <td style="border: none;">Category</td>
                        <td style="border: none;">Sub-Category</td>
                                        
        
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

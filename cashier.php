<?php
  session_start();
  include "connection.php";
  include "classes.php";
  include "functions.php";
  include "checkSession.php";
  $AdminInSession = unserialize($_SESSION['AdminSession']);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="img/logoblack.png" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}
.main {
  float:left;
  width:70%;
  padding:15px;
  color: white;
  margin-top:7px;
  background-color:gray;
height: 506px;
}
.purchase {
  background-color:#e5e5e5;
  float:left;
  width:30%;
  padding:15px;
  margin-top:7px;
  text-align:center;
 height: 506px;
 max-height: 506px;
 overflow-y: scroll;
}
@media only screen and (max-width:620px) {
  /* For mobile phones: */
  .menu, .main, .purchase {
    width:100%;
  }
.tab {
  background-color: #f1f1f1;
}
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;

}
.tablinks{
  background-color: green;
}
</style>
</head>
  <script>
    var cart = [];
    var items = [];

function category(evt, categoryName) {
   $("#"+categoryName).empty();  $("#item").empty();
cat = categoryName;
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(categoryName).style.display = "block";
  evt.currentTarget.className += " active";
 
    $.post("subcategory.php", {Subcategory: categoryName}, function(output) {
                $("#"+categoryName).append(output);
            });
     
}

function startup(){
  $("#tabs").empty(); $("#itemenu").empty();
 $.post("category.php", {category: ""}, function(output) {
                $("#tabs").append(output);
            });

 $.post("categorycontent.php", {category: ""}, function(output) {
                $("#main").append(output);
            });

}

function viewSubCategory(subcategory){
          $("#item").empty();
       $.post("thirdcategory.php", {subcat: subcategory}, function(output1) {
                $("#item").append(output1);

            });
     
     }

function viewItems(subcategory){
     
          $("#items").empty();
       $.post("items.php", {subcat: subcategory}, function(output1) {
                $("#items").append(output1);

            });
     
     }

function addtocart(item){
        var total=0;
         var div = document.getElementById("purchasediv");
        div.scrollTop = div.scrollHeight;
          cart.push(item.value);
          items.push(item.name);
          $("#purchase ").append("<tr><td>1</td><td>"+item.name+"</td><td>"+item.value+"</td></tr>");
        
        for (var i = cart.length - 1; i >= 0; i--) {
          total += parseFloat(cart[i]);
        }
        document.getElementById("total").innerHTML = "Total:"+parseFloat(total).toFixed(2);
     }

</script>
<body onload="startup()" style=" font-family: Fresh Eaters;">
    <?php include "template.php"; ?>
    <div class="back" onclick="window.history.back();"><img src="img/back.png" width="100%" height="100%"></div>
    <img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">

       

<div style="background-color: gold;padding:15px;text-align:center;">

  <div class="tab" id=tabs>
  </div>
</div>

<div style="overflow:scroll;">
 
  <div class="purchase" id=purchasediv>
    <div>
    <h3>PURCHASE</h3>
    <table id=purchase style="width: 100%;">
      <tr>
      <th>Qty</th>
      <th>Description</th>
      <th>Price</th>
    </tr>
    </table>
  </div>
</div>

  <div class="main" id=main>
  
</div>


<div style="background-color: black;color: white; font-size: 30px;text-align: left;">
<span id="total">Total:</span>
</div>
<div style="background-color: gold;text-align:center;padding:10px;"></div>

</body>
</html>

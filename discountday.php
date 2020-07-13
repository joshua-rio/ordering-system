<?php
    session_start();
    include "connection.php";
    include "classes.php";
    include "checkSession.php";
	$AdminInSession = unserialize($_SESSION['AdminSession']);
    
    $discount = array();
	$sql = "SELECT * FROM discountday";
	$pds = $pdo->prepare($sql);
	$pds->execute(array());
	if($pds->rowcount() == 0){
			$thisdisc = new DiscountDay("",0);
			array_push($discount,$thisdisc);
	}else{
			while($rows = $pds->fetch()){
                    $thisdisc = new DiscountDay($rows['Day'],$rows['Discount']);
					array_push($discount,$thisdisc);
			}
    }

    $dd = 0;
    foreach($discount as $d){
        if($d->day == "Monday"){
            $dd =$d->disc;
        }
    }
    
    if(isset($_POST['add'])){
        $disc = $_POST['disc'];
        $day = $_POST['day'];

        try{
            $sql = 'UPDATE discountday SET Discount = ? WHERE Day = ?';
            $pds = $pdo->prepare($sql);
            $pds->execute(array($disc,$day));
            echo "<script>alert('Discount has been updated!');location='discountday.php';</script>";
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
    <title>Guillys - Birthmonth Discounts</title>
    <link rel="icon" href="img/logoblack.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/discount.css">
    <link rel="stylesheet" href="css/menu.css">
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    <script>
        function discval(){
            var selector = document.getElementById('day');
            var disc = selector[selector.selectedIndex].value;
            $.post("discday.php", {searchQuery: disc}, function(output) {
                document.getElementById('discountBox').value = output;
            });

            //document.getElementById('discountBox').value = disc;
        }
    </script>
</head>
<body>
    <?php include "template.php"; ?>
	<img src="img/logo.png" width="100" style="margin:20px;visibility: hidden;" draggable="false">
    <div class="container myDiv">
        <div class="row">
            <div class="col-lg-6 col-md-6 details mx-auto">
                <h1 style="text-align: center;color: white;">DAY DISCOUNT</h1>
                <form method="POST">
                    <select id="day" name="day" class="form-control" style="margin-bottom:20px;text-align-last: center;" onchange="discval()">
                        
                        <?php foreach($discount as $d): ?>
                            <option value="<?php echo $d->day; ?>"><?php echo $d->day; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="form-control" style="text-align: center;margin-bottom: 20px;" name="disc" min="0" max="100" id="discountBox" value="<?php echo $dd; ?>" placeholder="Discount %">
                    <button type="submit" name="add" class="btn btn-warning">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
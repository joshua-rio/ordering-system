<?php

	$today = date('l');

	$isbirthmonth = false;
	$discountbmonth = 0;
	$discbmonth = 0;
	$cardnum = 0;
	$discount = 0;

	$sql = "SELECT * FROM discountbmonth";
	$pds = $pdo->prepare($sql);
	$pds->execute(array());
	if($pds->rowcount() == 0){
	}else{
		while($rows = $pds->fetch()){
			$discountbmonth = $rows['discount'];
			$discountbmonth*=0.01;
			$_SESSION["bdaydiscount"] = $discountbmonth * 100 ;
		}
	}

    //Add Button Pressed
	if(isset($_POST['add']) || isset($_POST['purchase'])){
		$cardnum = $_POST['cardnum'];

		$birthday = "";
		$sql = "SELECT * FROM profile WHERE CardNumber = ?";
		$pds = $pdo->prepare($sql);
		$pds->execute(array($cardnum));
		if($pds->rowcount() == 0){
			
		}else{
			while($rows = $pds->fetch()){
				$birthday = $rows['Birthday'];
			}
		}

		$month = date('F',strtotime($birthday));
		$monthtoday = date('F');
		$day = date('j',strtotime($birthday));
		$daybefore = date('n-j',strtotime('-3 days',strtotime($birthday)));
		$dayafter = date('n-j',strtotime('+3 days',strtotime($birthday)));
		$daytoday = date('n-j');
		if($month == $monthtoday){
			if($daytoday >= $daybefore && $daytoday <= $dayafter){
				$isbirthmonth = true;
				$discbmonth = $discountbmonth * 100;
			}
		}

	}


		
	

	//get number of orders
	$orderno = date("Ym");
	$sql = "SELECT DISTINCT Order_ID FROM logs";
	$pds = $pdo->prepare($sql);
	$pds->execute(array());
	
	$count = $pds->rowcount() + 1;
	$length = strlen($count);
	for($a=$length;$a<5;$a++){
		$orderno.="0";
	}
	$orderno.=$count;

	use Mike42\Escpos\Printer;
	use Mike42\Escpos\EscposImage;
	use Mike42\Escpos\PrintConnectors\NetworkPrintConnector; 
    //purchase button pressed
	if(isset($_POST['purchase'])){
		
	

		$price = $_POST['fprice'];
		$disc = $_POST['fdiscount'];
		$total = $_POST['ftotal'];
		$card = $_POST['cardnum'];
		$staff = $_POST['staff'];
		$copy = array("CUSTOMER COPY","ORIGINAL COPY");
		date_default_timezone_set("Asia/Manila");
		$datetime = date('Y-m-d h:i:s A');
		$name = "";
		try{
			$sql = 'INSERT INTO logs (CardNumber,Price,Discount,Total,Staff,Date, Order_ID)
					VALUES (?,?,?,?,?,NOW(),?)';
			$pds = $pdo->prepare($sql);
			$pds->execute(array($card,$price,$disc,$total,$staff,$orderno));
			
			$sql = 'SELECT Name from profile where CardNumber = ?';
			$pds = $pdo->prepare($sql);
			$pds->execute(array($card));
			
			foreach ($pds as $value) {
				$name = $value[0];
			}
			echo "<script>alert('Purchase Successful');</script>";


			header("Refresh:0");
		}catch(PDOException $e){
			echo "<script>alert('Purchase Not Successful!');</script>";
			header("Refresh:0");
		}

		try{
			$discount = 0;
			if($isbirthmonth == false){
				$discount = 10;
			}else{
				$discount = $discbmonth;
			}
			$sql = 'INSERT INTO orderiddisc (Order_ID,Discount)
					VALUES (?,?)';
			$pds = $pdo->prepare($sql);
			$pds->execute(array($orderno,$discount));
	


			header("Refresh:0");
		}catch(PDOException $e){
			echo "<script>alert('OrderIdDisc Error!');</script>";
			header("Refresh:0");
		}
		
			if($isbirthmonth == true)
				$discountbmonth *= 100;
		for ($i=0; $i <count($copy);$i++) { 
		
		try {
		
		 	$connector = new NetworkPrintConnector("192.168.1.179", 9100);
	 		$printer = new Printer($connector);	
		
		    $printer -> setJustification(Printer::JUSTIFY_CENTER);
		
		   
		    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
		    $printer -> text("Guilly's Night \nClub\n");
		    $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
		    $printer -> text("#27 Tomas Morato Avenue corner \nScout Albano St., South Triangle Quezon City Metro Manila \nPhilippines 1103\n\n");
		    
		    	
		   
            $printer -> setJustification(Printer::JUSTIFY_LEFT);    
            $printer -> text("Card Number:".trim($card)."\n");
            $printer -> text("Card Holder's Name:\n".trim($name)."\n\n");

			if($isbirthmonth == true){
				$printer -> text("VIP CARD DISCOUNT 10%\n\n");
			}else{
				$printer -> text("VIP CARD BIRTHDAY DISCOUNT $discount%\n\n");
			}
		   
		    $printer -> text("SUB-TOTAL              ".number_format($price,2)."\n");
		    $printer -> text("DISCOUNT               -".number_format($disc,2)."\n\n");
		 
		    $printer -> text("TOTAL                  "); 
   			$printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
		    $printer -> text(number_format($total,2)."\n\n");


		 $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
		    $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> text("DATE PURCHASED:\n".$datetime."\n\n");
           
            $printer -> text("DATE PRINTED:\n".$datetime."\n");
            $printer -> text("Cashier's Name:\n".$staff."\n\n\n");

		    
		     
		    $printer -> text($copy[$i]."\n");
		    $printer->cut();
		   


		    $printer -> close();
		} catch (Exception $e) {
		    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}
		}

	}
?>
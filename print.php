<?php
session_start();
    include "connection.php";
    include "classes.php";
require __DIR__ . '/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
    date_default_timezone_set("Asia/Manila");


    $oderid = $_POST["orderid"];
    $datetime = date('Y-m-d h:i:s A');
    $sql = "SELECT logs.CardNumber,logs.Price,logs.Discount,logs.Total,logs.Staff,logs.Date,profile.Name,profile.Birthday from logs INNER JOIN profile on logs.CardNumber = profile.CardNumber WHERE Order_ID = ? ";
    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array($oderid));

    if($pds->rowcount() != 0){
     
        while($row = $pds->fetch()){
            $cardnumber = $row['CardNumber'];;
            $name = $row['Name'];
            $subtotal = $row['Price'];
            $discount = $row['Discount'];
            $total =$row['Total'];
            $cashier = $row['Staff'];
            $day = $row['Date'];
            $birthdate = $row['Birthday'];
            }
    }

     $sql = "SELECT * from  orderiddisc WHERE Order_ID = ? ";
    $pdo->prepare($sql);
    $pds = $pdo->prepare($sql);
    $pds->execute(array($oderid));
    if($pds->rowcount() != 0){
     
        while($row = $pds->fetch()){
            $bdaydiscount = $row['Birthmonth'];;
           
            }
    }
try {
    $connector = new NetworkPrintConnector("192.168.1.179", 9100);
            $printer = new Printer($connector); 
        
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
        
           
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text("Guilly's Night \nClub\n");
            $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer -> text("#27 Tomas Morato Avenue corner \nScout Albano St., South Triangle Quezon City Metro Manila \nPhilippines 1103\n\n");
            
            
            $printer -> setJustification(Printer::JUSTIFY_LEFT);    
            $printer -> text("Card Number:".$cardnumber."\n");
            $printer -> text("Card Holder's Name:\n".$name."\n\n");

            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            
            $printer -> text("VIP CARD DISCOUNT 10%\n\n");
           
            if(date('F',strtotime($day)) == date('F',strtotime($birthdate)))
            $printer -> text("VIP CARD BIRTHDAY DISCOUNT $bdaydiscount%\n\n");
           
             $printer -> text("SUB-TOTAL              ".number_format($subtotal,2)."\n");
            $printer -> text("DISCOUNT               -".number_format($discount,2)."\n\n");
            $printer -> text("TOTAL                  "); 
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer -> text(number_format($total,2)."\n\n");

            $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> text("DATE PURCHASED:\n".date('Y-m-d h:i:s A',strtotime($day))."\n\n");
           
            $printer -> text("DATE PRINTED:\n".$datetime."\n");
            $printer -> text("Cashier's Name:\n".$cashier."\n\n\n");

            
             
    $printer -> text("2nd Copy\n");
    $printer->cut();
   


    $printer -> close();
} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}

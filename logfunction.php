<?php
    //get all logs from database
    $logs = array();
	$sql = "SELECT * FROM logs ORDER BY Date DESC LIMIT 20";
	$pds = $pdo->prepare($sql);
	$pds->execute(array());
	if($pds->rowcount() == 0){
			$thislog = new Logs(0,0,0,"",0,0,0,"","");
			array_push($logs,$thislog);
	}else{
			while($rows = $pds->fetch()){
                    $thislog = new Logs($rows['Id'],$rows['CardNumber'],$rows['ItemId'],
										$rows['Name'],$rows['Total'],$rows['Quantity'],$rows['Discount'],
										$rows['Staff'],$rows['Date']);
					array_push($logs,$thislog);
			}
	}
?>
<?php

    require 'db_connection.php';
	date_default_timezone_set('Asia/Manila');
    $amount = $_POST['amount'];
    $modeOfPayment = $_POST['cashin'];
	$timeNow = date("H:i:s");
    $stmt = $conn->prepare("INSERT INTO cashin (amount, means, time_updated) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $amount, $modeOfPayment, $timeNow);
    if($stmt->execute()){
		$stmt1 = $conn->prepare("UPDATE info SET current_load = current_load + $amount WHERE info_id = 1");
    
		if($stmt1->execute()){
			header('Location: ../main.php');
			exit();
		} else {
			echo $stmt->error;
		}
            
	} else {
		echo $stmt->error;
	}

    

?>
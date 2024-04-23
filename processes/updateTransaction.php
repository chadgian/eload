<?php
    session_start();
    $transactionID = $_GET['id'];
	date_default_timezone_set('Asia/Manila');
    include 'db_connection.php';
	$timeNow = date("H:i:s");
    $update = 'loaded';
    $loadedBy = $_SESSION['username'];
    $stmt = $conn->prepare("UPDATE transactions SET transaction_status = ?, loaded_by = ?, time_updated = ? WHERE transaction_id = ?");
    $stmt->bind_param("ssss", $update, $loadedBy, $timeNow, $transactionID);
    
    if($stmt->execute()){

        $stmt1 = $conn->prepare("UPDATE info SET current_load = current_load - 9.12 WHERE info_id = 1");
        
        if($stmt1->execute()){

            $stmt2 = $conn->prepare("SELECT * FROM transactions WHERE transaction_id = ?");
            $stmt2->bind_param("s", $transactionID);
            if($stmt2->execute()){
                $result = $stmt2->get_result();
                $data = $result->fetch_assoc();

                $availID = $data['avail_id'];

                $stmt3 = $conn->prepare("UPDATE avails set time_availed = ? WHERE avail_id = ?");
                $stmt3->bind_param("ss", $timeNow, $availID);

                if($stmt3->execute()){
                    header('Location: ../main.php');
                    exit();
                } else {
                    echo $stmt3->error;
                }
            } else {
                echo $stmt2->error;
            }

        } else {
            echo $stmt1->error;
        }

        
    } else {
        echo $stmt->error;
    }

?>
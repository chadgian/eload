<?php
    session_start();
    include "db_connection.php";
    date_default_timezone_set('Asia/Manila');

    $name = $_POST['name'];
    $phoneNumber = $_POST['phoneNumber'];
    $amountPaid = $_POST['amountPaid'];
    $time = $_POST['time'];
    $daysNumber = $_POST['daysNumber'];
    $dateStarted = $_POST['dateStarted'];
    $dateEnds = $_POST['dateEnds'];
    
    $status = "ongoing";
    $addedBy = $_SESSION['username'];
    $stmt = $conn->prepare("INSERT INTO avails (availer_name, phone_number, status, time_availed, paid_amount, number_of_days, date_started, date_ends, added_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssssss', $name, $phoneNumber, $status, $time, $amountPaid, $daysNumber, $dateStarted, $dateEnds, $addedBy);

    if($stmt->execute()){
        $stmt->close();

        $stmt1 = $conn->prepare("SELECT * FROM avails ORDER BY avail_id DESC LIMIT 1 ");
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        
        if($result1->num_rows > 0){
            
            $data1 = $result1->fetch_assoc();
            $status1 = "not_loaded";

            $stmt2 = $conn->prepare("INSERT INTO transactions (avail_id, part, transaction_status, deadline) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("ssss", $data1['avail_id'], $part, $status1, $date );
            $part = "";
            $date = "";

            for ($i = 0; $i < $daysNumber; $i++){
                $part = $i+1;
                $date = date("Y-m-d", strtotime($dateEnds . " -$i day"));
                $stmt2->execute();
                if($stmt2->error){
                    echo "Error: " . $stmt2->error . "<br>";
                }
                
            }

            $stmt2->close();
            $addProfit = $amountPaid-($daysNumber*9.12);
            $stmt3 = $conn->prepare("UPDATE info SET total_profit = total_profit + $addProfit WHERE info_id = 1");
            // $stmt3->bind_param('s', $addProfit);
            // echo $addProfit;

            if ($stmt3->execute()){
                $stmt3->close();
                header('Location: ../main.php');
                exit();
                
            } else {
                echo $stmt3->error;
            }

            
        }

        
    } else {
        echo error_log;
    }

?>
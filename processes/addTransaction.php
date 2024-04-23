<?php
    session_start();
    include "db_connection.php";
    date_default_timezone_set('Asia/Manila');

    $contact = $_POST['contactList'];

    // $amountPaid = $_POST['amountPaid'];
    $time = $_POST['time'];
    $daysNumber = $_POST['daysNumber'];
    $dateStarted = $_POST['dateStarted'];
    // $paid = $_POST['paid'];
    // $dateEnds = $_POST['dateEnds'];
    $forDateEnds = $daysNumber-1;
    $dateEnds = date("Y-m-d", strtotime($dateStarted . " +$forDateEnds day"));

    $prices = [0, 12, 24, 36, 48, 60, 70, 80];
    $amountPaid = $prices[$daysNumber];

    if(isset($_POST['paid'])){
        $debt = 0;            
    } else {
        $debt = $amountPaid;
    }

    if ($contact == 0){
        $name = $_POST['name'];
        $phoneNumber = $_POST['phoneNumber'];

        $addedBy = $_SESSION['username'];
        $stmt1 = $conn->prepare("INSERT INTO avails (availer_name, phone_number, time_availed, paid_amount, number_of_days, date_started, date_ends, added_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt1->bind_param('ssssssss', $name, $phoneNumber, $time, $amountPaid, $daysNumber, $dateStarted, $dateEnds, $addedBy);

        if($stmt1->execute()){
            $stmt1->close();

            $stmt2 = $conn->prepare("SELECT * FROM avails ORDER BY avail_id DESC LIMIT 1 ");
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            
            if($result2->num_rows > 0){
                
                $data2 = $result2->fetch_assoc();
                $status2 = "not_loaded";

                $stmt3 = $conn->prepare("INSERT INTO transactions (avail_id, part, transaction_status, deadline) VALUES (?, ?, ?, ?)");
                $stmt3->bind_param("ssss", $data2['avail_id'], $part, $status2, $date );
                $part = "";
                $date = "";

                for ($i = 0; $i < $daysNumber; $i++){
                    $part = $i+1;
                    $date = date("Y-m-d", strtotime($dateEnds . " -$i day"));
                    $stmt3->execute();
                    if($stmt3->error){
                        echo "Error: " . $stmt3->error . "<br>";
                    }
                }

                $stmt3->close();
                $addProfit = $amountPaid-($daysNumber*9.12);
                $stmt4 = $conn->prepare("UPDATE info SET total_profit = total_profit + $addProfit WHERE info_id = 1");

                if ($stmt4->execute()){
                    $stmt4->close();

                    $stmt5 = $conn->prepare("SELECT * FROM contacts WHERE contact_number = ?");
                    $stmt5->bind_param('s', $phoneNumber);
                    if($stmt5->execute()){
                        $result5 = $stmt5->get_result();

                        if ($result5->num_rows > 0){
                            return false;
                        } else {
                            $stmt6 = $conn->prepare("INSERT INTO contacts (contact_name, contact_number, last_used, debt) VALUES (?, ?, ?, ?)");
                            $timestamp = date("Y-m-d H:i:s");
                            $stmt6->bind_param('ssss', $name, $phoneNumber, $timestamp, $debt);

                            if($stmt6->execute()){
                                $stmt6->close();

                                header('Location: ../main.php');
                                exit();
                            } else {
                                $stmt6->error;
                            }
                        }
                    } else {
                        $stmt5->error;
                    }

                } else {
                    echo $stmt4->error;
                }
                
            }
        } else {
            echo error_log;
        }
    } else {
        $stmt = $conn->prepare("SELECT * FROM contacts WHERE contact_id = ?");
        $stmt->bind_param('s', $contact);
        if ($stmt->execute()){
            $result = $stmt->get_result();

            if ($result->num_rows > 0){
                $data = $result->fetch_assoc();

                $name = $data['contact_name'];
                $phoneNumber = $data['contact_number'];

                $stmt->close();

                $addedBy = $_SESSION['username'];
                $stmt1 = $conn->prepare("INSERT INTO avails (availer_name, phone_number, time_availed, paid_amount, number_of_days, date_started, date_ends, added_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt1->bind_param('ssssssss', $name, $phoneNumber, $time, $amountPaid, $daysNumber, $dateStarted, $dateEnds, $addedBy);

                if($stmt1->execute()){
                    $stmt1->close();

                    $stmt2 = $conn->prepare("SELECT * FROM avails ORDER BY avail_id DESC LIMIT 1 ");
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    
                    if($result2->num_rows > 0){
                        
                        $data2 = $result2->fetch_assoc();
                        $status2 = "not_loaded";

                        $stmt3 = $conn->prepare("INSERT INTO transactions (avail_id, part, transaction_status, deadline) VALUES (?, ?, ?, ?)");
                        $stmt3->bind_param("ssss", $data2['avail_id'], $part, $status2, $date );
                        $part = "";
                        $date = "";

                        for ($i = 0; $i < $daysNumber; $i++){
                            $part = $i+1;
                            $date = date("Y-m-d", strtotime($dateEnds . " -$i day"));
                            $stmt3->execute();
                            if($stmt3->error){
                                echo "Error: " . $stmt3->error . "<br>";
                            }
                        }

                        $stmt3->close();
                        $addProfit = $amountPaid-($daysNumber*9.12);
                        $stmt4 = $conn->prepare("UPDATE info SET total_profit = total_profit + $addProfit WHERE info_id = 1");

                        if ($stmt4->execute()){
                            $stmt4->close();

                            $stmt5 = $conn->prepare("UPDATE contacts SET last_used = ?, debt = debt+$debt WHERE contact_id = ?");
                            $timestamp = date("Y-m-d H:i:s");
                            $stmt5->bind_param("ss", $timestamp, $contact);

                            if ($stmt5->execute()){
                                header('Location: ../main.php');
                                exit();
                            } else {
                                echo $stmt5->error;
                            }

                        } else {
                            echo $stmt4->error;
                        }
                        
                    }
                } else {
                    echo $stmt1->error;
                }

            }
        } else {
            $stmt->error;
        }
    }

    

?>
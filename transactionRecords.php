<?php
    session_start();
    require_once 'processes/db_connection.php';
    date_default_timezone_set('Asia/Manila');

    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    } else {
        $pageName = "Transaction Records";
        $timestamp = date("Y-m-d H:i:s");
        $stmt0 = $conn->prepare("UPDATE users SET last_visited = ?, last_login = ? WHERE username = ?");
        $stmt0->bind_param('sss', $pageName, $timestamp, $_SESSION['username']);
        $stmt0->execute();
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eload Business</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <header><div>
        <h1>eLoad Application</h1> 
        <h4 id="time">
        </h4>
        <script src="css/time.js"></script>
    </div></header>
    <span class="loadProfit">
        <h3>
            <?php 
                include 'processes/db_connection.php';
                $stmt = $conn->prepare("SELECT * FROM info WHERE info_id = 1");
                $stmt->execute();
                $result = $stmt->get_result();
                echo $result->fetch_assoc()['current_load'];
            ?> 
            Load Left
        </h3>
        <p>Total Value: Php
            <?php
                include 'processes/db_connection.php';
                $stmt = $conn->prepare("SELECT * FROM info WHERE info_id = 1");
                $stmt->execute();
                $result = $stmt->get_result();
                echo $result->fetch_assoc()['total_profit'];
            ?>
        </p>
    </span>

    <div class="upcomingTransactions">
        <h2>Upcoming Transactions</h2>
        <div>
            <h3>Tomorrow</h3>
                <?php
                    include 'processes/db_connection.php';
                    date_default_timezone_set('Asia/Manila');
                    $today = date('Y-m-d');

                    $stmt = $conn->prepare("SELECT * FROM transactions WHERE deadline = ?");
                    $tomorrow = date("Y-m-d", strtotime($today . " +1 day"));
                    $stmt->bind_param("s", $tomorrow);

                    if($stmt->execute()){
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0){
                            while ($data = $result->fetch_assoc()){
                                $availID =  $data["avail_id"];

                                $stmt1 = $conn->prepare("SELECT * FROM avails WHERE avail_id = ?");
                                $stmt1->bind_param("s", $availID);
                                if($stmt1->execute()){
                                    $result1 = $stmt1->get_result();
                                    $data1 = $result1->fetch_assoc();

                                    $name = $data1['availer_name'];
                                    $phoneNumber = $data1['phone_number'];
                                    $time = $data1['time_availed'];
                                    $dateEnds = $data1['date_ends'];

                                    echo "<span class='upcomingTransactionsList'>";
                                    echo "<div>$name <small>($phoneNumber)</small></div> $time <div>Until ".date('l (F d)', strtotime($dateEnds))."</div></span>";
                                } else {
                                    echo $stmt1->error;
                                }

                            }
                        }
                    } else {
                        echo $stmt->error;
                    }
                ?>
        </div>
    </div>

    <div class="previousTransactions">
        <h2>Previous Transactions</h2>
        <select name="TransactionDates" id="TransactionDates" onChange="fetchTransactions()" required>
            <?php 
                include 'processes/db_connection.php';
                date_default_timezone_set('Asia/Manila');
                $today = new DateTime();
                $beginning = new DateTime('2024-03-03');

                $daysSince = $today->diff($beginning);

                for ($i= -$daysSince->format("%a")+1;$i<0;$i++){
                    $transactionDate = $today->modify( "-1 day" )->format('Y-m-d');
                    echo '<option value="'.$transactionDate.'">'.date('M d, Y', strtotime($transactionDate)).'</option>';
                }
            ?>
        </select>
        <div class="previousTransactionsGroup">
            <div>
                <?php
                    include 'processes/db_connection.php';
                    date_default_timezone_set('Asia/Manila');
                    $today = date('Y-m-d');
                    $loaded = 'loaded';
                    $stmt = $conn->prepare("SELECT * FROM transactions WHERE DATE(deadline) < ? AND transaction_status = ? ORDER BY deadline DESC");
                    $stmt->bind_param("ss", $today, $loaded);

                    if($stmt->execute()){
                        $result = $stmt->get_result(); 
                        if ($result->num_rows > 0){
                            while($data = $result->fetch_assoc()) {
                                $availID = $data['avail_id'];
                                $date = $data['deadline'];
                                $timeLoaded = $data['time_updated'];
                                $loadedBy = $data['loaded_by'];

                                $stmt1 = $conn->prepare("SELECT * FROM avails WHERE avail_id = ?");
                                $stmt1->bind_param('s', $availID);

                                if ($stmt1->execute()){
                                    $result1 = $stmt1->get_result();
                                    if($result1->num_rows > 0){
                                        $data1 = $result1->fetch_assoc();
                                        $name = $data1['availer_name'];
                                        $phoneNumber = $data1['phone_number'];

                                        echo "<span class='previousTransactionList' id='_$date' style='display: none;'>
                                             $name <small>($phoneNumber)</small><div>$date $timeLoaded</div><div>loaded by: $loadedBy</div>
                                        </span>";
                                    } else {
                                        echo "No data found.";
                                    }
                                } else {
                                    echo $stmt1->error;
                                }
                            }
                        } else {
                            echo "No previous transactions.";
                        }
                    } else {
                        echo $stmt->error;
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="navigation">
        <div>
            <a href="main.php">Home</a>
        </div>
        
    </div>

    <script>
        window.onload = function (){
            var initialSelectedDate = document.getElementById('TransactionDates').value;
            var selectedTransactions = document.querySelectorAll('#_'+initialSelectedDate);
            selectedTransactions.forEach(function(element) {
                element.style.display = 'flex';
            });
        };
        
        function fetchTransactions(){
            var initialSelectedDate = document.getElementById('TransactionDates').value;

            var theRest = document.querySelectorAll(".previousTransactionList");
            theRest.forEach(function(element){
                element.style.display = 'none';
            })

            var selectedTransactions = document.querySelectorAll('#_'+initialSelectedDate);
            selectedTransactions.forEach(function(element) {
                element.style.display = 'flex';
            });
        }
    </script>
</body>
</html>

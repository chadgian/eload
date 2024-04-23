<?php
    session_start();
    require_once 'processes/db_connection.php';
    date_default_timezone_set('Asia/Manila');

    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    } else {
        $pageName = "Homepage";
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
    
    <div class="todayLoad">
        <h2>Load for today</h2>

        <?php include (__DIR__ . '/today.php'); ?>
    </div>
    
    <div class="navigation">
        <div>
            <span>
                <a href="addTransaction.php"> Add Transaction</a>
                <a href="transactionRecords.php">Transaction Records</a>
            </span>
            <span>
                <a href="users.php">User List</a>
                <a href="debts.php">Debts</a>
                <a href="processes/logout.php">Logout</a>  
            </span> 
        </div>  
    </div>
</body>
</html>

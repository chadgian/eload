<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eload Business</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Add your CSS styling here */
    </style>
</head>
<body>
    <h1>eLoad Application</h1> 
    <h2><?php 
            date_default_timezone_set('Asia/Manila');
            echo date('M d, Y | h:i A'); 
            ?></h2>
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
    <p>Profit: Php
        <?php
            include 'processes/db_connection.php';
            $stmt = $conn->prepare("SELECT * FROM info WHERE info_id = 1");
            $stmt->execute();
            $result = $stmt->get_result();
            echo $result->fetch_assoc()['total_profit'];
        ?>
    </p>
    <div class="todayLoad">
        <h2>Load for today</h2>

        <?php include (__DIR__ . '/today.php'); ?>
    </div>
    <div>
    </div>
    <div class="addTransaction">
        <h2>Add Transaction</h2>

        <form action="processes/addTransaction.php" method="post" class="addTransactionForm">
            <div>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name">
            </div>
            <div>
                <label for="phoneNumber">Phone Number:</label>
                <input type="number" name="phoneNumber" id="phoneNumber">
            </div>
            <div>
                <label for="amountPaid">Amound paid:</label>
                <input type="number" name="amountPaid" id="amountPaid">
            </div>
            <div>
                <label for="time">Time Loaded:</label>
                <input type="time" name="time" id="time">
            </div>
            <div>
                <label for="daysNumber">Number of days:</label>
                <input type="number" name="daysNumber" id="daysNumber">
            </div>
            <div>
                <label for="dateStarted">Date Started:</label>
                <input type="date" name="dateStarted" id="dateStarted">
            </div>
            <div>
                <label for="dateEnds">Date Ends:</label>
                <input type="date" name="dateEnds" id="dateEnds">
            </div>
            <input type="submit" value="Add">
        </form>
    </div>

    <div class="cashin">
        <h2>Cash In</h2>
        <form action="processes/addCashin.php" method="post" class="cashinForm">
            <div>
                <label for="amount">Amount:</label>
                <input type="number" name="amount" id="amount">
            </div>
            <div>
                <label for="cashin">Mode of payment:</label>
                <select name="cashin" id="cashin">
                    <option value="gcash_chad">GCash (Chad)</option>
                    <option value="gcash_rayvil">GCash (Rayvil)</option>
                </select>
            </div>
            <input type="submit" value="Cash In">
        </form>
        
    </div>

    <a href="processes/logout.php">Logout</a>
</body>
</html>

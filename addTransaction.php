<?php
    session_start();
    require_once 'processes/db_connection.php';
    date_default_timezone_set('Asia/Manila');

    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    } else {
        $pageName = "Add Transaction";
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

    <div class="addTransaction">
        <h2>Add Transaction</h2>
        <form action="processes/addTransaction.php" method="post" class="addTransactionForm">
            <div>
                <label for="contactList">Contacts:</label>
                <select name="contactList" id="contactList">
                    <option value="0">New Contact</option>
                    <?php
                        include 'processes/db_connection.php';

                        $stmt = $conn->prepare("SELECT * FROM contacts ORDER BY contact_name ASC");
                        if($stmt->execute()){
                            $results = $stmt->get_result();
                            if ($results->num_rows > 0){
                                while ($data = $results->fetch_assoc()){
                                    $contactNumber = $data['contact_number'];
                                    $contactName = $data['contact_name'];
                                    $contact_id = $data['contact_id'];
                                    echo "<option value='$contact_id'>$contactName - $contactNumber</option>";
                                }
                            } else {
                                echo  "<i>No Contacts Yet</i>";
                            }
                        } else {
                            echo $stmt->error;
                        }
                    ?>
                </select>
            </div>
            <div id="contact-name">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name">
            </div>
            <div id="contact-number">
                <label for="phoneNumber">Phone Number:</label>
                <input type="number" name="phoneNumber" id="phoneNumber">
            </div>
            <div>
                <label for="time">Time Loaded:</label>
                <input type="time" name="time" id="time" value="<?php echo date('H:i'); ?>">
            </div>
            <div>
                <label for="daysNumber">Number of days:</label>
                <select name="daysNumber" id="daysNumber">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                </select>
            </div>
            <div>
                <label for="dateStarted">Date Started:</label>
                <input type="date" name="dateStarted" id="dateStarted" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div>
                <label for="paid">Paid:</label>
                <input type="checkbox" id="paid" name="paid" checked>
            </div>
            <input type="submit" value="Add">
        </form>
    </div>

    <script>
        console.log("Script started");
        document.getElementById("contactList").addEventListener("change", function(){
            console.log("Change event triggered");
            var selectedContact = this.value;
            console.log("Selected contact:", selectedContact);

            var contactName = document.getElementById("contact-name");
            var contactNumber = document.getElementById("contact-number");

            if (selectedContact === "0"){
                console.log("Option 0 selected");
                contactName.style.display = "flex";
                contactNumber.style.display = "flex";
            } else {
                console.log("Other option selected");
                contactName.style.display = "none";
                contactNumber.style.display = "none"; 
            }
        });
    </script>

    <div class="cashin">
        <h2>Cash In</h2>
        <form action="processes/addCashin.php" method="post" class="cashinForm">
            <div>
                <label for="amount">Amount:</label>
                <input type="text" name="amount" id="amount">
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
    <div class="navigation">
        <div>
            <a href="main.php">Home</a>
        </div>
        
    </div>
</body>
</html>

<?php
    session_start();
    require_once 'processes/db_connection.php';
    date_default_timezone_set('Asia/Manila');

    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    } else {
        $pageName = "User List";
        $timestamp = date("Y-m-d H:i:s");
        $stmt0 = $conn->prepare("UPDATE users SET last_visited = ?, last_login = ? WHERE username = ?");
        $stmt0->bind_param('sss', $pageName, $timestamp, $_SESSION['username']);
        $stmt0->execute();
    }
?>

<html>
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
        <h2>Update Debt</h2>
            <form action="processes/updateDebt.php" method="post" class="addTransactionForm">
                <div>
                    <label for="contactList">Contacts:</label>
                    <select name="contactList" id="contactList">
                        <?php
                            include 'processes/db_connection.php';

                            $stmt = $conn->prepare("SELECT * FROM contacts ORDER BY contact_name ASC ");
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
                <div>
                    <label for="amount">Amound paid:</label>
                    <input type="number" name="amount" id="amount">
                </div>
                <input type="submit" value="Update">
            </form>
    </div>

    <div class="previousTransactions">
        <h2>Debts (Total: 
            <?php
                $stmt0 = $conn->prepare("SELECT * FROM contacts");
                $totalDebt = 0;
                if($stmt0->execute()){
                    $result0 = $stmt0->get_result();
                    if ($result0 ->num_rows > 0) {
                        while($data0 = $result0->fetch_assoc()){
                            $singleDebt = $data0['debt'];
                            $totalDebt = $totalDebt + $singleDebt;
                        }
                        echo $totalDebt;
                    } else {
                        echo "<i>No data found!</i>";
                    }
                }
            ?>
        )</h2>

        <?php
            require_once 'processes/db_connection.php';

            $stmt1 = $conn->prepare("SELECT * FROM contacts WHERE debt != 0 ORDER BY last_used DESC");
            if($stmt1->execute()){
                $result1 = $stmt1->get_result();
                if($result1->num_rows > 0) {
                    while ($data1 = $result1->fetch_assoc()){
                        $name = $data1['contact_name'];
                        $lastTransaction = new DateTime($data1['last_used']);
                        $lastTransaction = $lastTransaction->format("M d, Y");
                        $debt = $data1['debt'];
                        echo "
                        <span class='previousTransactionList'>
                        $name <div>Last transaction: $lastTransaction</div><div style='font-size: large; font-weight: bold;'>Total Debt: Php $debt</div>
                        </span>
                        ";
                    }

                } else {
                    echo "No recorded debts.";
                }
            }
            
        ?>

    </div>

    

    <div class="navigation">
        <div>
            <a href="main.php">Home</a>
        </div>
        
    </div>
        
    </body>
</html>
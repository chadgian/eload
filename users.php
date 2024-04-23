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
    <div class="previousTransactions">
        <h2>User List</h2>

        <?php
            require_once 'processes/db_connection.php';

            $stmt = $conn->prepare("SELECT * FROM users");
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                while($data = $result->fetch_assoc()){
                    $username = $data['username'];
                    $lastLogin = $data['last_login'];
                    $lastVisited = $data['last_visited'];

                    $datetime = new DateTime($lastLogin);
                    $formattedDatetime = $datetime->format("M d, Y | g:i A");

                    echo "
                    <span class='previousTransactionList'>
                    $username <div>Last login: $formattedDatetime</div><div>Last visited: $lastVisited</div>
                    </span>
                    ";
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
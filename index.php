<?php
    session_start();

    if (isset($_SESSION['username'])) {
        header('Location: main.php');
        exit();
    }
?>

<html>
    <head>
        <link rel="icon" href="img/logo.png" type="image/png">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ChaRV eLoad</title>
        <link rel="stylesheet" href="css/login.css">
    </head>
    <body>
        <form id="login-form" action="processes/login-process.php" method="post">
            <div class="logo">

            </div>
            <?php 
                if (isset($_GET['error'])) {
                    $loginError = urldecode($_GET['error']);
                    echo '<p class="login-error">' . $loginError . '</p>';
                }
            ?>
            <div class="input-container">
                <div class="img-container"><img src="img/username-logo.png" alt=""></div>
                <input type="text" id="username" name="username" placeholder="Enter Username" required>
            </div>
            <div class="input-container">
                <div class="img-container"><img src="img/password-logo.png" alt=""></div>
                <input type="password" id="password" name="password" placeholder="Enter Password" required>
            </div>
            <input type="submit" value="Login">
        </form>
    </body>
</html> 
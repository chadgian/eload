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
        <title>LMS | Sign Up</title>
        <link rel="stylesheet" href="css/login.css">
    </head>
    <body>

        <form id="signup-form" action="processes/signup-process.php" method="post">
            <div class="csc-logo">

            </div>
            <!-- <label for="fname">First Name</label> -->
            <div class="input-container">
                <div class="img-container"><img src="img/name-icon.png" alt=""></div>
                <input type="text" id="fname" name="fname" placeholder="First Name" autocomplete="given-name" required>
            </div>
            <!-- <label for="lname">Last Name</label> -->
            <div class="input-container">
                <div class="img-container"><img src="img/name-icon.png" alt=""></div>
                <input type="text" id="lname" name="lname" placeholder="Last Name" autocomplete="family-name" required>
            </div>
            <!-- <label for="username">Username</label> -->
            <div class="input-container">
                <div class="img-container"><img src="img/username-logo.png" alt=""></div>
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <!-- <label for="password">Password  </label> -->
            <div class="input-container">
                <div class="img-container"><img src="img/password-logo.png" alt=""></div>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div style="display: flex; gap: 0.5em; flex-direction: column;">
                <label for="signup_code">6-digit OTP</label>
                <div class="input-container">
                    <div class="img-container"><img src="img/key-icon.png" alt=""></div>
                    <input type="text" id="signup_code" name="signup_code" placeholder="Code">
                </div>
            </div>
            
            <input type="submit" value="Sign Up">
            <a href="index.php"><small>Login</small></a>
        </form>
    </body>
</html>
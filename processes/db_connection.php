<?php
    $servername = "sql6.freemysqlhosting.net";
    $username = "sql6701161";
    $password = "3pQCsnk3BA"; // This might be empty or your MySQL root password
    $database = "sql6701161";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
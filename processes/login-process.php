<?php
require_once 'db_connection.php';
date_default_timezone_set('Asia/Manila');
$username = $_POST['username'];
$password = $_POST['password']; // password hashing using MD5 encryption method

$stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
$stmt->bind_param("ss", $username, $password);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    session_start();
    $_SESSION['name'] = $user['Name'];
    $_SESSION['username'] = $user['username'];

    $stmt1 = $conn->prepare("UPDATE users SET last_login = ? WHERE  username = ?");
    $timestamp = date("Y-m-d H:i:s");
    $stmt1->bind_param("ss", $timestamp, $username);
    if (!$stmt1->execute()) {
        echo "Error updating record: " . $mysqli->error;
        exit();
    } else {
        header('Location: ../main.php');
        exit();
    }

    

} else {
    $loginError = "Wrong username or password!";
    header('Location: ../index.php?error=' . urlencode($loginError));
    exit();
}

$stmt->close();
$conn->close();
?>
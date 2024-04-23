<?php
require_once 'db_connection.php';

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

    header('Location: ../main.php');
    exit();

} else {
    $loginError = "Wrong username or password!";
    header('Location: ../index.php?error=' . urlencode($loginError));
    exit();
}

$stmt->close();
$conn->close();
?>
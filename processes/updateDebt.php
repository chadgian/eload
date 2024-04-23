<?php

    include_once 'db_connection.php';

    $contactID = $_POST['contactList'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("UPDATE contacts SET debt = debt-$amount WHERE contact_id=?");
    $stmt->bind_param("s", $contactID);

    if($stmt->execute()){
        Header('Location: ../debts.php');
        exit();
    } else {
        echo $stmt->error;
    }

?>
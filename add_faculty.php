<?php

require("server/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fullname']) && isset($_POST['email'])) {
    $fullname = $connection->real_escape_string($_POST['fullname']);
    $email = $connection->real_escape_string($_POST['email']);

    $query = "INSERT INTO faculty (full_name, email) VALUES ('$fullname', '$email')";

    if ($connection->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add faculty member: ' . $connection->error
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request. Missing data.'
    ]);
}
?>

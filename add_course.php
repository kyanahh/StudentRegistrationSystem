<?php

require("server/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coursetype']) && isset($_POST['collegetype'])) {
    $coursetype = $connection->real_escape_string($_POST['coursetype']);
    $collegetype = $connection->real_escape_string($_POST['collegetype']);

    $query = "INSERT INTO courses (course_name, college) VALUES ('$coursetype', '$collegetype')";

    if ($connection->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add course: ' . $connection->error
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request. Missing data.'
    ]);
}
?>

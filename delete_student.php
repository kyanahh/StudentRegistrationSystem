<?php

require("server/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    $deleteQuery = "DELETE FROM students WHERE student_id = '$student_id'";
    $deleteResult = $connection->query($deleteQuery);

    if ($deleteResult) {
        echo json_encode(array('success' => 'Student information deleted successfully'));
    } else {
        echo json_encode(array('error' => 'Error deleting Student information: ' . $connection->error));
    }

} else {
    echo json_encode(array('error' => 'Invalid request'));
}

$connection->close();

?>

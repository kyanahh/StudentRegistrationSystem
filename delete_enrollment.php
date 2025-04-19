<?php

require("server/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['enrollment_id'])) {
    $enrollment_id = $_POST['enrollment_id'];

    $deleteQuery = "DELETE FROM enrollments WHERE enrollment_id = '$enrollment_id'";
    $deleteResult = $connection->query($deleteQuery);

    if ($deleteResult) {
        echo json_encode(array('success' => 'Subject enrollment dropped successfully'));
    } else {
        echo json_encode(array('error' => 'Error deleting enrollment record: ' . $connection->error));
    }

} else {
    echo json_encode(array('error' => 'Invalid request'));
}

$connection->close();

?>

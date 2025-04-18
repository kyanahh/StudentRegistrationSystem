<?php

require("server/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['subject_id'])) {
    $subject_id = $_POST['subject_id'];

    $deleteQuery = "DELETE FROM subjects WHERE subject_id = '$subject_id'";
    $deleteResult = $connection->query($deleteQuery);

    if ($deleteResult) {
        echo json_encode(array('success' => 'Subject deleted successfully'));
    } else {
        echo json_encode(array('error' => 'Error deleting subject: ' . $connection->error));
    }

} else {
    echo json_encode(array('error' => 'Invalid request'));
}

$connection->close();

?>

<?php

require("server/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['faculty_id'])) {
    $faculty_id = $_POST['faculty_id'];

    $deleteQuery = "DELETE FROM faculty WHERE faculty_id = '$faculty_id'";
    $deleteResult = $connection->query($deleteQuery);

    if ($deleteResult) {
        echo json_encode(array('success' => 'Faculty member deleted successfully'));
    } else {
        echo json_encode(array('error' => 'Error deleting faculty member: ' . $connection->error));
    }

} else {
    echo json_encode(array('error' => 'Invalid request'));
}

$connection->close();

?>

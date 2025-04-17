<?php

require("server/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    $deleteQuery = "DELETE FROM courses WHERE course_id = '$course_id'";
    $deleteResult = $connection->query($deleteQuery);

    if ($deleteResult) {
        echo json_encode(array('success' => 'Course deleted successfully'));
    } else {
        echo json_encode(array('error' => 'Error deleting course: ' . $connection->error));
    }

} else {
    echo json_encode(array('error' => 'Invalid request'));
}

$connection->close();

?>

<?php
require("server/connection.php");

if (isset($_POST['course_id']) && isset($_POST['coursetype']) && isset($_POST['collegetype'])) {
    $course_id = $_POST['course_id'];
    $coursetype = $_POST['coursetype'];
    $collegetype = $_POST['collegetype'];

    $query = $connection->prepare("UPDATE courses SET course_name = ?, college = ?
                                     WHERE course_id = ?");
    $query->bind_param("ssi", $coursetype, $collegetype, $course_id);

    if ($query->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update course.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>

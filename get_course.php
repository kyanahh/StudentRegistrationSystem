<?php
require("server/connection.php");

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    $query = $connection->prepare("SELECT * FROM courses WHERE course_id = ?");
    $query->bind_param("i", $course_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => true, 'data' => $result->fetch_assoc()]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Course not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>

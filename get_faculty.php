<?php
require("server/connection.php");

if (isset($_POST['faculty_id'])) {
    $faculty_id = $_POST['faculty_id'];
    $query = $connection->prepare("SELECT * FROM faculty WHERE faculty_id = ?");
    $query->bind_param("i", $faculty_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => true, 'data' => $result->fetch_assoc()]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Faculty member not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>

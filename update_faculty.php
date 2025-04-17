<?php
require("server/connection.php");

if (isset($_POST['faculty_id']) && isset($_POST['fullname']) && isset($_POST['email'])) {
    $faculty_id = $_POST['faculty_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    $query = $connection->prepare("UPDATE faculty SET full_name = ?, email = ?
                                     WHERE faculty_id = ?");
    $query->bind_param("ssi", $fullname, $email, $faculty_id);

    if ($query->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update faculty member.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>

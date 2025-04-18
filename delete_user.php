<?php

require("server/connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $deleteQuery = "DELETE FROM users WHERE user_id = '$user_id'";
    $deleteResult = $connection->query($deleteQuery);

    if ($deleteResult) {
        echo json_encode(array('success' => 'User deleted successfully'));
    } else {
        echo json_encode(array('error' => 'Error deleting user: ' . $connection->error));
    }

} else {
    echo json_encode(array('error' => 'Invalid request'));
}

$connection->close();

?>

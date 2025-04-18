<?php

require("server/connection.php");

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($connection, $_POST['query']);
    if (!empty($query)) {
        $sql = "SELECT * FROM users 
                WHERE 
                user_id LIKE '%$query%' 
                OR full_name LIKE '%$query%' 
                OR email LIKE '%$query%' 
                OR role LIKE '%$query%' 
                ";
    } else {
        $sql = "SELECT * FROM users";
    }

    $result = mysqli_query($connection, $sql);

    if ($result->num_rows > 0) {
        $count = 1; 

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $count . '</td>';
            echo '<td>' . $row['user_id'] . '</td>';
            echo '<td>' . $row['full_name'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['role'] . '</td>';
            echo '<td>' . $row['created_at'] . '</td>';
            echo '<td>';
            echo '<div class="d-flex justify-content-center">';
            echo '<button class="btn btn-primary me-2" onclick="editUser(' . $row['user_id'] . ')">Edit</button>';
            echo '<button class="btn btn-danger" onclick="deleteUser(' . $row['user_id'] . ')">Delete</button>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
            $count++; 
        }
    } else {
        echo '<tr><td colspan="5">No admin/staff found.</td></tr>';
    }
}

?>

<?php

require("server/connection.php");

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($connection, $_POST['query']);
    if (!empty($query)) {
        $sql = "SELECT * FROM faculty WHERE 
        (full_name LIKE '%$query%' 
        OR email LIKE '%$query%'
        OR faculty_id LIKE '%$query%')";
    } else {
        $sql = "SELECT * FROM faculty";
    }

    $result = mysqli_query($connection, $sql);

    if ($result->num_rows > 0) {
        $count = 1; 

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $count . '</td>';
            echo '<td>' . $row['faculty_id'] . '</td>';
            echo '<td>' . $row['full_name'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>';
            echo '<div class="d-flex justify-content-center">';
            echo '<button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editFacultyModal" onclick="loadFacultyData(' . $row['faculty_id'] . ')">Edit</button>';
            echo '<button class="btn btn-danger" onclick="deleteFaculty(' . $row['faculty_id'] . ')">Delete</button>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
            $count++; 
        }
    } else {
        echo '<tr><td colspan="5">No faculty found.</td></tr>';
    }
}

?>

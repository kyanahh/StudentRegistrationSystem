<?php

require("server/connection.php");

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($connection, $_POST['query']);
    if (!empty($query)) {
        $sql = "SELECT * FROM courses WHERE 
        (course_name LIKE '%$query%' 
        OR college LIKE '%$query%')";
    } else {
        // If the query is empty, retrieve all records where usertypeid = 1
        $sql = "SELECT * FROM courses";
    }
    $result = mysqli_query($connection, $sql);

    if ($result->num_rows > 0) {
        $count = 1; 

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $count . '</td>';
            echo '<td>' . $row['course_id'] . '</td>';
            echo '<td>' . $row['college'] . '</td>';
            echo '<td>' . $row['course_name'] . '</td>';
            echo '<td>';
            echo '<div class="d-flex justify-content-center">';
            echo '<button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editCourseModal" onclick="loadCourseData(' . $row['course_id'] . ')">Edit</button>';
            echo '<button class="btn btn-danger" onclick="deleteCourse(' . $row['course_id'] . ')">Delete</button>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
            $count++; 
        }
    } else {
        echo '<tr><td colspan="5">No courses found.</td></tr>';
    }
}

?>

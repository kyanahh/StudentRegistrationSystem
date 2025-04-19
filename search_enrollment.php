<?php

require("server/connection.php");

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($connection, $_POST['query']);
    if (!empty($query)) {
        $sql = "SELECT enrollments.*, students.first_name,
                students.last_name, subjects.subject_name 
                FROM enrollments 
                INNER JOIN students 
                ON enrollments.student_id = students.student_id 
                INNER JOIN subjects 
                ON enrollments.subject_id = subjects.subject_id 
                WHERE 
                enrollments.student_id LIKE '%$query%' 
                OR students.first_name LIKE '%$query%' 
                OR students.last_name LIKE '%$query%' 
                OR subjects.subject_name LIKE '%$query%' 
                OR enrollments.semester LIKE '%$query%' 
                OR enrollments.academic_year LIKE '%$query%' 
                ORDER BY enrollment_id DESC
                ";
    } else {
        $sql = "SELECT enrollments.*, students.first_name,
                students.last_name, subjects.subject_name 
                FROM enrollments 
                INNER JOIN students 
                ON enrollments.student_id = students.student_id 
                INNER JOIN subjects 
                ON enrollments.subject_id = subjects.subject_id 
                ORDER BY enrollment_id DESC";
    }

    $result = mysqli_query($connection, $sql);

    if ($result->num_rows > 0) {
        $count = 1; 

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $count . '</td>';
            echo '<td>' . $row['enrollment_id'] . '</td>';
            echo '<td>' . $row['student_id'] . '</td>';
            echo '<td>' . $row['last_name'] . ', ' . $row['first_name'] . '</td>';
            echo '<td>' . $row['subject_name'] . '</td>';
            echo '<td>' . $row['semester'] . '</td>';
            echo '<td>' . $row['academic_year'] . '</td>';
            echo '<td>';
            echo '<div class="d-flex justify-content-center">';
            echo '<button class="btn btn-primary me-2" onclick="editEnroll(' . $row['enrollment_id'] . ')">Edit</button>';
            echo '<button class="btn btn-danger" onclick="deleteEnroll(' . $row['enrollment_id'] . ')">Delete</button>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
            $count++; 
        }
    } else {
        echo '<tr><td colspan="5">No enrollment record found.</td></tr>';
    }
}

?>

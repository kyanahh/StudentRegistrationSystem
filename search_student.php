<?php

require("server/connection.php");

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($connection, $_POST['query']);
    if (!empty($query)) {
        $sql = "SELECT students.*, courses.course_name, 
                courses.college FROM students INNER JOIN courses ON 
                students.course_id = courses.course_id WHERE 
                (student_id LIKE '%$query%' 
                OR students.first_name LIKE '%$query%' 
                OR students.last_name LIKE '%$query%' 
                OR students.gender LIKE '%$query%' 
                OR students.email LIKE '%$query%' 
                OR students.contact_number LIKE '%$query%' 
                OR students.address LIKE '%$query%' 
                OR students.admission_year LIKE '%$query%' 
                OR courses.course_name LIKE '%$query%' 
                OR courses.college LIKE '%$query%' 
                )";
    } else {
        $sql = "SELECT students.*, courses.course_name, 
                courses.college FROM students INNER JOIN courses ON 
                students.course_id = courses.course_id";
    }

    $result = mysqli_query($connection, $sql);

    if ($result->num_rows > 0) {
        $count = 1; 

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $count . '</td>';
            echo '<td>' . $row['student_id'] . '</td>';
            echo '<td>' . $row['first_name'] . '</td>';
            echo '<td>' . $row['last_name'] . '</td>';
            echo '<td>' . $row['gender'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['contact_number'] . '</td>';
            echo '<td>' . $row['address'] . '</td>';
            echo '<td>' . $row['admission_year'] . '</td>';
            echo '<td>' . $row['college'] . '</td>';
            echo '<td>' . $row['course_name'] . '</td>';
            echo '<td>';
            echo '<div class="d-flex justify-content-center">';
            echo '<button class="btn btn-info me-2" onclick="viewSubj(' . $row['student_id'] . ')">View Enrollment</button>';
            echo '<button class="btn btn-primary me-2" onclick="editStudent(' . $row['student_id'] . ')">Edit</button>';
            echo '<button class="btn btn-danger" onclick="deleteStudent(' . $row['student_id'] . ')">Delete</button>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
            $count++; 
        }
    } else {
        echo '<tr><td colspan="5">No students found.</td></tr>';
    }
}

?>

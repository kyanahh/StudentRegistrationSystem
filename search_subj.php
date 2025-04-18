<?php

require("server/connection.php");

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($connection, $_POST['query']);
    if (!empty($query)) {
        $sql = "SELECT subjects.*, classdays.day_name,
                schedule.timesched, faculty.full_name 
                FROM subjects INNER JOIN classdays 
                ON subjects.day_id = classdays.day_id 
                INNER JOIN schedule 
                ON subjects.sched_id = schedule.sched_id 
                INNER JOIN faculty 
                ON subjects.faculty_id = faculty.faculty_id 
                WHERE 
                subjects.subject_name LIKE '%$query%' 
                OR subjects.units LIKE '%$query%' 
                OR faculty.full_name LIKE '%$query%' 
                OR schedule.timesched LIKE '%$query%' 
                OR classdays.day_name LIKE '%$query%' 
                ORDER BY subject_id DESC 
                ";
    } else {
        $sql = "SELECT subjects.*, classdays.day_name,
                schedule.timesched, faculty.full_name 
                FROM subjects INNER JOIN classdays 
                ON subjects.day_id = classdays.day_id 
                INNER JOIN schedule 
                ON subjects.sched_id = schedule.sched_id 
                INNER JOIN faculty 
                ON subjects.faculty_id = faculty.faculty_id 
                ORDER BY subject_id DESC";
    }

    $result = mysqli_query($connection, $sql);

    if ($result->num_rows > 0) {
        $count = 1; 

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $count . '</td>';
            echo '<td>' . $row['subject_id'] . '</td>';
            echo '<td>' . $row['subject_name'] . '</td>';
            echo '<td>' . $row['units'] . '</td>';
            echo '<td>' . $row['full_name'] . '</td>';
            echo '<td>' . $row['timesched'] . '</td>';
            echo '<td>' . $row['day_name'] . '</td>';
            echo '<td>';
            echo '<div class="d-flex justify-content-center">';
            echo '<button class="btn btn-primary me-2" onclick="editSubj(' . $row['subject_id'] . ')">Edit</button>';
            echo '<button class="btn btn-danger" onclick="deleteSubj(' . $row['subject_id'] . ')">Delete</button>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
            $count++; 
        }
    } else {
        echo '<tr><td colspan="5">No subject found.</td></tr>';
    }
}

?>

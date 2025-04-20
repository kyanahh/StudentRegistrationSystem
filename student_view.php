<?php

session_start();

require("server/connection.php");   

$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

if(isset($_SESSION["logged_in"])){
    if(isset($_SESSION["full_name"]) || isset($_SESSION["email"])){
        $textaccount = $_SESSION["full_name"];
        $useremail = $_SESSION["email"];
        $role = $_SESSION["role"];
    }else{
        $textaccount = "Account";
    }
}else{
    $textaccount = "Account";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iEnroll Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
</head>
<body>

    <div class="main-container d-flex">
        <div class="sidebar" id="side_nav">
            <div class="header-box px-2 pt-3 pb-4 d-flex justify-content-between">
                <h1 class="fs-4 ps-3 pt-3">
                <span class="text-white fw-bold">iEnroll</span></h1>
                <button class="btn d-md-none d-block close-btn px-1 py-0 text-white"><i class="fal fa-stream"></i></button>
            </div>

            <ul class="list-unstyled px-2">

                <li>
                    <a href="dashboard.php" class="text-decoration-none px-3 py-2 d-block">
                        <i class="fal fa-home me-2"></i>Dashboard
                    </a>
                </li>

                <li>
                    <a href="users.php" class="text-decoration-none px-3 py-2 d-block">
                    <i class="bi bi-person-fill me-2"></i>Admin / Staff
                    </a>
                </li>

                <li>
                    <a href="students.php" class="text-decoration-none px-3 py-2 d-block">
                    <i class="bi bi-person-square me-2"></i>Students
                    </a>
                </li>

                <li>
                    <a href="faculty.php" class="text-decoration-none px-3 py-2 d-block">
                    <i class="bi bi-person-badge me-2"></i>Faculty
                    </a>
                </li>

                <li>
                    <a href="courses.php" class="text-decoration-none px-3 py-2 d-block">
                    <i class="bi bi-card-list me-2"></i>Courses
                    </a>
                </li>

                <li>
                    <a href="subjects.php" class="text-decoration-none px-3 py-2 d-block">
                    <i class="bi bi-book me-2"></i>Subjects
                    </a>
                </li>

                <li>
                    <a href="enrollments.php" class="text-decoration-none px-3 py-2 d-block">
                    <i class="bi bi-file-earmark-text me-2"></i>Enrollments
                    </a>
                </li>

                <li>
                    <a href="userlogs.php" class="text-decoration-none px-3 py-2 d-block">
                    <i class="bi bi-journal me-2"></i>User Logs
                    </a>
                </li>

            </ul>

            <hr class="h-color mx-2">

            <ul class="list-unstyled px-2">
                <li class=""><a href="settings.php" class="text-decoration-none px-3 py-2 d-block">
                    <i class="fal fa-bars me-2"></i>Settings</a></li>
                <li class=""><a href="logout.php" class="text-decoration-none px-3 py-2 d-block">
                    <i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
            </ul>

            <hr class="h-color mx-2">
            
            <div class="d-flex align-items-end">
                <p class="text-white ms-3 fs-6">Logged in as: <?php echo $textaccount ?><br>(<?php echo $role ?>)</p>
            </div>
        </div>

        <div class="content">
            <nav class="navbar navbar-expand-md navbar-dark">
                <div class="container-fluid">
                </div>
            </nav>

            <!-- List of Student Enrolled Subjects  -->
            <div class="px-3">
                <div class="d-flex align-items-center mb-2">
                        <a href="students.php"><i class="bi bi-arrow-left-circle text-black fs-3"></i></a>
                        <h2 class="fs-5 ps-3 pt-2">Enrolled Subjects</h2>
                </div>
                
                <div class="card" style="height: 520px;">
                    <div class="card-body">
                        <div class="table-responsive" style="height: 420px;">
                            <table id="view-table" class="table table-bordered table-hover">
                                <thead class="table-light" style="position: sticky; top: 0;">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Units</th>
                                        <th scope="col">Faculty</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Day</th>
                                        <th scope="col">Semester</th>
                                        <th scope="col">Academic Year</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                <?php
                                    // Query the database to fetch user data
                                    $result = $connection->query("SELECT enrollments.*, subjects.subject_name, subjects.units,
                                    faculty.full_name, schedule.timesched, classdays.day_name
                                    FROM enrollments
                                    INNER JOIN subjects ON enrollments.subject_id = subjects.subject_id
                                    INNER JOIN faculty ON subjects.faculty_id = faculty.faculty_id
                                    INNER JOIN schedule ON subjects.sched_id = schedule.sched_id
                                    INNER JOIN classdays ON subjects.day_id = classdays.day_id 
                                    WHERE enrollments.student_id = '$student_id' 
                                    ORDER BY enrollments.academic_year DESC, enrollments.semester DESC");

                                    if ($result->num_rows > 0) {
                                        $count = 1; 

                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>' . $count . '</td>';
                                            echo '<td>' . $row['subject_name'] . '</td>';
                                            echo '<td>' . $row['units'] . '</td>';
                                            echo '<td>' . $row['full_name'] . '</td>';
                                            echo '<td>' . $row['timesched'] . '</td>';
                                            echo '<td>' . $row['day_name'] . '</td>';
                                            echo '<td>' . $row['semester'] . '</td>';
                                            echo '<td>' . $row['academic_year'] . '</td>';
                                            echo '</tr>';
                                            $count++; 
                                        }
                                    } else {
                                        echo '<tr><td colspan="5">No enrolled subjects found.</td></tr>';
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    <!-- Search results will be displayed here -->
                <div id="search-results"></div>
            </div>
            <!-- End of List of Student Enrolled Subjects -->
        </div>
    
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
</body>
</html>
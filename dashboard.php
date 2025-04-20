<?php

session_start();

require("server/connection.php");   

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

// Query data
$maleCount = $connection->query("SELECT COUNT(*) AS total FROM students WHERE gender = 'M'")->fetch_assoc()['total'];
$femaleCount = $connection->query("SELECT COUNT(*) AS total FROM students WHERE gender = 'F'")->fetch_assoc()['total'];
$totalStudents = $connection->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$totalFaculty = $connection->query("SELECT COUNT(*) AS total FROM faculty")->fetch_assoc()['total'];
$totalCourses = $connection->query("SELECT COUNT(*) AS total FROM courses")->fetch_assoc()['total'];
$totalSubjects = $connection->query("SELECT COUNT(*) AS total FROM subjects")->fetch_assoc()['total'];

// Query to count students enrolled per subject
$subjectEnrollmentsQuery = "SELECT subjects.subject_name, COUNT(enrollments.student_id) as total 
    FROM enrollments 
    INNER JOIN subjects ON enrollments.subject_id = subjects.subject_id 
    GROUP BY subjects.subject_name 
    ORDER BY total DESC"; // Optional: Sort from most to least
$subjectEnrollmentsResult = $connection->query($subjectEnrollmentsQuery);

// Students per academic year
$studentsPerYear = [];
$result = $connection->query("SELECT academic_year, COUNT(DISTINCT student_id) as total FROM enrollments GROUP BY academic_year ORDER BY academic_year DESC");
while ($row = $result->fetch_assoc()) {
    $studentsPerYear[] = $row;
}

// Students per semester
$studentsPerSemester = [];
$result = $connection->query("SELECT semester, COUNT(DISTINCT student_id) as total FROM enrollments GROUP BY semester");
while ($row = $result->fetch_assoc()) {
    $studentsPerSemester[] = $row;
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

        <div class="content bg-light p-3">

            <div class="row g-4">
                <!-- Gender Cards -->
                <div class="col-md-4">
                    <div class="card text-center shadow">
                        <div class="card-body">
                        <h5 class="card-title">Male Students</h5>
                        <p class="fs-4 fw-bold text-primary"><?php echo $maleCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow">
                        <div class="card-body">
                        <h5 class="card-title">Female Students</h5>
                        <p class="fs-4 fw-bold text-pink"><?php echo $femaleCount; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Other Summary Cards -->
                <div class="col-md-4">
                    <div class="card text-center shadow">
                        <div class="card-body">
                        <h5 class="card-title">Total Students</h5>
                        <p class="fs-4 fw-bold text-success"><?php echo $totalStudents; ?></p>
                        </div>
                    </div>
                </div>
            
            </div>

            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="card text-center shadow">
                        <div class="card-body">
                        <h5 class="card-title">Faculty Members</h5>
                        <p class="fs-4 fw-bold text-warning"><?php echo $totalFaculty; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-center shadow">
                        <div class="card-body">
                        <h5 class="card-title">Courses</h5>
                        <p class="fs-4 fw-bold text-secondary"><?php echo $totalCourses; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow">
                        <div class="card-body">
                        <h5 class="card-title">Subjects</h5>
                        <p class="fs-4 fw-bold text-dark"><?php echo $totalSubjects; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row pt-5">
                <div class="col-md-5">
                    <div class="card shadow">
                        <div class="card-body">
                        <h5 class="card-title text-center">Students per Academic Year</h5>
                        <canvas id="yearChart"></canvas>
                        </div>
                    </div>
                </div>

                <?php
                    if ($subjectEnrollmentsResult->num_rows > 0):
                        while($row = $subjectEnrollmentsResult->fetch_assoc()):
                ?>
                    <div class="col-md-3">
                        <div class="card text-center shadow">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo $row['subject_name']; ?></h6>
                                <p class="fs-5 fw-bold text-info"><?php echo $row['total']; ?> students</p>
                            </div>
                        </div>
                    </div>
                <?php
                        endwhile;
                    else:
                ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">No enrollment data found.</div>
                    </div>
                <?php endif; ?>

            </div>


        </div>
    
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ChartJS Script -->
    <script>
    const yearData = <?php echo json_encode($studentsPerYear); ?>;
    const semesterData = <?php echo json_encode($studentsPerSemester); ?>;

    // Academic Year Chart
    const yearLabels = yearData.map(item => item.academic_year);
    const yearCounts = yearData.map(item => item.total);

    new Chart(document.getElementById('yearChart'), {
        type: 'bar',
        data: {
        labels: yearLabels,
        datasets: [{
            label: 'Number of Students',
            data: yearCounts,
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
        }
    });

    // Semester Chart
    const semesterLabels = semesterData.map(item => item.semester);
    const semesterCounts = semesterData.map(item => item.total);

    new Chart(document.getElementById('semesterChart'), {
        type: 'doughnut',
        data: {
        labels: semesterLabels,
        datasets: [{
            label: 'Number of Students',
            data: semesterCounts,
            backgroundColor: ['#4CAF50', '#FF9800', '#F44336']
        }]
        }
    });
    </script>
 
</body>
</html>
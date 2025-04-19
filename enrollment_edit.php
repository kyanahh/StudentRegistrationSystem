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

$student_id = $subject = $semester = $academic_year = $errorMessage = "";

if (isset($_GET["enrollment_id"])) {
    $enrollment_id = $_GET["enrollment_id"];

    $query = "SELECT enrollments.*, students.first_name,
                students.last_name, subjects.subject_name 
                FROM enrollments 
                INNER JOIN students 
                ON enrollments.student_id = students.student_id 
                INNER JOIN subjects 
                ON enrollments.subject_id = subjects.subject_id 
                WHERE enrollment_id = '$enrollment_id'";

    $res = $connection->query($query);

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();

        $enrollment_id = $row["enrollment_id"];
        $student_id = $row["student_id"];
        $subject = $row["subject_id"];
        $semester = $row["semester"];
        $academic_year = $row["academic_year"];

    } else {
        $errorMessage = "Subject enrolled not found.";
    }
} else {
    $errorMessage = "Enrollment ID is missing.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $subject = $_POST["subject"];
    $semester = $_POST["semester"];
    $academic_year = $_POST["academic_year"];

    // Check if the enrolled subject already exists in the database
    $checkQuery = "SELECT * FROM enrollments WHERE student_id = '$student_id' 
                    AND subject_id = '$subject' 
                    AND semester = '$semester' 
                    AND academic_year = '$academic_year'
                    ";
    $checkResult = $connection->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        $errorMessage = "Subject already enrolled to the student";
        $student_id = $subject = $semester = $academic_year = "";
    } else {
        // Update the user data into the database
        $insertQuery = "UPDATE enrollments
        SET 
            student_id = '$student_id',
            subject_id = '$subject',
            semester = '$semester',
            academic_year = '$academic_year'
        WHERE enrollment_id = '$enrollment_id'";
        $result = $connection->query($insertQuery);

        if (!$result) {
            $errorMessage = "Invalid query " . $connection->error;
        } else {
            $_SESSION['success'] = "Enrollment information updated successfully.";
            header("Location: enrollments.php");
            exit();
        }
    }
}

$subjOptions = "";
$subjQuery = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_name ASC";
$subjResult = $connection->query($subjQuery);

if ($subjResult && $subjResult->num_rows > 0) {
    while ($row = $subjResult->fetch_assoc()) {
        $selected = ($subject == $row['subject_id']) ? "selected" : "";
        $subjOptions .= "<option value='" . $row['subject_id'] . "' $selected>" . $row['subject_name'] . "</option>";
    }
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

        <div class="content bg-light px-3">
            <nav class="navbar navbar-expand-md navbar-dark">
                <div class="container-fluid">
                </div>
            </nav>

            <!-- Edit Enrollment -->
            <div class="px-3 pt-4">
                <form method="POST" action="<?php htmlspecialchars("SELF_PHP"); ?>">

                    <div class="row mt-1">
                        <h2 class="fs-5">Enroll Subject</h2>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                                if (!empty($errorMessage)) {
                                    echo "
                                    <div class='alert alert-warning alert-dismissible fade show mt-2 ms-3' role='alert'>
                                        <strong>$errorMessage</strong>
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>
                                    ";
                                }
                            ?>
                        </div>
                    </div>

                    <div class="row mb-3 mt-2">
                        <div class="col-sm-2">
                            <label class="form-label mt-2 ps-3">Enrollemt ID</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="enrollment_id" id="enrollment_id" value="<?php echo $enrollment_id; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-3 mt-2">
                        <div class="col-sm-2">
                            <label class="form-label mt-2 ps-3">Student Number<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="student_id" id="student_id" value="<?php echo $student_id; ?>" placeholder="Enter student number" required>
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Subject<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <select id="subject" name="subject" class="form-select" required>
                                <option value="" disabled selected>Select Subject</option>
                                <?php echo $subjOptions; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 mt-2">
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Semester<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <select id="semester" name="semester" class="form-select" required>
                                <option value="" disabled selected>Select Semester</option>
                                <option value="1st" <?php echo ($semester === "1st") ? "selected" : ""; ?>>1st</option>
                                <option value="2nd" <?php echo ($semester === "2nd") ? "selected" : ""; ?>>2nd</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Academic Year<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="academic_year" id="academic_year" value="<?php echo $academic_year; ?>" placeholder="e.g. 2024-2025" required>
                        </div>
                    </div>

                    <div class="row mb-3 mt-2 float-end">
                        <div class="col-sm-5">
                            <button type="submit" class="btn btn-success px-5">Save</button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- End of Edit Enrollments -->

        </div>
    
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div id="toast" class="toast align-items-center text-white bg-success border-0 position-fixed bottom-0 end-0 m-4" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999;">
            <div class="d-flex">
                <div class="toast-body">
                    <?= $_SESSION['success']; ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        <script>
            const toastEl = document.getElementById('toast');
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 }); // 3 seconds
            toast.show();
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
</body>
</html>
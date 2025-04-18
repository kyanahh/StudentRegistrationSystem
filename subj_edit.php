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

$subject_name = $units = $faculty = $schedtime = $classday = $errorMessage = "";

if (isset($_GET["subject_id"])) {
    $subject_id = $_GET["subject_id"];

    $query = "SELECT subjects.*, classdays.day_name,
                schedule.timesched, faculty.full_name 
                FROM subjects INNER JOIN classdays 
                ON subjects.day_id = classdays.day_id 
                INNER JOIN schedule 
                ON subjects.sched_id = schedule.sched_id 
                INNER JOIN faculty 
                ON subjects.faculty_id = faculty.faculty_id 
                WHERE subject_id = '$subject_id'";

    $res = $connection->query($query);

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();

        $subject_id = $row["subject_id"];
        $subject_name = $row["subject_name"];
        $units = $row["units"];
        $faculty = $row["faculty_id"];
        $schedtime = $row["sched_id"];
        $classday = $row["day_id"];

    } else {
        $errorMessage = "Subject not found.";
    }
} else {
    $errorMessage = "Subject ID is missing.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_name =  ucwords($_POST["subject_name"]);
    $units = $_POST["units"];
    $faculty = $_POST["faculty"];
    $schedtime = $_POST["schedtime"];
    $classday = $_POST["classday"];

    // Update the user data into the database
    $insertQuery = "UPDATE subjects
                    SET 
                        subject_name = '$subject_name',
                        units = '$units',
                        faculty_id = '$faculty',
                        sched_id = '$schedtime',
                        day_id = '$classday'
                    WHERE subject_id = '$subject_id'";
    $result = $connection->query($insertQuery);

    if (!$result) {
        $errorMessage = "Invalid query " . $connection->error;
    } else {
        $_SESSION['success'] = "Subject information updated successfully.";
        header("Location: subjects.php");
        exit();
    }
}

$facultyOptions = "";
$facultyQuery = "SELECT faculty_id, full_name FROM faculty ORDER BY full_name ASC";
$facultyResult = $connection->query($facultyQuery);

if ($facultyResult && $facultyResult->num_rows > 0) {
    while ($row = $facultyResult->fetch_assoc()) {
        $selected = ($faculty == $row['faculty_id']) ? "selected" : "";
        $facultyOptions .= "<option value='" . $row['faculty_id'] . "' $selected>" . $row['full_name'] . "</option>";
    }
}

$classdayOptions = "";
$classdayQuery = "SELECT day_id, day_name FROM classdays";
$classdayResult = $connection->query($classdayQuery);

if ($classdayResult && $classdayResult->num_rows > 0) {
    while ($row = $classdayResult->fetch_assoc()) {
        $selected = ($classday == $row['day_id']) ? "selected" : "";
        $classdayOptions .= "<option value='" . $row['day_id'] . "' $selected>" . $row['day_name'] . "</option>";
    }
}

$schedtimeOptions = "";
$schedtimeQuery = "SELECT sched_id, timesched FROM schedule";
$schedtimeResult = $connection->query($schedtimeQuery);

if ($schedtimeResult && $schedtimeResult->num_rows > 0) {
    while ($row = $schedtimeResult->fetch_assoc()) {
        $selected = ($schedtime == $row['sched_id']) ? "selected" : "";
        $schedtimeOptions .= "<option value='" . $row['sched_id'] . "' $selected>" . $row['timesched'] . "</option>";
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

            <!-- Add Subject -->
            <div class="px-3 pt-4">
                <form method="POST" action="<?php htmlspecialchars("SELF_PHP"); ?>">

                    <div class="row mt-1">
                        <h2 class="fs-5">Add New Subject</h2>
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
                            <label class="form-label mt-2 ps-3">Subject Name<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="subject_name" id="subject_name" value="<?php echo $subject_name; ?>" placeholder="Enter subject name" required>
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Units<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="units" id="units" value="<?php echo $units; ?>" placeholder="Enter units" required>
                        </div>
                    </div>

                    <div class="row mb-3 mt-2">
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Faculty<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <select id="faculty" name="faculty" class="form-select" required>
                                <option value="" disabled selected>Select Faculty</option>
                                <?php echo $facultyOptions; ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Time Schedule<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <select id="schedtime" name="schedtime" class="form-select" required>
                                <option value="" disabled selected>Select Time Schedule</option>
                                <?php echo $schedtimeOptions; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 mt-2">
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Class Day<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <select id="classday" name="classday" class="form-select" required>
                                <option value="" disabled selected>Select Class Day</option>
                                <?php echo $classdayOptions; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 mt-2 float-end">
                        <div class="col-sm-5">
                            <button type="submit" class="btn btn-success px-5">Save</button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- End of Add Users -->

        </div>
    
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
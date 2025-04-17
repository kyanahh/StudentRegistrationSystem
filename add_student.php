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

$firstname = $lastname = $gender = $email = $phone = $course = $address = $errorMessage = "";
$yearnow = date("Y");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname =  ucwords($_POST["firstname"]);
    $lastname =  ucwords($_POST["lastname"]);
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $address = ucwords($_POST["address"]);
    $course = $_POST["course"];

    // Insert the user data into the database
    $insertQuery = "INSERT INTO students (first_name, last_name, gender, email, 
    contact_number, address, admission_year, course_id) VALUES ('$firstname', '$lastname', '$gender', 
    '$email', '$phone', '$address', '$yearnow', '$course')";
    $result = $connection->query($insertQuery);

    if (!$result) {
        $errorMessage = "Invalid query " . $connection->error;
    } else {
        $_SESSION['success'] = "Student added successfully.";
        header("Location: students.php");
        exit();
    }
}

$courseOptions = "";
$courseQuery = "SELECT course_id, course_name FROM courses ORDER BY course_name ASC";
$courseResult = $connection->query($courseQuery);

if ($courseResult && $courseResult->num_rows > 0) {
    while ($row = $courseResult->fetch_assoc()) {
        $selected = ($course == $row['course_id']) ? "selected" : "";
        $courseOptions .= "<option value='" . $row['course_id'] . "' $selected>" . $row['course_name'] . "</option>";
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

            <!-- Add Students -->
            <div class="px-3 pt-4">
                <form method="POST" action="<?php htmlspecialchars("SELF_PHP"); ?>">

                    <div class="row mt-1">
                        <h2 class="fs-5">Add New Student</h2>
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
                            <label class="form-label mt-2 ps-3">First Name<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $firstname; ?>" placeholder="Enter first name" required>
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Last Name<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $lastname; ?>" placeholder="Enter last name" required>
                        </div>
                    </div>

                    <div class="row mb-3 mt-2">
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Gender<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <select id="gender" name="gender" class="form-select" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="M" <?php echo ($gender === "M") ? "selected" : ""; ?>>Male</option>
                                <option value="F" <?php echo ($gender === "F") ? "selected" : ""; ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Phone<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="phone" id="phone" value="<?php echo $phone; ?>" placeholder="Enter phone number" required>
                        </div>
                    </div>

                    <div class="row mb-3 mt-2">
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Email<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>" placeholder="Enter email address" required>
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Course<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-4">
                            <select id="course" name="course" class="form-select" required>
                                <option value="" disabled selected>Select Course</option>
                                <?php echo $courseOptions; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 mt-2">
                        <div class="col-sm-2">
                            <label class="form-label mt-2 px-3">Address<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter home address" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-3 mt-2 float-end">
                        <div class="col-sm-5">
                            <button type="submit" class="btn btn-success px-5">Save</button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- End of Add Students -->

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
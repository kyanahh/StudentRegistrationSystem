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

        <div class="content bg-light">
            <nav class="navbar navbar-expand-md navbar-dark">
                <div class="container-fluid">
                </div>
            </nav>

            <!-- List of Courses -->
            <div class="px-3">
                <div class="row">
                    <div class="col-sm-2">
                        <h2 class="fs-5 mt-1 ms-2">Courses</h2>
                    </div>
                    <div class="col input-group mb-3">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search" aria-describedby="button-addon2" oninput="search()">
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addCourseModal"><i class="bi bi-plus-lg text-white"></i></button>
                    </div>
                </div>
                
                <div class="card" style="height: 520px;">
                    <div class="card-body">
                        <div class="table-responsive" style="height: 420px;">
                            <table id="courses-table" class="table table-bordered table-hover">
                                <thead class="table-light" style="position: sticky; top: 0;">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Course ID</th>
                                        <th scope="col">College</th>
                                        <th scope="col">Course</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                <?php
                                    // Query the database to fetch user data
                                    $result = $connection->query("SELECT * FROM courses");

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
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                    <!-- Search results will be displayed here -->
                <div id="search-results"></div>
            </div>
            <!-- End of List of Courses -->
        </div>

    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container">
        <div id="deleteToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <small>Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Course deleted successfully.
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this course?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCourseForm">
                        <div class="mb-3">
                            <label for="collegeTypeInput" class="form-label">College</label>
                            <input type="text" class="form-control" id="collegeTypeInput" name="college" placeholder="Enter college name" required>
                        </div>
                        <div class="mb-3">
                            <label for="courseTypeInput" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="courseTypeInput" name="course" placeholder="Enter course name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveCourseButton">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="dynamicToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div id="toastBody" class="toast-body">
                    <!-- Message will be injected here -->
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCourseForm">
                        <input type="hidden" id="editCourseId" name="course_id">
                        <div class="mb-3">
                            <label for="editCollegeType" class="form-label">College Name</label>
                            <input type="text" class="form-control" id="editCollegeType" name="college" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCourseType" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="editCourseType" name="course" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        //--------------------------- Dynamic Toast Notification ---------------------------//
        function showDynamicToast(message, type) {
            const toastElement = document.getElementById('dynamicToast');
            const toastBody = document.getElementById('toastBody');

            // Set the message
            toastBody.textContent = message;

            // Set the type (e.g., success, error)
            toastElement.className = `toast align-items-center border-0 text-bg-${type}`;

            // Show the toast
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        //---------------------------Search Results---------------------------//
        function search() {
            const query = document.getElementById("searchInput").value;

            // Make an AJAX request to fetch search results
            $.ajax({
                url: 'search_courses.php', // Replace with the actual URL to your search script
                method: 'POST',
                data: { query: query },
                success: function(data) {
                    // Update the user-table with the search results
                    $('#courses-table tbody').html(data);
                },
                error: function(xhr, status, error) {
                    console.error("Error during search request:", error);
                }
            });
        }

        //--------------------------- Add Course ---------------------------//
        $(document).ready(function () {
            $('#saveCourseButton').on('click', function () {
                const courseType = $('#courseTypeInput').val().trim();
                const collegeType = $('#collegeTypeInput').val().trim();

                // Validation
                if (collegeType === '' || courseType === '') {
                    showDynamicToast('Please fill in all required fields.', 'warning');
                    return;
                }

                // Send data to the server
                $.ajax({
                    url: 'add_course.php',
                    type: 'POST',
                    data: {
                        coursetype: courseType,
                        collegetype: collegeType
                    },
                    success: function (response) {
                        const result = JSON.parse(response);

                        if (result.success) {
                            showDynamicToast('Course added successfully!', 'success');
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showDynamicToast('Error adding course: ' + result.message, 'danger');
                        }
                    },
                    error: function () {
                        showDynamicToast('An error occurred while adding the course.', 'danger');
                    },
                });
            });
        });

        //--------------------------- Delete ---------------------------//
        let courseIdToDelete = null;

        function deleteCourse(course_id) {
            courseIdToDelete = course_id; // Store the course ID to delete
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            if (courseIdToDelete) {
                $.ajax({
                    url: 'delete_course.php',
                    method: 'POST',
                    data: { course_id: courseIdToDelete },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            showDynamicToast('Course deleted successfully!', 'success');
                            setTimeout(() => location.reload(), 3000); // Wait 3 seconds before refreshing
                        } else {
                            showDynamicToast('Error deleting course: ' + response.error, 'danger');
                        }
                    },
                    error: function () {
                        showDynamicToast('An error occurred while deleting the course.', 'danger');
                    },
                });
            }
        });

        //--------------------------- Edit  ---------------------------//
            // Load service data into the modal
            function loadCourseData(course_id) {
                $.ajax({
                    url: 'get_course.php',
                    type: 'POST',
                    data: { course_id: course_id },
                    success: function (response) {
                        const result = JSON.parse(response);

                        if (result.success) {
                            $('#editCourseId').val(result.data.course_id);
                            $('#editCollegeType').val(result.data.college);
                            $('#editCourseType').val(result.data.course_name);
                        } else {
                            showDynamicToast('Error fetching course data: ' + result.message, 'danger');
                        }
                    },
                    error: function () {
                        showDynamicToast('An error occurred while fetching the course data.', 'danger');
                    },
                });
            }

            $('#editCourseForm').on('submit', function (e) {
                e.preventDefault();

                const courseId = $('#editCourseId').val();
                const courseType = $('#editCourseType').val();
                const collegeType = $('#editCollegeType').val();

                $.ajax({
                    url: 'update_course.php',
                    type: 'POST',
                    data: {
                        course_id: courseId,
                        coursetype: courseType,
                        collegetype: collegeType
                    },
                    success: function (response) {
                        const result = JSON.parse(response);

                        if (result.success) {
                            $('#editCourseModal').modal('hide');
                            showDynamicToast('Course updated successfully!', 'success');
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showDynamicToast('Error updating course: ' + result.message, 'danger');
                        }
                    },
                    error: function () {
                        showDynamicToast('An error occurred while updating the course.', 'danger');
                    },
                });
            });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if the session has the update success flag set
            <?php if (isset($_SESSION['update_success'])): ?>
                var updateToast = new bootstrap.Toast(document.getElementById('updateToast'));
                updateToast.show();
                <?php unset($_SESSION['update_success']); // Clear the session variable after showing the toast ?>
            <?php endif; ?>
        });
    </script>
    
</body>
</html>
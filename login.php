<?php

session_start();

require("server/connection.php");

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $result = $connection->query("SELECT * FROM users 
    WHERE email = '$email' AND password = '$password'");

    if ($result->num_rows === 1) {
        $record = $result->fetch_assoc();

        // Set session variables
        $_SESSION["user_id"] = $record["user_id"];
        $_SESSION["full_name"] = $record["full_name"];
        $_SESSION["email"] = $record["email"];
        $_SESSION["role"] = $record["role"];
        $_SESSION["logged_in"] = true;

        // Assign user_id before using it
        $user_id = $record["user_id"];

        $logtime = date("Y-m-d H:i:s");
        $connection->query("INSERT INTO userlogs (logtime, user_id) VALUES ('$logtime', '$user_id')");

        header("Location: dashboard.php");
        
    } else {
        $errorMessage = "Incorrect email or password";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iEnroll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <style>
        body{
            background-color: darkgreen;
            padding-top: 8%;
        }

        .butn {
            background-color: darkgreen !important;
        }

        .butn:hover {
            background-color: #068027 !important;
        }
    </style>
</head>
<body>

    <div class="d-flex align-items-center justify-content-center">
        <div class="card col-sm-4 p-5 bg-light">
            <h3 class="card-title text-center mb-4">iEnroll</h3>
            <?php
                if (!empty($errorMessage)) {
                    echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>$errorMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                    ";
                }
            ?>
            <form action="<?php htmlspecialchars("SELF_PHP"); ?>" method="POST">
                <div class="mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <input type="checkbox" id="showPassword" onclick="togglePassword()"> Show Password
                    </div>
                </div>
                <div class="row">
                    <div class="col d-grid gap-2">
                        <button type="submit" class="btn butn mt-3 fw-bold text-white">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
    
</body>
</html>
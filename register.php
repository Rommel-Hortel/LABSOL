<?php
include("connection.php");

$success = "";
$error = "";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password != $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check_email = "SELECT * FROM `users` WHERE email='$email'";
        $result = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($result) > 0) {
            $error = "Email already registered. Please use a different email.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user
            $q = "INSERT INTO `users` (`name`, `email`, `password`) VALUES ('$name', '$email', '$hashed_password')";

            if (mysqli_query($conn, $q)) {
                $success = "Account created successfully! Redirecting to login...";
                echo "<script>setTimeout(() => { window.location='login.php'; }, 2000);</script>";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">PHP CRUD OPERATION</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">Home</a>
                        <a class="nav-link active" href="login.php">Log In</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="col-lg-6 m-auto">
        <form method="post">
            <br><br>
            <div class="card">
                <div class="card-header bg-gradient-aqua text-white" style="background: linear-gradient(to right, aqua, #80e0e5);">
                    <h1 class="text-white text-center">LabSolution Technologies</h1>
                </div>
                <br>

                <?php if ($success): ?>
                    <div class="alert alert-success text-center"><?= $success; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center"><?= $error; ?></div>
                <?php endif; ?>

                <label>NAME:</label>
                <input type="text" name="name" class="form-control" required> <br>

                <label>EMAIL:</label>
                <input type="email" name="email" class="form-control" required> <br>

                <label>PASSWORD:</label>
                <input type="password" name="password" class="form-control" required> <br>

                <label>CONFIRM PASSWORD:</label>
                <input type="password" name="confirm_password" class="form-control" required> <br>

                <button class="btn btn-dark" type="submit" name="submit">Submit</button><br>
                <a class="btn btn-dark" href="index.php">Cancel</a><br>
            </div>
        </form>
    </div>
</body>
</html>

<?php
session_start();
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];

            // Redirect to home page
            header('Location: home.php');
            exit();
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-gradient-aqua {
            background: linear-gradient(to right, aqua, #80e0e5);
        }
    </style>
</head>
<body class="bg-white">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-container bg-gradient-aqua text-white p-5 rounded shadow w-100" style="max-width: 400px;">
            <div class="text-center mb-4">
                <img src="pic/pic1.jpg" alt="Logo" class="img-fluid" style="width: 100px; height: 100px;">
                <h2 class="mt-3">LABSOLUTION INC.</h2>
            </div>
            <form method="POST">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-dark w-100 py-2">Log In</button>
            </form>
            <div class="mt-3 text-center">
                <a href="register.php" class="text-white">Don't have an account? Register</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

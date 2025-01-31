<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-aqua">

    <header class="bg-gradient-info text-black py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo d-flex align-items-center">
                <img src="pic/pic1.jpg" alt="Logo" class="me-2" style="width: 50px; height: 50px;">
                <h2 class="m-0">LabSolution Inc.</h2>
                
            </div>
            <div class="d-flex align-items-center">
                <h4 class="me-3">Welcome, <?php echo $_SESSION['user_name']; ?>!</h4>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </header>

    <main class="container my-5 bg-white text-black p-4 rounded shadow">
        <div class="text-center">
            <a href="form.php" class="text-dark text-decoration-none">FORM</a> |
            <a href="manual.php" class="text-dark text-decoration-none">MANUAL</a> |
            <a href="softwares.php" class="text-dark text-decoration-none">INSTALLER</a>
        </div>
    </main>

    <footer class="bg-info text-white text-center py-3">
        <p class="mb-0">LABSOLUTION INC. &copy; 2025</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

